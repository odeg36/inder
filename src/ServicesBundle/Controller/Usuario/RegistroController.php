<?php

namespace ServicesBundle\Controller\Usuario;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\UserType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistroController extends Controller  {

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
     *  description="Registrar Usuario Persona Natural",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  },
     *  requirements={
     *      {
     *          "name"="tipo_documento",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="tipo de documento del usuario"
     *      },
     *      {
     *          "name"="numero_identificacion",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="numero identificacion del usuario"
     *      },
     *      {
     *          "name"="nombre",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="nombres usuario"
     *      },
     *      {
     *          "name"="apellido",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="apellidos usuario"
     *      },
     *      {
     *          "name"="sexo",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="sexo de usuario"
     *      },
     *      {
     *          "name"="fecha_nacimiento",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="fecha nacimiento usuario"
     *      },
     *      {
     *          "name"="clave",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="clave usuario"
     *      },
     *      {
     *          "name"="barrio",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="barrio usuario"
     *      },
     *      {
     *          "name"="comuna",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="comuna usuario"
     *      },
     *      {
     *          "name"="direccion",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="direccion usuario"
     *      },
     *      {
     *          "name"="estrato",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="estrato usuario"
     *      },
     *      {
     *          "name"="correo",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="estrato usuario"
     *      },
     *      {
     *          "name"="telefono",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="estrato usuario"
     *      }
     * 
     *  }
     * )
     * 
     * @Post("/public/registro/usuario")
     */

    public function resgistroUsuarioAction(Request $request) {

        $tipo_documento = $request->request->get('tipo_documento');
        $numero_identificacion = $request->request->get('numero_identificacion');
        $nombre = $request->request->get('nombre');
        $apellido = $request->request->get('apellido');
        $sexo = $request->request->get('sexo');
        $fecha_nacimiento = $request->request->get('fecha_nacimiento');
        $clave = $request->request->get('clave');
        $barrio = $request->request->get('barrio');
        $comuna = $request->request->get('comuna');
        $direccion = $request->request->get('direccion');
        $estrato = $request->request->get('estrato');
        $correo = $request->request->get('correo');
        $telefono = $request->request->get('telefono');
        
        $errors = array();
        if(!$tipo_documento || $tipo_documento == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.tipo_documento"));
        }
        if(!$numero_identificacion || $numero_identificacion == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.numero_identificacion"));
        }
        if(!$nombre || $nombre == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.nombre"));
        }
        if(!$apellido || $apellido == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.apellido"));
        }
        if(!$sexo || $sexo == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.sexo"));
        }
        if(!$fecha_nacimiento || $fecha_nacimiento == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.fecha_nacimiento"));
        }
        if(!$clave || $clave == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.clave"));
        }else{            
            if (strlen($clave) >= 6) {
                if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $clave)){
                    if (!preg_match('/[A-Za-z].*[A-Za-z]/', $clave)){                    
                        array_push($errors, $this->trans->trans("api.mensajes.error.unaletra"));                        
                    }elseif (!preg_match('/[0-9]|[0-9]/', $clave)) {
                        array_push($errors, $this->trans->trans("api.mensajes.error.unnumero"));
                    }
                }                
            }else{                
                array_push($errors, $this->trans->trans("api.mensajes.error.caracteresminimo"));
            }            
        }
        $barrioVacio = false;
        $comunaVacia = false;
        if(!$barrio || $barrio == ""){
            $barrioVacio = true;            
        }
        if($barrioVacio == true){
            if(!$comuna || $comuna == ""){
                $comunaVacia = true;
                array_push($errors, $this->trans->trans("api.mensajes.error.comuna"));
                array_push($errors, $this->trans->trans("api.mensajes.error.barrio"));
            }
        }
        if(!$direccion || $direccion == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.direccion"));
        }
        if(!$estrato || $estrato == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.estrato"));
        }
        if(!$correo || $correo == ""){
            array_push($errors, $this->trans->trans("api.mensajes.error.correo"));
        }

        if(count($errors) > 0){
            return array(
                'errors' => $errors
            );
        }
        
        $dql = "SELECT u FROM ApplicationSonataUserBundle:User u 
                WHERE u.tipoIdentificacion = :tipoIdentificacion AND u.numeroIdentificacion = :numeroIdentificacion";

        $parameters = [];
        $parameters["tipoIdentificacion"] = $tipo_documento;
        $parameters["numeroIdentificacion"] = $numero_identificacion;
       

        $responseBuilder = $this->container->get('ito.tools.response.builder');
        $user = $responseBuilder->getItem($dql, $parameters);
        if ($user != null) {
            return array(
                'errors' => $this->trans->trans("api.mensajes.error.usuario_ya_registrado")
            );
        }else{
            $user = new User();
            $errorTipoDatos = false;
            $objectTipoDocumento = $this->em->getRepository("LogicBundle:TipoIdentificacion")->find($tipo_documento); 
            if ($objectTipoDocumento == null) {
                $errorTipoDatos = true;
                return array(
                    'errors' => $this->trans->trans("api.mensajes.error.tipo_identificacion_no_existe")
                );
            }else{
                $user->setTipoIdentificacion($objectTipoDocumento);
            }

            /*$objectOrientacionSexual = $this->em->getRepository("LogicBundle:OrientacionSexual")->find($sexo); 
            if ($objectOrientacionSexual == null) {
                $errorTipoDatos = true;
                return array(
                    'errors' => $this->trans->trans("api.mensajes.error.orientacion_sexual_no_existe")
                );
            }else{
                $user->setOrientacionSexual($objectOrientacionSexual);
            }*/
            if ($barrioVacio == false) {
                $objectBarrio = $this->em->getRepository("LogicBundle:Barrio")->find($barrio); 
                if ($objectBarrio == null) {
                    $errorTipoDatos = true;
                    return array(
                        'errors' => $this->trans->trans("api.mensajes.error.barrio_no_existe")
                    );
                }else{
                    $user->setBarrio($objectBarrio);
                }
            }
            if ($barrioVacio == true && $comunaVacia == false) {
                $objectComuna = $this->em->getRepository("LogicBundle:Comuna")->find($comuna); 
                if ($objectComuna == null) {
                    $errorTipoDatos = true;
                    return array(
                        'errors' => $this->trans->trans("api.mensajes.error.comuna_no_existe")
                    );
                }else{
                    //$user->setTipoIdentificacion($objectTipoDocumento);
                    //falta agregar la comuna
                }
            }else{
                $user->setTipoIdentificacion($objectTipoDocumento);
            }
            $objectEstrato = $this->em->getRepository("LogicBundle:Estrato")->find($estrato); 
            if ($objectEstrato == null) {
                $errorTipoDatos = true;
                return array(
                    'errors' => $this->trans->trans("api.mensajes.error.estrato_no_existe")
                );
            }else{
                $user->setEstrato($objectEstrato);
            }
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errorTipoDatos = true;
                return array(
                    'errors' => $this->trans->trans("api.mensajes.error.email_no_valido")
                );
            }

            if ($errorTipoDatos == false) {
                $user->setNumeroIdentificacion($numero_identificacion);
                $user->setFirstname($nombre);
                $user->setLastname($apellido);
                $fecha_nacimiento =  new \DateTime($fecha_nacimiento);// new \DateTime($fecha_inicio);                
                $user->setDateOfBirth($fecha_nacimiento);
                $user->setDireccionResidencia($direccion);
                $user->setEmail($correo);
                $user->setPhone($telefono);
                $user->setGender($sexo);
                $encoder = $this->container->get('inder.encoder');
                $password = $encoder->encodePassword($clave, $user->getSalt());
                $user->setPassword($password);
                $user->setRoles(array('ROLE_PERSONANATURAL'));
                $user->setEnabled(true);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $view = $this->view($user, 201);
                
            }
        }
        return $this->handleView($view);
    }
    
    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Usuario",
     *  resource=true,
     *  description="Obtiene el perfil de un usuario autenticado",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }
     * )
     * 
     * @Get("/usuario/profile/")
     */
    public function getPerfil(Request $request) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $view = $this->view($user, 200);
        
        return $this->handleView($view);
    }
}