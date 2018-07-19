<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EncuentroSistemaUnoController extends Controller {

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
     * @Route("/reporte/encuentroSistemaUno", name="reporte_encuentros_sistema_uno", options={"expose"=true})
     */
    public function generarReporteAsistenciaAction(Request $request) {
       
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $eventoId = $request->get('idevento');
        $sistemaId = $request->get('sistemaJuegoUno');
        
        $consulta = $em->getRepository('LogicBundle:SistemaJuegoUno')->findOneById($sistemaId);

            
        if($consulta == null){
            $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }else{


            $consulta->getEvento()->getCupo();

            if($consulta->getEvento()->getCupo() == "Individual")
            {
                $encuentros = $em->getRepository("LogicBundle:EncuentroSistemaUno")
                ->createQueryBuilder('encuentroSistemaUno')
                ->where('encuentroSistemaUno.sistemaJuegoUno = :tipo')                    
                ->setParameter('tipo', $consulta->getId())
                ->getQuery()->getResult();

                
            }

            if($consulta->getEvento()->getCupo() == "Equipos")
            {

                $encuentros = $em->getRepository("LogicBundle:EncuentroSistemaUno")
                ->createQueryBuilder('encuentroSistemaUno')
                ->where('encuentroSistemaUno.sistemaJuegoUno = :tipo')                    
                ->setParameter('tipo', $consulta->getId())
                ->getQuery()->getResult();
            }


            if($encuentros != null)
            {
                $html = $this->renderView('AdminBundle:Escalera\Reporte:reporte.asistentes.html.twig', [
                    'encuentros' => $encuentros,
                ]);
                
                
                return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                        'encoding' => 'utf-8'
                    ]),
                    'Encuentros.pdf'
                );

            }else{

                $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
                $url = $request->headers->get('referer');
                return $this->redirect($url);
            }
        
        }


        


    }


}