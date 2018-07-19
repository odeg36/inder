<?php

namespace AdminBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Form\CambiarContrasenaUsuarioType;
use LogicBundle\Form\CargarMultiplesUsuariosType;
use ReflectionClass;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;

class UsuarioAdminController extends CRUDController {

    public function cargarMultiplesUsuariosAction() {
        $request = $this->getRequest();
        $formCargarMultiplesUsuarios = $this->createForm(CargarMultiplesUsuariosType::class);
        $formCargarMultiplesUsuarios->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $archivo = $formCargarMultiplesUsuarios->get('archivo_usuarios')->getData();
        $mimes = array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel',
            'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel',
            'application/xls', 'application/x-xls', 'text/csv', 'application/xlsx',
            'application/octet-stream',
            'application/zip',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if ($archivo && $archivo != "") {
            if (in_array($archivo->getMimeType(), $mimes)) {
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo)->setActiveSheetIndex(0);
                $dataExcel = $phpExcelObject->toArray();
                $titulos = array_values($dataExcel[0]);
                $datos = [];
                unset($dataExcel[0]);
                foreach ($dataExcel as $data) {
                    array_splice($data, 3);
                    array_push($datos, $data);
                }
                $trans = $this->container->get('translator');
                if (count($datos) <= 0) {
                    $formCargarMultiplesUsuarios->addError(new FormError('error.usuario.carga_excel.vacio'));
                } else {
                    foreach ($datos as $key => $dato) {
                        foreach ($dato as $keys => $dat) {
                            if ($dat == null || $dat == "") {
                                $formCargarMultiplesUsuarios->addError(new FormError($trans->trans('error.usuario.carga_excel.campo_vacio', ['%columna%' => $titulos[$keys], '%fila%' => $key + 1])));
                            }
                        }
                        $tipoIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneBy(array('abreviatura' => $dato[0]));
                        $UsuarioPorDocumento = $em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('username' => $dato[0] . $dato[1]));

                        if (count($tipoIdentificacion) == 0) {
                            $formCargarMultiplesUsuarios->addError(new FormError($trans->trans('error.usuario.carga_excel.no_existe.tipo_identificacion', ['%columna%' => $titulos[0], '%fila%' => $key + 1])));
                        }
                        if (count($UsuarioPorDocumento) > 0) {
                            $formCargarMultiplesUsuarios->addError(new FormError($trans->trans('error.usuario.carga_excel.ya_existe.numero_documento', ['%columna%' => $titulos[1], '%fila%' => $key + 1])));
                        }
                    }

                    if ($formCargarMultiplesUsuarios->isValid()) {
                        $encoder = $this->container->get('inder.encoder');
                        foreach ($datos as $key => $dato) {
                            $tipoIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneBy(array('abreviatura' => $dato[0]));
                            $usuario = new User();
                            $usuario->setTipoIdentificacion($tipoIdentificacion);
                            $usuario->setNumeroIdentificacion($dato[1]);
                            $usuario->setFirstname($dato[2]);
                            $usuario->setRoles(array('ROLE_PERSONANATURAL'));
                            $usuario->setEnabled(false);
                            $em->persist($usuario);
                        }
                        $em->flush();
                        $this->addFlash('success', 'carga completa');
                        return $this->redirect($this->generateUrl('admin_sonata_user_user_create'));
                    }
                }
            }
        }
        $templateKey = 'edit';
        $newObject = $this->admin->getNewInstance();
        $this->admin->setSubject($newObject);
        $form = $this->admin->getForm();
        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());
        $formCargarMultiplesUsuariosView = $formCargarMultiplesUsuarios->createView();
        $formCambiarContrasenaUsuario = $this->createForm(CambiarContrasenaUsuarioType::class, [], ['container' => $this->container])->createView();
        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'formCargarMultiplesUsuarios' => $formCargarMultiplesUsuariosView,
                    'formCambiarContrasenaUsuario' => $formCambiarContrasenaUsuario,
                    'object' => $newObject,
                    'objectId' => null,
                        ), null);
    }

    public function cambiarContrasenaUsuarioAction($id = null) {
        $em = $this->getDoctrine()->getManager();
        $templateKey = 'edit';
        $encoder = $this->container->get('inder.encoder');
        $trans = $this->container->get('translator');
        $request = $this->getRequest();
        $formCambiarContrasena = $this->createForm(CambiarContrasenaUsuarioType::class, [], ['container' => $this->container]);
        $formCambiarContrasena->handleRequest($request);

        $usuario = $this->admin->getObject($id);
        $userAutenticado = $this->getUser();
        if (!$userAutenticado->hasRole('ROLE_SUPER_ADMIN')) {
            $password = $encoder->encodePassword($formCambiarContrasena->get('contrasenaActual')->getData(), $usuario->getSalt());
            $contrasenaNuevaUno = $formCambiarContrasena->get('contrasenaNueva')->getData();
            if ($usuario->getPassword() != $password) {
                $formCambiarContrasena->addError(new FormError($trans->trans('error.usuario.contrasena.incorrecta')));
            }
            if ($password == $contrasenaNuevaUno) {
                $formCambiarContrasena->addError(new FormError($trans->trans('error.usuario.contrasena.nueva.igual_anterior')));
            }
        }

        if ($formCambiarContrasena->isValid()) {
            $passwordNuevo = $encoder->encodePassword($formCambiarContrasena->get('contrasenaNueva')->get("first")->getData(), $usuario->getSalt());
            $usuario->setPassword($passwordNuevo);
            $em->persist($usuario);
            $em->flush();
            $this->addFlash('success', 'Cambio de contraseña correcto');
        }

        $existingObject = $this->admin->getObject($usuario->getId());
        $this->admin->setSubject($existingObject);
        $form = $this->admin->getForm();
        $form->setData($existingObject);
        $formView = $form->createView();
        $formCargarMultiplesUsuarios = $this->createForm(CargarMultiplesUsuariosType::class)->createView();
        $formCambiarContrasenaView = $formCambiarContrasena->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());
        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'formCambiarContrasenaUsuario' => $formCambiarContrasenaView,
                    'formCargarMultiplesUsuarios' => $formCargarMultiplesUsuarios,
                    'object' => $existingObject,
                    'objectId' => null,
                        ), null);
    }

    /**
     * Create action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function createAction() {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->render(
                            'SonataAdminBundle:CRUD:select_subclass.html.twig', array(
                        'base_template' => $this->getBaseTemplate(),
                        'admin' => $this->admin,
                        'action' => 'create',
                            ), null, $request
            );
        }

        $newObject = $this->admin->getNewInstance();

        $preResponse = $this->preCreate($request, $newObject);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($newObject);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO: remove this check for 4.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($newObject);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();
                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                try {
                    $newObject = $this->admin->create($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ), 200, array());
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_create_success', array('%name%' => $this->escapeHtml($this->admin->toString($newObject))), 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($newObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_create_error', array('%name%' => $this->escapeHtml($this->admin->toString($newObject))), 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        $formCargarMultiplesUsuarios = $this->createForm(CargarMultiplesUsuariosType::class)->createView();
        $formCambiarContrasenaUsuario = $this->createForm(CambiarContrasenaUsuarioType::class, [], ['container' => $this->container])->createView();
        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'formCargarMultiplesUsuarios' => $formCargarMultiplesUsuarios,
                    'formCambiarContrasenaUsuario' => $formCambiarContrasenaUsuario,
                    'object' => $newObject,
                    'objectId' => null,
                        ), null);
    }

    /**
     * Edit action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function editAction($id = null) {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $request->get($this->admin->getIdParameter());
        $existingObject = $this->admin->getObject($id);

        if (!$existingObject) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->admin->checkAccess('edit', $existingObject);

        $preResponse = $this->preEdit($request, $existingObject);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($existingObject);
        $objectId = $this->admin->getNormalizedIdentifier($existingObject);

        /** @var $form Form */
        $form = $this->admin->getForm();
        $form->setData($existingObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO: remove this check for 4.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($existingObject);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();
                $this->admin->setSubject($submittedObject);

                try {
                    $existingObject = $this->admin->update($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result' => 'ok',
                                    'objectId' => $objectId,
                                    'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                                        ), 200, array());
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_edit_success', array('%name%' => $this->escapeHtml($this->admin->toString($existingObject))), 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($existingObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', array(
                                '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                                '%link_start%' => '<a href="' . $this->admin->generateObjectUrl('edit', $existingObject) . '">',
                                '%link_end%' => '</a>',
                                    ), 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_edit_error', array('%name%' => $this->escapeHtml($this->admin->toString($existingObject))), 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        $formCargarMultiplesUsuarios = $this->createForm(CargarMultiplesUsuariosType::class)->createView();
        $formCambiarContrasenaUsuario = $this->createForm(CambiarContrasenaUsuarioType::class, [], ['container' => $this->container])->createView();
        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'edit',
                    'form' => $formView,
                    'formCargarMultiplesUsuarios' => $formCargarMultiplesUsuarios,
                    'formCambiarContrasenaUsuario' => $formCambiarContrasenaUsuario,
                    'object' => $existingObject,
                    'objectId' => $objectId,
                        ), null);
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param FormView $formView
     * @param string   $theme
     */
    private function setFormTheme(FormView $formView, $theme) {
        $twig = $this->get('twig');

        try {
            $twig
                    ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                    ->setTheme($formView, $theme);
        } catch (Twig_Error_Runtime $e) {
            // BC for Symfony < 3.2 where this runtime not exists
            $twig
                    ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                    ->renderer
                    ->setTheme($formView, $theme);
        }
    }

    /**
     * @return Response
     */
    public function dashboardAction() {
        $blocks = [
            'top' => [],
            'left' => [],
            'center' => [],
            'right' => [],
            'bottom' => [],
        ];
        foreach ($this->container->getParameter('sonata.admin.configuration.dashboard_blocks') as $block) {
            $blocks[$block['position']][] = $block;
        }

        $parameters = [
            'base_template' => $this->getBaseTemplate(),
            'admin_pool' => $this->container->get('sonata.admin.pool'),
            'blocks' => $blocks,
        ];

        if (!$this->getCurrentRequest()->isXmlHttpRequest()) {
            $parameters['breadcrumbs_builder'] = $this->get('sonata.admin.breadcrumbs_builder');
        }

        return $this->render($this->getAdminPool()->getTemplate('dashboard'), $parameters);
    }

    /**
     * Get the request object from the container.
     *
     * @return Request
     */
    private function getCurrentRequest() {
        // NEXT_MAJOR: simplify this when dropping sf < 2.4
        if ($this->container->has('request_stack')) {
            return $this->container->get('request_stack')->getCurrentRequest();
        }

        return $this->container->get('request');
    }

    /**
     * @return Pool
     */
    protected function getAdminPool() {
        return $this->container->get('sonata.admin.pool');
    }

}
