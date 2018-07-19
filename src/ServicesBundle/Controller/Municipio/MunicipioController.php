<?php

namespace ServicesBundle\Controller\Municipio;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;


class MunicipioController extends Controller {

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
     *  section="Municipios",
     *  resource=true,
     *  description="Obtine los municipios para mostrar al usaurio",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/municipios")
     */

     ///// metodo con el cual podemos hacer la consulta
    public function getMunicipios(Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $dql = "SELECT m.id, m.nombre  FROM LogicBundle:Municipio m
            ORDER BY m.nombre";
        
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

    
    //////esta anotacion me permitira hacer referencia al metodo de obtener el barrio

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Municipios",
     *  resource=true,
     *  description="Obtine los barrios para mostrar al usaurio",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/public/barrios/{municipio_id}")
     */

     ///// metodo con el cual podemos hacer la consulta del barrio
     public function getBarrios(Request $request, $municipio_id)
     {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $dql = "SELECT  b.id, b.nombre FROM LogicBundle:Barrio b
            JOIN b.municipio m
            WHERE m.id =  :municipio_id
            ORDER BY m.nombre";

        $parameters = [];
        $parameters["municipio_id"] = $municipio_id;

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

}