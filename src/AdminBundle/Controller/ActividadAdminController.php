<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

use LogicBundle\Form\ActividadType;
use LogicBundle\Entity\Actividad;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig_Error_Runtime;

use Doctrine\Common\Inflector\Inflector;

class ActividadAdminController extends CRUDController
{
    protected $em = null;
    
    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
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
        $idpam = $request->get('pam');
        
        if(!$idpam){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_list', array('id' => $idpam));
        }
        return $this->redirectToRoute('admin_logic_actividad_crearActividad', array('id' => 0, 'pam' => $idpam));
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
        $idpam = $request->get('pam');
        // the key used to lookup the template
        $templateKey = 'edit';

        $object = $this->admin->getObject($id);

        $em = $this->getDoctrine()->getManager();

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
                        return $this->renderJson([
                            'result' => 'ok',
                            'objectId' => $objectId,
                            'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                        ], 200, []);
                    }

                    
                    $this->addFlash('sonata_flash_success', $this->trans('formulario_actividades.actividad_editada'));

                    // redirect to edit mode
                    return $this->redirectToRoute('admin_logic_actividad_list', array('pam' => $idpam));

                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', [
                        '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                        '%link_start%' => '<a href="'.$this->admin->generateObjectUrl('edit', $existingObject).'">',
                        '%link_end%' => '</a>',
                    ], 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->trans(
                            'flash_edit_error',
                            ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                            'SonataAdminBundle'
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
            'actividad' => $object->getId(),
            'idpam' => $idpam
        ], null);
    }

    /**
     * List action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listAction()
    {
        $request = $this->getRequest();
        $idpam = $request->get('pam');

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), [
            'action' => 'list',
            'form' => $formView,
            'datagrid' => $datagrid,
            'idpam' => $idpam,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                $this->admin->getExportFormats(),
        ], null);
    }    


    function crearActividadAction(Request $request, $id){

        $idpam = $request->get('pam');

        if(!$idpam){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_list', array('id' =>$idpam ));
        }
        
        if($id == 0){
            $actividad = new Actividad();
        }else{
            $actividad = $this->em->getRepository("LogicBundle:Actividad")->find($id);
        }

        $form = $this->createForm( ActividadType::class, $actividad, array(
            'actividadId' => $actividad->getId(),
            'idpam' => $idpam,
            'em' => $this->container
        ));
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
          
            if ($form->isValid()) {
                $actividades = $this->em->getRepository("LogicBundle:Actividad")->createQueryBuilder('a')
                ->join('a.contenido', 'con')
                ->join('con.componente', 'c')
                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                ->where('planAnualMetodologico.id = :id_pam')
                ->setParameter('id_pam', $idpam)
                ->getQuery()
                ->getResult();

                $pam = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($idpam);
                if(count($actividades) == 0){
                    $pam->setEstado(false);
                }else if(count($actividades) > 0){
                    $pam->setEstado(true);
                }
                $this->em->persist($pam);
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_actividad_list', array(
                    'pam' => $idpam
            ));
            
            }
         }
        
        return $this->render('AdminBundle:Actividad:actividades.html.twig', array(
            'form' => $form->createView(),
            'actividadId' => $id ,
            'idpam' => $idpam
        ));
    }

    /**
     * Batch action.
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the HTTP method is not POST
     * @throws \RuntimeException     If the batch action is not defined
     */
    public function batchAction()
    {

        $request = $this->getRequest();
        $restMethod = $this->getRestMethod();
        $idpam = $request->get('pam');

        if ('POST' !== $restMethod) {
            throw $this->createNotFoundException(sprintf('Invalid request type "%s", POST expected', $restMethod));
        }

        // check the csrf token
        $this->validateCsrfToken('sonata.batch');

        $confirmation = $request->get('confirmation', false);

        
        if ($data = json_decode($request->get('data'), true)) {

            $action = $data['action'];
            $idx = $data['idx'];
            $allElements = $data['all_elements'];
            $request->request->replace(array_merge($request->request->all(), $data));
        } else {
            $request->request->set('idx', $request->get('idx', []));
            $request->request->set('all_elements', $request->get('all_elements', false));

            $action = $request->get('action');
            $idx = $request->get('idx');
            $allElements = $request->get('all_elements');
            $data = $request->request->all();

            unset($data['_sonata_csrf_token']);
        }


        // NEXT_MAJOR: Remove reflection check.
        $reflector = new \ReflectionMethod($this->admin, 'getBatchActions');
        if ($reflector->getDeclaringClass()->getName() === get_class($this->admin)) {
            @trigger_error('Override Sonata\AdminBundle\Admin\AbstractAdmin::getBatchActions method'
                .' is deprecated since version 3.2.'
                .' Use Sonata\AdminBundle\Admin\AbstractAdmin::configureBatchActions instead.'
                .' The method will be final in 4.0.', E_USER_DEPRECATED
            );
        }
        $batchActions = $this->admin->getBatchActions();
        if (!array_key_exists($action, $batchActions)) {
            throw new \RuntimeException(sprintf('The `%s` batch action is not defined', $action));
        }

        $camelizedAction = Inflector::classify($action);
        $isRelevantAction = sprintf('batchAction%sIsRelevant', $camelizedAction);

        

        if (method_exists($this, $isRelevantAction)) {
            $nonRelevantMessage = call_user_func([$this, $isRelevantAction], $idx, $allElements, $request);
        } else {
            $nonRelevantMessage = count($idx) != 0 || $allElements; // at least one item is selected
        }


        if (!$nonRelevantMessage) { // default non relevant message (if false of null)
            $nonRelevantMessage = 'flash_batch_empty';
        }


        $datagrid = $this->admin->getDatagrid();
        $datagrid->buildPager();


        if (true !== $nonRelevantMessage) {

            $this->addFlash(
                'sonata_flash_info',
                $this->trans($nonRelevantMessage, [], 'SonataAdminBundle')
            );

            
            return new RedirectResponse($this->admin->generateUrl('list',[ 'pam'=>$idpam ]));
        }


        $askConfirmation = isset($batchActions[$action]['ask_confirmation']) ?
            $batchActions[$action]['ask_confirmation'] :
            true;
           
        if ($askConfirmation && $confirmation != 'ok') {

            $actionLabel = $batchActions[$action]['label'];
            $batchTranslationDomain = isset($batchActions[$action]['translation_domain']) ?
                $batchActions[$action]['translation_domain'] :
                $this->admin->getTranslationDomain();

            $formView = $datagrid->getForm()->createView();
            $this->setFormTheme($formView, $this->admin->getFilterTheme());

            return $this->render($this->admin->getTemplate('batch_confirmation'), [
                'action' => 'list',
                'action_label' => $actionLabel,
                'batch_translation_domain' => $batchTranslationDomain,
                'datagrid' => $datagrid,
                'pam' => $request->get('pam'),
                'id' => $actividad->getId(),
                'form' => $formView,
                'data' => $data,
                'csrf_token' => $this->getCsrfToken('sonata.batch'),
            ], null);
        }

        // execute the action, batchActionXxxxx
        $finalAction = sprintf('batchAction%s', $camelizedAction);

        if (!is_callable([$this, $finalAction])) {

            throw new \RuntimeException(sprintf('A `%s::%s` method must be callable', get_class($this), $finalAction));
        }


        $query = $datagrid->getQuery();

        $query->setFirstResult(null);
        $query->setMaxResults(null);

        $this->admin->preBatchAction($action, $query, $idx, $allElements);

    
        if (count($idx) > 0) {
            $this->admin->getModelManager()->addIdentifiersToQuery($this->admin->getClass(), $query, $idx);
        } elseif (!$allElements) {
            $query = null;
        }

        $respose =  call_user_func([$this, $finalAction], $query, $request);
      
        
        return call_user_func([$this, $finalAction], $query, $request);
    }

    /**
     * Execute a batch delete.
     *
     * @param ProxyQueryInterface $query
     *
     * @return RedirectResponse
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function batchActionDelete(ProxyQueryInterface $query)
    {
        $request = $this->getRequest();
        $idpam = $request->get('pam');

        $this->admin->checkAccess('batchDelete');

        $modelManager = $this->admin->getModelManager();

        try {
            $modelManager->batchDelete($this->admin->getClass(), $query);
           
            
            $this->addFlash('sonata_flash_success', $this->trans('formulario_actividades.actividad_eliminada'));

        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash(
                'sonata_flash_error',
                $this->trans('flash_batch_delete_error', [], 'SonataAdminBundle')
            );
        }

        return new RedirectResponse($this->admin->generateUrl('list',[ 'pam' => $idpam ]));
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
}
