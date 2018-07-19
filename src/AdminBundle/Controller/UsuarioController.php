<?php

namespace AdminBundle\Controller;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use LogicBundle\Entity\DisciplinaOrganizacion;
use LogicBundle\Entity\OrganismoDeportista;
use LogicBundle\Entity\OrganizacionDeportiva;
use LogicBundle\Entity\Organo;
use LogicBundle\Entity\PerfilOrganismo;
use LogicBundle\Entity\TipoPersona;
use LogicBundle\Form\OrganizacionDeportivaType;
use LogicBundle\Utils\Validaciones;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use function dump;

class UsuarioController extends Controller {

    protected $validaciones;

    public function __construct() {
        $this->validaciones = new Validaciones();
    }

    protected $trans = null;
    protected $em = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * @Route("/registro", name="formulario_registro")
     */
    public function registroAction(Request $request) {

        $formularioRegistro = new User();

        $form = $this->createForm(UserType::class, $formularioRegistro);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $direccion = '';

            $encoder = $this->container->get('inder.encoder');
            $password = $encoder->encodePassword($form->get('password')->getData(), $formularioRegistro->getSalt());
            $formularioRegistro->setPassword($password);

            $formularioRegistro->setEnabled(TRUE);
            if ($form->getData()->getTipoPersona() == TipoPersona::D) {
                $formularioRegistro->setRoles(array('ROLE_ORGANISMO_DEPORTIVO'));

                $organizacion = new OrganizacionDeportiva();
                $organizacion->setRazonSocial($form->get("organizaciondeportiva")->getData());
                $organizacion->setTipoEntidad($form->get("tipoentidad")->getData());
                $organizacion->setNit($form->get("numeroidentificacion")->getData());

                $this->em->persist($organizacion);
                $this->em->flush();
                $formularioRegistro->setOrganizaciondeportiva($organizacion);
                $this->em->persist($formularioRegistro);
                $this->em->flush();
                $direccion = 'info_complementaria';
            } else {
                $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName("Registrado (Persona Natural)");
                $formularioRegistro->setRoles(array('ROLE_PERSONANATURAL'));
                $nombre = $form->get('firstname')->getData();
                $nombre = ucwords($nombre);
                $apellido = $form->get('lastname')->getData();
                $apellido = ucwords($apellido);
                $formularioRegistro->setFirstname($nombre);
                $formularioRegistro->setLastname($apellido);
                $formularioRegistro->addGroup($grupo);

                $this->em->persist($formularioRegistro);
                $this->em->flush();
                $this->addFlash("success", $this->trans->trans('formulario_registro.registrado'));
                $direccion = 'sonata_admin_dashboard';
            }
            $token = new UsernamePasswordToken($formularioRegistro, null, "usuario", $formularioRegistro->getRoles());
            $this->get('security.token_storage')->setToken($token);
            return $this->redirectToRoute($direccion);
        }

