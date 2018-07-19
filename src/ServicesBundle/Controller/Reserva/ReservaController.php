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

class ReservaController extends Controller implements TokenAuthenticatedController {

    protected $container = null;
    protected $em = null;
    protected $trans = null;

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
     *  section="Reserva",
     *  resource=true,
     *  description="Permite crear una reserva",
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
     *          "description"="fecha reserva"
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
     *      },
     *  }
     * )
     * 
     * 
     * @Route("/addReservaPasoUno")
     * @Method("POST")
     */
     
    public function getAddReservaPasoUno(Request $request) 
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("api.mensajes.error.no_encontrado"), 200);

        $em = $this->getDoctrine()->getManager();
        $userReserva = $this->getUser();        
    
        if($user !=null  || $user !=''){
            
            //obtenemos el id del escenario de la respuesta
            $escenario_id = $request->get('escenario_id');
            if($escenario_id == null || $escenario_id ==''){
                $error = array('error' => $this->trans->trans("api.mensajes.error.escenario_deportivo_no_encontrado"));
                $view = $this->view($error, 200);
                return $this->handleView($view);
            }

            //realizamos la consulta para saber si es el escenario correcto
            $resultEscenario = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($escenario_id); 

            //verificamos si esta el objeto consultado
            $conteo = count($resultEscenario);

            if($conteo == 0)
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.escenario_deportivo_no_encontrado"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);
            }            
            
            //obtenemos el id del barrio de la respuesta
            $barrio_id = $resultEscenario->getBarrio();
        
                        
            //realizamos la consulta para saber si existe el tipo de reserva
            //$resultTipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")->find($tipo_reserva_id);             
            $resultTipoReserva = $em->getRepository('LogicBundle:TipoReserva')
                ->createQueryBuilder('tipoReserva')
                ->where('tipoReserva.nombre = :nombreTipoReserva')
                ->setParameter('nombreTipoReserva', 'Practica Libre')
                ->getQuery()->getOneOrNullResult();
            //verificamos si esta el objeto consultado
            $conteoTipoReserva = count($resultTipoReserva);
            if($conteoTipoReserva == 0)
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);                
            }

            //verificamos los datos de fechas y hora

            $fechaInicio = $request->get('fecha');
            
            if($fechaInicio == null || $fechaInicio ==''){                
                $error = array('error' => $this->trans->trans("api.mensajes.error.fecha_campo_vacio"));
                $view = $this->view($error, 200);
                return $this->handleView($view);
            }
            //los volvemos objeto fecha
            $fecha_inicio_real = new \DateTime();
            $fechaInicio = $fechaInicio." 00:00:00";
            $fecha_inicio_real = $fecha_inicio_real->createFromFormat('d/m/Y H:i:s', $fechaInicio);

            $fechaFinal = $request->get('fecha');
            if($fechaFinal == null || $fechaFinal =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.fecha_campo_vacio"));
                $view = $this->view($error, 200);
                return $this->handleView($view);                
            }            
            //lo volvemos objeto fecha
            $fecha_final_real = new \DateTime();
            $fechaFinal = $fechaFinal." 00:00:00";
            $fecha_final_real = $fecha_final_real->createFromFormat('d/m/Y H:i:s', $fechaFinal);

            $horaInicial = $request->get('hora_inicial');
            if($horaInicial == null || $horaInicial =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.hora_inicial_campo_vacio"));
                $view = $this->view($error, 200);
                return $this->handleView($view);                
            }

            $horaFinal = $request->get('hora_final');
            if($horaFinal == null || $horaFinal =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.hora_final_campo_vacio"));
                $view = $this->view($error, 200);
                return $this->handleView($view);                
            }

            $diaNumero = $request->get('fecha');            
            $diaNumero = str_replace("/","-",$diaNumero);            
            $dia = date('N', strtotime($diaNumero));
            $dia = $this->em->getRepository("LogicBundle:Dia")->find($dia);            
            $disponible = $this->getDisponibilidadEscenario($request);            
            
            

            if($disponible['respuesta'] == true){
                $horaInicial = new \DateTime($horaInicial);
                $horaInicial->format('Y-m-d  H:i:s');

                $horaFinal = new \DateTime($horaFinal);
                $horaFinal->format('Y-m-d  H:i:s');

                $reserva = new Reserva;
                $reserva->setEscenarioDeportivo($resultEscenario);
                $reserva->setBarrio($barrio_id);
                $reserva->setTipoReserva($resultTipoReserva);
                $reserva->setFechaInicio($fecha_inicio_real);
                $reserva->setFechaFinal($fecha_final_real);
                $reserva->setHoraInicial($horaInicial);
                $reserva->setHoraFinal($horaFinal);
                $reserva->setEstado('Pendiente');
                $reserva->setUsuario($userReserva);

                $em = $this->getDoctrine()->getManager();                
                $em->persist($reserva);
                $em->flush();
                
                $diaReserva = new DiaReserva();
                $diaReserva->setReserva($reserva);
                $diaReserva->setDia($dia);        
                $em->persist($diaReserva);
                $em->flush();
                $dql = "SELECT r.id  FROM LogicBundle:Reserva r WHERE r.id = :reserva_id";        
                $parameters = [];
                $parameters["reserva_id"] = $reserva->getId();
                
                $responseBuilder = $this->container->get('ito.tools.response.builder');
                $entity = $responseBuilder->getItem($dql, $parameters);                
                $divisiones = $this->getDatosReservaPasoDos($request, $reserva->getId());                
                $entity['divisiones'] = $divisiones['divisiones'];                
                $view = $this->view($entity, 201);

            }else{
                
                if ($disponible['error'] == 1) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error1");
                }else if ($disponible['error'] == 2) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error2");
                    $mensaje = $mensaje." ".$disponible['diasPrevios'];
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error2Dias");
                }else if ($disponible['error'] == 3) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error3");
                    $mensaje = $mensaje." ".$disponible['maximoHoras'];
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras");
                }else if ($disponible['error'] == 4) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error4");
                    $mensaje = $mensaje." ".$disponible['minimoHoras'];
                    $mensaje = $mensaje." ".$this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras");
                }else if ($disponible['error'] == 5) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error5");
                }else if ($disponible['error'] == 7) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error7");
                }

                else if ($disponible['error'] == 12) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error12");
                } else if ($disponible['error'] == 34) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error34");
                } else if ($disponible['error'] == 42) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error42");
                } else if ($disponible['error'] == 43) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error43");
                } else if ($disponible['error'] == 44) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error44");
                } else if ($disponible['error'] == 45) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error45");
                } else if ($disponible['error'] == 46) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error46");
                } else if ($disponible['error'] == 47) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error47");
                } else if ($disponible['error'] == 48) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error48");
                } else if ($disponible['error'] == 33) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error33");
                } else if ($disponible['error'] == 32) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error32");
                } else if ($disponible['error'] == 31) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error31");
                } else if ($disponible['error'] == 30) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error30");
                } else if ($disponible['error'] == 29) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error29");
                } else if ($disponible['error'] == 28) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error28");
                } else if ($disponible['error'] == 27) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error27");
                } else if ($disponible['error'] == 50) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error50");
                } else if ($disponible['error'] == 51) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error51");
                } else if ($disponible['error'] == 52) {
                    $mensaje = $this->trans->trans("formulario_reserva.labels.paso_uno.error_disponibilidad_error52");
                } else if ($disponible['error'] == 53) {
                    $mensaje = $disponible['mensajeError'];                
                }else{
                    $mensaje = 'Error Crear Reserva';
                }
              
                $view = $this->view(array(
                    'error' => $mensaje
                ), 200);
            }
            return $this->handleView($view);
            
        }else{
            $view = $this->view($this->trans->trans("api.mensajes.error.usuario_no_encontrado"), 200);
            return $this->handleView($view);
        }
    }
    
    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="genera los datos para la vista del paso 2",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="reserva_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="reserva id"
     *      }
     * 
     *  }
     * )
     * @Route("/datosReservaPasoDos")
     * @Method("POST")
     */
    public function DatosReservaPasoDos(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();
        
        if($user !=null  || $user !=''){
            //obtenemos el id de la reserva
            $reserva_id = $request->get('reserva_id');
            //realizamos la consulta para saber si la  reserva es correcta
            $reserva = $this->em->getRepository("LogicBundle:Reserva")->find($reserva_id);
            $idEscenario = $reserva->getEscenarioDeportivo()->getId();
            //obtener y formatear divisiones de un escenario
            $divisiones  = $reserva->getEscenarioDeportivo()->getDivisiones();
            
            $arrayDivisiones= array();
            $cart = array();

            foreach ($divisiones as $division) {
                $division->setDisponibilidad(true);
            }
            
            $arrayDivisiones = $cart;
            $divisionesOcupadas = array();
            $dias = $reserva->getDiaReserva();
            $fecha_inicio = $reserva->getFechaInicio();
            $fecha_final = $reserva->getFechaFinal();
            $escenario = $reserva->getEscenarioDeportivo();

            $buscarfechas = new \LogicBundle\Utils\BuscarFechas();
           
            //obtener divisiones ocupadas por hora de  
            $horaInicial =  date("H:i:s", strtotime($reserva->getHoraInicial()));
            $horaFinal =  date("H:i:s", strtotime($reserva->getHoraFinal()));
            $diferenciaHora = round((strtotime($horaFinal) - strtotime($horaInicial))/3600, 1);
           
            $divisionValida = false;
            $fecha = $reserva->getFechaInicio()->format('Y-m-d');
            
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
                    
                    if ($fechaInicioValidate >= $fechaActualValidate){                                      
                        $divisionValida = true;
                        $division->setDisponibilidad($divisionValida);
                    }
                }else{
                    $division->setDisponibilidad($divisionValida);
                }
            }
            $repository = $this->em->getRepository('LogicBundle:Reserva');
            
            $qb = $repository->createQueryBuilder('reserva')
            ->where('reserva.fechaInicio BETWEEN :fi AND :ff')
            ->orWhere('reserva.fechaFinal BETWEEN :fi AND :ff')
            ->andWhere('reserva.horaInicial = :hi')
            ->andWhere('reserva.horaFinal = :hf')
            ->andWhere('reserva.escenarioDeportivo = :ed')
            ->andWhere('reserva.id != :idReserva')
            ->andWhere('reserva.estado != :rechazado')
            ->setParameter('fi', $reserva->getFechaInicio()->format('Y-m-d'))
            ->setParameter('ff', $reserva->getFechaFinal()->format('Y-m-d'))
            ->setParameter('hi', $reserva->getHoraInicial())
            ->setParameter('hf', $reserva->getHoraFinal())
            ->setParameter('rechazado', 'Rechazado')
            ->setParameter('ed', $idEscenario)
            ->setParameter('idReserva', $reserva->getId())
            ->getQuery();
            $resultados = $qb->getResult();
            
            // formatear divisiones ocupadas
            foreach($resultados as $resultado){                
                if($resultado->getDivision() != null){
                    foreach ($divisiones as $division) { 
                        if ($resultado->getDivision()->getId() == $division->getId()){
                            $division->setDisponibilidad(false);
                        }
                    }
                }         
            }
            
            //obtener divisiones ocupadas por hora de  
            $horaInicial =  date("H:i:s", strtotime($reserva->getHoraInicial()));
            $horaFinal =  date("H:i:s", strtotime($reserva->getHoraFinal()));
            $hourdiff = round((strtotime($horaFinal) - strtotime($horaInicial))/3600, 1);
            
            foreach ($divisiones as $division) {     
                $nombredivision = $division->getNombre();
                $idDivision = $division->getId();
                $horaDivMax = intval($division->getTiempoMaximo());
                $horaDivMin = intval($division->getTiempoMinimo());
    
                if( $horaDivMax < $hourdiff  or  $horaDivMin > $hourdiff ){
                    $division->setDisponibilidad(false);
                }
            }
            
            $cart = array();
            $cartNoDisponible = array();
            foreach ($divisiones as $division) {        
                $nombredivision = $division->getNombre();
                $idDivision = $division->getId();

                if($division->getDisponibilidad()){
                    $cart[] = array($nombredivision => $idDivision);
                }else{
                    $cartNoDisponible[] = array($nombredivision => $idDivision);
                }
                
            }

            $arrayDivisiones = $cart;
            $divisionesOcupadas = $cartNoDisponible;
            $respuesta = array();
            $respuesta['disponible'] = $arrayDivisiones;
            $respuesta['noDisponible'] = $divisionesOcupadas;            
            $view = $this->view($respuesta, 200);
            return $this->handleView($view);
        }else{            
            $error = array('error' => $this->trans->trans("api.mensajes.error.usuario_no_encontrado"));
            $view = $this->view($error, 200);                
            return $this->handleView($view);            
        }
    }

    
    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="Obtiene datos para guardar el paso 2",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="reserva_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="reserva id"
     *      },
     *      {
     *          "name"="division_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="id de la division"
     *      },
     * 
     *  }
     * )
     * 
     * 
     * @Route("/addReservaPasoDos")
     * @Method("POST")
     */
    public function addReservaPasoDos(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();        
        //$view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);
        $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
        $view = $this->view($error, 200);                
        //return $this->handleView($view);

        $em = $this->getDoctrine()->getManager();

        if($user !=null  || $user !=''){
            
            //obtenemos el id de la reserva
            $reserva_id = $request->get('reserva_id');
            if($reserva_id == null || $reserva_id =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_id_campo_vacio"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);                
            }
            //realizamos la consulta para saber si la reserva es correcta
            $resultReserva = $this->em->getRepository("LogicBundle:Reserva")->find($reserva_id); 
            
            //verificamos si esta el objeto consultado
            $conteo = count($resultReserva);
            
            if($conteo == 0)
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_no_encontrado"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);                
            }

            //obtenemos el id de la division
            $division_id = $request->get('division_id');
            if($division_id == null || $division_id =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.division_id_campo_vacio"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);
            }
            //realizamos la consulta para saber si la division es correcta
            $resultDivision = $this->em->getRepository("LogicBundle:Division")->find($division_id); 

            //verificamos si esta el objeto consultado
            $conteoDivision = count($resultDivision);
            
            if($conteoDivision == 0)
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.division_no_encontrado"));
                $view = $this->view($error, 200);                
                return $this->handleView($view);                
            }

            //obtenemos la imagen del escenario dividido
            $escenarios = $resultReserva->getEscenarioDeportivo()->getImagenEscenarioDividido();

            $phath =  "/uploads/".$escenarios;
            
            //procedemos a actualizar la reserva
            //utilizamos el objeto reserva para actualizar 
            $resultReserva->setDivision($resultDivision);

            $em = $this->getDoctrine()->getManager();
            $em->persist($resultReserva);
            $em->flush();
            
            $dql = "SELECT div.id, div.nombre, div.tiempoMinimo, div.tiempoMaximo, div.usuariosMinimos, div.usuariosMaximos, div.edadMinima  FROM LogicBundle:Division div WHERE div.id = :division_id";
            $parameters = [];
            $parameters["division_id"] = $resultReserva->getDivision()->getId();
            
            $responseBuilder = $this->container->get('ito.tools.response.builder');
            $infoDivision  = $responseBuilder->getItem($dql, $parameters);                
            $entity = array(
                'reserva' => $resultReserva->getId(),
                'idDivision' => $infoDivision['id'],
                'nombre' => $infoDivision['nombre'],
                'tiempoMinimo' => $infoDivision['tiempoMinimo'],
                'tiempoMaximo' => $infoDivision['tiempoMaximo'],
                'usuariosMinimos' => $infoDivision['usuariosMinimos'],
                'usuariosMaximos' => $infoDivision['usuariosMaximos'],
                'edadMinima' => $infoDivision['edadMinima']
            );                  
            $view = $this->view($entity, 200);
            return $this->handleView($view);
        }else{              
            $error = array('error' => $this->trans->trans("api.mensajes.error.usuario_no_encontrado"));
            $view = $this->view($error, 200);
            return $this->handleView($view);
        }
    }

    /**
    * Catalogo
    *
    * @ApiDoc(
    *  section="Reserva",
    *  resource=true,
    *  description="obtiene los usuarios para mostrar",
    *  statusCodes = {
    *     200 = "Ok",
    *     400 = "Errores"
    *  }
    * )
    * 
    * @Get("/reserva/usuarios/{reserva_id}")
    */
   public function getReservaUsuarios(Request $request, $reserva_id)
   {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
        $view = $this->view($error, 200);                        

        $em = $this->getDoctrine()->getManager();

        if($user !=null  || $user !='')
        {
            if($reserva_id == null || $reserva_id =='')
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_id_campo_vacio"));
                $view = $this->view($error, 200);
                return $this->handleView($view);                
            }
            //realizamos la consulta para saber si la  reserva es correcta
            $resultReserva = $this->em->getRepository("LogicBundle:Reserva")->find($reserva_id); 
            if(count($resultReserva) == 0){                
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_no_encontrado"));
                $view = $this->view($error, 200);
                return $this->handleView($view);
            }

            $idUsuarios = array();
            foreach ($resultReserva->getUsuarios() as $user) {                
                array_push($idUsuarios, $user->getId());
            }
            
            //verificamos si esta el objeto consultado
            $conteo = count($resultReserva);
            
            if($conteo == 0)
            {
                $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 200);
                return $this->handleView($view);                
            }

            $dql = "SELECT u FROM ApplicationSonataUserBundle:User u where u.id IN (:id_usuarios)";

            $parameters = [];
            $parameters["id_usuarios"] = $idUsuarios;

            $responseBuilder = $this->container->get('ito.tools.response.builder');
            $entity = $responseBuilder->getCollection($dql, $parameters);

            if ($entity) {
                if(count($entity['items'])){
                    foreach ($entity['items'] as $e) {
                        $e->setPassword(null);
                        $e->setSalt(null);

                        if ($e->getDateOfBirth() != null) {
                            $e->setDateOfBirth($e->getDateOfBirth()->format('Y-m-d'));
                        }
                    }              
                    $view = $this->view($entity, 200);  
                }else{
                    $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_usuarios_no_existe"));
                    $view = $this->view($error, 200);
                }
            }else{
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_usuarios_no_existe"));
                $view = $this->view($error, 200);
            }

            return $this->handleView($view);
        }else{
            $error = array('error' => $this->trans->trans("api.mensajes.error.usuario_no_encontrado"));
            $view = $this->view($error, 200);            
            return $this->handleView($view);
        }
        
               
   }


    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="Obtine datos para guardar el paso 3",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="reserva_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="reserva id"
     *      },
     *      {
     *          "name"="usuarios",
     *          "dataType"="varchar",
     *          "requirement"="\d+",
     *          "description"="usuarios de la reserva"
     *      }
     * 
     *  }
     * )
     * 
     * 
     * @Route("/addReservaPasoTres")
     * @Method("POST")
     */
    public function addReservaPasoTres(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();

        if($user !=null  || $user !=''){
            
            //obtenemos el id de la reserva
            $reserva_id = $request->get('reserva_id');
            if($reserva_id == null || $reserva_id =='')
            {
                $mensaje = $this->trans->trans("api.mensajes.error.reserva_id_campo_vacio");
                $view = $this->view($mensaje, 200);
                return $this->handleView($view);
            }
            //realizamos la consulta para saber si la  reserva es correcta
            $resultReserva = $this->em->getRepository("LogicBundle:Reserva")->find($reserva_id); 
                        
            //verificamos si esta el objeto consultado
            $conteo = count($resultReserva);

            if($conteo == 0){
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_no_encontrado"));
                $view = $this->view($error, 200);            
                return $this->handleView($view);
                
                
            }

            $usarios = $request->get('usuarios');      
            if($usarios == null || $usarios =='')
            {                
                $error = array('error' => $this->trans->trans("api.mensajes.error.usuarios_reserva_campo_vacio"));
                $view = $this->view($error, 200);            
                return $this->handleView($view);
            }
            $usarios = str_replace(array("{", "}"), "", $usarios);            
            $usarios = explode(',', $usarios);

            if(count($usarios) > 0){
                foreach ($usarios as $id) {                
                    $existeUsuario = false;
                    foreach ($resultReserva->getUsuarios() as $us) {
                        if ($us->getId() == $id) {
                            $existeUsuario = true;
                            break;
                        }                        
                    }    
                    
                    if ($existeUsuario == false) {
                        $dql = "SELECT u FROM ApplicationSonataUserBundle:User u 
                        WHERE u.id = :id";
                        $parameters = [];
                        $parameters["id"] = $id;
                        
                        $responseBuilder = $this->container->get('ito.tools.response.builder');
                        $entity = $responseBuilder->getItem($dql, $parameters);
                        if ($entity != null && $entity != '') {
                            $resultReserva->addUsuario($entity);
                        }else{
                            $mensaje = $this->trans->trans("api.mensajes.error.usuarios_reserva_usuario_id");
                            $mensaje = $mensaje." ".$id;
                            $mensaje = $mensaje." ".$this->trans->trans("api.mensajes.error.usuarios_reserva_no_existe");                            
                            $error = array('error' => $mensaje);
                            $view = $this->view($error, 200);            
                            return $this->handleView($view);
                        }
                    }
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($resultReserva);
                $em->flush();
                                
                $infoReserva = array(
                    'idReserva' => $resultReserva->getId(),
                    'nombreEscenario' => $resultReserva->getEscenarioDeportivo()->getNombre(),
                    'normasEscenario' => $resultReserva->getEscenarioDeportivo()->getNormaEscenario(),
                    'direccionEscenario' => $resultReserva->getEscenarioDeportivo()->getDireccion(),
                    'telefonoEscenario' => $resultReserva->getEscenarioDeportivo()->getTelefono(),
                    'longitudEscenario' => $resultReserva->getEscenarioDeportivo()->getLongitud(),
                    'latitudEscenario' => $resultReserva->getEscenarioDeportivo()->getLatitud()
                );
                
                $view = $this->view($infoReserva, 200);
                return $this->handleView($view);

            }else{                
                $error = array('error' => $this->trans->trans("api.mensajes.error.reserva_no_encontrado"));
                $view = $this->view($error, 200);            
                return $this->handleView($view);
            }
                       
        }else{
            $error = array('error' => $this->trans->trans("api.mensajes.error.usuario_no_encontrado"));
            $view = $this->view($error, 200);            
            return $this->handleView($view);            
        }
    }

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="Obtine datos para guardar el paso 3",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="reserva_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="reserva id"
     *      }     
     * 
     *  }
     * )
     * 
     * 
     * @Route("/addReservaPasoCinco")
     * @Method("POST")
     */
    public function addReservaPasoCinco(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();

        if($user !=null  || $user !=''){
            
            //obtenemos el id de la reserva
            $reserva_id = $request->get('reserva_id');
            
            //realizamos la consulta para saber si la  reserva es correcta
            $resultReserva = $this->em->getRepository("LogicBundle:Reserva")->find($reserva_id); 
                        
            //verificamos si esta el objeto consultado
            $conteo = count($resultReserva);

            if($conteo == 0)
            {                
                $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 200);            
                return $this->handleView($view);            
            }

            $resultReserva->setEstado('Pendiente');
            $em = $this->getDoctrine()->getManager();
            $em->persist($resultReserva);
            $em->flush();

            $infoReserva = array(
                'idReserva' => $resultReserva->getId(),
                'estado' => $resultReserva->getEstado()                
            );            
            $view = $this->view($infoReserva, 200);
            return $this->handleView($view);
                       
        }else{
            $error = array('error' => $this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 200);
            return $this->handleView($view);
        }

    }


     /**
    * Catalogo
    *
    * @ApiDoc(
    *  section="Reserva",
    *  resource=true,
    *  description="obtiene mis reservas",
    *  statusCodes = {
    *     200 = "Ok",
    *     400 = "Errores"
    *  }
    * )
    * 
    * @Get("/usuarios/misreservas")
    */
   public function getMisReservas(Request $request)
   {

        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $dql = "SELECT re.id, re.estado, re.fechaInicio, re.fechaFinal, re.horaInicial, re.horaFinal, es.nombre as nombreEscenario, di.nombre as nombreDivision
        FROM LogicBundle:Reserva re 
        JOIN LogicBundle:EscenarioDeportivo es
        WITH re.escenarioDeportivo = es.id         
        JOIN LogicBundle:DivisionReserva diRe
        WITH re.id = diRe.reserva
        JOIN LogicBundle:Division di
        WITH diRe.division = di.id
        WHERE re.usuario = :id_usuario AND re.estado != 'Pre-Reserva'";        
            
        $parameters["id_usuario"] = $user->getId();
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);
              
        if ($entity) {
            if(count($entity['items'])){
                $view = $this->view($entity, 200);                
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.mis_reservas_no_existe"));
                $view = $this->view($error, 200);
            }
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.mis_reservas_no_existe"));
            $view = $this->view($error, 200);            
        }

        return $this->handleView($view);  
    }

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="Obtine las disponibilidad del escenario deportivo",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="id_reserva",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="escenario id"
     *      },    
     *  }
     * )
     * 
     * 
     * @Route("/usuarios/cancelarreserva")
     * @Method("POST")
     */
    public function cancelarReserva(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $userReserva = $this->getUser();        
        $idUsuarioReserva = $userReserva->getId();

        $idReserva = $request->get('id_reserva');
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);
        
        $reserva = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
            ->where('reserva.usuario = :usuario')
            ->andWhere('reserva.id = :idReserva')
            ->setParameter('usuario', $idUsuarioReserva ?: 0)
            ->setParameter('idReserva', $idReserva ?: 0)
            ->getQuery()->getResult();
                
        if (count($reserva) > 0) {
            foreach ($reserva as $r) {
                $r->setEstado('Rechazado');            
                $em->persist($r);
                $em->flush();
                
                $dql = "SELECT re.id, re.fechaInicio, re.estado, re.fechaFinal, re.horaInicial, re.horaFinal  FROM LogicBundle:Reserva re WHERE re.id = :id_reserva";                
                $parameters["id_reserva"] = $r->getId();
                
                $responseBuilder = $this->container->get('ito.tools.response.builder');
                $entity = $responseBuilder->getItem($dql, $parameters);                
                if ($entity) {
                    $view = $this->view($entity, 200);
                } else {
                    $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                    $view = $this->view($error, 200);                    
                }
                return $this->handleView($view);
            }
            
        }else{
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 200);            
            return $this->handleView($view);
        }        
    }

    /**
    * Catalogo
    *
    * @ApiDoc(
    *  section="Reserva",
    *  resource=true,
    *  description="obtiene mis escenarios",
    *  statusCodes = {
    *     200 = "Ok",
    *     400 = "Errores"
    *  }
    * )
    * 
    * @Get("/usuarios/misescenarios")
    */
   public function getMisEscenarios(Request $request)
   {
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $idUsuarioEscenario = $user->getId();        
        
        $dql = "SELECT ed.nombre ,ed.nombre,ed.normaEscenario,ed.direccion,ed.telefono,ed.longitud,ed.latitud
        FROM LogicBundle:EscenarioDeportivo ed 
        JOIN LogicBundle:UsuarioEscenarioDeportivo ued 
        WITH  ed.id = ued.escenarioDeportivo 
        WHERE ued.usuario = :usuario_id";
        $parameters = [];
        $parameters["usuario_id"] = $idUsuarioEscenario;
        //$parameters["usuario_id"] = 122355454;
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);
        
        if ($entity) {
            //$view = $this->view($entity, 200);
            if(count($entity['items'])){
                $view = $this->view($entity, 200);                
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.mis_escenarios_no_existe"));
                $view = $this->view($error, 200);
            }
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.mis_escenarios_no_existe"));
            $view = $this->view($error, 200);
        }

        return $this->handleView($view);
    }


    /************************** Metodo de ayuda **************************/

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
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
     *          "name"="fecha",
     *          "dataType"="varchar",
     *          "requirement"="\d+",
     *          "description"="fecha final reserva"
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
     *  }
     * )
     * 
     * 
     * @Route("/disponibilidadPorEscenario")
     * @Method("POST")
     */
    public function getDisponibilidadEscenario(Request $request)
    {
        
        $escenario = $request->request->get('escenario_id');
        $fecha_inicio = $request->request->get('fecha');
        $fecha_final = $request->request->get('fecha');
        $hora_inicial = $request->request->get('hora_inicial');
        $hora_final = $request->request->get('hora_final');

        $tipo_reserva = $request->request->get('tipo_reserva_id');
        
        $hora_inicial = strtotime($hora_inicial);
        $hora_final = strtotime($hora_final);
        $em = $this->getDoctrine()->getManager();
        
        $tiempoLim = false;        

        $diferenciaHora = $hora_final - $hora_inicial;
        $diferenciaHora = $diferenciaHora / 3600;

        $escenarioDeportivo = $em->getRepository('LogicBundle:EscenarioDeportivo')->find($escenario);

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
            /*$divisiones = $em->getRepository('LogicBundle:Division')->createQueryBuilder('division')                    
                    ->where('division.escenarioDeportivo = :escenario_deportivo')
                    ->setParameter('escenario_deportivo', $escenario ?: 0)
                    ->getQuery()->getResult();*/
            
            $divisiones = $em->getRepository('LogicBundle:Division')->createQueryBuilder('division')
                    ->join('division.tiposReservaEscenarioDeportivo', 'tiposReservaEscenarioDeportivo')
                    ->join('division.escenarioDeportivo', 'escenarioDeportivo')
                    ->where('tiposReservaEscenarioDeportivo.tipoReserva = :tipoReserva')
                    ->andWhere('escenarioDeportivo.id = :idEscenarioDeportivo')
                    ->setParameter('tipoReserva', $tipo_reserva ?: 0)
                    ->setParameter('idEscenarioDeportivo', $escenario ?: 0)
                    ->getQuery()->getResult();
            
            if (count($divisiones) > 0) {
                $error = '';
            }else{
                $error = 7;
            }

            $array_dias['Sunday'] = "Domingo";
            $array_dias['Monday'] = "Lunes";
            $array_dias['Tuesday'] = "Martes";
            $array_dias['Wednesday'] = "Miercoles";
            $array_dias['Thursday'] = "Jueves";
            $array_dias['Friday'] = "Viernes";
            $array_dias['Saturday'] = "Sabado";

            $horaInicioReservaValida = date("H:i", strtotime($request->request->get('hora_inicial')));
            $horaFinalReservaValida = date("H:i", strtotime($request->request->get('hora_final')));

            if (count($divisiones) > 0) {
                foreach ($divisiones as $divTipoRes) {
                    $cualquiera = false;
                    if ($divTipoRes->getHoraInicialLunes() == null &&
                            $divTipoRes->getHoraFinalLunes() == null &&
                            $divTipoRes->getHoraInicialMartes() == null &&
                            $divTipoRes->getHoraFinalMartes() == null &&
                            $divTipoRes->getHoraFinalMiercoles() == null &&
                            $divTipoRes->getHoraInicialMiercoles() == null &&
                            $divTipoRes->getHoraInicialJueves() == null &&
                            $divTipoRes->getHoraFinalJueves() == null &&
                            $divTipoRes->getHoraInicialViernes() == null &&
                            $divTipoRes->getHoraFinalViernes() == null &&
                            $divTipoRes->getHoraInicialSabado() == null &&
                            $divTipoRes->getHoraFinalSabado() == null &&
                            $divTipoRes->getHoraInicialDomingo() == null &&
                            $divTipoRes->getHoraFinalDomingo() == null &&
                            $divTipoRes->getHoraInicial2Lunes() == null &&
                            $divTipoRes->getHoraFinal2Lunes() == null &&
                            $divTipoRes->getHoraInicial2Martes() == null &&
                            $divTipoRes->getHoraFinal2Martes() == null &&
                            $divTipoRes->getHoraFinal2Miercoles() == null &&
                            $divTipoRes->getHoraInicial2Miercoles() == null &&
                            $divTipoRes->getHoraInicial2Jueves() == null &&
                            $divTipoRes->getHoraFinal2Jueves() == null &&
                            $divTipoRes->getHoraInicial2Viernes() == null &&
                            $divTipoRes->getHoraFinal2Viernes() == null &&
                            $divTipoRes->getHoraInicial2Sabado() == null &&
                            $divTipoRes->getHoraFinal2Sabado() == null &&
                            $divTipoRes->getHoraInicial2Domingo() == null &&
                            $divTipoRes->getHoraInicial2Domingo() == null
                    ) {
                        $error = 12;
                        $divTipoRes->setDisponibilidad(false);
                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                        $cualquiera = true;
                    }
                    
                    if ($cualquiera == false) {
                        foreach ($fechasAConsultar as $dia) {

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Monday']) {

                                if ($divTipoRes->getHoraInicialLunes() != null && $divTipoRes->getHoraFinalLunes() != null || $divTipoRes->getHoraInicial2Lunes() != null && $divTipoRes->getHoraFinal2Lunes() != null) {
                                    $horaInicialLunesMañana = date("H:i", strtotime($divTipoRes->getHoraInicialLunes()));
                                    $horaFinalLunesMañana = date("H:i", strtotime($divTipoRes->getHoraFinalLunes()));

                                    $horaInicialLunesTarde = date("H:i", strtotime($divTipoRes->getHoraInicial2Lunes()));
                                    $horaFinalLunesTarde = date("H:i", strtotime($divTipoRes->getHoraFinal2Lunes()));

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;
                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;
                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 42;
                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 33;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }


                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Tuesday']) {
                                if ($divTipoRes->getHoraInicialMartes() != null && $divTipoRes->getHoraFinalMartes() != null || $divTipoRes->getHoraInicial2Martes() != null && $divTipoRes->getHoraFinal2Martes() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialMartes();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalMartes();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Martes();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Martes();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 43;

                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Wednesday']) {
                                if ($divTipoRes->getHoraInicialMiercoles() != null && $divTipoRes->getHoraFinalMiercoles() != null || $divTipoRes->getHoraInicial2Miercoles() != null && $divTipoRes->getHoraFinal2Miercoles() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialMiercoles();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalMiercoles();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Miercoles();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Miercoles();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;
                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;
                                        $divTipoRes->setDisponibilidad(true);
                                    }

                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 44;
                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 31;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Thursday']) {
                                if ($divTipoRes->getHoraInicialJueves() != null && $divTipoRes->getHoraFinalJueves() != null ||
                                    $divTipoRes->getHoraInicial2Jueves() != null && $divTipoRes->getHoraFinal2Jueves() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialJueves();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalJueves();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Jueves();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Jueves();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 45;

                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 30;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Friday']) {
                                if ($divTipoRes->getHoraInicialViernes() != null && $divTipoRes->getHoraFinalViernes() != null ||
                                        $divTipoRes->getHoraInicial2Viernes() != null && $divTipoRes->getHoraFinal2Viernes() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialViernes();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalViernes();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Viernes();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Viernes();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 46;

                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 29;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Saturday']) {
                                if ($divTipoRes->getHoraInicialSabado() != null && $divTipoRes->getHoraFinalSabado() != null ||
                                        $divTipoRes->getHoraInicial2Sabado() != null && $divTipoRes->getHoraFinal2Sabado() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialSabado();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalSabado();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Sabado();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Sabado();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 47;

                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 28;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }

                            if ($array_dias[date('l', strtotime($dia))] == $array_dias['Sunday']) {
                                if ($divTipoRes->getHoraInicialDomingo() != null && $divTipoRes->getHoraFinalDomingo() != null ||
                                        $divTipoRes->getHoraInicial2Domingo() != null && $divTipoRes->getHoraFinal2Domingo() != null) {
                                    $horaInicialLunesMañana = $divTipoRes->getHoraInicialDomingo();
                                    $horaFinalLunesMañana = $divTipoRes->getHoraFinalDomingo();

                                    $horaInicialLunesTarde = $divTipoRes->getHoraInicial2Domingo();
                                    $horaFinalLunesTarde = $divTipoRes->getHoraFinal2Domingo();

                                    $errorHorarioDivision = true;
                                    if ($horaInicioReservaValida >= $horaInicialLunesMañana && $horaFinalReservaValida <= $horaFinalLunesMañana) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    } else if ($horaInicioReservaValida >= $horaInicialLunesTarde && $horaFinalReservaValida <= $horaFinalLunesTarde) {
                                        $errorHorarioDivision = false;

                                        $divTipoRes->setDisponibilidad(true);
                                    }
                                    if ($errorHorarioDivision == false) {
                                        if ($divTipoRes->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($divTipoRes->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {

                                                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                                                    
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                                        
                                                        if ($tiposReservaEscenarioDeportivo != null){
                                                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                                                    if ($divTipoRes->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {
                                                                        
                                                                        $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                                                        $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                                                        $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                                                        $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                                                        $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;

                                                                        
                                                                        $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                                                        $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                                                        $interval = $datetime1->diff($datetime2);

                                                                        $horas = $interval->format("%H%");
                                                                        $minutos = $interval->format("%I%");
                                                                        $minutosReserva = ($horas * 60) + $minutos;

                                                                        if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva){                                                                            
                                                                            $error = 53;
                                                                            $translator = $this->get('translator');

                                                                            $horas = floor($tiempoMinimoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                                            $horas = floor($tiempoMaximoTipoReserva / 60);
                                                                            $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                                            if ($minutos < 10) {
                                                                                $minutos = "0" . $minutos;
                                                                            }
                                                                            $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                                            $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));

                                                                            $divTipoRes->setDisponibilidad(false);
                                                                            $divTipoRes->setErrorDisponibilidad($mensaje);
                                                                            $divTipoRes->setNumeroErrorDisponibilidad($error);
                                                                            break;
                                                                        }
                                                                    }                                                                
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    } else if ($errorHorarioDivision == true) {
                                        $error = 48;

                                        $divTipoRes->setDisponibilidad(false);
                                        $divTipoRes->setNumeroErrorDisponibilidad($error);
                                        break;
                                    }
                                } else {

                                    $error = 27;
                                    $divTipoRes->setDisponibilidad(false);
                                    $divTipoRes->setNumeroErrorDisponibilidad($error);
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $minimoHoras = 0;
            $maximoHoras = 0;
            $diasPreviosReserva = 0;

            if (count($divisiones) > 0) {
                $divisioValida = false;
                $divisionNombre = null;
                $divisionErrorDisponibilidad = null;
                $divisionNumeroErrorDisponibilidad = null;

                foreach ($divisiones as $divTipoRes) {
                    $divisionNombre = $divTipoRes->getNombre();
                    $divisionErrorDisponibilidad = $divTipoRes->getErrorDisponibilidad();
                    $divisionNumeroErrorDisponibilidad = $divTipoRes->getNumeroErrorDisponibilidad();
                    
                    if ($divTipoRes->getDisponibilidad() == true) {
                        $divisioValida = true;
                        break;
                    }
                }
                                            
                if ($divisioValida == false) {
                    $fechasNoDisponibles = array();
                    array_push($fechasNoDisponibles, array('error' => $divisionNumeroErrorDisponibilidad, 'mensajeError' => $divisionErrorDisponibilidad));
                    $agregoFecha = true;

                    $respuesta = array(
                        'respuesta' => false,
                        'error' => $divisionNumeroErrorDisponibilidad,
                        'mensajeError' => $divisionErrorDisponibilidad,
                        'minimoHoras' => $minimoHoras, 
                        'maximoHoras' => $maximoHoras, 
                        'diasPrevios' => $diasPreviosReserva
                    );
                    return $respuesta;                    
                }
            }

            /*$divisionTipoReserva = $em->getRepository('LogicBundle:Division')->createQueryBuilder('division')
                            ->join('division.tiposReservaEscenarioDeportivo', 'tiposReservaEscenarioDeportivo')
                            ->join('division.escenarioDeportivo', 'escenarioDeportivo')
                            ->where('tiposReservaEscenarioDeportivo.tipoReserva = :tipoReserva')
                            ->andWhere('escenarioDeportivo.id = :idEscenarioDeportivo')
                            ->setParameter('tipoReserva', $tipo_reserva ?: 0)
                            ->setParameter('idEscenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();*/

            $fechasDisponibles = array();
            $fechasNoDisponibles = array();
            $fechasMantenimiento = array();

            foreach ($fechasAConsultar as $fecha) {

                $divisionValida = array();
                $divisionNoValida = array();
                //$error = '';
                $datetime = new \DateTime();
                $date = explode('-', $fecha);
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
                } else {
                    $agregoFecha = false;
                    foreach ($divisiones as $division) {

                        $reservas = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                            ->innerJoin('reserva.escenarioDeportivo', 'escenarioDeportivo')
                            ->join('reserva.divisiones', 'divisiones')
                            ->where('escenarioDeportivo.id = :escenario_deportivo')
                            ->andWhere('reserva.id != :reserva_id')
                            ->andWhere('reserva.estado != :rechazado')
                            ->andWhere('reserva.estado != :prereserva')
                            ->andWhere('reserva.fechaInicio <= :fecha')
                            ->andWhere('reserva.fechaFinal >= :fecha')
                            ->andWhere('reserva.tipoReserva = :tipoReserva')
                            ->andWhere('divisiones.division = :division')
                            ->orderBy("reserva.id", 'ASC')
                            ->setParameter('escenario_deportivo', $escenario ?: 0)
                            ->setParameter('rechazado', 'Rechazado')
                            ->setParameter('prereserva', 'Pre-Reserva')
                            ->setParameter('reserva_id', 0)
                            ->setParameter('fecha', $fecha ?: 0)
                            ->setParameter('tipoReserva', $tipo_reserva ?: 0)
                            ->setParameter('division', $division->getId() ?: 0)
                            ->getQuery()->getResult();

                        $totalUsuariosReserva = 0;
                        foreach ($reservas as $res) {
                            if ($res->getTipoReserva() != null) {
                                if ($res->getTipoReserva()->getNombre() == 'Mantenimiento') {
                                    $datetime = new \DateTime();
                                    $date = explode('-', $fecha);
                                    $datetime->setDate($date[0], $date[1], $date[2]);
                                    array_push($fechasMantenimiento, array('fecha' => $datetime->format('Y/m/d')));
                                    $agregoFecha = true;
                                    break;
                                }

                                $horaInicioRes = strtotime($res->getHoraInicial()->format('H:i'));
                                $horaFinRes = strtotime($res->getHoraFinal()->format('H:i'));

                                //$division = $res->getDivision();

                                if (
                                        ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes /* && $division == null */ /* && $res->getEstado() == "Pendiente" */) ||
                                        ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes /* && $validarDivision */ ) ||
                                        ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes /* && $division == null */ /* && $res->getEstado() == "Pendiente" */) ||
                                        ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes /* && $validarDivision */ ) ||
                                        ($hora_inicial <= $horaInicioRes && $horaInicioRes <= $hora_final /* && $division == null */ /* && $res->getEstado() == "Pendiente" */) ||
                                        ($hora_inicial <= $horaInicioRes && $horaInicioRes <= $hora_final /* && $validarDivision */ ) ||
                                        ($hora_inicial <= $horaFinRes && $horaFinRes <= $hora_final /* && $division == null */ /* && $res->getEstado() == "Pendiente" */) ||
                                        ($hora_inicial <= $horaFinRes && $horaFinRes <= $hora_final /* && $validarDivision */ )
                                ) {
                                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "Piscina") {
                                        
                                        $totalUsuariosReserva += 10;

                                        $maximoUsuariosEscenario = 0;

                                        if ($division->getTiposReservaEscenarioDeportivo() != null) {
                                            foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                                                if ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() != null) {
                                                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tipoReservaDivisiones) {
                                                        $maximoUsuariosEscenario += $tipoReservaDivisiones->getUsuariosMaximos();
                                                    }
                                                }
                                            }
                                        }


                                        if ($totalUsuariosReserva >= $maximoUsuariosEscenario) {

                                            $error = 50;

                                            $datetime = new \DateTime();
                                            $date = explode('-', $fecha);
                                            $datetime->setDate($date[0], $date[1], $date[2]);

                                            array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'diasPrevios' => $diasPreviosReserva));
                                            $agregoFecha = true;
                                            break;
                                        }
                                    } else {
                                        $datetime = new \DateTime();
                                        $date = explode('-', $fecha);
                                        $datetime->setDate($date[0], $date[1], $date[2]);
                                        $error = 5;
                                        array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'diasPrevios' => $diasPreviosReserva));
                                        $agregoFecha = true;
                                        break;
                                    }
                                } else {
                                    $tiempoLim = true;
                                }
                            }
                        }

                        if ($agregoFecha == true) {
                            break;
                        }
                    }
                    if ($agregoFecha == false) {
                        $datetime = new \DateTime();
                        $date = explode('-', $fecha);
                        $datetime->setDate($date[0], $date[1], $date[2]);
                        array_push($fechasDisponibles, array('fecha' => $datetime->format('Y/m/d')));
                    }
                }
            }
                  
            if (count($fechasDisponibles)> 0) {
                $respuesta = array(
                    'respuesta' => true,
                    'error' => $error,
                    'minimoHoras' => $minimoHoras, 
                    'maximoHoras' => $maximoHoras, 
                    'diasPrevios' => $diasPreviosReserva

                );
            }else if (count($fechasNoDisponibles)> 0) {
                $respuesta = array(
                    'respuesta' => false,
                    'error' => $error,
                    'minimoHoras' => $minimoHoras, 
                    'maximoHoras' => $maximoHoras, 
                    'diasPrevios' => $diasPreviosReserva

                );
            }
            
            return $respuesta;
        }else{
            $respuesta = array(
                'respuesta' => false,
                'error' => $error,
                'minimoHoras' => $minimoHoras, 
                'maximoHoras' => $maximoHoras, 
                'diasPrevios' => $diasPreviosReserva

            );         
            return $respuesta;
        }
    }

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Reserva",
     *  resource=true,
     *  description="genera los datos para la vista del paso 2",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={     
     *      {
     *          "name"="reserva_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="reserva id"
     *      }
     * 
     *  }
     * )
     * @Route("/datosReservaPasoDos")
     * @Method("POST")
     */
    public function getDatosReservaPasoDos(Request $request, $idReserva)
    {
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();    

        if($user !=null  || $user !=''){
            $reserva = $em->getRepository('LogicBundle:Reserva')->find($idReserva);
            $object = $reserva;
            
            $idEscenario = $object->getEscenarioDeportivo()->getId();
            //obtener y formatear divisiones de un escenario
            $dql = "SELECT d FROM LogicBundle:Division d 
            INNER JOIN d.tipoReservaEscenarioDeportivoDivisiones tredd 
            INNER JOIN tredd.tipoReservaEscenarioDeportivo tred 
            LEFT  JOIN LogicBundle:DivisionReserva dr WITH dr.division = d.id 
            LEFT  JOIN LogicBundle:Reserva r WITH r.id = dr.reserva 
            AND (r.fechaInicio BETWEEN :fechaInicial AND :fechaFinal OR r.fechaFinal BETWEEN :fechaInicial AND :fechaFinal)
            AND (r.horaInicial BETWEEN :horaInicial AND :horaFinal OR r.horaFinal BETWEEN :horaInicial AND :horaFinal)
            WHERE d.escenarioDeportivo = :escenario 
            AND tred.tipoReserva = :tipoReserva
            and r.id IS NULL OR r.id = :id";

            $parameters = array();
            $parameters["fechaInicial"] = $object->getFechaInicio();
            $parameters["fechaFinal"] = $object->getFechaFinal();
            $parameters["horaInicial"] = $object->getHoraInicial();
            $parameters["horaFinal"] = $object->getHoraFinal();
            $parameters["escenario"] = $object->getEscenarioDeportivo();
            $parameters["tipoReserva"] = $object->getTipoReserva();
            $parameters["id"] = $idReserva;



            $query = $em->createQuery($dql);
            foreach ($parameters as $name => $value) {
                $query->setParameter($name, $value);
            }
            $divisioneDisponibles = $query->getResult();
            $divisioneTotales = $em->getRepository("LogicBundle:Division")
                    ->createQueryBuilder('division')
                    ->join('division.tipoReservaEscenarioDeportivoDivisiones', 'tipoReservaEscenarioDeportivoDivisiones')
                    ->join('tipoReservaEscenarioDeportivoDivisiones.tipoReservaEscenarioDeportivo', 'tipoReservaEscenarioDeportivo')
                    ->where('division.escenarioDeportivo = :escenarioDeportivo')
                    ->setParameter('escenarioDeportivo', $object->getEscenarioDeportivo())
                    ->getQuery()
                    ->getResult();
            $divisionesNoDisponibles = [];
            $divisiones = [];
            foreach ($divisioneTotales as $division) {                
                $encontrada = false;
                foreach ($divisioneDisponibles as $division2) {
                    if ($division->getId() === $division2->getId()) {
                        $divisiones[$division2->getNombre()] = $division2->getId();
                        $encontrada = true;
                        break;
                    }
                }
                if (!$encontrada) {
                    $divisionesNoDisponibles[] = [$division->getNombre() => $division->getId()];
                }
            }
            $divisioneSeleccionadas = $em->getRepository("LogicBundle:Division")
                    ->createQueryBuilder('d')
                    ->join('d.reservas', 'dr')
                    ->where('dr.reserva = :reserva')
                    ->setParameter('reserva', $object->getId())
                    ->getQuery()
                    ->getResult();
            $seleccion = [];
            foreach ($divisioneSeleccionadas as $division) {
                $seleccion[$division->getId()] = $division->getId();
            }
            
            $cart = array();
            
            foreach ($divisiones as $divDispo) {
                $indice = array_keys($divNoDispo);
                $indice = $indice[0];
                $cart[] = array('id' => $divNoDispo[$indice], 'nombre' => $indice, 'disponible' => true);
            }

            foreach ($divisionesNoDisponibles as $divNoDispo) {
                $indice = array_keys($divNoDispo);
                $indice = $indice[0];
                $cart[] = array('id' => $divNoDispo[$indice], 'nombre' => $indice, 'disponible' => false);
            }

            $respuesta = array();
            $respuesta['divisiones'] = $cart;

            return $respuesta;

        }else{            
            $view = $this->view($this->trans->trans("api.mensajes.error.encontrado"), 200);
            return $this->handleView($view);
        }
    }

}