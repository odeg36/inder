<?php

namespace LogicBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EncuentroSistemaCuatroController extends Controller {

   /* variables a usar en la el controlador */
   protected $session;
   protected $trans;
   
   /* Se inican variables para el controlador */
   public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
       parent::setContainer($container);

       $this->session = $container->get("session");
       $this->trans = $container->get("translator");
   }

    /**
     * @Route("/reporte/encuentroSistemaCuatro", name="reporte_encuentros_sistema_cuatro", options={"expose"=true})
     */
    public function generarReporteAsistenciaAction(Request $request) {
       
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        
        $eventoId = $request->get('idevento');
        $sistemaId = $request->get('sistemaJuego');
        
        $consulta = $em->getRepository('LogicBundle:SistemaJuegoCuatro')->findOneById($sistemaId);

            
        if($consulta == null){
            $this->addFlash('sonata_flash_error', $this->trans->trans('formulario_escalera.noDatos'));
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }else{

                $encuentros = $em->getRepository("LogicBundle:EncuentroSistemaCuatro")
                ->createQueryBuilder('encuentroSistemaCuatro')
                ->where('encuentroSistemaCuatro.sistemaJuegoCuatro = :tipo')                    
                ->setParameter('tipo', $consulta->getId())
                ->getQuery()->getResult();


            if($encuentros != null)
            {
                $html = $this->renderView('AdminBundle:PlayOff\Reporte:reporte.asistentes.html.twig', [
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