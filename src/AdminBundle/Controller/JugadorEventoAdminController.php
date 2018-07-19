<?php

namespace AdminBundle\Controller;

// agregar cuando se usa batch action delete


use Doctrine\Common\Inflector\Inflector;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use LogicBundle\Entity\Evento;
use LogicBundle\Entity\JugadorEvento;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;
use function dump;
use Whyte624\SonataAdminExtraExportBundle\Controller\CRUDControllerExtraExportTrait;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Swift_Message;

class JugadorEventoAdminController extends CRUDController {

    protected $em = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

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


        if ($request->get('id') != null) {
            $idEvento == $request->get('id');
        }

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $em = $this->getDoctrine()->getManager();
        $this->admin->setEm($em);
        $this->admin->setIdEvento($idEvento);
        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();


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
                array_push($jugadoresEvento, array('jugadores' => $jugador->getUsuarioJugadorEvento(), 'estado' => $jugador->getEstado(), 'observacion' => $jugador->getObservacion()));
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
     * Carnes action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listaCarnesAction() {

        $request = $this->getRequest();
        $idEvento = $request->get('id');

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $em = $this->getDoctrine()->getManager();
        $this->admin->setEm($em);
        $this->admin->setIdEvento($idEvento);
        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();


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
                array_push($jugadoresEvento, array('jugadores' => $jugador->getUsuarioJugadorEvento(), 'estado' => $jugador->getEstado(), 'observacion' => $jugador->getObservacion()));
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


        return $this->render($this->admin->getTemplate('listCarnes'), [
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'idevento' => $idEvento,
                    'evento' => $evento,
                    'nombreEvento' => $evento->getNombre(),
                    'equipos' => $equiposEvento,
                    'jugadores' => $jugadoresEvento,
                    'tabActivo' => 'carnes',
                    'cupo' => $cupo,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
                        ], null);
    }

