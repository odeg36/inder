<?php

namespace ServicesBundle\Controller\Recomendado;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;


class RecomendadoController extends Controller {

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
     *  section="Recomendado",
     *  resource=true,
     *  description="Obtiene los Recomendados",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/recomendados/")
     */
    public function getRecomendados(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $servidor = $_SERVER["HTTP_HOST"];
        $dql = "SELECT n  FROM LogicBundle:Recomendado n ORDER BY n.id DESC";        
        $parameters = [];
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);
        
        if ($entity) {
            if(count($entity['items'])){
                foreach ($entity['items'] as $rec) {
                    if ($rec->getImagenUrl() != null) {
                        $rutaImage = $this->protocolo.$servidor."/uploads/".$rec->getImagenUrl();
                        $rec->setImagenUrl($rutaImage);
                    }
                }                
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
     *  section="Recomendado",
     *  resource=true,
     *  description="Obtiene un recomendado por id",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/recomendado/{idRecomendado}")
     */
    public function getRecomendado(Request $request, $idRecomendado)
    {        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $servidor = $_SERVER["HTTP_HOST"];
        $dql = "SELECT n FROM LogicBundle:Recomendado n  WHERE n.id =  :recomendado_id";
        $parameters = [];
        $parameters["recomendado_id"] = $idRecomendado;
        
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getItem($dql, $parameters);
        if ($entity) {   
            if(count($entity)){
                if ($entity->getImagenUrl() != null) {
                    $rutaImage = $this->protocolo.$servidor."/uploads/".$entity->getImagenUrl();
                    $entity->setImagenUrl($rutaImage);
                }
                $view = $this->view($entity, 200);  
            }else{                
                $error = array('error' =>$this->trans->trans("api.mensajes.error.reserva_usuarios_no_existe"));
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
}