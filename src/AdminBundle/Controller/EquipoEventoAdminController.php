<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use AdminBundle\ValidData\Validaciones;
use AdminBundle\ValidData\ValidarDatos;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;
use function dump;
use LogicBundle\Entity\EquipoEvento;
use LogicBundle\Entity\Evento;
// agregar cuadno se usa batch action delete
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EquipoEventoAdminController extends CRUDController {

    protected $validar = null;
    protected $validaciones = null;
    protected $em = null;

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->validar = new ValidarDatos($container);
        $this->validaciones = new Validaciones();
        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * List action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listAction() {

        $request = $this->getRequest();
        $idEvento = $request->get('id');

        $ultimaRuta = $request->headers->get('referer');
        $form = $this->admin->getForm();
        $form->handleRequest($request);


        if ($request->get('id') != null) {
            $idEvento == $request->get('id');
        }
        if ($idEvento == null) {
            $idEvento = 1;
        }
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
        $em = $this->getDoctrine()->getManager();
        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
        }

        $cupo = $evento->getCupo();

        $jugadores = $evento->getJugadorEventos();
        $jugadoresEvento = array();
        $equipos = null;
        $equiposEvento = array();
        if ($cupo == "Individual") {
            foreach ($jugadores as $jugador) {
                array_push($jugadoresEvento, array('jugadores' => $jugador->getUsuarioJugadorEvento(), 'estado' => $jugador->getEstado()));
            }
        } else if ($cupo == "Equipos") {
            foreach ($jugadores as $jugador) {
                $equipo = $jugador->getEquipoEvento();
                if ($equipo != null) {
                    $equipo = $jugador->getEquipoEvento()->getNombre();
                }
                array_push($jugadoresEvento, array('jugadores' => $jugador->getUsuarioJugadorEvento(), 'estado' => $jugador->getEstado(), 'observacion' => $jugador->getObservacion(), 'equipo' => $equipo));
            }
            $equipos = $evento->getEquipoEventos();
        }
        if ($equipos != null) {
            foreach ($equipos as $equipo) {
                $numJugadores = count($equipo->getJugadorEventos());
                array_push($equiposEvento, array('equipo' => $equipo, 'numeroJugadores' => $numJugadores));
            }
        }


        return $this->render($this->admin->getTemplate('list'), [
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'idevento' => $idEvento,
                    'evento' => $evento,
                    'nombreEvento' => $evento->getNombre(),
                    'equipos' => $equiposEvento,
                    'jugadores' => $jugadoresEvento,
                    'tabActivo' => 'admin_logic_evento_equiposParticipantes',
                    'cupo' => $cupo,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'ultimaRuta' => $ultimaRuta,
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
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
        $idEvento = $request->get('id');
        $templateKey = 'edit';

        $em = $this->getDoctrine()->getManager();
        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
        }

        $newObject = $this->admin->getNewInstance();

        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $submittedObject = $form->getData();
                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                $em = $this->getDoctrine()->getManager();
                if ($idEvento == 0) {
                    $evento = new Evento();
                } else {
                    $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
                }

                try {

                    $submittedObject->setEvento($evento);

                    $publica = false;
                    foreach ($evento->getCampoFormularioEventos() as $value) {
                        $ins = $value->getPertenece();
                        if ($ins == "Inscripcion Publica") {
                            $publica = true;
                            break;
                        }
                    }

                    if ($publica == true) {
                        $submittedObject->setEstado(1);
                    } else {
                        $submittedObject->setEstado(0);
                    }

                    $newObject = $this->admin->create($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ), 200, array());
                    }

                    //return $this->redirectTo($newObject);
                    return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'idevento' => $idEvento,
                    'nombreEvento' => $evento->getNombre(),
                    'object' => $newObject,
                    'objectId' => null,
        ));
    }

    // ------------------------ batchActionDelete ------------------------------

    /**
     * Batch action.
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the HTTP method is not POST
     * @throws \RuntimeException     If the batch action is not defined
     */
    public function batchAction() {

        $request = $this->getRequest();
        $restMethod = $this->getRestMethod();

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
                            . ' is deprecated since version 3.2.'
                            . ' Use Sonata\AdminBundle\Admin\AbstractAdmin::configureBatchActions instead.'
                            . ' The method will be final in 4.0.', E_USER_DEPRECATED
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
                    'sonata_flash_info', $this->trans($nonRelevantMessage, [], 'SonataAdminBundle')
            );



            return new RedirectResponse($this->admin->generateUrl('list', ['id' => $this->getRequest()->get('id')]));
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
                        'id' => $this->getRequest()->get('id'),
                        'tipo' => $this->getRequest()->get('tipo'),
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

        $respose = call_user_func([$this, $finalAction], $query, $request);


        return call_user_func([$this, $finalAction], $query, $request);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionAprobar(ProxyQueryInterface $selectedModelQuery, Request $request = null) {

        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('delete');

        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $selectedCategories = $selectedModelQuery->execute();

        $parameterBag = $request->request;

        if (!$parameterBag->has('idx')) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
        }

        $targetId = $parameterBag->get('idx');


        try {
            /** @var Category $category */
            foreach ($targetId as $id) {

                $equipo = $em->getRepository('LogicBundle:EquipoEvento')->find($id);

                $equiposAprobados = $em->getRepository('LogicBundle:EquipoEvento')
                        ->createQueryBuilder('equipoEvento')
                        ->Where('equipoEvento.evento = :evento')
                        ->andWhere('equipoEvento.estado = :estado')
                        ->setParameter('evento', $idEvento)
                        ->setParameter('estado', '1')
                        ->getQuery()
                        ->getResult();

                if (count($equiposAprobados) >= $equipo->getEvento()->getNumeroEquipos()) {
                    $this->addFlash('sonata_flash_error', $this->trans('errorCupoEquiposEventos'));

                    return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
                } else {

                    $equipo->setEstado(1);

                    $this->admin->update($equipo);
                }
            }
        } catch (\Exception $e) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
        }

        $this->addFlash('sonata_flash_success', $this->trans('alerta.equipos_aprobados'));

        return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionRechazar(ProxyQueryInterface $selectedModelQuery, Request $request = null) {

        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('delete');

        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $selectedCategories = $selectedModelQuery->execute();

        $parameterBag = $request->request;

        if (!$parameterBag->has('idx')) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
        }

        $targetId = $parameterBag->get('idx');

        try {
            /** @var Category $category */
            foreach ($targetId as $id) {

                $equipo = $em->getRepository('LogicBundle:EquipoEvento')->find($id);

                $equipo->setEstado(2);

                $this->admin->update($equipo);
            }
        } catch (\Exception $e) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
        }

        $this->addFlash('sonata_flash_success', $this->trans('alerta.equipos_rechazados'));

        return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
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
