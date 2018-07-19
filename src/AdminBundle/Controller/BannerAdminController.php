<?php

namespace AdminBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Banner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use DateTime;
use Sonata\AdminBundle\Controller\CRUDController;

class BannerAdminController extends CRUDController {

    protected $em = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
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
        $imagenWeb = $existingObject->getImagenWeb();
        $imagenMobil = $existingObject->getImagenMobil();

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

            date_default_timezone_set('America/Bogota');
            $fechaActualAComparar = new \DateTime();
            $fechaActualAComparar = $fechaActualAComparar->format('Y-m-d');
            $fechaActualAComparar = strtotime($fechaActualAComparar);

            if ($form->get("fechaInicio")->getData() != null || $form->get("fechaInicio")->getData() != null) {
                $fechaInicio = $form->get("fechaInicio")->getData()->format('Y-m-d');
                $fechaInicio = strtotime($fechaInicio);

                $fechaFin = $form->get("fechaFin")->getData()->format('Y-m-d');
                $fechaFin = strtotime($fechaFin);
                if ($fechaInicio < $fechaActualAComparar) {
                    $form->get("fechaInicio")->addError(
                            new FormError($this->trans->trans('fechaMenorActual'))
                    );
                }

                if ($fechaFin < $fechaActualAComparar) {
                    $form->get("fechaFin")->addError(
                            new FormError($this->trans->trans('fechaMenorActual'))
                    );
                }

                if ($fechaFin < $fechaInicio) {
                    $form->get("fechaFin")->addError(
                            new FormError($this->trans->trans('fechaFinMenorInicio'))
                    );
                }
            }

            //TODO: remove this check for 4.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($existingObject);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();

                $formImagenWeb = $form->get("imagenWeb")->getData();
                $formImagenMobil = $form->get("imagenMobil")->getData();

                if ($formImagenWeb == null) {
                    $submittedObject->setImagenWeb($imagenWeb);
                }
                if ($formImagenMobil == null) {
                    $submittedObject->setImagenMobil($imagenMobil);
                }
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
        //$this->setFormTheme($formView, $this->admin->getFormTheme());
        $templateKey = "edit";
        return $this->renderWithExtraParams($this->admin->getTemplate($templateKey), [
                    'action' => 'edit',
                    'form' => $formView,
                    'object' => $existingObject,
                    'objectId' => $objectId,
                    'imagenWeb' => $imagenWeb,
                    'imagenMobil' => $imagenMobil
                        ], null);
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

        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->renderWithExtraParams(
                            'SonataAdminBundle:CRUD:select_subclass.html.twig', [
                        'base_template' => $this->getBaseTemplate(),
                        'admin' => $this->admin,
                        'action' => 'create',
                            ], null
            );
        }

        $newObject = $this->admin->getNewInstance();

        $imagenWeb = $newObject->getImagenWeb();
        $imagenMobil = $newObject->getImagenMobil();

        $preResponse = $this->preCreate($request, $newObject);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($newObject);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            date_default_timezone_set('America/Bogota');
            $fechaActualAComparar = new \DateTime();
            $fechaActualAComparar = $fechaActualAComparar->format('Y-m-d');
            $fechaActualAComparar = strtotime($fechaActualAComparar);

            if ($form->get("fechaInicio")->getData() != null || $form->get("fechaInicio")->getData() != null) {
                $fechaInicio = $form->get("fechaInicio")->getData()->format('Y-m-d');
                $fechaInicio = strtotime($fechaInicio);

                $fechaFin = $form->get("fechaFin")->getData()->format('Y-m-d');
                $fechaFin = strtotime($fechaFin);

                if ($fechaInicio < $fechaActualAComparar) {
                    $form->get("fechaInicio")->addError(
                            new FormError($this->trans->trans('fechaMenorActual'))
                    );
                }


                if ($fechaFin < $fechaActualAComparar) {
                    $form->get("fechaFin")->addError(
                            new FormError($this->trans->trans('fechaMenorActual'))
                    );
                }

                if ($fechaFin < $fechaInicio) {
                    $form->get("fechaFin")->addError(
                            new FormError($this->trans->trans('fechaFinMenorInicio'))
                    );
                }
            }

            if ($form->get("imagenWeb")->getData() == null) {
                $form->get("imagenWeb")->addError(
                        new FormError($this->trans->trans('novacio'))
                );
            }


            if ($form->get("imagenMobil")->getData() == null) {
                $form->get("imagenMobil")->addError(
                        new FormError($this->trans->trans('novacio'))
                );
            }



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
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ], 200, []);
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_create_success', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
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
                                    'flash_create_error', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
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

        return $this->renderWithExtraParams($this->admin->getTemplate($templateKey), [
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $newObject,
                    'objectId' => null,
                    'imagenWeb' => $imagenWeb,
                    'imagenMobil' => $imagenMobil
                        ], null);
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param string $theme
     */
    private function setFormTheme(FormView $formView, $theme) {
        $twig = $this->get('twig');

        // BC for Symfony < 3.2 where this runtime does not exists
        if (!method_exists(AppVariable::class, 'getToken')) {
            $twig->getExtension(FormExtension::class)->renderer->setTheme($formView, $theme);

            return;
        }

        // BC for Symfony < 3.4 where runtime should be TwigRenderer
        if (!method_exists(DebugCommand::class, 'getLoaderPaths')) {
            $twig->getRuntime(TwigRenderer::class)->setTheme($formView, $theme);

            return;
        }

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }

}
