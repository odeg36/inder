<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservaController extends Controller {
    /* variables a usar en la el controlador */
    protected $session;
    protected $trans;
    
    /* Se inican variables para el controlador */
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->session = $container->get("session");
        $this->trans = $container->get("translator");
    }

    /* Controlador que permite realizar la busqueda de los aistenetes a una oferta y fecha en concreto */
    /**
     * @Route("/usaurios/reserva", name="usuarios_reserva", options={"expose"=true})
     */
    public function usuariosReservaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $reservaId = $request->get('reserva');
        $fecha = $request->get('fecha');
        
        $asistentes = $em->getRepository("LogicBundle:AsistenciaReserva")->buscarAsistentesReservaHorario($reservaId, $fecha);
        
        $serializer = $this->container->get('jms_serializer');
        $asistentes = $serializer->serialize($asistentes, 'json');
        
        $response->setContent($asistentes);
        
        return $response;
    }
    
    /* Hace la busqueda de los usuarios que se pueden registrar a la asistencia */
    /**
     * @Route("/usaurios/asistencia/reserva", name="reserva_asistencia", options={"expose"=true})
     */
    public function usuariosAsistenciaReservaAction(Request $request) {
        $response = new Response();
        $json = [];

        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->query->get('tipoDocumento');
        $tipoIdentificacion = $this->session->get($tipoIdentificacion);
        $numeroIdentificacion = $request->query->get('q');
        $reserva = $request->query->get('reserva');
        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneById($tipoIdentificacion);
        if (!$abreviaturaIdentificacion) {
            $response->setContent(json_encode($json));
            return $response;
        }

        $usuario = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $results = $em->getRepository('ApplicationSonataUserBundle:User')->buscarUsuariosReserva($usuario, $reserva);

        if ($results) {
            $json = [];
            foreach ($results as $usuario) {
                $texto = $usuario->getNumeroIdentificacion();
                if(!$texto){
                    $texto = "";
                }
                $json[] = array(
                    'id' => $usuario->getId(),
                    'text' => $texto,
                );
            }
        }

        $response->setContent(json_encode($json));
        return $response;
    }
    
    /* Controlador que permite Crear un reporte de los asintes a las ofertas */
    /**
     * @Route("/reporte/asistentes/reserva", name="reporte_asistentes_reserva", options={"expose"=true})
     */
    public function generarReporteReservaAsistenciaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $reservaId = $request->get('reserva');
        $fecha = $request->get('fecha');
        $dia = $request->get('dia');
        
        $asistentes = $em->getRepository("LogicBundle:AsistenciaReserva")->buscarAsistentesReservaHorario($reservaId, $fecha);
        
        if(count($asistentes) <= 0){
            $this->addFlash("sonata_flash_error", $this->trans->trans("error.preinscripcion.reporte_no_data", ["%fecha%" => $fecha]));
            
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }
        
        $programacion = $em->getRepository("LogicBundle:DiaReserva")->buscarProgramacion($dia, $reservaId);
        $reserva = $em->getRepository("LogicBundle:Reserva")->findOneById($reservaId);
        
        $html = $this->renderView('AdminBundle:Reservas\Reporte:reporte.asistentes.html.twig', [
            'asistentes' => $asistentes,
            'programacion' => $programacion,
            'fecha' => $fecha,
            'reserva' => $reserva
        ]);
        
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                'encoding' => 'utf-8'
            ]),
            'asistentes-' . $fecha . '.pdf'
        );
    }
}
