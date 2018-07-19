<?php

namespace LogicBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;

class JugadorEventoController extends Controller {
    /* variables a usar en la el controlador */
    protected $session;
    protected $trans;
    
    /* Se inican variables para el controlador */
    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->session = $container->get("session");
        $this->trans = $container->get("translator");
    }

    /**
     * @Route("/carne/jugador/evento", name="crear_carne_evento_jugador", options={"expose"=true})
     * @POST()
     */
    public function crearCarneAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fs = new Filesystem();
        
        $id = $request->get("id");
        
        $jugadorEvento = $em->getRepository("LogicBundle:JugadorEvento")->findOneById($id);
        if(!$jugadorEvento){
            $response = new Response();
            $response->setStatusCode(204);
            return $response;
        }
        $carneEvento = $em->getRepository("LogicBundle:CarneEvento")->findOneBy(["evento"=> $jugadorEvento->getEvento()]);
        $htmlImagen = $this->renderView('AdminBundle:JugadorEvento\Carne:imagen.carne.html.twig', [
            'jugadorEvento' => $jugadorEvento,
            'carne'=> $carneEvento
        ]);
        
        $html = $this->renderView('AdminBundle:JugadorEvento\Carne:vista.carne.html.twig', [
            'jugadorEvento' => $jugadorEvento,
            'carne'=> $carneEvento
        ]);
        
        $nombreCarne = $jugadorEvento->getUsuarioJugadorEvento()->getUsername() . '.jpg';
        $imgGenerador = $this->get('knp_snappy.image');
        
        $file = $imgGenerador->getTemporaryFolder() . '/' . $nombreCarne;
        if($fs->exists($file)){
            $fs->remove($file);
        }
        
        $imgGenerador->setOption("width", "244");
        $imgGenerador->setOption("height", "215");
        $imagen = $imgGenerador->generateFromHtml($htmlImagen, $file);
        $json = [
            'html' => $html, 
            'file' => '/carnes/' . $nombreCarne
        ];

        $serializer = $this->container->get('jms_serializer');
        $json = $serializer->serialize($json, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);
        
        return $response;
    }


    /**
     * @Route("/carne/jugador/evento/mostrar", name="mostrar_carne_evento_jugador", options={"expose"=true})
     * @POST()
     */
    public function mostrarCarneAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
       
        $id = $request->get("id");
        
        $jugadorEvento = $em->getRepository("LogicBundle:JugadorEvento")->findOneById($id);
      
        $nombreCarne = $jugadorEvento->getUsuarioJugadorEvento()->getUsername();
        $equipo=  $jugadorEvento->getEquipoEvento()->getNombre();
        $nombre= $jugadorEvento->getUsuarioJugadorEvento()->nombreCompleto();
        $fecha= $jugadorEvento->getUsuarioJugadorEvento()->getDateOfBirth();
        $imagen = $jugadorEvento->getUsuarioJugadorEvento()->getImagenPerfil();

        $evento = $jugadorEvento->getEvento()->getNombre();
        $diciplina = $jugadorEvento->getEvento()->getDisciplina()->getNombre();

        $fechaR = date_format($fecha,"Y/m/d");
        
        return $this->json(array('cc' => $nombreCarne, 'equipo' => $equipo, 'nombre' => $nombre, 'fecha' => $fechaR , 'imagen'=>$imagen, 'evento' => $evento, 'disciplina' => $diciplina ));
        
    }


}
