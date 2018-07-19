<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PreinscripcionController extends Controller {
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
     * @Route("/usaurios/oferta/preinscritos", name="usuarios_oferta_preinscritos", options={"expose"=true})
     */
    public function usuariosPreinscritosOfertaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $ofertaId = $request->get('oferta');
        $fecha = $request->get('fecha');
        
        $asistentes = $em->getRepository("LogicBundle:Asistencia")->buscarAsistentesOfertaHorario($ofertaId, $fecha);
        
        $serializer = $this->container->get('jms_serializer');
        $asistentes = $serializer->serialize($asistentes, 'json');
        
        $response->setContent($asistentes);
        
        return $response;
    }
    
    /* Hace la busqueda de los usuarios que se pueden registrar a la asistencia */
    /**
     * @Route("/usaurios/asistencia", name="oferta_asistencia", options={"expose"=true})
     */
    public function usuariosAsistenciaAction(Request $request) {
        $response = new Response();
        $json = [];

        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->query->get('tipoDocumento');
        $tipoIdentificacion = $this->session->get($tipoIdentificacion);
        $numeroIdentificacion = $request->query->get('q');
        $oferta = $request->query->get('oferta');
        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneById($tipoIdentificacion);
        if (!$abreviaturaIdentificacion) {
            $response->setContent(json_encode($json));
            return $response;
        }

        $usuario = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $results = $em->getRepository('ApplicationSonataUserBundle:User')->buscarUsuariosPreinscritos($usuario, $oferta);

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
     * @Route("/reporte/asistentes", name="reporte_asistentes", options={"expose"=true})
     */
    public function generarReporteAsistenciaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $ofertaId = $request->get('oferta');
        $fecha = $request->get('fecha');
        
        $asistentes = $em->getRepository("LogicBundle:Asistencia")->buscarAsistentesOfertaHorario($ofertaId, $fecha);
        
        if(count($asistentes) <= 0){
            $this->addFlash("sonata_flash_error", $this->trans->trans("error.preinscripcion.reporte_no_data", ["%fecha%" => $fecha]));
            
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }
        foreach ($asistentes as $as) {
            if ($as->getProgramacion() != null) {
                $programacion = $as->getProgramacion()->getId();
                break;
            }
        }
        $programacion = $em->getRepository("LogicBundle:Programacion")->findOneById($programacion);
        $oferta = $em->getRepository("LogicBundle:Oferta")->findOneById($ofertaId);
        
        $html = $this->renderView('AdminBundle:Preinscripcion\Reporte:reporte.asistentes.html.twig', [
            'asistentes' => $asistentes,
            'programacion' => $programacion,
            'fecha' => $fecha,
            'oferta' => $oferta
        ]);
        
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                'encoding' => 'utf-8'
            ]),
            'Asistentes-' . $fecha . '.pdf'
        );
    }
}
