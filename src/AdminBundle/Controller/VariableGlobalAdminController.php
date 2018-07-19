<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Form\VariableGlobalType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
class VariableGlobalAdminController extends CRUDController
{
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
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function editAction($id = null) {     	
        
        $request = $this->getRequest();        
        
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
        if($id == null){
            return $this->redirectToRoute('admin_logic_variableglobal_list');
        }else if($id == 0){
            return $this->redirectToRoute('admin_logic_variableglobal_list');
        }else{
            $variableGlobal = $this->em->getRepository("LogicBundle:VariableGlobal")->find($id);
        }

        $form = $this->admin->getForm();
        $form = $this->createForm(VariableGlobalType::class, $variableGlobal);      
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
        	if (count($variableGlobal->getDato1()) <= 0 ) {
                $form->get("dato1")->addError(
                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                );                
            }

            if (count($variableGlobal->getDato2()) <= 0 ) {
                $form->get("dato2")->addError(
                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                );
            } 
          
            if ($variableGlobal->getDato1() > $variableGlobal->getDato2()) {
                $form->get("dato1")->addError(
                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_fecha_inicial_mayor'))
                );
            }            

            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) {            	
            	$em->persist($variableGlobal);                            
				$em->flush();

            	$this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'flash_edit_success',
                        ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                        'SonataAdminBundle'
                    )
                );
                return $this->redirectToRoute('admin_logic_variableglobal_list');
            }
        }

        $formView = $form->createView();
        return $this->renderWithExtraParams($this->admin->getTemplate($templateKey), [
            'action' => 'edit',
            'form' => $formView,
            'object' => $existingObject,
            'objectId' => $objectId,
        ], null);
    }

    private function setFormTheme(FormView $formView, $theme)
    {
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
