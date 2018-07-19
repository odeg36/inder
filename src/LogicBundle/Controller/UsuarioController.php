<?php

namespace LogicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsuarioController extends Controller {

    protected $session;
    protected $trans;
    protected $em;

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->session = $container->get("session");
        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * @Route("/existe/usuario", name="existe_usuario", options={"expose"=true})
     */
    public function existeUsuarioAction(Request $request) {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->request->get('tipoidentificacion');
        $numeroIdentificacion = $request->request->get('numeroidentificacion');

        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->find($tipoIdentificacion);
        if (!$abreviaturaIdentificacion) {
            return $this->json(array('usuario_existe' => false));
        }
        $usuario = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $existeUsuario = $em->getRepository('ApplicationSonataUserBundle:User')->findByUsername($usuario);

        if ($existeUsuario) {
            return $this->json(array('resultado' => $translator->trans('formulario_registro.nit_registrado'), 'usuario_existe' => true));
        }
        return $this->json(array('usuario_existe' => false));
    }

    /**
     * @Route("/existe/usuario/deportista", name="existe_usuario_deportista", options={"expose"=true})
     */
    public function existeUsuarioDeportistaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');

        $existeUsuario = $em->getRepository('ApplicationSonataUserBundle:User')->findOneById($id);

        if ($existeUsuario) {
            $serializer = $this->container->get('jms_serializer');
            $json = $serializer->serialize($existeUsuario, 'json');
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("verifica/existe/usuario/deportista", name="verifica_existe_usuario_deportista", options={"expose"=true})
     */
    public function verificaExisteUsuarioDeportistaAction(Request $request) {
        $response = new Response();

        $noCrear = $request->query->get('noCrear', null);
        $json = [];
        if (!$noCrear) {
            $json = [
                ['id' => '0000', 'text' => $this->trans->trans('opcion.crear.nuevo.usuario')]
            ];
        }

        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->query->get('tipoDocumento');
        $tipoIdentificacion = $this->session->get($tipoIdentificacion);
        $numeroIdentificacion = $request->query->get('q');
        $rol = $request->query->get('rol');
        $organizacionDeportiva = $request->query->get('organizacionDeportiva');
        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneById($tipoIdentificacion);
        if (!$abreviaturaIdentificacion) {
            $response->setContent(json_encode($json));
            return $response;
        }

        $usuario = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $results = $em->getRepository('ApplicationSonataUserBundle:User')->buscarUsername($usuario, null, true);
        if ($results) {
            $json = [];
            foreach ($results as $usuario) {
                $organizacionEntity = $this->em->getRepository("LogicBundle:OrganizacionDeportiva")->buscarOrganizacionDeportista($organizacionDeportiva, $usuario->getId());
                if ($organizacionEntity) {
                    $json[] = array(
                        'id' => '00',
                        'text' => $this->trans->trans("error.mensaje.deportista.organizacion", [
                            "%deportista%" => $usuario->getFullnameIdentificacion(),
                            "%organizacionDeportiva%" => $organizacionEntity->getRazonSocial()
                        ])
                    );
                } else {
                    $texto = $usuario->getNumeroIdentificacion();
                    if (!$texto) {
                        $texto = "";
                    }
                    $json[] = array(
                        'id' => $usuario->getId(),
                        'text' => $texto,
                    );
                }
            }
        }

        $response->setContent(json_encode($json));
        return $response;
    }

    /**
     * @Route("buscar/usuarios/tipoDocumento/documento", name="listar_usuarios_tipo_documento_documento", options={"expose"=true})
     */
    public function bucarUsuariosTipoDocumentoDocumento(Request $request) {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->query->get('tipoDocumento');
        $tipoIdentificacion = $this->session->get($tipoIdentificacion);
        $numeroIdentificacion = $request->query->get('q');
        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneById($tipoIdentificacion);
        $json = [];
        if (!$abreviaturaIdentificacion) {
            $response->setContent(json_encode($json));
            return $response;
        }
        $username = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $results = $em->getRepository('ApplicationSonataUserBundle:User')->buscarUsuario($username);
        if ($results) {
            foreach ($results as $usuario) {
                $texto = $usuario->getNumeroIdentificacion();
                if (!$texto) {
                    $texto = "";
                }
                $json[] = array(
                    'id' => $usuario->getId(),
                    'text' => $texto,
                );
            }
        }

        $response->setContent(json_encode($json));
        return $response;
    }

    /**
     * @Route("verifica/existe/usuario/deportista/acompanante", name="verifica_existe_usuario_deportista_acompanante", options={"expose"=true})
     */
    public function verificaExisteUsuarioDeportistaAcompananteAction(Request $request) {
        $response = new Response();

        $noCrear = $request->query->get('noCrear', null);
        $json = [];
        if (!$noCrear) {
            $json = [
                ['id' => '0000', 'text' => $this->trans->trans('opcion.registrarse')]
            ];
        }

        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->query->get('tipoDocumento');
        $tipoIdentificacion = $this->session->get($tipoIdentificacion);
        $numeroIdentificacion = $request->query->get('q');
        $rol = $request->query->get('rol');
        $abreviaturaIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneById($tipoIdentificacion);
        if (!$abreviaturaIdentificacion) {
            $response->setContent(json_encode($json));
            return $response;
        }

        $usuario = $abreviaturaIdentificacion->getAbreviatura() . $numeroIdentificacion;
        $results = $em->getRepository('ApplicationSonataUserBundle:User')->buscarUsuario($usuario);
        if ($results) {
            $json = [];
            foreach ($results as $usuario) {
                $texto = $usuario->getNumeroIdentificacion();
                if (!$texto) {
                    $texto = "";
                }
                $json[] = array(
                    'id' => $usuario->getId(),
                    'text' => $texto,
                );
            }
        }

        $response->setContent(json_encode($json));
        return $response;
    }

    /**
     * @Route("/usuario/deportista/{id}", defaults={"id" = null}, name="get_usuario", options={"expose"=true})
     */
    public function usuarioDeportistaAction(Request $request, $id) {
        $response = new Response();
        $json = [];

        $em = $this->getDoctrine()->getManager();

        $deportista = $em->getRepository('ApplicationSonataUserBundle:User')->findOneById($id);

        if ($deportista) {
            return new Response($deportista->getNumeroIdentificacion());
        }

        return new Response("");
    }

    /**
     * @Route("/usuario/{id}", defaults={"id" = null}, name="get_usuario_nombre", options={"expose"=true})
     */
    public function getUsuarioAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('ApplicationSonataUserBundle:User')->findOneById($id);
        if ($usuario) {
            return new Response($usuario->getFullnameIdentificacion());
        }
        return new Response("");
    }

    /**
     * @Route("/registrar/usuario", name="registrar_usuario", options={"expose"=true})
     * @Method({"POST"})
     */
    public function registrarUsuarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $tipoIdentificacion = $request->request->get('tipodocumento');
        $numeroIdentificacion = $request->request->get('numerodocumento');
        $nombreDeportista = $request->request->get('nombredeportista');

        $idIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->find($tipoIdentificacion);
        $formularioRegistro = new User();
        $formularioRegistro->setFirstName($nombreDeportista);
        $formularioRegistro->setTipoIdentificacion($idIdentificacion);
        $formularioRegistro->setNumeroIdentificacion($nombreDeportista);
        $formularioRegistro->setFirstLastname($numeroIdentificacion);

        $em->persist($formularioRegistro);
        $em->flush();

        return $this->json(array('registro' => true));
    }

}
