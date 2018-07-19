<?php

namespace ServicesBundle\Controller\Reserva;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Entity\Division;
use LogicBundle\Entity\Disciplina;
use LogicBundle\Entity\DisciplinasEscenarioDeportivo;
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\DiaReserva;
use UserBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use DateTime;

class EscenarioDeportivoController extends Controller  {

    protected $container = null;
    protected $em = null;
    protected $trans = null;
    protected $protocolo = "http://";

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
        $this->trans = $container->get("translator");
    }

    
    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="EscenarioDeportivo",
     *  resource=true,
     *  description="Obtiene los escenarios deportivos para mostrar al usuario",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/escenariosDeportivos")
     */
    public function getEscenariosDeportivos(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();        
        $servidor = $_SERVER["HTTP_HOST"];
        $dql = "SELECT e.id,  e.nombre, e.direccion, e.latitud, e.longitud, e.telefono, e.email, e.normaEscenario, e.informacionReserva, e.imagenEscenarioDividido FROM LogicBundle:EscenarioDeportivo e";
        $parameters = [];            
        $nombre = $request->get('nombre');
        $nombre = strtoupper($nombre);
        if($nombre){
            $dql .= " WHERE e.nombre LIKE :nombre";
            $parameters["nombre"] = '%'.$nombre.'%';
        }
        
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);

        if ($entity) {
            if(count($entity['items'])){
                $escenarios = array();
                foreach ($entity['items'] as $escenario) {
                    if ($escenario['imagenEscenarioDividido'] != null) {
                        $rutaImage = $this->protocolo.$servidor."/uploads/".$escenario['imagenEscenarioDividido'];
                        $escenario['imagenEscenarioDividido'] = $rutaImage;
                    }
                    array_push($escenarios, $escenario);                    
                }   

                $entity['items'] =  $escenarios;
                $view = $this->view($entity, 200);  
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 404);
            }
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
        }

        return $this->handleView($view);
    }



    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="EscenarioDeportivo",
     *  resource=true,
     *  description="Obtiene las disciplinas por el escenario deportivo",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/disciplinasPorEscenario/{escenario_id}")
     */
    public function getDisciplinasPorEscenario(Request $request, $escenario_id)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();


        if($user !=null  || $user !=''){

            $disciplinas = $em->getRepository('LogicBundle:Disciplina')->createQueryBuilder('disciplina')
            ->innerJoin('disciplina.disciplinas', 'disciplinas')
            ->innerJoin('disciplinas.escenarioDeportivo', 'escenarioDeportivo')
            ->where('escenarioDeportivo.id = :escenario_deportivo')
            ->orderBy("disciplina.nombre", 'DESC')
            ->setParameter('escenario_deportivo', $escenario_id ?: 0)
            ->getQuery()->getResult();
            
            $respuesta = array();
    
            foreach ($disciplinas as $disci) {
                array_push($respuesta, array('id' => $disci->getId(), 'nombre' => $disci->getNombre() ));
            }

            $view = $this->view($respuesta, 200);

            return $this->handleView($view);

        }else{
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }
    }

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="EscenarioDeportivo",
     *  resource=true,
     *  description="Obtiene la disponibilidad de las divisiones del escenario deportivo",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="escenario_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="escenario id"
     *      },
     *      {
     *          "name"="fecha",
     *          "dataType"="varchar",
     *          "requirement"="\d+",
     *          "description"="fecha inicio reserva"
     *      },     
     *      {
     *          "name"="hora_inicial",
     *          "dataType"="varchar",
     *          "requirement"="\d+",
     *          "description"="hora inicio reserva"
     *      },
     *      {
     *          "name"="hora_final",
     *          "dataType"="varchar",
     *          "requirement"="\d+",
     *          "description"="hora final reserva"
     *      }
     * 
     *  }
     * )
     * 
     * 
     * @Route("/public/disponibilidadPorEscenario")
     * @Method("POST")
     */
    public function getDisponibilidadEscenario(Request $request)
    {
        
        $escenario = $request->request->get('escenario_id');
        $fecha_inicio = $request->request->get('fecha');
        $fecha_final = $request->request->get('fecha');
        $hora_inicial = $request->request->get('hora_inicial');
        $hora_final = $request->request->get('hora_final');        
        
        $hora_inicial = strtotime($hora_inicial);
        $hora_final = strtotime($hora_final);
        $em = $this->getDoctrine()->getManager();
        
        $tiempoLim = false;        
        $error = 0;
        $diferenciaHora = $hora_final - $hora_inicial;
        $diferenciaHora = $diferenciaHora / 3600;
        if ($hora_final > $hora_inicial) {
            $buscarfechas = new \LogicBundle\Utils\BuscarFechas();            
            
            $fecha_inicio =  \DateTime::createFromFormat('d/m/Y', $fecha_inicio);
            $fecha_final = \DateTime::createFromFormat('d/m/Y', $fecha_final);
            
            $fechasAConsultar = array();
            $fechas = $buscarfechas->todasLosDias($fecha_inicio, $fecha_final);                
            for ($i=0; $i < 7; $i++) {
                $dias = $buscarfechas->tenerDias($fechas, $i);
                foreach ($dias as $dia) {              
                    array_push($fechasAConsultar, $dia);    
                }
            }
            
            //retorna las fechas ocupadas
            $fechasDisponibles = array();
            $fechasNoDisponibles = array();
            $divisiones = $em->getRepository('LogicBundle:Division')->createQueryBuilder('division')                    
                    ->where('division.escenarioDeportivo = :escenario_deportivo')
                    ->setParameter('escenario_deportivo', $escenario ?: 0)
                    ->getQuery()->getResult();
            if (count($divisiones) > 0) {
                $error = '';
            }else{
                $error = 7;
            }     
            foreach ($fechasAConsultar as $fecha) {
                
                $divisionValida = array();           
                $divisionNoValida = array();         

                $datetime = new \DateTime(); 
                $date = explode('-',$fecha);
                $datetime->setDate($date[0], $date[1], $date[2]); 
                $fechaAComparar = $datetime->format('Y/m/d');
                $fechaAComparar = strtotime($fechaAComparar);
                $minimoHoras = 0;
                $maximoHoras = 0;
                $diasPreviosReserva = 0;
                $fechaActualAComparar = new \DateTime();
                $fechaActualAComparar = $fechaActualAComparar->format('Y/m/d');
                $fechaActualAComparar = strtotime($fechaActualAComparar);
                if ($fechaAComparar < $fechaActualAComparar) {
                    $error = 1;
                }else{

                    foreach ($divisiones as $division) {                    
                        $divisionTiempoMinimo = $division->getTiempoMinimo();
                        $divisionTiempoMaximo = $division->getTiempoMaximo();
                                      
                        if ($diferenciaHora >= (int)$divisionTiempoMinimo && $diferenciaHora <= (int)$divisionTiempoMaximo ) {                        
                            $divisionDiasPreviosReserva = $division->getDiasPreviosReserva();
                            $horaActual = new \DateTime();
                            $dayAdd = "+";
                            $dayAdd = $dayAdd.(string)$divisionDiasPreviosReserva." days";                        
                            $horaActual->modify($dayAdd);                            
                            $fechaActualValidate = strtotime($horaActual->format('Ymd'));
                            
                            $datetime = new \DateTime();
                            $date = explode('-',$fecha);
                            $datetime->setDate($date[0], $date[1], $date[2]); 
                            $fechaInicioValidate = strtotime($datetime->format('Ymd'));
                                                    
                            if ($fechaInicioValidate >= $fechaActualValidate) {                            
                                $divisionValida[$division->getId()] = "OK";
                            }else{
                                $divisionNoValida[$division->getId()] = "OK";
                                $error = 2;
                                $diasPreviosReserva = $division->getDiasPreviosReserva();
                            }
                        }else{
                            $divisionNoValida[$division->getId()] = "OK";
                            if ($diferenciaHora <= (int)$divisionTiempoMinimo){
                                $error = 4;
                                $minimoHoras = $divisionTiempoMinimo;

                            }elseif ($diferenciaHora >= (int)$divisionTiempoMaximo) {
                                $error = 3;
                                $maximoHoras = $divisionTiempoMaximo;
                            }
                        }
                    }
                }

                if (count($divisionValida) > 0) {
                        $reservas = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                            ->innerJoin('reserva.escenarioDeportivo', 'escenarioDeportivo')
                            ->where('escenarioDeportivo.id = :escenario_deportivo')                             
                            ->andWhere('reserva.estado != :rechazado')
                            ->andWhere('reserva.estado != :prereserva')
                            ->andWhere('reserva.fechaInicio <= :fecha')
                            ->andWhere('reserva.fechaFinal >= :fecha')
                            ->orderBy("reserva.id", 'ASC')
                            ->setParameter('escenario_deportivo', $escenario ?: 0)
                            ->setParameter('rechazado', 'Rechazado')
                            ->setParameter('prereserva', 'Pre-Reserva')
                            ->setParameter('fecha', $fecha ?: 0)                
                            ->getQuery()->getResult();
                            
                        $agregoFecha = false;
                        foreach ($reservas as $res) {                
                            $horaInicioRes = strtotime($res->getHoraInicial());    
                            $horaFinRes = strtotime($res->getHoraFinal());       
                            $division = $res->getDivision();

                            $validarDivision =  $division != null && $divisionValida[$division->getId()] == "OK" && count($divisionValida) == 1;

                            //if (($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes) || ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes) || ($hora_inicial  <= $horaInicioRes && $horaInicioRes <= $hora_final) || ($hora_inicial  <= $horaFinRes && $horaFinRes <= $hora_final)  ) 
                            if (
                                ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes && $division == null && $res->getEstado() == "Pendiente") || 
                                ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes && $validarDivision   ) || 
    
                                ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes && $division == null && $res->getEstado() == "Pendiente") || 
                                ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes && $validarDivision    ) || 
    
                                ($hora_inicial  <= $horaInicioRes && $horaInicioRes <= $hora_final && $division == null && $res->getEstado() == "Pendiente") || 
                                ($hora_inicial  <= $horaInicioRes && $horaInicioRes <= $hora_final && $validarDivision    ) || 
    
                                ($hora_inicial  <= $horaFinRes && $horaFinRes <= $hora_final && $division == null && $res->getEstado() == "Pendiente") || 
                                ($hora_inicial  <= $horaFinRes && $horaFinRes <= $hora_final && $validarDivision    ) 
                            ){
                                $datetime = new \DateTime(); 
                                $date = explode('-',$fecha);
                                $datetime->setDate($date[0], $date[1], $date[2]);                                 
                                $agregoFecha = true;
                                $error = 5;
                                array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'diasPrevios' => $diasPreviosReserva));
                                break;

                            }else{
                                $tiempoLim = true;
                            }
                        }

                        if ($agregoFecha == false) {
                            $datetime = new \DateTime(); 
                            $date = explode('-',$fecha);
                            $datetime->setDate($date[0], $date[1], $date[2]); 
                            array_push($fechasDisponibles, array('fecha' => $datetime->format('Y/m/d'), '$dias' => $fechasAConsultar));
                        }
                        
                }else {
                    $datetime = new \DateTime(); 
                    $date = explode('-',$fecha);
                    $datetime->setDate($date[0], $date[1], $date[2]); 

                    //array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d')));
                    array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'minimoHoras' => $minimoHoras, 'maximoHoras' => $maximoHoras, 'diasPrevios' => $diasPreviosReserva));
                    $agregoFecha = true;                    
                }
                
            }            
            if (count($fechasDisponibles)> 0) {
                $respuesta = array(
                    'respuesta' => true,
                );
            }else if (count($fechasNoDisponibles)> 0) {
                $mensaje ="";
                if ($error == 1) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error1");
                }else if ($error == 2) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error2");
                    $mensaje = $mensaje." ".$diasPreviosReserva;
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error2Dias");
                }else if ($error == 3) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error3");
                    $mensaje = $mensaje." ".$maximoHoras;
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras");
                }else if ($error == 4) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error4");
                    $mensaje = $mensaje." ".$minimoHoras;
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras");
                }else if ($error == 5) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error5");
                }else if ($error == 7) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error7");
                }                      
                $respuesta = array(
                    'respuesta' => false,
                    'error' => $mensaje,
                    'minimoHoras' => $minimoHoras, 
                    'maximoHoras' => $maximoHoras, 
                    'diasPrevios' => $diasPreviosReserva
                );
            }
            
            return $respuesta;
        }else{            
            $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_hora_fecha_reserva_mayor_menor");
            $respuesta = array(
                'respuesta' => false,
                'error' => $mensaje                
            );         
            return $respuesta;
        }
    }
}
