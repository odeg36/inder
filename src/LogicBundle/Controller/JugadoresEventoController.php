<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class JugadoresEventoController extends Controller {

   /* variables a usar en la el controlador */
   protected $session;
   protected $trans;
   
   /* Se inican variables para el controlador */
   public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
       parent::setContainer($container);

       $this->session = $container->get("session");
       $this->trans = $container->get("translator");
   }


   /* Controlador que permite crear un reporte de los jugadores pertenecientes a un evento*/
    /**
     * @Route("/reporte/jugadoresevento", name="reporte_jugadores_evento", options={"expose"=true})
     */
    public function generarReporteJugadoresEventoAction(Request $request) {
       
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
        
        $jugadores = $em->getRepository('LogicBundle:JugadorEvento')
                ->createQueryBuilder('j')
                ->join('j.equipoEvento', 'e')
                ->Where('j.equipoEvento IN (:equipos)')                                       
                ->andWhere('e.evento = :evento')
                ->setParameter('equipos', $equipos)
                ->setParameter('evento', $eventoId)
                ->getQuery()
                ->getResult();
            
        if($jugadores != null){
        
            $html = $this->renderView('AdminBundle:Evento\Jugadores:reporte.jugadores_evento.html.twig', [            
                'jugadores' => $jugadores
            ]);
            
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                    'encoding' => 'utf-8'
                ]),
                'Jugadores Evento '.$evento->getNombre().'.pdf'
            );

      }else{
        $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
        $url = $request->headers->get('referer');
        return $this->redirect($url);
      }

    }


}