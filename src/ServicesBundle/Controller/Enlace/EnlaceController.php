<?php

namespace ServicesBundle\Controller\Enlace;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;


class EnlaceController extends Controller {

    //parametros necesarios para utilizar las consultas a la base de datos 

    protected $container = null;
    protected $em = null;
    protected $trans = null;

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
     *  section="Enlace",
     *  resource=true,
     *  description="Obtiene las noticias",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/public/enlaces/")
     */
    public function getEnlaces(Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $dql = "SELECT e FROM LogicBundle:CategoriaEnlace e ORDER BY e.id ASC";
        
        $parameters = [];

        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);

        if ($entity) {
            $view = $this->view($entity, 200);
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
     *  section="Enlace",
     *  resource=true,
     *  description="Obtiene una enlace por id",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/public/enlace/{idCategoriaEnlace}")
     */
    public function getEnlace(Request $request, $idCategoriaEnlace)
    {        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $dql = "SELECT e FROM LogicBundle:CategoriaEnlace e WHERE e.id =  :enlace_id";
        
        $parameters = [];
        $parameters["enlace_id"] = $idCategoriaEnlace;
        
        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getItem($dql, $parameters);
        if ($entity) {
            $view = $this->view($entity, 200);
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }

        return $this->handleView($view);
    }
}