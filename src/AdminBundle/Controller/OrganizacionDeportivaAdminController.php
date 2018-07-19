<?php

namespace AdminBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use DateTime;
use LogicBundle\Form\OrganizacionDeportivaType;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;

class OrganizacionDeportivaAdminController extends CRUDController {

    protected $em;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->em = $this->container->get("doctrine")->getManager();
    }

    /**
     * Edit action.
     *
     * @param int|string|null $id
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response|RedirectResponse
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
        if (null !== $preResponse) {
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
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $objectId,
                                    'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                                        ], 200, []);
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_edit_success', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
                            )
                    );
                    $em = $this->getDoctrine()->getManager();
                    $usuario = new User();
                    foreach ($existingObject->getUsuarios() as $usuario) {
                        $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName("Registrado (Organismos deportivos)");
                        $usuario->addGroup($grupo);
                        $em->persist($usuario);
                    }
                    $em->flush();
                    // redirect to edit mode
                    return $this->redirectTo($existingObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', [
                                '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                                '%link_start%' => '<a href="' . $this->admin->generateObjectUrl('edit', $existingObject) . '">',
                                '%link_end%' => '</a>',
                                    ], 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_edit_error', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
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

        return $this->renderWithExtraParams($this->admin->getTemplate($templateKey), [
                    'action' => 'edit',
                    'form' => $formView,
                    'object' => $existingObject,
                    'objectId' => $objectId,
                        ], null);
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

    public function getUsuarioAcceso($id = null) {
        $user = $this->getUser();
        $securityContext = $this->container->get('security.authorization_checker');

        if (!$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = $this->em->getRepository("LogicBundle:OrganizacionDeportiva")->buscarOrganizacionesDeportivas($user, $id);

            if ($entity) {
                if ($entity->getId() != $id) {
                    throw new AccessDeniedException();
                }
            } else {
                throw new AccessDeniedException();
            }
        }
    }

    /**
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function solicitudRegistroAction($id = null) {
        $request = $this->getRequest();
        $templateKey = 'solicitudRegistro';
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
        $form = $this->createForm(OrganizacionDeportivaType::class, $existingObject, array('paso' => 6));
        $form->setData($existingObject);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($existingObject);
            }
            $isFormValid = $form->isValid();
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $translator = $this->get('translator');
                $submittedObject = $form->getData();
                $vigencia = $request->get('organizacion_deportiva')['vigencia_calculada'];
                
                if (!$vigencia) {
                    $submittedObject->setAprobado(false);
                } else {
                    $submittedObject->setAprobado(true);
                }
                
                $this->admin->setSubject($submittedObject);

                try {
                    if ($submittedObject->getAprobado() == 1) {
                        $estado = $translator->trans('correos.cambiossolicitud.aprobado');
                        $submittedObject->setTerminoregistro(TRUE);
                        $submittedObject->setAprobado(TRUE);

                        $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName("Registrado (Organismos deportivos)");
                        if ($grupo) {
                            foreach ($submittedObject->getUsuarios() as $key => $usuario) {
                                $usuarioConGrupo = $this->em->getRepository("ApplicationSonataUserBundle:User")->buscarGrupoUsuario($usuario, $grupo);
                                if ($usuarioConGrupo) {
                                    $usuario->addGroup($grupo);
                                }
                            }
                        }
                    } else {
                        $estado = $translator->trans('correos.cambiossolicitud.rechazada');
                        $submittedObject->setTerminoregistro(false);
                        $submittedObject->setAprobado(false);
                    }

                    $vigencia = $request->request->get('organizacion_deportiva')['vigencia_calculada'];
                    if($vigencia){
                        $submittedObject->setVigencia(DateTime::createFromFormat('d/m/Y', $vigencia));
                    }
                    $existingObject = $this->admin->update($submittedObject);
                    $informacion = array('objeto' => $submittedObject, 'estado' => $estado, 'plantilla' => 'AdminBundle:OrganizacionDeportiva:mails/mailSolicitud.html.twig');
                    foreach ($submittedObject->getUsuarios() as $key => $usuario) {
                        if ($usuario->getEmail()) {
                            $destinatario = $usuario->getEmail();
                            $this->enviarCorreo($destinatario, $translator->trans('correos.cambiossolicitud.asunto'), $informacion);
                        }
                    }

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
                    return new RedirectResponse($this->admin->generateUrl('list'));
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
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_edit_error', array('%name%' => $this->escapeHtml($this->admin->toString($existingObject))), 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }
        $formView = $form->createView();
        return $this->render("AdminBundle:OrganizacionDeportiva:solicitudRegistro.html.twig", array(
                    'action' => 'solicitudRegistro',
                    'form' => $formView,
                    'object' => $existingObject,
                    'objectId' => $objectId,
                        ), null);
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
