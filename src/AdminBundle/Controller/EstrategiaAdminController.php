<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bundle\TwigBundle\Command\DebugCommand;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EstrategiaAdminController extends CRUDController {

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

                    // redirect to edit mode
                    return $this->redirectTo($existingObject);
                } catch (ModelManagerException $e) {
                    $mensaje = $e->getPrevious()->getMessage();
                    if (
                            strpos($mensaje, 'disciplina_estrategia') !== false &&
                            strpos($mensaje, 'DELETE') !== false
                    ) {
                        $mensajeArray = explode("[", $mensaje);
                        $mensajeArra2 = explode("]", $mensajeArray[1]);
                        $disciplinaEstrategiaId = $mensajeArra2[0];
                        $em = $this->container->get('doctrine')->getManager();
                        $disciplinaEstrategia = $em->getRepository('LogicBundle:DisciplinaEstrategia')->find($disciplinaEstrategiaId);
                        $this->addFlash('sonata_flash_error', $this->trans('error.oferta.disciplina_relacion', ['%disciplina%' => $disciplinaEstrategia->getDisciplina()->getNombre()]));
                    } else if (
                            strpos($mensaje, 'tendencia_estrategia') !== false &&
                            strpos($mensaje, 'DELETE') !== false
                    ) {
                        $mensajeArray = explode("[", $mensaje);
                        $mensajeArra2 = explode("]", $mensajeArray[1]);
                        $tendenciaEstrategiaId = $mensajeArra2[0];
                        $em = $this->container->get('doctrine')->getManager();
                        $tendenciaEstrategia = $em->getRepository('LogicBundle:TendenciaEstrategia')->find($tendenciaEstrategiaId);
                        $this->addFlash('sonata_flash_error', $this->trans('error.oferta.tendencia_relacion', ['%tendencia%' => $tendenciaEstrategia->getTendencia()->getNombre()]));
                    } else if (
                            strpos($mensaje, 'institucional_estrategia') !== false &&
                            strpos($mensaje, 'DELETE') !== false
                    ) {
                        $mensajeArray = explode("[", $mensaje);
                        $mensajeArra2 = explode("]", $mensajeArray[1]);
                        $institucionalEstrategiaId = $mensajeArra2[0];
                        $em = $this->container->get('doctrine')->getManager();
                        $institucionalEstrategia = $em->getRepository('LogicBundle:InstitucionalEstrategia')->find($institucionalEstrategiaId);
                        $this->addFlash('sonata_flash_error', $this->trans('error.oferta.categoria_institucional_relacion', ['%categoria%' => $institucionalEstrategia->getCategoriaInstitucional()->getNombre()]));
                    } else {
                        $this->handleModelManagerException($e);
                    }

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

        return $this->render($this->admin->getTemplate($templateKey), [
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