    /**
     * Show action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function showAction($id = NULL) {

        $request = $this->getRequest();
        $idJugadorEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $jugadorEventoRepository = $em->getRepository('LogicBundle:JugadorEvento');

        $jugador = $em->getRepository('LogicBundle:JugadorEvento')->find($idJugadorEvento);

        $jugadorEvento = $jugador;
        $idEvento = $jugador->getEvento()->getId();
        $idUsuario = $jugador->getUsuarioJugadorEvento()->getId();
        $this->admin->setIdEvento($idEvento);
        $this->admin->checkAccess('show');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
        }

        $jugador = $em->getRepository('ApplicationSonataUserBundle:User')->find($idUsuario);

        return $this->render('AdminBundle:Evento/Jugadores:mostrar_jugador_evento.html.twig', array(
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => '$datagrid',
                    'idevento' => $idEvento,
                    'nombreEvento' => $evento->getNombre(),
                    'jugadores' => '$jugadoresEvento',
                    'tabActivo' => 'admin_logic_evento_equiposParticipantes',
                    'evento' => $evento,
                    'jugador' => $jugador,
                    'jugadorEvento' => $jugadorEvento
        ));
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
        $cupo = $evento->getCupo();
        $this->admin->setCupo($cupo);

        $newObject = $this->admin->getNewInstance();

        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);
        $jugadorEvento = new JugadorEvento();


        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $usuario = $em->getRepository("ApplicationSonataUserBundle:User")
                            ->createQueryBuilder('user')
                            ->where('user.tipoIdentificacion = :tipoIdentificacion')
                            ->andWhere('user.numeroIdentificacion = :numeroIdentificacion')
                            ->setParameter('tipoIdentificacion', $form->get("tipoIdentificacion")->getData()->getId())
                            ->setParameter('numeroIdentificacion', $form->get("usuarioJugadorEvento")->getData())
                            ->getQuery()->getOneOrNullResult();

            if ($usuario == null) {
                $form->get("usuarioJugadorEvento")->addError(
                        new FormError($this->trans->trans('jugadorNoexiste'))
                );
            } else {
                $jugadorEvento = $em->getRepository("LogicBundle:JugadorEvento")
                                ->createQueryBuilder('jugador')
                                ->where('jugador.evento = :evento_id')
                                ->andWhere('jugador.usuarioJugadorEvento = :id_usuario')
                                ->setParameter('evento_id', $idEvento)
                                ->setParameter('id_usuario', $usuario->getId())
                                ->getQuery()->getOneOrNullResult();
                if ($jugadorEvento != null) {
                    $form->get("usuarioJugadorEvento")->addError(
                            new FormError($this->trans->trans('jugadorYaAsociado'))
                    );
                }
            }

            if ($form->isValid()) {


                $submittedObject = $form->getData();
                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                try {
                    $em = $this->getDoctrine()->getManager();
                    $usuario = $em->getRepository("ApplicationSonataUserBundle:User")
                                    ->createQueryBuilder('user')
                                    ->where('user.tipoIdentificacion = :tipoIdentificacion')
                                    ->andWhere('user.numeroIdentificacion = :numeroIdentificacion')
                                    ->setParameter('tipoIdentificacion', $form->get("tipoIdentificacion")->getData()->getId())
                                    ->setParameter('numeroIdentificacion', $form->get("usuarioJugadorEvento")->getData())
                                    ->getQuery()->getOneOrNullResult();




                    $submittedObject->setUsuarioJugadorEvento($usuario);
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
                        $submittedObject->setEstado('Aprobado');
                    } else {
                        $submittedObject->setEstado('Pendiente');
                    }
                    $newObject = $this->admin->create($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ), 200, array());
                    }

                    $mailsGestores = array();
                    $user = $this->getUser();
                    array_push($mailsGestores, $user->getEmail());
                    if ($usuario->getEmail() != null) {
                        array_push($mailsGestores, $usuario->getEmail());
                    }

                    if (count($mailsGestores) > 0) {
                        $informacion = array('objeto' => $submittedObject, 'plantilla' => 'AdminBundle:EscenarioDeportivo:mails/mailSolicitudArchivos.html.twig');
                        $this->enviarCorreo($mailsGestores, $this->trans('correos.evento.jugadorEventoAsunto'), $informacion);
                    }

                    $mensaje = $this->trans('formulario_evento.labels.jugador_evento.informacion_enviar_usuario_correo');
                    $mensaje = $mensaje . ": " . $this->trans('formulario_evento.labels.jugador_evento.imagen_documento');
                    $mensaje = $mensaje . ", " . $this->trans('formulario_evento.labels.jugador_evento.imagen_eps');
                    $this->addFlash('sonata_flash_success', $mensaje);
                    // redirect to edit mode                    
                    if ($cupo == "Individual") {
                        return $this->redirectToRoute('admin_logic_jugadorevento_list', array('id' => $idEvento));
                    } else {
                        return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $idEvento));
                    }
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
                    'cupo' => $cupo,
                    'idevento' => $idEvento,
                    'nombreEvento' => $evento->getNombre(),
                    'object' => $newObject,
                    'objectId' => null,
        ));
    }

    /**
     * Delete action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function deleteAction($id) {
        $request = $this->getRequest();

        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if ($object->getSistemaJuegoUno()->getTipoSistema() == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }

        $em = $this->getDoctrine()->getManager();


        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Escalera") {
            $tipo = 1;
        }

        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Piramide") {
            $tipo = 2;
        }

        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Chimenea") {
            $tipo = 3;
        }

        $eventoId = $object->getSistemaJuegoUno()->getEvento()->getId();

        $em->remove($object);
        $em->flush();

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                        'result' => 'ok',
                            ), 200, array());
        }

        $this->addFlash('sonata_flash_error', 'Encuentro Eliminado');


        //return $this->redirectToRoute('admin_logic_jugadorevento_list', array('idevento' => $eventoId, 'id' => $eventoId));
        return $this->redirectToRoute('admin_logic_jugadorevento_list', array('id' => $idEvento));
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

            $documentacion = false;
            if ($request->get("action") == "carnes") {
                $documentacion = 1;
            }

            return new RedirectResponse($this->admin->generateUrl('listaCarnes', ['id' => $this->getRequest()->get('id'), 'documentacion' => $documentacion]));
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
     * Execute a batch delete.
     *
     * @param ProxyQueryInterface $query
     *
     * @return RedirectResponse
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function batchActionDelete(ProxyQueryInterface $query) {


        $this->admin->checkAccess('batchDelete');

        $modelManager = $this->admin->getModelManager();

        try {
            $modelManager->batchDelete($this->admin->getClass(), $query);
            $this->addFlash('sonata_flash_success', $this->trans('formulario_evento.labels.jugador_evento.label_jugador_eliminado'));
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash(
                    'sonata_flash_error', $this->trans('flash_batch_delete_error', [], 'SonataAdminBundle')
            );
        }

        $request = $this->getRequest();
        $ultimaRuta = $request->headers->get('referer');
        return $this->redirect($ultimaRuta);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionRecambio(ProxyQueryInterface $selectedModelQuery, Request $request = null) {

        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('delete');

        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $selectedCategories = $selectedModelQuery->execute();

        $parameterBag = $request->request;

        if (!$parameterBag->has('idx')) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $targetId = $parameterBag->get('idx');

        try {
            /** @var Category $category */
            foreach ($targetId as $id) {

                $jugador = $em->getRepository('LogicBundle:JugadorEvento')->find($id);

                $jugador->setEstado('Recambio');

                $this->admin->update($jugador);
            }
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $this->addFlash('sonata_flash_success', $this->trans('alerta.usuarios_recambio'));

