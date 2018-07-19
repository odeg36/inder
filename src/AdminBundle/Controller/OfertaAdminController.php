<?php

namespace AdminBundle\Controller;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\OfertaDivision;
use LogicBundle\Entity\Programacion;
use LogicBundle\Form\OfertaType;
use LogicBundle\Utils\BuscarFechas;
use PHPExcel_Style_Alignment;
use ReflectionClass;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;

class OfertaAdminController extends CRUDController {

    protected $em;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->trans = $container->get("translator");
        $this->em = $this->container->get("doctrine")->getManager();
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

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        $mostrar = $request->query->get('mostrar');
        $map = null;
        $id_mapa = 'mapa_ubicacion';
        $ofertas = [];
        if ($mostrar == "mapa") {
            $filtros = $request->query->get('filter');
            $em = $this->getDoctrine()->getManager();
            $qb = $em->getRepository('LogicBundle:Oferta')->createQueryBuilder('o');

            if (array_key_exists("nombre", $filtros) && $filtros['nombre']['value']) {
                $qb->andWhere("o.nombre LIKE :nombre ")
                        ->setParameter('nombre', "%" . $filtros['nombre']['value'] . "%");
            }
            if ($filtros['estrategia']['value']) {
                $qb->andWhere("o.estrategia = :estrategia ")
                        ->setParameter('estrategia', $filtros['estrategia']['value']);
            }
            if ($filtros['disciplinaEstrategia__disciplina']['value']) {
                $qb->join('o.disciplinaEstrategia', 'de')
                        ->andWhere("de.disciplina = :disciplina ")
                        ->setParameter('disciplina', $filtros['disciplinaEstrategia__disciplina']['value']);
            }
            if ($filtros['tendenciaEstrategia__tendencia']['value']) {
                $qb->join('o.tendenciaEstrategia', 'te')
                        ->andWhere("te.tendencia = :tendencia ")
                        ->setParameter('tendencia', $filtros['tendenciaEstrategia__tendencia']['value']);
            }
            if ($filtros['estrategia__segmentacion']['value']) {
                $qb->join('o.estrategia', 'e')
                        ->andWhere("e.segmentacion = :segmentacion ")
                        ->setParameter('segmentacion', $filtros['estrategia__segmentacion']['value']);
            }
            if ($filtros['activo']['value']) {
                $qb->andWhere("o.activo = :activo")
                        ->setParameter('activo', $filtros['activo']['value'] == 2 ? false : true);
            }
            $usuario = $this->getUser();
            if ($usuario->hasRole("ROLE_PERSONANATURAL") && !$usuario->hasRole("ROLE_SUPER_ADMIN") && !$usuario->hasRole("ROLE_FORMADOR")) {
                $qb->andWhere("o.activo = :activo")
                        ->setParameter('activo', true);
            }
            if (!$usuario->hasRole("ROLE_SUPER_ADMIN") && ($usuario->hasRole("ROLE_FORMADOR") || $usuario->hasRole("ROLE_GESTOR_TERRITORIAL"))) {
                $qb->leftJoin("o.formador", "f")
                        ->leftJoin("o.gestor", "g")
                        ->andWhere("f.id = :usuario OR g.id = :usuario")
                        ->setParameter("usuario", $usuario->getId());
            }
            $query = $qb->getQuery();
            $ofertas = $query->getResult();
            $ubicaciones = [];
            $ubicaciones['escenarios'] = [];
            $ubicaciones['puntosAtencion'] = [];
            foreach ($ofertas as $oferta) {
                if ($oferta->getEscenarioDeportivo()) {
                    $escenario = $oferta->getEscenarioDeportivo();
                    if (!array_key_exists($escenario->getId(), $ubicaciones['escenarios'])) {
                        $url = '<a href="' . $this->generateUrl('admin_logic_preinscripcionoferta_list', ['filter' => ['oferta' => ['value' => $oferta->getId()]]]) . '">' . $oferta->getNombre() . '</a>';
                        $ubicaciones['escenarios'][$escenario->getId()]['urls'] = '<p>' . $url . '</p>';
                        $ubicaciones['escenarios'][$escenario->getId()]['latitud'] = $escenario->getLatitud() ?: 0;
                        $ubicaciones['escenarios'][$escenario->getId()]['longitud'] = $escenario->getLongitud() ?: 0;
                    } else {
                        $url = '<a href="' . $this->generateUrl('admin_logic_preinscripcionoferta_list', ['filter' => ['oferta' => ['value' => $oferta->getId()]]]) . '">' . $oferta->getNombre() . '</a>';
                        $ubicaciones['escenarios'][$escenario->getId()]['urls'] = $ubicaciones['escenarios'][$escenario->getId()]['urls'] . '<p>' . $url . '</p>';
                    }
                }

                if ($oferta->getPuntoAtencion()) {
                    $puntoAtencion = $oferta->getPuntoAtencion();
                    if (!array_key_exists($puntoAtencion->getId(), $ubicaciones['puntosAtencion'])) {
                        $url = '<a href="' . $this->generateUrl('admin_logic_preinscripcionoferta_list', ['filter' => ['oferta' => ['value' => $oferta->getId()]]]) . '">' . $oferta->getNombre() . '</a>';
                        $ubicaciones['puntosAtencion'][$puntoAtencion->getId()]['urls'] = '<p>' . $url . '</p>';
                        $ubicaciones['puntosAtencion'][$puntoAtencion->getId()]['latitud'] = $puntoAtencion->getLatitud() ?: 0;
                        $ubicaciones['puntosAtencion'][$puntoAtencion->getId()]['longitud'] = $puntoAtencion->getLongitud() ?: 0;
                    } else {
                        $url = '<a href="' . $this->generateUrl('admin_logic_preinscripcionoferta_list', ['filter' => ['oferta' => ['value' => $oferta->getId()]]]) . '">' . $oferta->getNombre() . '</a>';
                        $ubicaciones['puntosAtencion'][$puntoAtencion->getId()]['urls'] = $ubicaciones['puntosAtencion'][$puntoAtencion->getId()]['urls'] . '<p>' . $url . '</p>';
                    }
                }
            }
            $map = new Map();
            $map->setHtmlId($id_mapa);
            $map->setCenter(new Coordinate('6.2399919', '-75.5215587,11'));
            $map->setMapOption('zoom', 8);
            foreach ($ubicaciones['escenarios'] as $ubicacion) {
                $marker = new Marker(new Coordinate($ubicacion['latitud'], $ubicacion['longitud']));
                $infoWindow = new InfoWindow($ubicacion['urls']);
                $marker->setInfoWindow($infoWindow);
                $map->getOverlayManager()->addMarker($marker);
            }
            foreach ($ubicaciones['puntosAtencion'] as $ubicacion) {
                $marker = new Marker(new Coordinate($ubicacion['latitud'], $ubicacion['longitud']));
                $infoWindow = new InfoWindow($ubicacion['urls']);
                $marker->setInfoWindow($infoWindow);
                $map->getOverlayManager()->addMarker($marker);
            }
        }

