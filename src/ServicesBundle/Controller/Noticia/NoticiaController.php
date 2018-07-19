<?php

namespace ServicesBundle\Controller\Noticia;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;


class NoticiaController extends Controller  {

    //parametros necesarios para utilizar las consultas a la base de datos 

    protected $container = null;
    protected $em = null;
    protected $trans = null;
    protected $protocolo = "http://";

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
     *  section="Noticia",
     *  resource=true,
     *  description="Obtiene las noticias",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/public/noticias/")
     */
    public function getNoticias(Request $request)
    {
        

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $servidor = $_SERVER["HTTP_HOST"];
        $dql = "SELECT n.id, n.titulo, n.descripcion, n.noticiaImagen, n.fecha FROM LogicBundle:Noticia n ORDER BY n.id DESC";        
        $parameters = [];
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);

        if ($entity) {
            if(count($entity['items'])){

                $noticias = array();
                foreach ($entity['items'] as $noticia) {                    
                    if ($noticia['noticiaImagen'] != null) {
                        $rutaImage = $this->protocolo.$servidor."/uploads/".$noticia['noticiaImagen'];
                        $noticia['noticiaImagen'] = $rutaImage;
                    }
                    $noticia['descripcion'] = strip_tags($noticia['descripcion']);
                    $noticia['fecha'] = $noticia['fecha']->format('Y-m-d');
                    array_push($noticias, $noticia);                    
                }   

                $entity['items'] =  $noticias;
                $view = $this->view($entity, 200);
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 404);
                return $this->handleView($view);
            }
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }

        return $this->handleView($view);


    }
    
    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Noticia",
     *  resource=true,
     *  description="Obtiene una noticia por id",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/public/noticia/{idNoticia}")
     */
    public function getNoticia(Request $request, $idNoticia)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $servidor = $_SERVER["HTTP_HOST"];
        $dql = "SELECT n.id, n.titulo, n.descripcion, n.noticiaImagen, n.fecha FROM LogicBundle:Noticia n  WHERE n.id =  :noticia_id";
        $parameters = [];
        $parameters["noticia_id"] = $idNoticia;
        
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getItem($dql, $parameters);        
        if ($entity) {   
            if(count($entity)){
                
                $noticia = array();  
                if ($entity['noticiaImagen'] != null) {                                      
                    $rutaImage = $this->protocolo.$servidor."/uploads/".$entity['noticiaImagen'];
                    $entity['noticiaImagen'] = $rutaImage;
                }
                $entity['descripcion'] = strip_tags($entity['descripcion']);
                $entity['fecha'] = $entity['fecha']->format('Y-m-d');                
                array_push($noticia, $entity);                
                $entity =  $noticia;
                $view = $this->view($entity, 200);  
            }else{
                $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 404);
                return $this->handleView($view);
            }
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);            
        }
        return $this->handleView($view);
    }
}