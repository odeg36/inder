<?php

namespace ServicesBundle\Controller\TipoIdentificacion;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;


class TipoIdentificacionController extends Controller {

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
     *  section="TipoIdentificacion",
     *  resource=true,
     *  description="Obtine los tipos de Identificacion para mostrar al usuario",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/public/tipoIdentificacion")
     */

    public function getTipoIdentificacion(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $dql = "SELECT ti.id, ti.abreviatura , ti.nombre FROM LogicBundle:TipoIdentificacion ti ORDER BY ti.nombre";
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
}