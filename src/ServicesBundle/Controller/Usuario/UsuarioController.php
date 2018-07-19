<?php

namespace ServicesBundle\Controller\Usuario;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

class UsuarioController extends Controller implements TokenAuthenticatedController {

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
     *  section="Usuario",
     *  resource=true,
     *  description="Optiene los datos del usuario",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="tipoIdentificacion",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Tipo Identificacion del usuario"
     *      },
     *      {
     *          "name"="numeroIdentificacion",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Numero Identificacion del usuario"
     *      }
     *  }
     * )
     * 
     * @Get("/usuarios")
     */
    public function getUsuariosAction(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $dql = "SELECT u FROM ApplicationSonataUserBundle:User u";
        $parameters = [];

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $tipoIdentificacion = $request->get('tipoIdentificacion');
        $numeroIdentificacion = $request->get('numeroIdentificacion');
        if($tipoIdentificacion && $numeroIdentificacion){
            $dql .= " WHERE u.tipoIdentificacion = :tipoIdentificacion ";
            $dql .= " AND u.numeroIdentificacion LIKE :numeroIdentificacion";
            $parameters["tipoIdentificacion"] = $tipoIdentificacion;
            $parameters["numeroIdentificacion"] = '%'.$numeroIdentificacion.'%';
        }

        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getCollection($dql, $parameters);
        if ($entity) {            
            foreach ($entity['items'] as $e) {
                $e->setPassword(null);
                $e->setSalt(null);

                if ($e->getDateOfBirth() != null) {
                    $e->setDateOfBirth($e->getDateOfBirth()->format('Y-m-d'));
                }
            }
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
     *  section="Usuario",
     *  resource=true,
     *  description="Obtiene un usuario de la base de datos",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Id del usuario"
     *      }
     *  }
     * )
     * 
     * @Get("/usuario/{id}")
     */
    public function getUsuarioAction(Request $request, $id) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $dql = "SELECT u FROM ApplicationSonataUserBundle:User u 
                WHERE u.id = :id";
        
        $parameters = [];
        $parameters["id"] = $id;

        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $entity = $responseBuilder->getItem($dql, $parameters);

        if ($entity) {
            
            $entity->setPassword(null);
            $entity->setSalt(null);

            if ($entity->getDateOfBirth() != null) {
                $entity->setDateOfBirth($entity->getDateOfBirth()->format('Y-m-d'));
            }
            
            $view = $this->view($entity, 200);
        } else {
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }

        return $this->handleView($view);
    }
    
    
    
}