        return $this->render('AdminBundle:Usuario/Registro:registro.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/organismo/deportivo/info/complementaria", name="info_complementaria")
     */
    public function pasoUnoAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $organizacionId = $request->get('organismo');

        $formularioRegistro = $this->getUser();
        if (!$organizacionId) {
            $url = $this->validaciones->registroAprobado($formularioRegistro);
            if ($url) {
                return $this->redirectToRoute($url);
            }
        } else {
            if (!$formularioRegistro->hasRole('ROLE_SUPER_ADMIN') && !$formularioRegistro->hasRole('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
                if ($formularioRegistro->getOrganizacionDeportiva()->getId() != $organizacionId) {
                    return $this->redirectToRoute('sonata_admin_dashboard');
                }
            }
        }

        $organizacion = $this->em->getRepository('LogicBundle:OrganizacionDeportiva')->findOneById($organizacionId);

        if (!$organizacion) {
            $organizacion = $formularioRegistro->getOrganizacionDeportiva();
        }
        if (!$organizacion) {
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        
        $tipoEntidadViejo = $organizacion->getTipoEntidad();

        $originalDisciplinas = [];
        foreach ($organizacion->getDisciplinaOrganizaciones() as $key => $disciplina) {
            $originalDisciplinas[] = ["disciplina" => $disciplina->getDisciplina(), "disciplinaOrganizacion" => $disciplina];
        }

        $form = $this->createForm(OrganizacionDeportivaType::class, $organizacion, array(
            'paso' => 1,
            'esEdicion' => $organizacionId ? true : false,
            'organizacionDeportiva' => $organizacion
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $disciplinasId = [];
            
            $objeto = $form->getData();
            
            foreach ($objeto->getDisciplinas() as $key => $disciplina) {
                $disciplinaOrganizacion = $this->em->getRepository("LogicBundle:DisciplinaOrganizacion")->buscarDisciplinaOrganizacion($organizacion, $disciplina);
                if (!$disciplinaOrganizacion) {
                    $disciplinaOrganizacion = new DisciplinaOrganizacion();
                }

                $disciplinaOrganizacion->setDisciplina($disciplina);
                $disciplinaOrganizacion->setOrganizacion($objeto);
                $this->em->persist($disciplinaOrganizacion);

                array_push($disciplinasId, $disciplina->getId());
            }

            foreach ($originalDisciplinas as $disciplina) {
                if (array_search($disciplina["disciplina"]->getId(), $disciplinasId) === false) {
                    $this->em->remove($disciplina["disciplinaOrganizacion"]);
                }
                $this->em->flush();
            }

            if($objeto->getId()){
                if(($objeto->getTipoEntidad() && $tipoEntidadViejo) && $objeto->getTipoEntidad()->getNombre() != $tipoEntidadViejo->getNombre()){
                    foreach ($objeto->getOrganismosorganizacion() as $key => $organismosOrganizacion) {
                        $this->em->remove($organismosOrganizacion);
                    }
                    $this->em->flush();
                }
            }
            
            $this->em->persist($objeto);
            try {
                $this->em->flush();
            } catch (\Exception $exc) {}

            return $this->redirectToRoute('info_organigrama', ['organismo' => $organizacionId]);
        }

        $listaDisciplinas = $this->em->getRepository('LogicBundle:Disciplina')->findAll();
        return $this->render('AdminBundle:Usuario/Pasos:paso_1.html.twig', [
                    'organizacion' => $organizacion,
                    'organizacionId' => $organizacionId,
                    'form' => $form->createView(), 'disciplinas' => $listaDisciplinas
        ]);
    }

    /**
     * @Route("/organismo/deportivo/info/organigrama", name="info_organigrama")
     */
    public function pasoDosAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $organizacionId = $request->get('organismo');
        $formularioRegistro = $this->getUser();
        if (!$organizacionId) {
            $url = $this->validaciones->registroAprobado($formularioRegistro);
            if ($url) {
                return $this->redirectToRoute($url);
            }
        } else {
            if (!$formularioRegistro->hasRole('ROLE_SUPER_ADMIN') && !$formularioRegistro->hasRole('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
                if ($formularioRegistro->getOrganizacionDeportiva()->getId() != $organizacionId) {
                    return $this->redirectToRoute('sonata_admin_dashboard');
                }
            }
        }

        $organizacion = $this->em->getRepository('LogicBundle:OrganizacionDeportiva')->findOneById($organizacionId);
        if (!$organizacion) {
            $organizacion = $formularioRegistro->getOrganizacionDeportiva();
        }
        if (!$organizacion) {
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        if (!$organizacion->getTipoEntidad()) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.entidad'));
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }

        $tipoEntidad = null;
        $organizacionDeportiva = null;
        
        if ($organizacion->getDisciplinaOrganizaciones()->count() <= 0 && $organizacion->getTendencias()->count() <= 0) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.disciplina_tendencia'));
            $url = 'info_complementaria';
            return $this->redirectToRoute($url);
        }

        if ($organizacion) {
            $organizacionDeportiva = $organizacion;
            if ($organizacionDeportiva->getTipoEntidad()) {
                $tipoEntidad = $organizacionDeportiva->getTipoEntidad();
            }
        }
        if ($tipoEntidad && $organizacionDeportiva->getOrganismosorganizacion()->count() == 0) {
            foreach ($tipoEntidad->getTipoOrganismos() as $key => $tipoOrganismo) {
                $organo = new Organo();
                $organo->setTipoOrgano($tipoOrganismo);
                for ($i = 0; $i < $tipoOrganismo->getMinimo(); $i++) {
                    $perfilOrganismo = new PerfilOrganismo();
                    $organo->addPerfilOrganismo($perfilOrganismo);
                }

                $organizacionDeportiva->addOrganismosorganizacion($organo);
            }
        }

        $listaOrganismos = $this->em->getRepository('LogicBundle:TipoOrganismo')->findAllArray();
        $form = $this->createForm(OrganizacionDeportivaType::class, $organizacion, [
            'paso' => 2,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('info_deportistas', ['organismo' => $organizacionId]);
        }

        return $this->render('AdminBundle:Usuario/Pasos:paso_2.html.twig', [
                    'organizacion' => $organizacion,
                    'organizacionId' => $organizacionId,
                    'form' => $form->createView(),
                    'organismos' => $listaOrganismos
        ]);
    }

    /**
     * @Route("/organismo/deportivo/info/deportistas", name="info_deportistas")
     */
    public function pasoTresAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $organizacionId = $request->get('organismo');
        $formularioRegistro = $this->getUser();
        if (!$organizacionId) {
            $url = $this->validaciones->registroAprobado($formularioRegistro);
            if ($url) {
                return $this->redirectToRoute($url);
            }
        } else {
            if (!$formularioRegistro->hasRole('ROLE_SUPER_ADMIN') && !$formularioRegistro->hasRole('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
                if ($formularioRegistro->getOrganizacionDeportiva()->getId() != $organizacionId) {
                    return $this->redirectToRoute('sonata_admin_dashboard');
                }
            }
        }

        $organizacion = $this->em->getRepository('LogicBundle:OrganizacionDeportiva')->findOneById($organizacionId);
        if (!$organizacion) {
            $organizacion = $formularioRegistro->getOrganizacionDeportiva();
        }
        if (!$organizacion) {
            $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        if (!$organizacion->getTipoEntidad()) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.entidad'));
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }

        $deportistaViejos = new ArrayCollection();
        foreach ($organizacion->getDisciplinaOrganizaciones() as $key => $disciplina) {
            foreach ($disciplina->getDeportistas() as $key => $deportista) {
                $deportistaViejos->add($deportista);
            }
        }
        
        if ($organizacion->getOrganismosorganizacion()->count() <= 0 ) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.organigrama'));
            $url = 'info_organigrama';
            return $this->redirectToRoute($url);
        }

        $form = $this->createForm(OrganizacionDeportivaType::class, $organizacion, [
            'paso' => 3,
            'organizacionDeportiva' => $organizacion->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            foreach ($form->getData()->getDisciplinaOrganizaciones() as $key => $disciplina) {
                $usuarios = [];
                $usuariosRepetido = [];
                foreach ($disciplina->getDeportistas() as $key => $deportista) {
                    $deportista = $deportista->getUsuarioDeportista();
                    $id = $deportista ? $deportista->getId() : '';
                    if (key_exists($id, $usuarios)) {
                        $usuariosRepetido[$id] = ["deportista" => $deportista, "disciplina" => $disciplina];
                    }

                    $usuarios[$id] = [];
                }

                foreach ($usuariosRepetido as $key => $repetido) {
                    $deportista = $repetido["deportista"];
                    $form->get("disciplinaOrganizaciones")->addError(
                        new FormError($this->trans->trans('error.disciplina.deportista.repetido', ["%disciplina%" => $repetido["disciplina"]->getDisciplina(), "%deportista%" => $deportista->getNumeroIdentificacion() . ' - ' . $deportista->getFirstname() . ' ' . $deportista->getLastname()]))
                    );
                }
            }

            if ($form->isValid()) {
                foreach ($deportistaViejos as $key => $deportista) {
                    $this->em->remove($deportista);
                }

                foreach ($form["disciplinaOrganizaciones"] as $disciplina) {
                    foreach ($disciplina["deportistas"] as $key => $deportista) {
                        $usuario = $deportista["usuariodeportista"]->getData();
                        $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName(Group::GRUPO_DEPORITISTA);
                        $grupoUsuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->buscarGrupoUsuario($usuario->getId(), $grupo);
                        
                        if(!$grupoUsuario){
                            $usuario->addGroup($grupo);
                        }
                        
                        $organismoDeportista = new OrganismoDeportista();
                        
                        $organismoDeportista->setUsuarioDeportista($usuario);
                        $organismoDeportista->setDisciplinaOrganizacion($disciplina->getData());
                    }
                }
                if ($organizacion->getTipoEntidad()->getAbreviatura() == "END") {
                    $form->getData()->setTerminoregistro(true);
                    $form->getData()->setFechaRegistro(new \DateTime());
                }
                
                $this->em->persist($form->getData());
                $this->em->persist($form->getData());
                $this->em->flush();
                
                if ($organizacion->getTipoEntidad()->getAbreviatura() == "END") {
                    return $this->redirectToRoute('registroterminado', ['organismo' => $organizacionId]);
                }
                return $this->redirectToRoute('info_pestatutario', ['organismo' => $organizacionId]);
            }
        }

        return $this->render('AdminBundle:Usuario/Pasos:paso_3.html.twig', array(
                    'organizacion' => $organizacion,
                    'organizacionId' => $organizacionId,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/organismo/deportivo/info/documentos", name="info_documentos")
     */
    public function pasoCuatroAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $organizacionId = $request->get('organismo');
        $formularioRegistro = $this->getUser();
        if (!$organizacionId) {
            $url = $this->validaciones->registroAprobado($formularioRegistro);
            if ($url) {
                return $this->redirectToRoute($url);
            }
        } else {
            if (!$formularioRegistro->hasRole('ROLE_SUPER_ADMIN') && !$formularioRegistro->hasRole('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
                if ($formularioRegistro->getOrganizacionDeportiva()->getId() != $organizacionId) {
                    return $this->redirectToRoute('sonata_admin_dashboard');
                }
            }
        }

        $organizacion = $this->em->getRepository('LogicBundle:OrganizacionDeportiva')->findOneById($organizacionId);
        if (!$organizacion) {
            $organizacion = $formularioRegistro->getOrganizacionDeportiva();
        }
        if (!$organizacion) {
            $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        if (!$organizacion->getTipoEntidad()) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.entidad'));
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        
        $form = $this->createForm(OrganizacionDeportivaType::class, $organizacion, array('paso' => 4));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('info_pestatutario', ['organismo' => $organizacionId]);
        }

        return $this->render('AdminBundle:Usuario/Pasos:paso_4.html.twig', array(
                    'organizacion' => $organizacion,
                    'organizacionId' => $organizacionId,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/organismo/deportivo/info/estatutaria", name="info_pestatutario")
     */
    public function pasoCincoAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }
        $organizacionId = $request->get('organismo');
        $formularioRegistro = $this->getUser();
        if (!$organizacionId) {
            $url = $this->validaciones->registroAprobado($formularioRegistro);
            if ($url) {
                return $this->redirectToRoute($url);
            }
        } else {
            if (!$formularioRegistro->hasRole('ROLE_SUPER_ADMIN') && !$formularioRegistro->hasRole('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
                if ($formularioRegistro->getOrganizacionDeportiva()->getId() != $organizacionId) {
                    return $this->redirectToRoute('sonata_admin_dashboard');
                }
            }
        }

        $organizacion = $this->em->getRepository('LogicBundle:OrganizacionDeportiva')->findOneById($organizacionId);
        if (!$organizacion) {
            $organizacion = $formularioRegistro->getOrganizacionDeportiva();
        }
        if (!$organizacion) {
            $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        if (!$organizacion->getTipoEntidad()) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.entidad'));
            return $this->redirect($this->generateUrl('admin_logic_organizaciondeportiva_list'));
        }
        
        $deportistas = 0;
        foreach ($organizacion->getDisciplinaOrganizaciones() as $key => $disciplina) {
            $deportistas += $disciplina->getDeportistas()->count();
        }
        
        if ($deportistas <= 0) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('error.organizacion.sin.deporistas'));
            $url = 'info_deportistas';
            return $this->redirectToRoute($url);
        }
        
        $form = $this->createForm(OrganizacionDeportivaType::class, $organizacion, array('paso' => 5));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setTerminoregistro(true);
            $form->getData()->setFechaRegistro(new \DateTime());
            $this->em->persist($form->getData());
            $this->em->flush();
            if (!$organizacionId) {
                return $this->redirectToRoute('registroterminado', ['organismo' => $organizacionId]);
            } else {
                $trans = $this->container->get('translator');
                $this->addFlash(
                        'sonata_flash_success', $trans->trans(
                                'flash_edit_success'
                        )
                );

                return $this->redirectToRoute('admin_logic_organizaciondeportiva_list');
            }
        }

        return $this->render('AdminBundle:Usuario/Pasos:paso_5.html.twig', array(
                    'organizacion' => $organizacion,
                    'form' => $form->createView(),
                    'organizacionId' => $organizacionId,
        ));
    }

    /**
     * @Route("/organismo/deportivo/registro/terminado", name="registroterminado")
     */
    public function registroTerminadoAction() {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ORGANISMO_DEPORTIVO')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $mensaje = $this->trans->trans('formulario_evento.labels.jugador_evento.informacion_enviar_usuario_correo_organismo');
        $this->addFlash('sonata_flash_success', $mensaje);

        $mailsGestores = array();
        $user = $this->getUser();
        array_push($mailsGestores, $user->getEmail());

        if (count($mailsGestores) > 0) {
            $informacion = array('objeto' => null, 'plantilla' => 'AdminBundle:Usuario:mails/mailSolicitudArchivos.html.twig');
            $this->enviarCorreo($mailsGestores, $this->trans->trans('correos.usuario.registroUsuarioAsunto'), $informacion);
        }

        return $this->render('AdminBundle:Usuario:Registro/registroterminado.html.twig');
    }

    public function problemasInicioSesionAction() {
        $form = $this->getUser();
        return $this->render('AdminBundle:Usuario/Registro:problemasInicioSesion.html.twig', array(
                    'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                    'admin_pool' => $this->get('sonata.admin.pool')
        ));
    }

    private function enviarCorreo($destinatarios, $asunto, $informacion) {
        if (is_array($destinatarios)) {
            foreach ($destinatarios as $destinatario) {
                $message = (new Swift_Message($asunto))
                        ->setTo($destinatario)
                        ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                        ->setBody(
                        $this->renderView($informacion['plantilla'], $informacion), 'text/html');
                try {
                    $this->get('mailer')->send($message);
                } catch (Exception $ex) {
                    
                }
            }
        } else {
            $message = (new Swift_Message($asunto))
                    ->setTo($destinatarios)
                    ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                    ->setBody(
                    $this->renderView($informacion['plantilla'], $informacion), 'text/html');
            try {
                $this->get('mailer')->send($message);
            } catch (Exception $ex) {
                
            }
        }
    }

}
