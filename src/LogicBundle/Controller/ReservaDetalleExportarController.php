<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReservaDetalleExportarController extends Controller {

   /* variables a usar en la el controlador */
   protected $session;
   protected $trans;
   
   /* Se inican variables para el controlador */
   public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
       parent::setContainer($container);

       $this->session = $container->get("session");
       $this->trans = $container->get("translator");
   }


   /* Controlador que permite Crear un reporte de los asintes a las ofertas */
    /**
     * @Route("/reporte/reservaDetalleExportar", name="reporte_reserva_detalle_exportar", options={"expose"=true})
     */
    public function generarReporteAsistenciaAction(Request $request) {      
      foreach ($request->query as $pa) {
        $idReserva = $pa;
        break;
      }
      
      $em = $this->getDoctrine()->getManager();
      $response = new Response();
      $reserva = $em->getRepository("LogicBundle:Reserva")->createQueryBuilder('reserva') 
          ->Where('reserva.id = :idReserva')            
          ->setParameter('idReserva', $idReserva)    
          ->getQuery()->getOneOrNullResult();
      
      if($reserva != null)
      {
        $usuarios = $reserva->getUsuarios();
        $usuariosReserva = array();
        foreach ($usuarios as $user) {
            array_push($usuariosReserva, array('nombre' => $user->nombreCompleto()));            
        }

        $dias = $reserva->getDiaReserva();
        $diasReserva = array();
        foreach ($dias as $dia) {
            array_push($diasReserva, array('dia' => $dia->getDia()->getNombre()));
        }
      
        $html = $this->renderView('AdminBundle:Reservas\Reporte:reporte.detallereservas.html.twig', [            
            'reserva' => $reserva,
            'usuariosReserva' => $usuariosReserva, 
            'diasReserva' => $diasReserva
        ]);
        
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                'encoding' => 'utf-8'
            ]),
            'Reservas'.$reserva->getId().'.pdf'
        );

      }else{
        $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
        $url = $request->headers->get('referer');
        return $this->redirect($url);
      }
    }
}