        $formView = $datagrid->getForm()->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFilterTheme());
        return $this->render($this->admin->getTemplate('list'), array(
                    'action' => 'list',
                    'map' => $map,
                    'id_mapa' => $id_mapa,
                    'form' => $formView,
                    'ofertas' => $ofertas,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
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

        $em = $this->getDoctrine()->getManager();
        $dias = $em->getRepository('LogicBundle:Dia')->findAll();
        foreach ($dias as $value) {
            $programacion = new Programacion();
            $programacion->setDia($value);
            $newObject->addProgramacion($programacion);
        }

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
                $hoy = new \Datetime();
                if ($submittedObject->getFechaFinal() > $hoy) {
                    $submittedObject->setActivo(true);
                } else {
                    $submittedObject->setActivo(false);
                }
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
                } catch (\Exception $e) {
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

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $newObject,
                    'objectId' => null,
        ));
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
        $this->getUsuarioAcceso($id);

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

        if ($existingObject->getProgramacion()->count() < 1) {
            $em = $this->getDoctrine()->getManager();
            $dias = $em->getRepository('LogicBundle:Dia')->findAll();
            foreach ($dias as $value) {
                $programacion = new Programacion();
                $programacion->setDia($value);
                $existingObject->addProgramacion($programacion);
            }
        }
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
                $hoy = new \Datetime();
                if ($submittedObject->getFechaFinal() > $hoy) {
                    $submittedObject->setActivo(true);
                } else {
                    $submittedObject->setActivo(false);
                }
                if ($form->get("division")->getData()) {
                    foreach ($existingObject->getDivisiones() as $division) {
                        $this->em->remove($division);
                        $existingObject->removeDivisione($division);
                        $this->em->persist($existingObject);
                    }
                    $registro = false;
                    foreach ($form->get("division")->getData() as $division) {
                        $ofertaDivision = new OfertaDivision($division, $submittedObject);
                        $submittedObject->addDivisione($ofertaDivision);
                        $this->em->persist($ofertaDivision);
                        $this->em->persist($submittedObject);
                    }
                    $this->em->flush();
                }
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

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'edit',
                    'form' => $formView,
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

    public function getUsuarioAcceso($id = null) {
        $user = $this->getUser();
        $securityContext = $this->container->get('security.authorization_checker');

        if (!$securityContext->isGranted('ROLE_SUPER_ADMIN') && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = $this->em->getRepository("LogicBundle:Oferta")->buscarOferta($user, $id);

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
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionGenerarReporte(ProxyQueryInterface $selectedModelQuery, Request $request = null) {
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        $arrayOferta = array();
        foreach ($selectedModels as $oferta) {
            array_push($arrayOferta, $oferta->getId());
        }
        return $this->redirectToRoute('admin_logic_oferta_fechaReporteOferta', array('id' => $arrayOferta));
    }

    public function fechaReporteOfertaAction(Request $request) {
        $idOfertas = $request->get('id');

        $form = $this->createForm(OfertaType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get("tipoReporte")->getData() == null) {
                $form->get("tipoReporte")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                );
            }

            if ($form->get("fechaInicial")->getData() == null) {
                $form->get("fechaInicial")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                );
            }

            if ($form->get("fechaFinal")->getData() == null) {
                $form->get("fechaFinal")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                );
            }

            if ($form->get("fechaInicial")->getData() > $form->get("fechaFinal")->getData()) {
                $form->get("fechaInicial")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_fecha_inicial_mayor'))
                );
            }
            if ($form->get("tipoReporte")->getData() == "PDF") {
                if ($form->get("fechaInicial")->getData() && $form->get("fechaFinal")->getData()) {
                    $mesInicial = $form->get("fechaInicial")->getData()->format('m');
                    $mesFinal = $form->get("fechaFinal")->getData()->format('m');
                    if ($mesInicial != $mesFinal) {
                        $form->get("fechaInicial")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.mismo_mes'))
                        );
                    }
                }
            }
            $isValid = $form->isValid();
            if ($isValid) {
                $fechaInicial = $form->get("fechaInicial")->getData()->format('Y-m-d');
                $fechaFinal = $form->get("fechaFinal")->getData()->format('Y-m-d');

                if ($form->get("tipoReporte")->getData() == "PDF") {
                    return $this->redirectToRoute('reporte_oferta_PDF_exportar', array('id' => $idOfertas, 'fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
                } else if ($form->get("tipoReporte")->getData() == "EXCEL") {
                    return $this->redirectToRoute('admin_logic_oferta_generarReporteOfertaExcel', array('id' => $idOfertas, 'fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
                }
            }
        }


        return $this->render('AdminBundle:Oferta/bacth:ask_confirmation.html.twig', [
                    'action' => 'create',
                    'form' => $form->createView(),
        ]);
    }

    public function generarReporteOfertaExcelAction(Request $request) {
        $idOfertas = $request->get('id');

        $indiceOferta = 1;

        $fechaInicial = $request->get('fechaInicial');
        $fechaFinal = $request->get('fechaFinal');
        $buscarfechas = new BuscarFechas();

        $fechaInicialObtenerDias = new \DateTime($fechaInicial);
        $fechaFinalObtenerDias = new \DateTime($fechaFinal);

        //calcular meses para reportes
        $arr_months = array();
        $date1 = new \DateTime($fechaInicial);
        $date2 = new \DateTime($fechaFinal);
        $month1 = new \DateTime($date1->format('Y-m')); //The first day of the month of date 1
        while ($month1 < $date2) { //Check if the first day of the next month is still before date 2
            $arr_months[$month1->format('Y-m')] = array('mes' => $month1->format('Y-m-d'), 'numeroAnio' => $month1->format('Y'), 'numeroMes' => $month1->format('m'), 'numeroDias' => cal_days_in_month(CAL_GREGORIAN, $month1->format('m'), $month1->format('Y'))); //Add it to the array
            $month1->modify('+1 month'); //Add one month and repeat
        }
        $cantidadOfertas = (count($idOfertas) * count($arr_months));

        $pestaña = 0;
        $filaUsuario = 9;
        $numeroUsuario = 1;
        $primeraLetraAsistencia = 70;
        $valueLetraA = 65;
        $nuevaLetraDespuesA = 65;
        $cantidadDias = 0;

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Prueba")
                ->setLastModifiedBy("Prueba 2")
                ->setTitle("Office 2005 XLSX Test Document")
                ->setSubject("Office 2005 XLSX Test Document")
                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                ->setKeywords("office 2005 openxml php")
                ->setCategory("Test result file");

        foreach ($arr_months as $mes) {

            $fechaInicialReporte = strtotime($fechaInicial);
            $fechaInicialMes = strtotime($mes['mes']);

            if ($fechaInicialReporte < $fechaInicialMes) {
                $fechaInicialObtenerDias = new \DateTime($mes['mes']);
            } else {
                $fechaInicialObtenerDias = new \DateTime($fechaInicial);
            }

            $month = $mes['numeroMes'];
            $year = $mes['numeroAnio'];
            $day = date("d", (mktime(0, 0, 0, $month + 1, 1, $year) - 1));

            $fechaFinalReporte = strtotime($fechaFinal);
            $fechaFinalMes = strtotime($year . $month . $day);

            if ($fechaFinalReporte > $fechaFinalMes) {
                $fechaFinalObtenerDias = new \DateTime($year . $month . $day);
            } else {
                $fechaFinalObtenerDias = new \DateTime($fechaFinal);
            }

            $fechas = $buscarfechas->todasLosDias(\DateTime::createFromFormat('Ymd', $fechaInicialObtenerDias->format('Ymd')), \DateTime::createFromFormat('Ymd', $fechaFinalObtenerDias->format('Ymd')));
            foreach ($idOfertas as $oferta_id) {
                $oferta = $this->em->getRepository("LogicBundle:Oferta")->find($oferta_id);
                $activesheet = $phpExcelObject->getActiveSheet();
                $phpExcelObject->setActiveSheetIndex($pestaña)
                        ->setCellValue('A3', 'Area:')
                        ->setCellValue('B3', $oferta->getEstrategia()->getProyecto()->getArea()->getNombre())
                        ->setCellValue('C3', 'Proyecto:')
                        ->setCellValue('D3', $oferta->getEstrategia()->getProyecto()->getNombre())
                        ->setCellValue('E3', 'Estrategia:')
                        ->setCellValue('F3', $oferta->getEstrategia()->getNombre())
                        ->setCellValue('A4', 'Rango Asistencia:')
                        ->setCellValue('B4', $fechaInicial)
                        ->setCellValue('C4', $fechaFinal)
                        ->mergeCells('D4:E4')
                        ->setCellValue('D4', 'Formador: ' . $oferta->getFormador()->nombreCompleto())
                        ->setCellValue('D5', 'Tipo Documento: ' . $oferta->getFormador()->getTipoIdentificacion()->getNombre())
                        ->setCellValue('D6', 'Número Documento: ' . $oferta->getFormador()->getNumeroIdentificacion())
                        ->setCellValue('A5', 'OFERTA')
                        ->setCellValue('B5', $oferta->getNombre())
                        ->setCellValue('C5', 'SEGMENTO')
                        ->mergeCells('A7:A8')
                        ->setCellValue('A7', 'Nº')
                        ->mergeCells('B7:B8')
                        ->setCellValue('B7', 'Nombre y apellido')
                        ->mergeCells('C7:C8')
                        ->setCellValue('C7', 'Número de Teléfono')
                        ->mergeCells('D7:D8')
                        ->setCellValue('D7', 'Tipo Documento')
                        ->mergeCells('E7:E8')
                        ->setCellValue('E7', 'Número documento');

                $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth("15");
                $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth("20");
                $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth("18");
                $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth("18");
                $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth("18");

                $phpExcelObject->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
                $phpExcelObject->getActiveSheet()->getRowDimension('2')->setRowHeight(100);
                $phpExcelObject->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
                $phpExcelObject->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
                $phpExcelObject->getActiveSheet()->getRowDimension('5')->setRowHeight(20);
                $phpExcelObject->getActiveSheet()->getRowDimension('6')->setRowHeight(20);
                $phpExcelObject->getActiveSheet()->getRowDimension('7')->setRowHeight(20);
                $phpExcelObject->getActiveSheet()->getRowDimension('8')->setRowHeight(20);

                $drawingobject = $this->get('phpexcel')->createPHPExcelWorksheetDrawing();
                $drawingobject->setName('Image name');
                $drawingobject->setDescription('Image description');
                $drawingobject->setPath('bundles/admin/img/logo-simon-header.png');
                $drawingobject->setHeight(100);
                $drawingobject->setOffsetY(0);
                $drawingobject->setCoordinates('A2');
                $drawingobject->setWorksheet($activesheet);

                $letra = 'A';
                foreach ($fechas as $dia) {

                    $cantidadDias = $cantidadDias + 1;
                    if ($primeraLetraAsistencia > 90) {

                        $letra1 = chr($valueLetraA);
                        $letra2 = chr($nuevaLetraDespuesA);
                        $letra3 = chr($nuevaLetraDespuesA + 1);
                        $letra = $letra1 . $letra2;
                        $letraTotalAsistencias = $letra1 . $letra3;
                        $nuevaLetraDespuesA = $nuevaLetraDespuesA + 1;
                    } else {
                        $letra = chr($primeraLetraAsistencia);
                        if ($primeraLetraAsistencia > 89) {
                            $letraTotalAsistencias = chr(65);
                        } else {
                            $letraTotalAsistencias = chr($primeraLetraAsistencia + 1);
                        }
                    }

                    $fecha = new \DateTime($dia);
                    $phpExcelObject->getActiveSheet()->getColumnDimension($letra)->setWidth("3");
                    $phpExcelObject->setActiveSheetIndex($pestaña)
                            ->mergeCells('F7:' . $letra . '7')
                            ->mergeCells('A1:' . $letra . '1')
                            ->mergeCells('A2:' . $letra . '2')
                            ->setCellValue('A1', 'Registro de Asistencia oferta: ' . $oferta->getNombre() . " Mes " . $this->trans($fecha->format('F')))
                            ->setCellValue('F7', $this->trans($fecha->format('F')))
                            ->setCellValue($letra . '8', $fecha->format('d'));

                    $filaUsuario = 9;
                    $numeroUsuario = 1;
                    $nombreHoja = substr($oferta->getNombre(), 0, 20);
                    $phpExcelObject->getActiveSheet()->setTitle($nombreHoja . " " . $this->trans($fecha->format('F')));
                    $drawingobject = $this->get('phpexcel')->createPHPExcelWorksheetDrawing();
                    $drawingobject->setName('Image name');
                    $drawingobject->setDescription('Image description');
                    $drawingobject->setPath('bundles/admin/img/logo-simon-header.png');
                    $drawingobject->setHeight(100);
                    $drawingobject->setOffsetY(0);
                    $drawingobject->setCoordinates('A2');
                    $drawingobject->setWorksheet($activesheet);

                    foreach ($this->em->getRepository("LogicBundle:Oferta")->getPreinscritosAlfabeticamente($oferta) as $preinscrito) {
                        $idUsuarioPreinscrito = $preinscrito->getUsuario()->getId();
                        $em = $this->getDoctrine()->getManager();

                        $asistencia = $em->getRepository('LogicBundle:Asistencia')->createQueryBuilder('asistencia')
                                        ->where('asistencia.usuario = :idUsuario')
                                        ->andWhere('asistencia.oferta = :idOferta')
                                        ->andWhere('asistencia.fecha = :fecha')
                                        ->andWhere('asistencia.asistio = :asistio')
                                        ->setParameter('idUsuario', $idUsuarioPreinscrito ?: 0)
                                        ->setParameter('idOferta', $oferta_id ?: 0)
                                        ->setParameter('fecha', $fecha->format('Y-m-d') ?: 0)
                                        ->setParameter('asistio', true)
                                        ->getQuery()->getResult();

                        if (count($asistencia) > 0) {
                            $phpExcelObject->setActiveSheetIndex($pestaña)
                                    ->setCellValue($letra . $filaUsuario, 'X');
                        }
                        ////////    $letraTotalAsistencias = $letraTotalAsistencias.'7:'.$letraTotalAsistencias.'8';


                        $phpExcelObject->setActiveSheetIndex($pestaña)
                                ->setCellValue('A' . $filaUsuario, $numeroUsuario)
                                ->setCellValue('B' . $filaUsuario, $preinscrito->getUsuario()->getApellidoNombre())
                                ->setCellValue('C' . $filaUsuario, $preinscrito->getUsuario()->getPhone())
                                ->setCellValue('D' . $filaUsuario, $preinscrito->getUsuario()->getTipoIdentificacion()->getNombre())
                                ->setCellValue('E' . $filaUsuario, $preinscrito->getUsuario()->getNumeroIdentificacion());

                        $phpExcelObject->getActiveSheet()->getRowDimension($filaUsuario)->setRowHeight(20);

                        $filaUsuario = $filaUsuario + 1;
                        $numeroUsuario = $numeroUsuario + 1;
                    }
                    $primeraLetraAsistencia = $primeraLetraAsistencia + 1;
                }
                $filaUsuario = $filaUsuario + 1;
                $phpExcelObject->getActiveSheet()->setCellValue('A' . $filaUsuario, 'TOTAL ASISTENCIAS');
                $phpExcelObject->getActiveSheet()->mergeCells('A' . $filaUsuario . ':' . $letra . $filaUsuario);
                $styleCenterText = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ]
                ];
                $phpExcelObject->getActiveSheet()->getStyle("A" . $filaUsuario . ":" . $letra . $filaUsuario)->applyFromArray($styleCenterText);
                $phpExcelObject->getActiveSheet()->getColumnDimension($letraTotalAsistencias)->setWidth("20");
                $filaUsuario = 9;
                $totalAsistenciaHojaActual = 0;
                foreach ($this->em->getRepository("LogicBundle:Oferta")->getPreinscritosAlfabeticamente($oferta) as $preinscrito) {
                    $idUsuario = $preinscrito->getUsuario()->getId();
                    $qb = $em->getRepository('LogicBundle:Asistencia')->createQueryBuilder('asistencia');
                    $qb->where('asistencia.usuario = :idUsuario')
                            ->andWhere('asistencia.oferta = :idOferta')
                            ->andWhere('asistencia.asistio = :asistio')
                            ->andWhere("date_part('month', asistencia.fecha) = :mes")
                            ->setParameter('mes', (integer) $mes['numeroMes'])
                            ->setParameter('idUsuario', $idUsuario ?: 0)
                            ->setParameter('idOferta', $oferta_id ?: 0)
                            ->setParameter('asistio', true)
                    ;
                    $query = $qb->getQuery();
                    $asistencias = count($query->getResult());
                    $totalAsistenciaHojaActual += $asistencias;
                    $phpExcelObject->setActiveSheetIndex($pestaña)
                            ->mergeCells($letraTotalAsistencias . '7:' . $letraTotalAsistencias . '8')
                            ->setCellValue($letraTotalAsistencias . '7', 'Total Asistencias')
                            ->setCellValue($letraTotalAsistencias . $filaUsuario, $asistencias);
                    $filaUsuario = $filaUsuario + 1;
                }
                $filaUsuario = $filaUsuario + 1;
                $phpExcelObject->getActiveSheet()->setCellValue($letraTotalAsistencias . $filaUsuario, $totalAsistenciaHojaActual);
                $pestaña = $pestaña + 1;

                if ($cantidadOfertas > $indiceOferta) {
                    $phpExcelObject->createSheet();
                    $indiceOferta = $indiceOferta + 1;
                }

                $primeraLetraAsistencia = 70;
                $valueLetraA = 65;
                $nuevaLetraDespuesA = 65;
            }
        }

        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'Reporte-Asistencia-Ofertas.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
