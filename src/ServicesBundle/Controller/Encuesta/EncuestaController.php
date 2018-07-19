<?php

namespace ServicesBundle\Controller\Encuesta;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

use Symfony\Component\Validator\Constraints\NotBlank;
use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Entity\Division;
use LogicBundle\Entity\Disciplina;
use LogicBundle\Entity\DisciplinasEscenarioDeportivo;
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\Encuesta;
use LogicBundle\Entity\EncuestaOpcion;
use LogicBundle\Entity\EncuestaPregunta;
use LogicBundle\Entity\EncuestaRespuesta;

use UserBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use DateTime;

class EncuestaController extends Controller implements  TokenAuthenticatedController {

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
    *  section="Encuesta",
    *  resource=true,
    *  description="obtiene las encuestras para mostrar",
    *  statusCodes = {
    *     200 = "Ok",
    *     400 = "Errores"
    *  }    
    * )
    * 
    * @Get("/encuesta/mostrar")
    */
    public function getEncuesta(Request $request)
    { 
        date_default_timezone_set('America/Bogota');
        //validador, sirver para comprobar si hay encuesta en la comuna o en las estrategio y oferta.
        $isQuery= false;
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 200);
        $em = $this->getDoctrine()->getManager();
    
        if($user == null){
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);            
        }
    
        $encuestaRepository = $em->getRepository("LogicBundle:Encuesta");     
        $query = $encuestaRepository->createQueryBuilder('e');
               
        if($user->getBarrio() != null){
            if($user->getBarrio()->getComuna() != null){
                $comuna = $user->getBarrio()->getComuna();
                $query
                    ->setParameter('comuna', $comuna)
                    ->orWhere('e.comuna = :comuna')
                    ->getQuery();
                $isQuery = true;
            }

        }
        // comentado porque da error 500 y no se sabe como funciona el codigo
        /*if($user->getPreinscripciones() != null)  {
            foreach ($user->getPreinscripciones() as $preinscripcionOferta) {
                if($preinscripcionOferta->getOferta() != null){
                    if($preinscripcionOferta->getEstrategia() != null){
                        $query->setParameter('estrategia', $user->getPreinscripciones()->getOferta()->getEstrategia())
                        ->orWhere('e.estrategia = :estrategia')
                        ->setParameter('oferta', $preinscripcionOferta->getOferta())
                        ->andWhere('e.estrategia = :oferta')
                        ->getQuery();
                        $isQuery = true;
                   }     
                }
            }
        }*/

        if($user->getPreinscripciones() != null)  {
            foreach ($user->getPreinscripciones() as $preinscripcionOferta) {                
                if($preinscripcionOferta->getOferta() != null){

                    if($preinscripcionOferta->getOferta()->getEstrategia() != null){
                        $query->setParameter('estrategia', $preinscripcionOferta->getOferta()->getEstrategia())
                        ->orWhere('e.estrategia = :estrategia')
                        ->setParameter('oferta', $preinscripcionOferta->getOferta())
                        ->andWhere('e.estrategia = :oferta')
                        ->getQuery();

                        $isQuery = true;
                   }     
                }
            }
        }

        if(!$isQuery){
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }

        //ordenar consulta por fecha
        $encuestasQuery = $query->andWhere('e.activo = true')
                                ->orderBy('e.fechaInicio', 'DESC')  
                                ->getQuery()
                                ->getResult();

        //fecha actual del sistema                
        $fechaActual = new \DateTime();
        //Fecha consultada
        $fechaConsulta = null;

        $encuestaCumple = array();
        //validaciones para mostrar encuesta
        if($encuestasQuery != null){
            foreach ($encuestasQuery as $encuestaInte) {
                $encuestaPregunta = $encuestaInte->getEncuestaPreguntas()[0];
                //Obtener la ultima respuesta de la encuesta que se esta interando
                $respuestaUser = $em->getRepository("LogicBundle:EncuestaRespuesta")
                                    ->createQueryBuilder('er')
                                    ->join('er.encuestaOpcion','eo')
                                    ->where('eo.encuestaPregunta = :encuestaPreguntaCurrent')
                                    ->setParameter('encuestaPreguntaCurrent', $encuestaPregunta)
                                    ->andWhere('er.usuario = :user')
                                    ->setParameter('user', $user)
                                    ->orderBy('er.fechaRespuesta', 'DESC')              
                                    ->getQuery()
                                    ->getResult();
        
                if($respuestaUser != null){
                    $fechaConsulta = current($respuestaUser)->getFechaRespuesta();
                }else{
                    $fechaConsulta = null;
                }
                

                $fechaInicioEncuesta = $encuestaInte->getFechaInicio();
                $duracionEncuesta = $encuestaInte->getDuracion();
                $validarFecha = true;
                $temFechaConPeriodoValidador = $fechaInicioEncuesta;
              
                while ($validarFecha) {
                   
                    $temFechaConPeriodoValidador->modify('+'.($encuestaInte->diasPeriodo()+$duracionEncuesta).'day');
            
                    if($fechaActual < $temFechaConPeriodoValidador){

                        $fechaInicio = clone $temFechaConPeriodoValidador;
                        $fechaInicio->modify('-'.($encuestaInte->diasPeriodo()+$duracionEncuesta).'day');
                        $fechaFinEjecucionEncuesta = clone $fechaInicio;
                        $fechaFinEjecucionEncuesta->modify('+'.$duracionEncuesta.'day');
                        if($fechaInicio <= $fechaActual  && $fechaActual <= $fechaFinEjecucionEncuesta){
                            if($fechaConsulta != null){
                                if($fechaInicio <= $fechaConsulta  && $fechaConsulta <= $fechaFinEjecucionEncuesta){
                                    //no se hace nada 
                                    $validarFecha = false;   
                                }else{
                                    //no ha respondido la encuesta
                                    array_push($encuestaCumple,array(
                                        "encuesta" => $encuestaInte, 
                                        "fechaSinPeriodo" => $fechaInicio, 
                                        "fechaConPeriodo" => $fechaFinEjecucionEncuesta,
                                    ));
                                    $validarFecha = false; 
                                }
                            }else{
                                //no ha respondido la encuesta
                                array_push($encuestaCumple,array(
                                    "encuesta" => $encuestaInte, 
                                    "fechaSinPeriodo" => $fechaInicio, 
                                    "fechaConPeriodo" => $fechaFinEjecucionEncuesta,
                                ));
                                $validarFecha = false; 
                            }

                        }else{
                            $validarFecha = false; 
                        }
                    }else{
                        //se vuelve a iterar para sumar los dias de periodicidad con la duraci�n 
                        //para alcanzar a la fecha actual y comparar si se muestra la encuesta
                        $validarFecha = true; 
                    }
                }   
            }
        }
   
        if(isset($encuestaCumple)){
            if(count($encuestaCumple) > 0){
                //Se construye el formulario apartir del current de las encuestar habilitadas
                $arrayEncuesta = current($encuestaCumple);
                $encuestaCuerrent = $arrayEncuesta['encuesta'];
                $fechaConPeriodo = $arrayEncuesta['fechaConPeriodo'];
                $fechaSinPeriodo = $arrayEncuesta['fechaSinPeriodo'];
                
                //Verificar si ya se cumplio con la muestra    
                $encuestaPregunta = $encuestaCuerrent->getEncuestaPreguntas()[0];        
                $countRespuesta = $em->createQueryBuilder()
                                            ->select('count(ep.id)')
                                            ->from('LogicBundle:EncuestaRespuesta', 'ep')
                                            ->innerJoin('ep.encuestaOpcion','eo')
                                            ->where('eo.encuestaPregunta = :encuestaPreguntaCurrent')
                                            ->setParameter('encuestaPreguntaCurrent', $encuestaPregunta)
                                            ->andWhere('ep.fechaRespuesta BETWEEN :fechaMin AND :fechaMax')
                                            ->setParameter('fechaMin', $fechaSinPeriodo)
                                            ->setParameter('fechaMax', $fechaConPeriodo)
                                            ->getQuery()
                                            ->getSingleScalarResult();
                                        
        
                //Obtener el total de muestra
                $muestraTotal = 0;
    
                if($encuestaCuerrent->getMuestraComuna()){
                    $muestraTotal +=  $encuestaCuerrent->getMuestraComuna();
                }
                if($encuestaCuerrent->getMuestraEstrategia()){
                    $muestraTotal += $encuestaCuerrent->getMuestraEstrategia();
                }
                if($encuestaCuerrent->getMuestraOferta()){
                    $muestraTotal += $encuestaCuerrent->getMuestraOferta();
                }
                if( $countRespuesta >= $muestraTotal ){
                    $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 204);
                    return $this->handleView($view);
                }
                $preguntas = $encuestaCuerrent->getEncuestaPreguntas();
                if($preguntas) {
                    $ids=array();
                    foreach($preguntas as $pregunta){
                        array_push($ids, $pregunta->getId());
                    }
                    $dql = "SELECT e, eo
                    FROM LogicBundle:EncuestaPregunta e 
                    JOIN e.encuestaOpciones eo
                    WHERE e.id IN (:ids)
                    ";
                    $parameters = [];
                    $parameters['ids'] = $ids;
                    
                    $responseBuilder = $this->container->get('ito.tools.response.builder');
                    $entity = $responseBuilder->getCollection($dql, $parameters);
                    $arrayEncuesta = array();
                    foreach ($entity['items'] as $encuesta) {
                        $encuestaPregunta = array('id' => $encuesta->getId(), 'nombre' => $encuesta->getNombre());
                        $encuestaOpciones = array();
                        foreach ($encuesta->getEncuestaOpciones() as $opcion) {
                            array_push($encuestaOpciones, array('id'=>$opcion->getId(), 'nombre' => $opcion->getNombre()));
                        }                        
                        array_push($arrayEncuesta, array('id' => $encuesta->getId(), 'nombre' => $encuesta->getNombre(), 'encuesta_opciones' => $encuestaOpciones));                        
                    }
                    
                    $entity['items'] = $arrayEncuesta;
                    $view = $this->view($entity, 200);

                }else{
                    $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                    $view = $this->view($error, 404);                    
                }
                return $this->handleView($view);
                
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 404);
                return $this->handleView($view);
            }       
        }

        $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
        $view = $this->view($error, 404);
        return $this->handleView($view);        
    }



    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Encuesta",
     *  resource=true,
     *  description="Guarda las respuesta de una encuesta",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={     
     *      {
     *          "name"="encuesta_respuesta",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="encuesta_id"
     *      },
     *  }
     * )
     * 
     * 
     * @Route("/guardarEncuesta")
     * @Method("POST")
     */
    public function PostGuardarEncuesta(Request $request)
    {
        date_default_timezone_set('America/Bogota');

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 200);

        $em = $this->getDoctrine()->getManager();
    

        if($user == null){

            $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 204);
            return $this->handleView($view);

        }

        $encuesta_respuesta = $request->get('encuesta_respuesta');

        $encuesta_respuesta = str_replace(array("{", "}"), "", $encuesta_respuesta);
        
        $encuesta_respuesta = explode(',', $encuesta_respuesta);
        
        foreach ($encuesta_respuesta as $asw) {
            if ($asw == "") {
                if (($key = array_search($asw, $encuesta_respuesta)) !== false) {
                    unset($encuesta_respuesta[$key]);
                }
            }
        }

        $fechaActual = new DateTime();
        
        $arrayRespuestas = array();
        foreach ($encuesta_respuesta as $asw) {
            $encuestaOpcion = $this->em->getRepository("LogicBundle:EncuestaOpcion")->find($asw); 
                
            $respuestas = new EncuestaRespuesta();
            $respuestas->setEncuestaOpcion($encuestaOpcion);
            $respuestas->setFechaRespuesta($fechaActual);
            $respuestas->setUsuario($user);
            $this->em->persist($respuestas);                
            $this->em->flush();
            array_push($arrayRespuestas, array('id' => $respuestas->getId(), 'fecha' => $fechaActual->format('Y-m-d'), 'respuesta' => $respuestas->getEncuestaOpcion()->getNombre()));                        
        }
        
        if ($arrayRespuestas) {            
            $view = $this->view($arrayRespuestas, 200);
        } else {            
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 400);
        }
        return $this->handleView($view);
    }
}