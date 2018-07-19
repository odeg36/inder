<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\UsuarioPersonaNaturalType;
use AdminBundle\ValidData\Validaciones;
use AdminBundle\ValidData\ValidarDatos;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\InfoComplementariaUserType;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\Asistencia;
use LogicBundle\Entity\LogPreinscriopcionEliminada;
use LogicBundle\Entity\PreinscripcionOferta;
use LogicBundle\Form\AsistenciaOfertaType;
use LogicBundle\Form\CargaUsuarioNuevoPreinscripcionType;
use LogicBundle\Utils\ReemplazarCaracteresEspeciales;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Swift_Message;
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

class PreinscripcionOfertaAdminController extends CRUDController {

    protected $validar = null;
    protected $validaciones = null;
    protected $inasistencias = 3;
    protected $container;
    protected $em;
    protected $trans;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->container = $container;
        $this->validar = new ValidarDatos($container);
        $this->validaciones = new Validaciones();
        $this->em = $this->get("doctrine")->getManager();
        $this->trans = $container->get('translator');
    }

    /**
     * Aprobar action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function batchActionAprobar(ProxyQuery $selectedModelQuery, Request $request = null) {
        $this->admin->checkAccess('edit');

        $modelManager = $this->admin->getModelManager();

        $selectedModels = $selectedModelQuery->execute();
        $correos = [];
        // do the merge work here
        $oferta = null;
        try {
            foreach ($selectedModels as $selectedModel) {
                if (!$oferta) {
                    $oferta = $selectedModel->getOferta();
                }
                if (!$selectedModel->getActivo()) {
                    $usuario = $selectedModel->getUsuario();
                    array_push($correos, $usuario->getEmail());
                    $selectedModel->setActivo(true);
                    $modelManager->update($selectedModel);
                }
            }
            $translator = $this->container->get('translator');
            $informacion = array('oferta' => $oferta, 'estado' => "aprobada", 'plantilla' => 'AdminBundle:Preinscripcion:mails/mailPreinscripcion.html.twig');
            $this->enviarCorreo($correos, $translator->trans('correos.preinscripcion.asunto'), $informacion);
            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }

        $this->addFlash('sonata_flash_success', 'alerta.usuarios_aprobados');

        return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    /**
     * Aprobar action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function batchActionRechazar(ProxyQuery $selectedModelQuery, Request $request = null) {
        $this->admin->checkAccess('edit');

        $selectedModels = $selectedModelQuery->execute();

        foreach ($selectedModels as $key => $elemento) {
            $this->rechazarPreinscritos($elemento);
        }

        $this->addFlash('sonata_flash_success', 'mensaje.preinscripcion.rechazar.eliminados');

        return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    /**
     * Aprobar action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function batchActionExportar(ProxyQuery $selectedModelQuery, Request $request = null) {

//        $modelManager = $this->admin->getModelManager();
        $selectedModelQuery
                ->getQueryBuilder()->addOrderBy('u.lastname', 'ASC');
        $selectedModels = $selectedModelQuery->execute();
        // DATOS DE LA OFERTA
        $oferta = $selectedModels[0]->getOferta();
        $estrategia = $oferta->getEstrategia();
        $camposUsuario = $estrategia->getEstrategiaCampos();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->setActiveSheetIndex(0);
        $activeSheet = $phpExcelObject->getActiveSheet();
        $activeSheet->setTitle('Simple');
        $fila = 1;
        $columnaCabecera = $columna = "A";
        // CABECERAS PARA USUARIO
        $cabeceras = [
                ['nombre' => $this->trans('formulario.usuario.tipoIdentificacion'), 'metodo' => 'getTipoIdentificacion'],
                ['nombre' => $this->trans('formulario.usuario.numeroIdentificacion'), 'metodo' => 'getNumeroIdentificacion'],
                ['nombre' => $this->trans('formulario.usuario.lastname'), 'metodo' => 'getLastname'],
                ['nombre' => $this->trans('formulario.usuario.firstname'), 'metodo' => 'getFirstname'],
                ['nombre' => $this->trans('formulario_registro.pnatural_informacion.genero'), 'metodo' => 'getGender'],
                ['nombre' => $this->trans('formulario_registro.pnatural_informacion.fecha_nacimiento'), 'metodo' => 'getDateOfBirth', 'tipo' => 'Date'],
                ['nombre' => $this->trans('formulario.usuario.edad'), 'metodo' => 'getEdad'],
                ['nombre' => $this->trans('formulario_registro.contacto.correo_electronico'), 'metodo' => 'getEmail'],
                ['nombre' => $this->trans('formulario_registro.contacto.telefono_movil'), 'metodo' => 'getPhone'],
                ['nombre' => $this->trans('formulario_registro.contacto.municipio'), 'metodo' => 'getBarrio', 'metodoPadre' => 'getMunicipio'],
                ['nombre' => $this->trans('formulario_registro.contacto.barrio'), 'metodo' => 'getBarrio'],
                ['nombre' => $this->trans('formulario.oferta.puntoAtencion.nueva.direccion'), 'metodo' => 'getDireccionResidencia'],
                ['nombre' => $this->trans('formulario_registro.pnatural_informacion.estrato'), 'metodo' => 'getEstrato'],
                ['nombre' => $this->trans('formulario.info_adicional.eps'), 'metodo' => 'getEps'],
                ['nombre' => $this->trans('formulario.info_adicional.nivel_escolaridad'), 'metodo' => 'getNivelEscolaridad'],
                ['nombre' => $this->trans('formulario.info_adicional.tipo_establecimiento_educativo'), 'metodo' => 'getTipoEstablecimientoEducativo'],
                ['nombre' => $this->trans('formulario.info_adicional.establecimiento_educativo'), 'metodo' => 'getEstablecimientoEducativo'],
                ['nombre' => $this->trans('formulario.info_adicional.ocupacion'), 'metodo' => 'getOcupacion'],
                ['nombre' => $this->trans('titulo.etnia'), 'metodo' => 'getEtnia'],
                ['nombre' => $this->trans('formulario.info_adicional.desplazado'), 'metodo' => 'getTipoDesplazado', 'tipo' => 'Boolean'],
                ['nombre' => $this->trans('formulario.info_adicional.tipo_desplazado'), 'metodo' => 'getTipoDesplazado'],
                ['nombre' => $this->trans('formulario.info_adicional.tiene_discapacidad'), 'metodo' => 'getSubDiscapacidad', 'metodoPadre' => 'getDiscapacidad', 'tipo' => 'Boolean'],
                ['nombre' => $this->trans('formulario.info_adicional.discapacidad'), 'metodo' => 'getSubDiscapacidad', 'metodoPadre' => 'getDiscapacidad'],
                ['nombre' => $this->trans('formulario.info_adicional.sub_discapacidad'), 'metodo' => 'getSubDiscapacidad'],
                ['nombre' => $this->trans('formulario.info_adicional.es_jefe_cabeza_hogar'), 'metodo' => 'getEsJefeCabezaHogar', 'tipo' => 'Boolean'],
                ['nombre' => $this->trans('formulario.preinscripcion.activo'), 'metodo' => 'getActivo', 'tipo' => 'Boolean', 'entidadPrincipal' => true],
                ['nombre' => $this->trans('formulario.preinscripcion.fecha_inscripcion'), 'metodo' => 'getFechaCreacion', 'tipo' => 'Date', 'entidadPrincipal' => true],
        ];
        // CABECERAS SEGUN ESTRATEGIA
        foreach ($camposUsuario as $campoUsuario) {
            if ($campoUsuario->getUsar()) {
                $metodo = 'get' . ucfirst($campoUsuario->getCampoUsuario()->getNombreMapeado());
                $cabeceras[] = [
                    'nombre' => $campoUsuario->getCampoUsuario()->getNombre(),
                    'metodo' => $metodo,
                    'campoEstrategia' => true,
                    'tipo' => $campoUsuario->getCampoUsuario()->getTipo(),
                ];
            }
        }
        // CABECERAS EN EL ARCHIVO
        foreach ($cabeceras as $cabecera) {
            $activeSheet
                    ->setCellValue($columnaCabecera . $fila, $cabecera['nombre']);
            $columnaCabecera++;
        }
        foreach ($this->excelColumnRange('A', $columnaCabecera) as $value) {
            $activeSheet->getColumnDimension($value)->setAutoSize(true);
        }
        $activeSheet->getStyle("A1:$columnaCabecera" . "1")->getFont()->setBold(true);

        // INSCRITOS
        foreach ($selectedModels as $preinscripcion) {
            $fila++;
            $usuario = $preinscripcion->getUsuario();
            $informacionExtra = $usuario->getInformacionExtraUsuario();
            $columna = "A";
            foreach ($cabeceras as $cabecera) {
                $valor = "";
                if (array_key_exists('entidadPrincipal', $cabecera) && $cabecera['entidadPrincipal']) {
                    $valor = call_user_func([$preinscripcion, $cabecera['metodo']]);
                    if (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Boolean") {
                        $atributo = call_user_func([$preinscripcion, $cabecera['metodo']]);
                        $valor = $atributo ? "SÍ" : "NO";
                    }
                    if (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Date") {
                        $atributo = call_user_func([$preinscripcion, $cabecera['metodo']]);
                        $valor = $atributo->format('d/m/Y');
                    }
                } elseif (array_key_exists('campoEstrategia', $cabecera) && $cabecera['campoEstrategia']) {
                    $valor = call_user_func([$informacionExtra, $cabecera['metodo']]);
                    if (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Boolean") {
                        $atributo = call_user_func([$informacionExtra, $cabecera['metodo']]);
                        $valor = $atributo ? "SÍ" : "NO";
                    }
                    if (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Date") {
                        $atributo = call_user_func([$informacionExtra, $cabecera['metodo']]);
                        $valor = $atributo->format('d/m/Y');
                    }
                } elseif (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Boolean") {
                    $atributo = call_user_func([$usuario, $cabecera['metodo']]);
                    $valor = $atributo ? "SÍ" : "NO";
                } elseif (array_key_exists('tipo', $cabecera) && $cabecera['tipo'] == "Date") {
                    $atributo = call_user_func([$usuario, $cabecera['metodo']]);
                    if ($atributo) {
                        $valor = $atributo->format('d/m/Y');
                    }
                } elseif (array_key_exists('metodoPadre', $cabecera)) {
                    $valor = "";
                    $metodoHijo = call_user_func([$usuario, $cabecera['metodo']]);
                    if (is_object($metodoHijo)) {
                        $valor = call_user_func([$metodoHijo, $cabecera['metodoPadre']]);
                    }
                } else {
                    $valor = call_user_func([$usuario, $cabecera['metodo']]);
                }
                $activeSheet
                        ->setCellValue($columna . $fila, $valor);
                $columna++;
            }
        }

        $utilReemplazarCaracteres = new ReemplazarCaracteresEspeciales();
        $nombreArchivo = $utilReemplazarCaracteres->sanear_string('listado_de_usuarios_' . $estrategia->getNombre()) . '.xlsx';
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nombreArchivo
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    function excelColumnRange($lower, $upper) {
        ++$upper;
        for ($i = $lower; $i !== $upper; ++$i) {
            yield $i;
        }
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
        $oferta_id = $request->get('filter')['oferta']['value'];
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->find($oferta_id);
        if (!$oferta) {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta.no_existe"));

            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $escenario = $oferta->getEscenarioDeportivo();
        $puntoAtencion = $oferta->getPuntoAtencion();
        $latitud = 0;
        $longitud = 0;
        if ($escenario) {
            $latitud = $escenario->getLatitud() ?: 0;
            $longitud = $escenario->getLongitud() ?: 0;
        } elseif ($puntoAtencion) {
            $latitud = $puntoAtencion->getLatitud() ?: 0;
            $longitud = $puntoAtencion->getLongitud() ?: 0;
        }
        $map = new Map();
        $id_mapa = 'mapa_ubicacion';
        $map->setHtmlId($id_mapa);

        $marker = new Marker(new Coordinate($latitud, $longitud));
        $map->getOverlayManager()->addMarker($marker);
        $map->setCenter(new Coordinate($latitud, $longitud));
        $map->setMapOption('zoom', 15);


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

        return $this->render($this->admin->getTemplate('list'), array(
                    'map' => $map,
                    'extra' => [
                        'id_mapa' => $id_mapa,
                    ],
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
                        ), null);
    }

    /* Realiza el registro de los asistentes a una oferta */

    /**
     * Preinscripcion action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function asistenciaAction() {
        $request = $this->getRequest();
        $container = $this->container;
        $oferta_id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->find($oferta_id);
        if (!$oferta) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        if ($this->permisoOferta($oferta) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($oferta) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $this->admin->checkAccess('list');
        if ($this->getUser()->hasRole('ROLE_FORMADOR')) {
            $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        };

        $serializer = $this->container->get('jms_serializer');
        $preinscritos = $this->em->getRepository("LogicBundle:PreinscripcionOferta")->buscarOfertaPreinscritos($oferta_id);
        $preinscritos = $serializer->serialize($preinscritos, 'json');
        $preinscritos = json_decode($preinscritos, true);
        
        $form = $this->createForm(AsistenciaOfertaType::class, $oferta, [
            'em' => $em,
            'container' => $container
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $corteAsistencia = $oferta->getEstrategia()->getCorteAsistencia();
            $diasPlazo = $oferta->getEstrategia()->getPlazoAdicional();
            $fechaInicial = $oferta->getFechaInicial()->format('d-m-Y');
            $fechaInicial = strtotime($fechaInicial);
            $fechaActual = date('d-m-Y');
            $fechaActual = strtotime($fechaActual);

            if ($corteAsistencia == 'Mensual') {
                $fechaFinal = strtotime('+1 month', $fechaInicial);
            } else {
                $fechaFinal = strtotime('+1 week', $fechaInicial);
            }

            if ($diasPlazo != null) {
                $prueba = "+" . $diasPlazo . " days";
                $fechaFinal = strtotime($prueba, $fechaFinal);
            }

            if ($fechaActual > $fechaFinal) {
                $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta.asistencia_vencida"));
                $url = $this->admin->generateUrl('list') . '?filter[oferta][value]=' . $oferta->getId();
                return $this->redirect($url);
            }
            if ($form->isValid()) {
                $programacion = $em->getRepository("LogicBundle:Programacion")->findOneById($form->get('dias_semana')->getData());
                $fecha = $form->get("seleccion_dia_unico")->getData();

                if ($form->get("usuariosAsistentes")->getData()) {
                    $asistentes = json_decode($form->get("usuariosAsistentes")->getData());
                    foreach ($asistentes as $key => $asistencia) {
                        $usuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneById($asistencia);
                        $asistenciaEntity = $this->em->getRepository("LogicBundle:Asistencia")->buscarAsistente($usuario, $oferta, $fecha);
                        if (!$asistenciaEntity) {
                            $asistenciaEntity = new Asistencia();
                        }

                        $asistenciaEntity->setFecha(new \DateTime($fecha));
                        $asistenciaEntity->setOferta($oferta);
                        $asistenciaEntity->setProgramacion($programacion);
                        $asistenciaEntity->setUsuario($usuario);
                        $asistenciaEntity->setAsistio(true);

                        $em->persist($asistenciaEntity);
                    }
                }

                //$this->validarInasistenciaUsuario($oferta_id);

                $this->addFlash('sonata_flash_success', $this->trans("mensaje.preinscripcion.asistentes.registrados"));

                $em->flush();
                $url = $this->admin->generateUrl('list') . '?filter[oferta][value]=' . $oferta->getId();
                return $this->redirect($url);
            }
        }

        // set the theme for the current Admin Form
        $this->setFormTheme($form->createView(), $this->admin->getFormTheme());

        return $this->render('AdminBundle:Preinscripcion:asistencia.html.twig', array(
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'oferta' => $oferta,
                    'preinscritos' => $preinscritos
                        ), null);
    }

    /**
     * Preinscripcion action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function preinscripcionAction() {
        $request = $this->getRequest();
        $container = $this->container;
        $oferta_id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->find($oferta_id);
        if (!$oferta) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $mensaje = $this->permisoOferta($oferta);
        if ($mensaje != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $mensaje . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $usuario = $this->getUser();
        $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneBy(['usuario' => $usuario, 'oferta' => $oferta]);
        if ($preinscripcion) {
            return $this->redirect($this->admin->generateUrl('preinscripcionExitosa', ['id' => $preinscripcion->getId()]));
        }

        $this->admin->checkAccess('list');
        $form = $this->createForm(InfoComplementariaUserType::class, $usuario, [
            'usuario' => $usuario,
            'oferta' => $oferta,
            'em' => $em,
            'container' => $container,
            'request' => $request
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $usuario = $form->getData();
            if ($form->get('direccionOcomuna')->getData() == User::DIRECCION && !$usuario->getDireccionResidencia()) {
                $form->get('direccionResidencia')->addError(new FormError($this->trans('formulario_registro.contacto.ingresedireccionresidencia')));
            }
            if ($form->get('direccionOcomuna')->getData() == User::COMUNA && !$usuario->getDireccionComuna()) {
                $form->get('direccionComuna')->addError(new FormError($this->trans('formulario_registro.contacto.ingresedireccionresidencia')));
            }
            if ($form->isValid()) {
                $em->persist($usuario);

                $nuevaPreinscripcion = new PreinscripcionOferta();
                $nuevaPreinscripcion->setOferta($oferta);
                $nuevaPreinscripcion->setUsuario($usuario);
                $nuevaPreinscripcion->setActivo(false);
                $em->persist($nuevaPreinscripcion);
                $em->flush();
                
                return $this->redirect($this->admin->generateUrl('preinscripcionExitosa', ['id' => $nuevaPreinscripcion->getId()]));
            }
        }
        // set the theme for the current Admin Form
        $this->setFormTheme($form->createView(), $this->admin->getFilterTheme());
        return $this->render('AdminBundle:Preinscripcion:formulario_preinscripcion.html.twig', array(
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'oferta' => $oferta,
                        ), null);
    }

    public function preinscripcionAcompanantesAction() {
        $request = $this->getRequest();
        $preinscripcionId = $request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneById($preinscripcionId);
        if (!$preinscripcion) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        if ($preinscripcion->getInscriptor()) {
            $this->addFlash('sonata_flash_error', $this->trans("error.preinscripcion.acompanante"));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $oferta = $preinscripcion->getOferta();
        $disponibilidad = $oferta->ofertaDisponible($this->getUser(), $this->container);
        if ($disponibilidad != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta.$disponibilidad"));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        if ($this->permisoOferta($oferta) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($oferta) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $form = $this->createForm(UsuarioPersonaNaturalType::class, null, []);

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->render('AdminBundle:Preinscripcion:formulario_acompanante.html.twig', array(
                    'action' => 'edit',
                    'oferta' => $oferta,
                    'preinscripcion' => $preinscripcion,
                    'form' => $formView,
                        ), null);
    }

    public function preinscripcionAcompananteAction() {
        $request = $this->getRequest();
        $preinscripcionId = $request->get('id', 0);
        $usuarioId = $request->get('usuario', 0);
        $uniqId = $request->get('uniqId', "");
        $em = $this->getDoctrine()->getManager();
        $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneById($preinscripcionId);
        if (!$preinscripcion) {
            return new Response('');
        }
        $usuario = $em->getRepository('ApplicationSonataUserBundle:User')->findOneById($usuarioId);
        if (!$usuario) {
            return new Response('');
        }

        $oferta = $preinscripcion->getOferta();
        if ($this->permisoOferta($oferta) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($oferta) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $this->admin->checkAccess('create');
        $form = $this->createForm(InfoComplementariaUserType::class, $usuario, [
            'action' => $this->generateUrl('admin_logic_preinscripcionoferta_preinscripcionAcompanante', ["id" => $preinscripcion->getId(), "usuario" => $usuario->getId(), "uniqId" => $uniqId]),
            'method' => 'POST',
            'usuario' => $usuario,
            'oferta' => $oferta,
            'em' => $em,
            'container' => $this->container
        ]);
        $usuarioOferta = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneBy([
            'oferta' => $oferta,
            'usuario' => $usuario,
        ]);
        if ($usuario->getId() && $usuarioOferta) {
            $this->addFlash('sonata_flash_error', $this->trans("error.preinscripcion.usuario_repetido", [
                        '%usuario%' => $usuario->getFullName(),
            ]));
        }
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $usuarioOferta = $em->getRepository("ApplicationSonataUserBundle:User")->buscarUsarioPreinscritoOferta($oferta, $usuario);
            if ($usuarioOferta) {
                $form->addError(New FormError("error.preinscripcion.usuario"));
            }
            $usuario = $form->getData();
            $disponibilidad = $oferta->ofertaDisponible($usuario, $this->container);
            if ($disponibilidad != "") {
                $form->addError(New FormError($this->trans("mensaje.oferta.$disponibilidad")));
            }
            if ($form->isValid()) {
                $formRequest = $request->request;

                $em->persist($usuario);

                $nuevaPreinscripcion = new PreinscripcionOferta();
                $nuevaPreinscripcion->setOferta($oferta);
                $nuevaPreinscripcion->setUsuario($usuario);
                $nuevaPreinscripcion->setActivo(false);
                $nuevaPreinscripcion->setInscriptor($preinscripcion);

                $em->persist($nuevaPreinscripcion);
                $em->flush();

                $url = $this->admin->generateUrl('preinscripcionExitosa', ['id' => $oferta->getId()]);
                if ($formRequest->get("nuevo") == true) {
                    $this->addFlash('sonata_flash_success', $this->trans("titulo.solicitud_guardada"));
                    $url = $this->admin->generateUrl('preinscripcionAcompanantes', ['id' => $preinscripcion->getId()]);
                }

                $json = [
                    'url' => $url
                ];

                $serializer = $this->container->get('jms_serializer');
                $json = $serializer->serialize($json, 'json');
                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        // set the theme for the current Admin Form
        $this->setFormTheme($form->createView(), $this->admin->getFilterTheme());

        return $this->render('AdminBundle:Preinscripcion/Formulario:preinscripcion.acompanate.html.twig', array(
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'oferta' => $oferta,
                    'oferta' => $usuario,
                    "uniqId" => $uniqId
                        ), null);
    }

    public function registarUsuarioOfertaAction() {
        $request = $this->getRequest();
        $ofertaId = $request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->findOneById($ofertaId);
        if (!$oferta) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        if ($this->permisoOferta($oferta) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($oferta) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $disponibilidad = $oferta->ofertaDisponible($this->getUser(), $this->container, true);
        if ($disponibilidad != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta.$disponibilidad"));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $form = $this->createForm(UsuarioPersonaNaturalType::class, null, []);

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->render('AdminBundle:Preinscripcion:nuevos_usuarios_oferta.html.twig', array(
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $oferta,
                    'objectId' => null,
                        ), null);
    }

    public function cargaUsuarioNuevoPreinscripcionAction() {
        $translator = $this->container->get('translator');
        $request = $this->getRequest();
        $ofertaId = $request->get('id', 0);
        $usuarioId = $request->get('usuario', 0);
        $uniqId = $request->get('uniqId', "");
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->findOneById($ofertaId);
        if (!$oferta) {
            return new Response('');
        }

        $usuario = $em->getRepository('ApplicationSonataUserBundle:User')->findOneById($usuarioId);
        if (!$usuario) {
            $usuario = new User();
        }
        $usuarioOferta = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneBy([
            'oferta' => $oferta,
            'usuario' => $usuario,
        ]);
        if ($usuario->getId() && $usuarioOferta) {
            $this->addFlash('sonata_flash_error', $this->trans("error.preinscripcion.usuario_repetido", [
                        '%usuario%' => $usuario->getFullName(),
            ]));
        }
        if ($this->permisoOferta($oferta) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($oferta) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

        $this->admin->checkAccess('create');
        $form = $this->createForm(CargaUsuarioNuevoPreinscripcionType::class, $usuario, [
            'action' => $this->generateUrl('admin_logic_preinscripcionoferta_cargaUsuarioNuevoPreinscripcion', ["id" => $oferta->getId(), "usuario" => $usuario->getId(), "uniqId" => $uniqId]),
            'method' => 'POST',
            'usuario' => $usuario,
            'oferta' => $oferta,
            'em' => $em,
            'container' => $this->container,
            'request' => $request
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $usuario = $form->getData();
            $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneBy([
                'oferta' => $oferta,
                'usuario' => $usuario,
            ]);
            if ($preinscripcion) {
                $mensage = $this->container->get('translator')->trans('error.preinscripcion.usuario_repetido', ['%usuario%' => $usuario->getFullName()]);
                $form->addError(new FormError($mensage));
            }
            $disponibilidad = $oferta->ofertaDisponible($usuario, $this->container);
            if ($disponibilidad != "") {
                $form->addError(New FormError($this->trans("mensaje.oferta.$disponibilidad")));
            }
            if ($form->isValid()) {
                $usuario->setEnabled(true);
                $em->persist($usuario);
                $preinscripcionOferta = new PreinscripcionOferta();
                $preinscripcionOferta->setOferta($oferta);
                $preinscripcionOferta->setUsuario($usuario);
                $preinscripcionOferta->setActivo($form->get('activo')->getData());
                $em->persist($preinscripcionOferta);
                $em->flush();
                $this->addFlash(
                        'sonata_flash_success', $translator->trans(
                                'mensaje.usuario.creado.preinscripcion_oferta', array('%usuario%' => $usuario->getFullName())
                        )
                );
                $url = $this->generateUrl('admin_logic_preinscripcionoferta_list') . "?filter[oferta][value]=" . $oferta->getId();
                $json = [
                    'url' => $url
                ];

                $serializer = $this->container->get('jms_serializer');
                $json = $serializer->serialize($json, 'json');
                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        $formView = $form->createView();
        return $this->render('AdminBundle:Preinscripcion\Formulario:nuevo.usuario.preinscrito.html.twig', array(
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $oferta,
                    'objectId' => null,
                        ), null);
    }

    /**
     * preinscripcionExitosa action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function preinscripcionExitosaAction() {
        $request = $this->getRequest();
        $preInscripcion_id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $preInscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->find($preInscripcion_id);
        if (!$preInscripcion) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $oferta = $preInscripcion->getOferta();
        if (!$oferta) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        $acompanantes = $oferta->getEstrategia()->getAcompanantes();
        $usuario = $this->getUser();
        $preinscripcion = $em->getRepository('LogicBundle:PreinscripcionOferta')->findOneBy(['usuario' => $usuario, 'oferta' => $oferta]);
        if (!$preinscripcion) {
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }
        return $this->render('AdminBundle:Preinscripcion:inscripcion_exitosa.html.twig', array(
                    'action' => 'edit',
                    'oferta' => $oferta,
                    'preinscripcion' => $preinscripcion,
                    'acompanantes' => $acompanantes,
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
        $oferta_id = $request->get('oferta');
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $request->get($this->admin->getIdParameter());
        $existingObject = $this->admin->getObject($id);
        if ($this->permisoOferta($existingObject) != "") {
            $this->addFlash('sonata_flash_error', $this->trans("mensaje.oferta." . $this->permisoOferta($existingObject) . ""));
            return $this->redirect($this->generateUrl('admin_logic_oferta_list'));
        }

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
                    $oferta = $submittedObject->getOferta();
                    $existingObject = $this->admin->update($submittedObject);
                    if ($existingObject->getActivo()) {
                        $translator = $this->container->get('translator');
                        $informacion = array('oferta' => $oferta, 'estado' => "aprobada", 'plantilla' => 'AdminBundle:Preinscripcion:mails/mailPreinscripcion.html.twig');
                        $this->enviarCorreo($existingObject->getUsuario()->getEmail(), $translator->trans('correos.preinscripcion.asunto'), $informacion);
                    }
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
                    $url = $this->generateUrl('admin_logic_preinscripcionoferta_list') . "?filter[oferta][value]=" . $oferta_id;
                    return $this->redirect($url);
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
        return $this->render($this->admin->getTemplate($templateKey), [
                    'action' => 'edit',
                    'form' => $formView,
                    'object' => $existingObject,
                    'objectId' => $objectId,
                    'oferta' => $oferta_id,
                        ], null);
    }

    public function permisoOferta($oferta) {
        $mensaje = "";
        $usuarioAutenticado = $this->getUser();
        if (!$usuarioAutenticado->hasRole('ROLE_PREINSCRIPCION_ADMINISTRAR_PREINSCRITOS')) {
            $mensaje = $oferta->ofertaDisponible($usuarioAutenticado, $this->container);
        }
        return $mensaje;
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

    public function rechazarPreInscripcionAction($id = null) {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        $existingObject = $this->admin->getObject($id);
        if (!$existingObject) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $oferta = $existingObject->getOferta();
        $this->rechazarPreinscritos($existingObject);

        $this->addFlash(
                'sonata_flash_success', $this->trans(
                        'mensaje.preinscripcion.rechazar.eliminado', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
                )
        );

        $url = $this->generateUrl('admin_logic_preinscripcionoferta_list') . "?filter[oferta][value]=" . $oferta->getId();
        $json = [
            'url' => $url
        ];

        $serializer = $this->container->get('jms_serializer');
        $json = $serializer->serialize($json, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function rechazarPreinscritos($preInscrito) {
        foreach ($preInscrito->getAcompanantes() as $key => $acompanante) {
            if ($acompanante->getAcompanantes()) {
                $this->rechazarPreinscritos($acompanante);
            } else {
                $oferta = $acompanante->getOferta();
                $usuario = $acompanante->getUsuario();

                try {
                    $this->em->remove($acompanante);
                    $this->em->flush();

                    $informacion = [
                        'usuario' => $usuario->getFullname(), 'oferta' => $oferta,
                        'estado' => $this->trans->trans('admin.detallesolicitud.rechazar'),
                        'plantilla' => 'AdminBundle:Preinscripcion:mails/mailPreinscripcion-rechazada.html.twig'
                    ];

                    $this->enviarCorreo([$usuario->getEmail()], $this->trans->trans('correos.preinscripcion.asunto'), $informacion);
                } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $fe) {
                    $this->addFlash('sonata_flash_error', $this->trans->trans('mensaje.preinscripcion.rechazar.acompanantes', [
                                "%usuario%" => $usuario
                    ]));

                    return new RedirectResponse(
                            $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                    );
                } catch (\Exception $e) {
                    $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

                    return new RedirectResponse(
                            $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                    );
                }
            }
        }

        $oferta = $preInscrito->getOferta();
        $usuario = $preInscrito->getUsuario();

        try {
            $this->em->remove($preInscrito);
            $this->em->flush();

            $informacion = [
                'usuario' => $usuario->getFullname(), 'oferta' => $oferta,
                'estado' => $this->trans->trans('admin.detallesolicitud.rechazar'),
                'plantilla' => 'AdminBundle:Preinscripcion:mails/mailPreinscripcion-rechazada.html.twig'
            ];

            $this->enviarCorreo([$usuario->getEmail()], $this->trans->trans('correos.preinscripcion.asunto'), $informacion);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $fe) {
            $this->addFlash('sonata_flash_error', $this->trans->trans('mensaje.preinscripcion.rechazar.acompanantes', [
                        "%usuario%" => $usuario
            ]));

            return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }
    }

    public function modificarDatosAction($id) {
        $translator = $this->container->get('translator');
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $existingObject = $this->admin->getObject($id);
        
        $this->admin->checkAccess('edit');
        $form = $this->createForm(CargaUsuarioNuevoPreinscripcionType::class, $existingObject->getUsuario(), [
            'method' => 'POST',
            'usuario' => $existingObject->getUsuario(),
            'oferta' => $existingObject->getOferta(),
            'em' => $em,
            'container' => $this->container,
            'request' => $request
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $usuario = $form->getData();
                $em->persist($usuario);
                
                $em->flush();
                
                $this->addFlash(
                    'sonata_flash_success', $this->trans(
                        'flash_edit_success', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
                    )
                );

                // redirect to edit mode
                $url = $this->generateUrl('admin_logic_preinscripcionoferta_list') . "?filter[oferta][value]=" . $existingObject->getOferta()->getId();
                return $this->redirect($url);
            }
        }

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());
        return $this->render('AdminBundle:Preinscripcion:modificar_base_edit.html.twig', [
            'action' => 'edit',
            'form' => $formView,
            'object' => $existingObject,
            'objectId' => $existingObject->getId(),
            'oferta' => $existingObject->getOferta()->getId(),
        ], null);
    }
    
}
