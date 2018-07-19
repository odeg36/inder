<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class OfertaReportePDFController extends Controller {

   /* variables a usar en la el controlador */
   protected $session;
   protected $trans;
   
   /* Se inican variables para el controlador */
   public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
       parent::setContainer($container);

       $this->session = $container->get("session");
       $this->trans = $container->get("translator");
   }


   /* Controlador que permite Crear un reporte de los asistentes a las ofertas */
    /**
     * @Route("/reporte/ofertaReportePDF", name="reporte_oferta_PDF_exportar", options={"expose"=true})
     */
    public function ofertaReportePDFAction(Request $request) {
      
        $idOfertas = $request->get('id');
        $fechaInicio = $request->get('fechaInicial');
        $fechaFin = $request->get('fechaFinal');
        $ofertas = array();
        
        $em = $this->getDoctrine()->getManager();
        $response = new Response();

        foreach ($idOfertas as $idOferta) {
            $oferta = $em->getRepository("LogicBundle:Oferta")->find($idOferta);
            array_push($ofertas, $oferta);
        }
      
        if($ofertas != null){
            $formadores = array();
            $ofertasNombre = array();
            $asistencias = array();
            $preinscripciones = array();
                
            foreach ($ofertas as $oferta) {
                $formador = $oferta->getFormador()->nombreCompleto();
                array_push($formadores, $formador);
            
                $nombreOferta = $oferta->getNombre();
                array_push($ofertasNombre, $nombreOferta);

                $asistencia = $em->getRepository('LogicBundle:Asistencia')->createQueryBuilder('asistencia')            
                    ->andWhere('asistencia.oferta = :idOferta')                    
                    ->andWhere('asistencia.asistio = :asistio')                
                    ->andWhere('asistencia.fecha <= :fechaFinal')
                    ->andWhere('asistencia.fecha >= :fechaInicial')
                    ->setParameter('idOferta', $oferta->getId() ?: 0)                    
                    ->setParameter('asistio', true)           
                    ->setParameter('fechaFinal', $fechaFin)
                    ->setParameter('fechaInicial', $fechaInicio)
                    ->getQuery()->getResult();
                
                $asistencias[$oferta->getId()] = count($asistencia);

                $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->createQueryBuilder('p')            
                    ->Where('p.activo = :activo')                    
                    ->andWhere('p.oferta = :oferta')
                    ->setParameter('activo', 1)                    
                    ->setParameter('oferta', $oferta)
                    ->getQuery()->getResult();

                $preinscripciones[$oferta->getId()] = count($preinscripcion);
            }
            
            $html = $this->renderView('AdminBundle:Oferta\Reporte:reporteOfertaPDF.html.twig', [
                'ofertas' => $ofertas,
                'formadores' => $formadores,
                'ofertasNombre' => $ofertasNombre,
                'asistencias' => $asistencias,
                'preinscripciones' => $preinscripciones,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);
            
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                    'encoding' => 'utf-8'
                ]),
                'Ofertas.pdf'
            );
        }else{
            $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }
    }
}