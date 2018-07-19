<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EquiposEventoController extends Controller {

   /* variables a usar en la el controlador */
   protected $session;
   protected $trans;
   
   /* Se inican variables para el controlador */
   public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
       parent::setContainer($container);

       $this->session = $container->get("session");
       $this->trans = $container->get("translator");
   }


   /* Controlador que permite crear un reporte de los equipos pertenecientes a un evento*/
    /**
     * @Route("/reporte/equiposevento", name="reporte_equipos_evento", options={"expose"=true})
     */
    public function generarReporteEquiposEventoAction(Request $request) {
       
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $eventoId = $request->get('idevento');
        $em = $this->getDoctrine()->getManager();

        $evento = $em->getRepository("LogicBundle:Evento")->createQueryBuilder('evento') 
        ->Where('evento.id = :idEvento')            
        ->setParameter('idEvento', $eventoId)    
        ->getQuery()->getOneOrNullResult();
        
        $equipos = $em->getRepository('LogicBundle:EquipoEvento')
                ->createQueryBuilder('equipoEvento')
                ->Where('equipoEvento.evento = :evento')
                ->setParameter('evento', $eventoId)
                ->getQuery()
                ->getResult();
            
        if($equipos != null){
        
            $html = $this->renderView('AdminBundle:Evento\Equipos:reporte.equipos_evento.html.twig', [            
                'equipos' => $equipos
            ]);
            
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                    'encoding' => 'utf-8'
                ]),
                'Equipos Evento '.$evento->getNombre().'.pdf'
            );

      }else{
        $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
        $url = $request->headers->get('referer');
        return $this->redirect($url);
      }

    }


}