        //return $this->redirectToRoute('admin_logic_jugadorevento_list', array('id' => $idEvento)); 
        $request = $this->getRequest();
        $ultimaRuta = $request->headers->get('referer');
        return $this->redirect($ultimaRuta);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionExitoso(ProxyQueryInterface $selectedModelQuery, Request $request = null) {

        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $selectedCategories = $selectedModelQuery->execute();

        $parameterBag = $request->request;

        if (!$parameterBag->has('idx')) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $targetId = $parameterBag->get('idx');

        try {
            /** @var Category $category */
            foreach ($targetId as $id) {

                $jugador = $em->getRepository('LogicBundle:JugadorEvento')->find($id);

                $jugador->setEstado('Exitoso');

                $this->admin->update($jugador);
            }
        } catch (\Exception $e) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $this->addFlash('sonata_flash_success', $this->trans('alerta.usuarios_exitoso'));

        $request = $this->getRequest();
        $ultimaRuta = $request->headers->get('referer');
        return $this->redirect($ultimaRuta);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionPendiente(ProxyQueryInterface $selectedModelQuery, Request $request = null) {
        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('delete');

        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $selectedCategories = $selectedModelQuery->execute();

        $parameterBag = $request->request;

        if (!$parameterBag->has('idx')) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $targetId = $parameterBag->get('idx');

        try {
            /** @var Category $category */
            foreach ($targetId as $id) {

                $jugador = $em->getRepository('LogicBundle:JugadorEvento')->find($id);

                $jugador->setEstado('Pendiente');

                $this->admin->update($jugador);
            }
        } catch (\Exception $e) {

            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            $request = $this->getRequest();
            $ultimaRuta = $request->headers->get('referer');
            return $this->redirect($ultimaRuta);
        }

        $this->addFlash('sonata_flash_success', $this->trans('alerta.usuarios_Pendiente'));

        $request = $this->getRequest();
        $ultimaRuta = $request->headers->get('referer');
        return $this->redirect($ultimaRuta);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionCarnes(ProxyQueryInterface $selectedModelQuery, Request $request = null) {
        $request = $this->getRequest();
        $idEvento = $request->get('id');

        try {

            $selectedModels = $selectedModelQuery->execute();
            $html = $this->renderView('AdminBundle:JugadorEvento\Carne:pdf.carnes.html.twig', [
                'jugadores' => $selectedModels,
                'carne' => $selectedModels[0]->getEvento()->getCarne(),
            ]);
            return new PdfResponse(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                        'encoding' => 'utf-8',
                        'page-height' =>  45,
                        'page-width' => 78,
                        'margin-top' => 0,
                        'margin-right' => 0,
                        'margin-bottom' => 0,
                        'margin-left' => 0,
                    ]), 'carnes.pdf'
            );
        } catch (Exception $e) {
            $this->addFlash('sonata_flash_error', $this->trans('error.carnes.pdf'));

            return new RedirectResponse(
                    $this->admin->generateUrl('listaCarnes', array('id' => $idEvento)) . '?documentacion=' . 1
            );
        }
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
//                    return $ex;
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
//                return $ex;
            }
        }
    }

}
