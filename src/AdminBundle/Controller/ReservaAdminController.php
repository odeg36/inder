<?php

namespace AdminBundle\Controller;

use DateInterval;
use DatePeriod;
use DateTime;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\AsistenciaReserva;
use LogicBundle\Entity\DiaReserva;
use LogicBundle\Entity\DivisionReserva;
use LogicBundle\Entity\InformacionExtraUsuario;
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\UsuarioDivisionReserva;
use LogicBundle\Form\AsistenciasReservaType;
use LogicBundle\Form\CancelarReservaBatchActionType;
use LogicBundle\Form\DivisionesType;
use LogicBundle\Form\InformacionExtraUsuarioReservaType;
use LogicBundle\Form\MotivoCancelacionReservaType;
use LogicBundle\Form\ReservaType;
use LogicBundle\Utils\BuscarFechas;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;
use LogicBundle\Entity\ProgramacionReserva;
use LogicBundle\Entity\TipoReserva;
use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Entity\Oferta;
use AdminBundle\Export\CRUDControllerExtraExportTrait;

class ReservaAdminController extends CRUDController {

    use CRUDControllerExtraExportTrait;

    protected $trans = null;
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
        $this->admin->checkAccess('create');

        return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
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
        if ($id == null) {
            $id = 0;
        }

        $this->admin->checkAccess('edit');

        return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => $id));
    }

    /**
     * Show action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function showAction($id = NULL) {

        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $ROLE_USER = $user->hasRole('ROLE_USER');
        $mostrarBtnAprobar = false;
        if ($ROLE_SUPER_ADMIN == true || $ROLE_ORGANISMO_DEPORTIVO == true || $ROLE_GESTOR_ESCENARIO == true) {
            $mostrarBtnAprobar = true;
        }

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $reserva = new Reserva();
        } else {
            $reserva = $this->em->getRepository("LogicBundle:Reserva")->find($id);
        }

        $dias = $reserva->getDiaReserva();
        $diasReserva = array();
        foreach ($dias as $dia) {
            array_push($diasReserva, array('dia' => $dia->getDia()->getNombre()));
        }

        $isPendiente = $reserva->getEstado() == "Pendiente";
        $reservaUsada = false;

        $buscarfechas = new BuscarFechas();
        $fechasAConsultar = array();
        $dias = $reserva->getDiaReserva();

        $fechaInicio = $reserva->getFechaInicio()->format('Y-m-d');
        $fechaActual = new DateTime();
        $fechaInicio = strtotime($fechaInicio);
        $fechaActual = strtotime($fechaActual->format('Y-m-d'));

        $puedeCancelarReserva = false;
        if ($fechaInicio > $fechaActual && $ROLE_SUPER_ADMIN) {
            $puedeCancelarReserva = true;
        }

        return $this->render('AdminBundle:Reservas/Pasos:mostrar_reserva.html.twig', [
                    'idReserva' => $id,
                    'reserva' => $reserva,
                    'diasReserva' => $diasReserva,
                    'pendiente' => $isPendiente,
                    'mostrarBtnAprobar' => $mostrarBtnAprobar,
                    'puedeCancelarReserva' => $puedeCancelarReserva
        ]);
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionAprobar(ProxyQueryInterface $selectedModelQuery, Request $request = null) {
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();

        try {
            $errorAprobarReserva = false;
            $completadoAprobarReserva = false;
            foreach ($selectedModels as $selectedModel) {

                $fechaInicio = $selectedModel->getFechaInicio()->format('Y-m-d');
                $fechaActual = new DateTime();
                $fechaInicio = strtotime($fechaInicio);
                $fechaActual = strtotime($fechaActual->format('Y-m-d'));
                $motivoCancelacion = $selectedModel->getMotivoCancelacion();

                $completadoAprobarReserva = true;
                $object = $selectedModel;
                $reserva = $object;
                $idEscenario = $object->getEscenarioDeportivo()->getId();
                $idReserva = $object->getId();
                $dias = $object->getDiaReserva();
                $fecha_inicio = $object->getFechaInicio();
                $fecha_final = $object->getFechaFinal();
                $escenario = $object->getEscenarioDeportivo();
                $em = $this->getDoctrine()->getManager();
                $buscarfechas = new BuscarFechas();
                $fechasAConsultar = array();
                $fechas = $buscarfechas->todasLosDias($fecha_inicio, $fecha_final);
                if (count($dias) < 1) {
                    for ($i = 0; $i < 7; $i++) {
                        $dias = $buscarfechas->tenerDias($fechas, $i);
                        foreach ($dias as $dia) {
                            array_push($fechasAConsultar, $dia);
                        }
                    }
                } else {
                    foreach ($dias as $diaSeleccionado) {
                        $diaSeleccionado = $em->getRepository('LogicBundle:Dia')->find($diaSeleccionado->getDia()->getId());

                        if ($diaSeleccionado != null) {
                            if ($diaSeleccionado->getNumero() == 7) {
                                $diasEncontrados = $buscarfechas->tenerDias($fechas, 0);
                            } else {
                                $diasEncontrados = $buscarfechas->tenerDias($fechas, $diaSeleccionado->getNumero());
                            }
                            foreach ($diasEncontrados as $dia) {
                                array_push($fechasAConsultar, $dia);
                            }
                        }
                    }
                }
                foreach ($fechasAConsultar as $fecha) {
                    $reservas = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                                    ->innerJoin('reserva.escenarioDeportivo', 'escenarioDeportivo')
                                    ->where('escenarioDeportivo.id = :escenario_deportivo')
                                    ->andWhere('reserva.id != :reserva_id')
                                    ->andWhere('reserva.estado != :rechazado')
                                    ->andWhere('reserva.estado != :prereserva')
                                    ->andWhere('reserva.fechaInicio <= :fecha')
                                    ->andWhere('reserva.fechaFinal >= :fecha')
                                    ->orderBy("reserva.id", 'ASC')
                                    ->setParameter('escenario_deportivo', $idEscenario ?: 0)
                                    ->setParameter('rechazado', 'Rechazado')
                                    ->setParameter('prereserva', 'Pre-Reserva')
                                    ->setParameter('reserva_id', $idReserva ?: 0)
                                    ->setParameter('fecha', $fecha ?: 0)
                                    ->getQuery()->getResult();
                }

                $selectedModel->setEstado('Aprobado');
                $selectedModel->setMotivoCancelacion(null);
                $modelManager->update($selectedModel);
                $user = $this->getUser();
                $userMail = $user->getEmail();
                $mailsGestores = array();
                if ($selectedModel->getUsuario()->getEmail() != null) {
                    array_push($mailsGestores, $selectedModel->getUsuario()->getEmail());
                }
                if (count($mailsGestores) > 0) {
                    $informacion = array('objeto' => $selectedModel, 'plantilla' => 'AdminBundle:Reservas:mails/mailRespuestaSolicitud.html.twig');
                    $this->enviarCorreo($mailsGestores, $this->trans('correos.reserva_respuesta.asunto'), $informacion);
                }
            }

            if ($completadoAprobarReserva == true) {
                $this->addFlash('sonata_flash_success', $this->trans('alerta.reservas_aprobadas'));
            }


            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }
        return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionRechazar(ProxyQueryInterface $selectedModelQuery, Request $request = null) {

        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        $arrayReservas = array();
        foreach ($selectedModels as $reserva) {
            array_push($arrayReservas, $reserva->getId());
        }
        return $this->redirectToRoute('admin_logic_reserva_motivoCancelacionBatch', array('id' => $arrayReservas));
    }

    public function paso1Action(Request $request, $id) {
        $this->admin->checkAccess('create');

        $em = $this->getDoctrine()->getManager();
        $variableGlobal = $em->getRepository('LogicBundle:VariableGlobal')->createQueryBuilder('variableGlobal')
                        ->Where('variableGlobal.nombre = :variableGlobal')
                        ->setParameter('variableGlobal', 'Bloqueo Reserva')
                        ->getQuery()->getOneOrNullResult();

        if ($variableGlobal != null) {
            $fechaBloqueada1 = $variableGlobal->getDato1();
            $fechaBloqueada2 = $variableGlobal->getDato2();
            $fechaActual = new DateTime();
            if ($fechaActual >= $fechaBloqueada1 && $fechaActual <= $fechaBloqueada2) {
                $this->addFlash('sonata_flash_success', $this->trans('formulario_reserva.error_fecha_deshabilitada'));
                return $this->redirectToRoute('admin_logic_reserva_list');
            }
        }

        $imagen = $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/icono-escenario.svg';
        $jornada = null;

        if ($request->request->get("reserva") && array_key_exists('jornada', $request->request->get("reserva"))) {
            $jornada = $request->request->get("reserva")['jornada'];
        }
        if ($id == 0) {
            $reserva = new Reserva();
            $mostrarInfoEscenario = 'false';
            $dias = $em->getRepository('LogicBundle:Dia')->findAll();
            foreach ($dias as $value) {
                $programacion = new ProgramacionReserva();
                $programacion->setDia($value);
                $reserva->addProgramacione($programacion);
            }
        } else {
            $reserva = $this->em->getRepository("LogicBundle:Reserva")->find($id);
            foreach ($reserva->getProgramaciones() as $programacion) {
                if ($programacion->getInicioManana()) {
                    $programacion->setInicioManana($programacion->getInicioManana()->format("H:i"));
                    $programacion->setFinManana($programacion->getFinManana()->format("H:i"));
                }
                if ($programacion->getInicioTarde()) {
                    $programacion->setInicioTarde($programacion->getInicioTarde()->format("H:i"));
                    $programacion->setFinTarde($programacion->getFinTarde()->format("H:i"));
                }
            }
            $mostrarInfoEscenario = $reserva->getEscenarioDeportivo();
            $existeImagen = false;
            if ($mostrarInfoEscenario != null) {
                if (count($mostrarInfoEscenario->getArchivoEscenarios()) > 0) {
                    foreach ($mostrarInfoEscenario->getArchivoEscenarios() as $archivoEscenarios) {
                        if ($archivoEscenarios->getType() == "imagen") {
                            $existeImagen = true;
                            $imagen = $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $archivoEscenarios->getFile();
                            break;
                        }
                    }
                    if ($existeImagen == false) {
                        $imagen = $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/icono-escenario.svg';
                    }
                }
            }
            if ($this->getUser()->hasRole('ROLE_PERSONANATURAL')) {
                foreach ($reserva->getProgramaciones() as $programacion) {
                    if ($programacion->getInicioManana()) {
                        $jornada = 1;
                    } else if ($programacion->getInicioTarde()) {
                        $jornada = 2;
                    }
                }
            }
        }

        $mostrarPaso5 = false;
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $ROLE_USER = $user->hasRole('ROLE_USER');

        $isSuperAdminOrganismoDeportivo = false;
        if ($ROLE_SUPER_ADMIN == true || $ROLE_ORGANISMO_DEPORTIVO == true || $ROLE_GESTOR_ESCENARIO == true) {
            $isSuperAdminOrganismoDeportivo = true;
        }

        $isGestorEscenario = false;
        if ($ROLE_GESTOR_ESCENARIO == true) {
            $isGestorEscenario = true;
        }
        $contador = 0;
        if ($this->getUser()->hasRole('ROLE_ORGANISMO_DEPORTIVO')) {
            if ($this->getUser()->getOrganizacionDeportiva() != null) {
                $organismo = $this->getUser()->getOrganizacionDeportiva();
                $organizacionDeportiva = $this->em->getRepository("LogicBundle:OrganizacionDeportiva")->find($organismo);
                if ($organizacionDeportiva = !null) {
                    $disciplinaOrganizacionDeportiva = $em->getRepository('LogicBundle:DisciplinaOrganizacion')
                            ->createQueryBuilder('d')
                            ->where('d.organizacion = :idOrganizacionDeportiva')
                            ->setParameter('idOrganizacionDeportiva', $organismo)
                            ->getQuery()
                            ->getResult();


                    if ($disciplinaOrganizacionDeportiva != null) {
                        foreach ($disciplinaOrganizacionDeportiva as $porDisciplina) {

                            $deportista = $em->getRepository('LogicBundle:OrganismoDeportista')
                                    ->createQueryBuilder('a')
                                    ->where('a.disciplinaOrganizacion = :idDiciplinaOrganizacionDeportiva')
                                    ->setParameter('idDiciplinaOrganizacionDeportiva', $porDisciplina)
                                    ->getQuery()
                                    ->getResult();
                        }
                        $contador = count($deportista);
                    }
                }
            }
        }

        if ($this->getUser()->hasRole('ROLE_ORGANISMO_DEPORTIVO') == true && $contador == 0) {
            $this->addFlash('sonata_flash_error', $this->trans("Error_usuarios_organismo_deportivo"));
            return $this->redirectToRoute('admin_logic_reserva_list');
        } else {
            $permitirReservaOrganisD = 2;
        }

        if ($reserva != null) {
            if ($reserva->getDisciplina() != null) {
                $tendenciaDisciplina = 'Disciplina';
            } else if ($reserva->getTendenciaEscenarioDeportivo() != null) {
                $tendenciaDisciplina = 'Tendencia';
            }
        }

        if ($reserva->getDisciplina() == null && $reserva->getTendenciaEscenarioDeportivo() == null) {
            $tendenciaDisciplina = '';
        }

        $form = $this->createForm(ReservaType::class, $reserva, array('paso' => 1,
            'em' => $this->getDoctrine()->getManager(),
            'isSuperAdminOrganismoDeportivo' => $isSuperAdminOrganismoDeportivo,
            'isGestorEscenario' => $isGestorEscenario,
            'valida' => $this->getUser()->hasRole('ROLE_ORGANISMO_DEPORTIVO'),
            'tendenciaDisciplina' => $tendenciaDisciplina,
            'validaPeersonaNatural' => ($this->getUser()->hasRole('ROLE_PERSONANATURAL') && (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && !$this->getUser()->hasRole('ROLE_ORGANISMO_DEPORTIVO') && !$user->hasRole('ROLE_GESTOR_ESCENARIO'))),
            'jornada' => $jornada));

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $mostrarPaso5 = false;
            $mostrarInfoEscenario = 'false';
            $this->validarFormularioPasoUno($form, $reserva);
            if ($form->isValid()) {
                if ($form->getData()) {
                    $errorReserva = 0;
                    $em = $this->getDoctrine()->getManager();
                    $user = $this->getUser();
                    if ($isGestorEscenario == true) {
                        if ($reserva->getTipoReserva()->getNombre() == 'Evento' || $reserva->getTipoReserva()->getNombre() == 'Oferta y Servicio') {
                            if ($form->get("numeroIdentificacion")->getData() != null) {
                                $tipoIdentificacion = $form->get("tipoIdentificacion")->getData();
                                $user = $form->get("numeroIdentificacion")->getData();

                                $user = $this->em->getRepository('ApplicationSonataUserBundle:User')->createQueryBuilder('user')
                                                ->where('user.tipoIdentificacion = :tipoIdentificacion')
                                                ->andWhere('user.numeroIdentificacion = :numeroIdentificacion')
                                                ->setParameter('tipoIdentificacion', $tipoIdentificacion->getId() ?: 0)
                                                ->setParameter('numeroIdentificacion', $user)
                                                ->getQuery()->getOneOrNullResult();
                            } else {
                                $errorReserva = 9;
                            }
                        }
                    }
                    if ($form->get("seleccion")->getData() == 'Tendencia') {
                        $reserva->setDisciplina(null);
                        $reserva->setTendenciaEscenarioDeportivo($form->get("tendenciaEscenarioDeportivo")->getData());
                    }
                    if ($form->get("seleccion")->getData() == 'Disciplina') {
                        $reserva->setDisciplina($form->get("disciplina")->getData());
                        $reserva->setTendenciaEscenarioDeportivo(null);
                    }
                    $reserva->setUsuario($user);
                    $reserva->setEstado('Pre-Reserva');
                    if ($isSuperAdminOrganismoDeportivo == false) {
                        $reserva->setFechaFinal($form->get("fechaInicio")->getData());
                    }
                    $fechaInicio = $reserva->getFechaInicio()->format('Y-m-d');
                    $fechaActual = new DateTime();
                    $fechaInicio = strtotime($fechaInicio);
                    $fechaActual = strtotime($fechaActual->format('Y-m-d'));
                    $validarMaximoDias = false;
                    $errorMaximoDias = false;
                    $divisionesEscenario = $reserva->getEscenarioDeportivo()->getDivisiones();
                    $tendenciasarray = array();
                    $bandera = 1;
                    $consulta = $this->em->getRepository("LogicBundle:Division")
                            ->createQueryBuilder('division')
                            ->where('division.escenarioDeportivo = :escenario')
                            ->setParameter('escenario', $form->get("escenario_deportivo")->getData())
                            ->getQuery()
                            ->getResult();
                    if ($consulta != null) {
                        $cantidadDivisiones = count($consulta);
                    }

                    if ($consulta != null || $consulta != '') {
                        if ($form->get("tendenciaEscenarioDeportivo")->getData() != null && $form->get("tendenciaEscenarioDeportivo")->getData() != '') {
                            foreach ($consulta as $tendencia) {
                                foreach ($tendencia->getTendenciasEscenarioDeportivo() as $tende) {
                                    if ($tende == $form->get("tendenciaEscenarioDeportivo")->getData()) {
                                        $bandera = 0;
                                        break;
                                    }
                                    if ($bandera == 0) {
                                        break;
                                    }
                                }
                                if ($bandera == 0) {
                                    break;
                                }
                            }
//si bandera es igual a uno es que no hay divisiones con esa tendencia
                            if ($bandera == 1) {
                                $errorReserva = 10;
                            }
                        }
                        if ($form->get("disciplina")->getData() != null && $form->get("disciplina")->getData() != '') {
                            foreach ($consulta as $disciplina) {
                                foreach ($disciplina->getDisciplinasEscenarioDeportivo() as $disci) {
                                    if ($disci->getDisciplina() == $form->get("disciplina")->getData()) {
                                        $bandera = 0;
                                        break;
                                    }

                                    if ($bandera == 0) {
                                        break;
                                    }
                                }

                                if ($bandera == 0) {
                                    break;
                                }
                            }

//si bandera es igual a uno es que no hay divisiones con esa tendencia
                            if ($bandera == 1) {
                                $errorReserva = 11;
                            }
                        }
                    } else {

                        $errorReserva = 8;
                    }
                    if ($errorReserva == 0) {
                        if (count($divisionesEscenario) > 0) {
                            $errorReserva = 0;
                        } else {
                            $errorReserva = 8;
                        }
                    }
                    if ($isSuperAdminOrganismoDeportivo == false) {
                        $validarMaximoDias = true;
                    }

                    if ($errorReserva == 0) {
                        $em->persist($reserva);
// Ejecuta las querys de las operaciones indicadas.
                        $em->flush();
                        return $this->redirectToRoute('admin_logic_reserva_paso2', array('id' => $reserva->getId()));
                    } else if ($errorReserva == 1) {

                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("fechaInicio")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error1'))
                            );
                        }
                    } else if ($errorReserva == 2) {

                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("escenario_deportivo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error2'))
                            );
                        }
                    } else if ($errorReserva == 3) {

                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $message = $this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error3');
                            $message = $message . " " . $maximoHoras;
                            $message = $message . " " . $this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras');
                            ;

                            $form->get("horaFinal")->addError(
                                    new FormError($message)
                            );
                        }
                    } else if ($errorReserva == 4) {

                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $message = $this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error4');
                            $message = $message . " " . $minimoHoras;
                            $message = $message . " " . $this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_errorHoras');
                            ;

                            $form->get("horaInicial")->addError(
                                    new FormError($message)
                            );
                        }
                    } else if ($errorReserva == 5) {
                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("fechaInicio")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error5'))
                            );
                        }
                    } else if ($errorReserva == 6) {
                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("fechaInicio")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error6'))
                            );
                        }
                    } else if ($errorReserva == 7) {
                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("fechaInicio")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_fecha_maximo_dias'))
                            );
                        }
                    } else if ($errorReserva == 8) {
                        if ($this->getUser()->hasRole('ROLE_PERSONANATURAL') == true) {
                            
                        } else {
                            $form->get("escenario_deportivo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error7'))
                            );
                        }
                    } else if ($errorReserva == 9) {
                        $form->get("numeroIdentificacion")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                        );
                    } else if ($errorReserva == 10) {

                        $form->get("tendenciaEscenarioDeportivo")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error10'))
                        );

                        $form->get("escenario_deportivo")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error10'))
                        );
                    } else if ($errorReserva == 11) {

                        $form->get("disciplina")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error11'))
                        );

                        $form->get("escenario_deportivo")->addError(
                                new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error11'))
                        );
                    }
                }
            }
        }
        if ($reserva->getEscenarioDeportivo() != null) {
            $idEscenarioDeportivo = 'true';
        } else {
            $idEscenarioDeportivo = 'false';
        }
        return $this->render('AdminBundle:Reservas/Pasos:reserva_paso1.html.twig', [
                    'form' => $form->createView(),
                    'idreserva' => $id,
                    'mostrarPaso5' => $mostrarPaso5,
                    'mostrarInfoEscenario' => $mostrarInfoEscenario,
                    'imagen' => $imagen,
                    'disciplina' => $reserva->getDisciplina(),
                    'isSuperAdminOrganismoDeportivo' => $isSuperAdminOrganismoDeportivo,
                    'isGestorEscenario' => $isGestorEscenario,
                    'idEscenarioDeportivo' => $idEscenarioDeportivo,
        ]);
    }

    public function validarFormularioPasoUno($form, $reserva) {
        $programo = false;
        $escenarioDeportivo = $form->get("escenario_deportivo")->getData();
        $fechaInicial = $form->get('fechaInicio')->getData();
        $fechaFinal = $form->get('fechaFinal')->getData();
        $tipoReserva = $form->get('tipoReserva')->getData();
        $programaciones = [];
        if ($this->getUser()->hasRole('ROLE_ORGANISMO_DEPORTIVO')) {
            $hoy = new \DateTime();
            if($hoy->format("Y")< $fechaFinal->format("Y")){
                $form->get('fechaFinal')->addError(new FormError($this->trans->trans('error.programar.organismo.anio')));
            }
        }
        if ($escenarioDeportivo && $fechaInicial && $tipoReserva) {
            $tipoReserva = false;
            foreach ($escenarioDeportivo->getDivisiones() as $division) {
                if ($division->getTiposReservaEscenarioDeportivo() != null) {
                    foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                        foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tipo) {
                            if ($tipo->getTipoReservaEscenarioDeportivo()->getTipoReserva()->getId() == $reserva->getTipoReserva()->getId()) {
                                $tipoReserva = true;
                            }
                        }
                    }
                }
            }
            if (!$tipoReserva) {
                $form->addError(new FormError($this->trans->trans('error.programar.tipo_reserva')));
            }
            foreach ($form->get("programaciones")->getData() as $programacion) {
                $programacion->setReserva($reserva);
                $progr = new \stdClass();
                $dia = $programacion->getDia();
                $progr->dia = $dia->getId();
                $progr->inicioManana = $programacion->getInicioManana();
                $progr->finManana = $programacion->getFinManana();
                $progr->inicioTarde = $programacion->getInicioTarde();
                $progr->finTarde = $programacion->getFinTarde();
                $programaciones[] = $progr;
                $inicioManana = $programacion->getInicioManana();
                $finManana = $programacion->getFinManana();
                $inicioTarde = $programacion->getInicioTarde();
                $finTarde = $programacion->getFinTarde();
                $query = $this->em->getRepository('LogicBundle:ProgramacionReserva')->createQueryBuilder('p');
                $query->join('p.reserva', 'r')
                        ->where('r.escenarioDeportivo = :escenario')
                        ->andWhere('r.fechaInicio BETWEEN :fechaInicial AND :fechaFinal')
                        ->andWhere('r.fechaFinal BETWEEN :fechaInicial AND :fechaFinal')
                        ->andWhere('p.dia = :dia')
                        ->setParameter("escenario", $escenarioDeportivo)
                        ->setParameter("fechaInicial", $fechaInicial)
                        ->setParameter("fechaFinal", $fechaFinal)
                        ->setParameter("dia", $dia);
                if ($reserva->getId()) {
                    $query->andwhere('r.id != :reserva')
                            ->setParameter("reserva", $reserva);
                }
                $consultar = true;
                if (($inicioManana && $finManana) || ($inicioTarde && $finTarde)) {
                    $programo = true;
                    if ($inicioManana && $finManana) {
                        if ($inicioManana > $finManana) {
                            $consultar = false;
                            $form->addError(
                                    new FormError($this->trans->trans('error.programar.hora.mayor', ['%dia%' => $dia->getNombre(), '%horario%' => 'mañana']))
                            );
                        } else {
                            $inicioManana = new \DateTime($inicioManana);
                            $finManana = new \DateTime($finManana);
                            $programacion->setInicioManana($inicioManana);
                            $programacion->setFinManana($finManana);
                            $im = new \DateTime($inicioManana->format("H:i"));
                            $im->modify("+1 second");
                            $fm = new \DateTime($finManana->format("H:i"));
                            $fm->modify("-1 second");
                            $query->andWhere(':inicioManana BETWEEN p.inicioManana AND p.finManana OR :finManana BETWEEN p.inicioManana AND p.finManana')
                                    ->setParameter('inicioManana', $im)
                                    ->setParameter('finManana', $finManana);
                        }
                    }
                    if ($inicioTarde && $finTarde) {
                        if ($inicioTarde > $finTarde) {
                            $consultar = false;
                            $form->addError(
                                    new FormError($this->trans->trans('error.programar.hora.mayor.tarde', ['%dia%' => $dia->getNombre(), '%horario%' => 'tarde']))
                            );
                        } else {
                            $inicioTarde = new \DateTime($inicioTarde);
                            $finTarde = new \DateTime($finTarde);
                            $programacion->setInicioTarde($inicioTarde);
                            $programacion->setFinTarde($finTarde);
                            $it = new \DateTime($inicioTarde->format("H:i"));
                            $it->modify("+1 second");
                            $ft = new \DateTime($finTarde->format("H:i"));
                            $ft->modify("-1 second");
                            $query->andWhere(':inicioTarde BETWEEN p.inicioTarde AND p.finTarde OR :finTarde BETWEEN p.inicioTarde AND p.finTarde')
                                    ->setParameter('inicioTarde', $it)
                                    ->setParameter('finTarde', $ft);
                        }
                    }
                    $query->andWhere("r.estado != :rechazado")
                            ->setParameter("rechazado", "Rechazado")
                            ->andWhere("r.completada != :completada")
                            ->setParameter("completada", true);
                    $programacionReserva = $query->getQuery()->getResult();
                    if ($programacionReserva) {
                        $form->addError(new FormError($this->trans->trans('error.programacion.encontrada', ['%dia%' => $programacion->getDia()->getNombre()])));
                    }
                }
            }

            if (!$programo) {
                $form->addError(
                        new FormError($this->trans->trans('error.programar.minimo'))
                );
            }
            if (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && $this->getUser()->hasRole('ROLE_PERSONANATURAL')) {
                $reservas = $this->em->getRepository('LogicBundle:Reserva')->buscarReservasTipoEscenario($reserva->getEscenarioDeportivo(), $this->getUser());
                if ($reservas) {
                    $form->addError(new FormError($this->trans->trans('error.reserva.pendiente', ['%tipo%' => $reservas[0]->getEscenarioDeportivo()->getTipoEscenario()])));
                }
            }
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->findOneById($escenarioDeportivo);
            $erroresExtra = $this->em->getRepository("LogicBundle:Reserva")->comprobarDiasPrevios($escenarioDeportivo, $fechaInicial->format("Y-m-d"), $programaciones);
            if ($erroresExtra["error"]) {
                $form->addError(new FormError($erroresExtra["mensaje"]));
            }
        }
    }

    public function paso2Action(Request $request, $id) {
        $this->admin->checkAccess('create');

        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $reserva = $reservaRepository->find($id);
            if ($reserva === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
        }

        $user = $this->getUser();
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
//aca obtenemos el id del escenario que encontramos
        $idEscenario = $reserva->getEscenarioDeportivo()->getId();
//obtener y formatear divisiones de un escenario
        $divisiones = $reserva->getEscenarioDeportivo()->getDivisiones();
        $validarDisponibilidadTotal = true;
        $form = $this->createForm(ReservaType::class, $reserva, array('paso' => 2, 'em' => $this->getDoctrine()->getManager(), 'ROLE_ORGANISMO_DEPORTIVO' => $ROLE_ORGANISMO_DEPORTIVO, 'usuario' => $this->getUser()));
        $form->handleRequest($request);
        foreach ($divisiones as $division) {
            foreach ($division->getDisciplinasEscenarioDeportivo() as $disciplinas) {
                if ($disciplinas->getDisciplina() == $reserva->getDisciplina()) {
                    if ($division->getDisponibilidad() && !$division->getDisponibilidad()) {
                        $validarDisponibilidadTotal = false;
                    }
                }
            }
        }

        if ($reserva->getEscenarioDeportivo()->getImagenEscenarioDividido() == null) {
            $escenarios = "icono-escenario.svg";
        } else {
            $escenarios = $reserva->getEscenarioDeportivo()->getImagenEscenarioDividido();
        }
        $phath = "/uploads/" . $escenarios;
        if ($form->isSubmitted()) {
            if (!$form->get("divisiones")->getData()) {
                $form->get("divisiones")->addError(new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio')));
            }
            if ($form->isValid()) {
                if ($form->getData()) {
                    if ($form->get("divisiones")->getData()) {
                        foreach ($reserva->getDivisiones() as $division) {
                            $this->em->remove($division);
                            $this->em->persist($reserva);
                        }
                        $registro = false;
                        foreach ($form->get("divisiones")->getData() as $div) {
                            $division = $this->em->getRepository('LogicBundle:Division')->find($div);
                            $divisionReserva = new DivisionReserva($division, $reserva);
                            $reserva->addDivisione($divisionReserva);
                            $this->em->persist($divisionReserva);
                            $this->em->persist($reserva);
                            if ($reserva->getTipoReserva()->getid() == 6 && !$registro) {
                                $registro = true;
                                $usuarioDivisionReserva = new UsuarioDivisionReserva();
                                $usuarioDivisionReserva->setDivisionReserva($divisionReserva);
                                $usuarioDivisionReserva->setUsuario($this->getUser());
                                $this->em->persist($usuarioDivisionReserva);
                            }
                        }
                        $this->em->flush();
                    }
                    if (array_key_exists('reservaTodoEscenario', $form->all()) && $form->get("reservaTodoEscenario")->getData() == true) {
                        $reserva->setReservaTodoEscenario(true);
                    }
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($reserva);
                    $em->flush();
                    if ($division->getTiposReservaEscenarioDeportivo() != null) {
                        foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                            foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                                if ($tiposReservaEscenarioDeportivo->getUsuariosMaximos() <= 1) {
                                    return $this->redirectToRoute('admin_logic_reserva_paso4', array('id' => $reserva->getId()));
                                }
                            }
                        }
                    }
                    return $this->redirectToRoute('admin_logic_reserva_paso3', array('id' => $reserva->getId()));
                }
            }
        }
        $mostrarPaso5 = false;

        return $this->render('AdminBundle:Reservas/Pasos:reserva_paso2.html.twig', [
                    'form' => $form->createView(),
                    'imagen' => $phath,
                    'idEscenario' => $idEscenario,
                    'idreserva' => $id,
                    'disponibilidadTotal' => $validarDisponibilidadTotal,
                    'mostrarPaso5' => $mostrarPaso5,
                    'em' => $this->getDoctrine()->getManager()
        ]);
    }

    public function paso3Action(Request $request, $id) {
        $this->admin->checkAccess('create');

        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        $reserva = $reservaRepository->find($id);
        if ($id == 0 || !$reserva) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        }

        $datosDivision = $reservaRepository->find($id);
        $idDivision = $datosDivision->getId();
        $mostrarPaso5 = true;
        $disciplina = $reserva->getDisciplina();
        if ($disciplina) {
            $disciplina = $disciplina->getNombre();
            if ($disciplina != "BOLO") {
                $mostrarPaso5 = false;
            }
        }
        $user = $this->getUser();
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        if ($ROLE_ORGANISMO_DEPORTIVO == true) {
            if ($this->getUser()->getOrganizacionDeportiva() != null) {
                $organismo = $this->getUser()->getOrganizacionDeportiva();
                $organizacionDeportiva = $this->em->getRepository("LogicBundle:OrganizacionDeportiva")->find($organismo);
                $em = $this->em;
                if ($organizacionDeportiva = !null) {
                    $disciplinaOrganizacionDeportiva = $em->getRepository('LogicBundle:DisciplinaOrganizacion')
                            ->createQueryBuilder('d')
                            ->where('d.organizacion = :idOrganizacionDeportiva')
                            ->setParameter('idOrganizacionDeportiva', $organismo)
                            ->getQuery()
                            ->getResult();

                    if ($disciplinaOrganizacionDeportiva != null) {
                        foreach ($disciplinaOrganizacionDeportiva as $porDisciplina) {
                            $deportista = $em->getRepository('LogicBundle:OrganismoDeportista')
                                    ->createQueryBuilder('a')
                                    ->where('a.disciplinaOrganizacion = :idDiciplinaOrganizacionDeportiva')
                                    ->setParameter('idDiciplinaOrganizacionDeportiva', $porDisciplina)
                                    ->getQuery()
                                    ->getResult();
                        }
                        $contador = count($deportista);
                    }
                }
            }
            $organizacionDeporId = $this->getUser()->getOrganizacionDeportiva()->getId();
            $organismoDeportistas = array();
            $deportistas = $user->getOrganizacionDeportiva();
            foreach ($deportistas->getDisciplinaOrganizaciones() as $disci) {
                foreach ($disci->getDeportistas() as $depor) {
                    $orgaUser = $depor->getUsuarioDeportista()->getId();
                    array_push($organismoDeportistas, $orgaUser);
                }
            }
            $form = $this->createForm(ReservaType::class, $reserva, array(
                'paso' => 31,
                'id' => $idDivision,
                'em' => $this->em,
                'organizacionDeporId' => $organizacionDeporId,
                'organismoDeportistas' => $organismoDeportistas,
                'reserva' => $reserva
            ));

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($this->validarInscritosOrganizacion($reserva, $request, $form)) {
                    if ($form->isValid()) {
                        $this->em->persist($reserva);
                        $this->em->flush();
                        return $this->redirectToRoute('admin_logic_reserva_paso4', array('id' => $reserva->getId()));
                    }
                }
            }
            $inscritos = $em->getRepository('LogicBundle:UsuarioDivisionReserva')->createQueryBuilder('u')
                            ->join('u.divisionReserva ', 'd')
                            ->where('d.reserva = :reserva')
                            ->setParameter('reserva', $reserva)
                            ->getQuery()->getResult();

            return $this->render('AdminBundle:Reservas/Pasos:reserva_paso3-1.html.twig', [
                        'form' => $form->createView(),
                        'idreserva' => $id,
                        'mostrarPaso5' => $mostrarPaso5,
                        'datosDivision' => $reserva->getDivisiones(),
                        'tipoEntrada' => '$datosDivision',
                        'reserva' => $reserva,
                        'inscritos' => $inscritos
            ]);
        } else {
            $form = $this->createForm(DivisionesType::class, $reserva);
            if ($request->request->get('usuarios_division_reserva_type') != null && array_key_exists("divisiones", $request->request->get('usuarios_division_reserva_type'))) {
                $form->handleRequest($request);
            }
            $originalUsuarios = $reserva->getUsuarios();
            if ($form->isSubmitted()) {
                if ($this->validarInscritos($form) == true) {
                    if ($form->isValid()) {
                        $this->em->persist($reserva);
                        $this->em->flush();
                        return $this->redirectToRoute('admin_logic_reserva_paso4', array('id' => $reserva->getId()));
                    }
                }
            }
            $inscritos = $this->em->getRepository('LogicBundle:UsuarioDivisionReserva')->createQueryBuilder('u')
                            ->join('u.divisionReserva ', 'd')
                            ->where('d.reserva = :reserva')
                            ->setParameter('reserva', $reserva)
                            ->getQuery()->getResult();
            return $this->render('AdminBundle:Reservas/Pasos:reserva_paso3.html.twig', [
                        'form' => $form->createView(),
                        'datosDivision' => $reserva->getDivisiones(),
                        'tipoEntrada' => '$datosDivision',
                        'idreserva' => $id,
                        'mostrarPaso5' => $mostrarPaso5,
                        'numeroUsuarios' => count($originalUsuarios),
                        'divisiones' => $reserva->getDivisiones(),
                        'reserva' => $reserva,
                        'inscritos' => $inscritos
            ]);
        }
    }

    public function paso4Action(Request $request, $id) {
        $this->admin->checkAccess('create');

        $normas = "";
        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $reserva = $reservaRepository->find($id);
            if ($reserva === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
            $normas = $reserva->getEscenarioDeportivo()->getNormaEscenario();
        }

        $divisiones = $reserva->getDivisiones();

        if ($divisiones == null) {
            return $this->redirectToRoute('admin_logic_reserva_paso2', array('id' => $reserva->getId()));
        }
        $usuariosMaximos = 10;
        $form = $this->createForm(ReservaType::class, $reserva, array('paso' => 4));

        $form->handleRequest($request);

        if ($reserva->getDisciplina()) {
            $disciplina = $reserva->getDisciplina()->getNombre();
            if ($disciplina != "BOLO") {
                $mostrarPaso5 = false;
            } else {
                $mostrarPaso5 = true;
            }
        } else {
            $mostrarPaso5 = false;
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->getData()) {
                    $terminos = $form->get("terminos")->getData();
                    if (count($terminos) > 0) {
                        $user = $reserva->getUsuario();
                        if ($terminos[0] == 1) {
                            return $this->redirectToRoute('admin_logic_reserva_paso4Extra', array('id' => $reserva->getId(), 'idUser' => $user->getId()));
                        } else {
                            $form->get("terminos")->addError(
                                    new FormError($this->trans->trans('Debe aceptar los Terminos y Condiciones para continuar'))
                            );
                        }
                    } else {
                        $form->get("terminos")->addError(
                                new FormError($this->trans->trans('Debe aceptar los Terminos y Condiciones para continuar'))
                        );
                    }
                }
            }
        }


        $listaDisciplinas = $this->em->getRepository('LogicBundle:Disciplina')->findAll();

        $listaDeEScenarios = $this->em->getRepository('LogicBundle:EscenarioDeportivo')->find(1);

        return $this->render('AdminBundle:Reservas/Pasos:reserva_paso4.html.twig', [
                    'form' => $form->createView(),
                    'disciplinas' => $listaDisciplinas,
                    'idreserva' => $id,
                    'mostrarPaso5' => $mostrarPaso5,
                    'normas' => $normas,
                    'usuariosDivision' => $usuariosMaximos
        ]);
    }

    public function paso4ExtraAction(Request $request, $id) {
        $this->admin->checkAccess('create');

        $idReserva = $id;
        $idUser = null;
        foreach ($request->query as $param) {
            $idUser = $param;
            break;
        }

        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $reserva = $reservaRepository->find($id);
            if ($reserva === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
        }
        $usuarioRepository = $this->em->getRepository('ApplicationSonataUserBundle:User');
        if ($idUser == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $usuario = $usuarioRepository->find($idUser);
            if ($usuario === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
        }
        $informacionExtraUsuario = null;
        if ($usuario->getInformacionExtraUsuario() != null) {
            $informacionExtraUsuario = $usuario->getInformacionExtraUsuario();
        } else {
            $informacionExtraUsuario = new InformacionExtraUsuario();
        }

        $divisiones = $reserva->getDivisiones();
        $form = $this->createForm(InformacionExtraUsuarioReservaType::class, $usuario, array('paso' => 41));
        $form->handleRequest($request);
        if ($reserva->getDisciplina()) {
            $disciplina = $reserva->getDisciplina()->getNombre();
            if ($disciplina != "BOLO") {
                $mostrarPaso5 = false;
            } else {
                $mostrarPaso5 = true;
            }
        } else {
            $mostrarPaso5 = false;
        }

        if ($form->isSubmitted()) {
            if ($form->get('esDesplazado')->getData()) {
                if ($form->get('tipoDesplazado')->getData() == null) {
                    $form->get("tipoDesplazado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                    );
                }
            }
            if ($form->get('esDiscapacitado')->getData()) {
                if ($form->get('discapacidad')->getData() == null) {
                    $form->get("discapacidad")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_vacio'))
                    );
                }
            }

            if ($form->isValid()) {
                if ($form->getData()) {
                    $this->em->persist($usuario);
                    $this->em->flush();
                    return $this->redirectToRoute('admin_logic_reserva_paso5', array('id' => $reserva->getId()));
                }
            }
        }

        return $this->render('AdminBundle:Reservas/Pasos:reserva_paso4_informacion_extra_usuario.html.twig', [
                    'form' => $form->createView(),
                    'idreserva' => $id,
                    'action' => 'edit',
                    'mostrarPaso5' => $mostrarPaso5
        ]);
    }

    public function paso5Action(Request $request, $id) {
        $this->admin->checkAccess('create');

        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $reserva = $reservaRepository->find($id);
            if ($reserva === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
        }

        $user = $reserva->getUsuario();

        $mostrarFormularioCaracterizacion = false;
        if ($user->getPhone() == null) {
            $mostrarFormularioCaracterizacion = true;
        }
        if ($user->getEps() == null) {
            $mostrarFormularioCaracterizacion = true;
        }
        if ($user->getNivelEscolaridad() == null) {
            $mostrarFormularioCaracterizacion = true;
        }
        if ($user->getTipoEstablecimientoEducativo() == null) {
            $mostrarFormularioCaracterizacion = true;
        }
        if ($user->getEstablecimientoEducativo() == null) {
            $mostrarFormularioCaracterizacion = true;
        }
        if ($user->getOcupacion() == null) {
            $mostrarFormularioCaracterizacion = true;
        }

        if ($mostrarFormularioCaracterizacion) {
            return $this->redirectToRoute('admin_logic_reserva_paso4Extra', array('id' => $reserva->getId(), 'idUser' => $user->getId()));
        }

        $divisiones = $reserva->getDivisiones();

        if ($divisiones == null) {
            return $this->redirectToRoute('admin_logic_reserva_paso2', array('id' => $reserva->getId()));
        }

        if ($reserva->getDisciplina()) {
            $disciplina = $reserva->getDisciplina()->getNombre();
            if ($disciplina != "BOLO") {
                $mostrarPaso5 = false;
            } else {
                $mostrarPaso5 = true;
            }
        } else {
            $mostrarPaso5 = false;
        }
        $form = $this->createForm(ReservaType::class, $reserva, array('paso' => 5));
        $form->handleRequest($request);
        $listaDisciplinas = $this->em->getRepository('LogicBundle:Disciplina')->findAll();

        $infoReserva = $reserva;
        $fechaInicio = $infoReserva->getFechaInicio()->format('Y-m-d');
        $fechaFinal = $infoReserva->getFechaFinal()->format('Y-m-d');
        if ($form->isSubmitted()) {
            $user = $this->getUser();
            $userMail = $user->getEmail();
            $mailsGestores = array();
            foreach ($reserva->getEscenarioDeportivo()->getUsuarioEscenarioDeportivos() as $gestor) {
                if ($gestor->getUsuario()->getEmail() != null) {
                    array_push($mailsGestores, $gestor->getUsuario()->getEmail());
                }
            }

            if (count($mailsGestores) > 0) {
                $informacion = array('objeto' => $reserva, 'plantilla' => 'AdminBundle:Reservas:mails/mailSolicitudGestor.html.twig');
                $this->enviarCorreo($mailsGestores, $this->trans('correos.reserva_gestor.asunto'), $informacion);
            }

            if ($userMail != null) {
                $informacion = array('objeto' => $reserva, 'plantilla' => 'AdminBundle:Reservas:mails/mailSolicitud.html.twig');
                $this->enviarCorreo($userMail, $this->trans('correos.reserva.asunto'), $informacion);
            }
            $noNecesitaAprobacion = true;
            foreach ($reserva->getDivisiones() as $divisionReserva) {
                if (!$divisionReserva->getDivision()->getNecesitaAprobacion()) {
                    $noNecesitaAprobacion = false;
                }
            }

            if ($noNecesitaAprobacion == true) {
                $reserva->setEstado('Aprobado');
            } else {
                $reserva->setEstado('Pendiente');
            }
            $this->em->persist($reserva);
            $this->em->flush();

            return $this->redirectToRoute('admin_logic_reserva_pasoFinal', array('id' => $reserva->getId()));
        }

        $map = new Map();
        $map->setHtmlId("mapa_escenario");
        $map->setCenter(new Coordinate($infoReserva->getEscenarioDeportivo()->getLatitud(), $infoReserva->getEscenarioDeportivo()->getLongitud()));
        $map->setMapOption('zoom', 12);
        $marker = new Marker(new Coordinate($infoReserva->getEscenarioDeportivo()->getLatitud(), $infoReserva->getEscenarioDeportivo()->getLongitud()));
        $marker->setOptions(array(
            'draggable' => true
        ));

        $map->getOverlayManager()->addMarker($marker);


        $informacionReserva = "";
        if ($reserva->getEscenarioDeportivo() != null) {
            $informacionReserva = $reserva->getEscenarioDeportivo()->getInformacionReserva();
        }

        return $this->render('AdminBundle:Reservas/Pasos:reserva_paso5.html.twig', [
                    'form' => $form->createView(),
                    'disciplinas' => $listaDisciplinas,
                    'idreserva' => $id,
                    'mostrarPaso5' => $mostrarPaso5,
                    'infoReserva' => $infoReserva,
                    'fechaInicio' => $fechaInicio,
                    'fechaFinal' => $fechaFinal,
                    'informacionReserva' => $informacionReserva,
                    'map' => $map,
                    'id_mapa' => 'mapa_escenario',
                    'reserva' => $reserva
        ]);
    }

    public function pasoFinalAction(Request $request, $id) {
        $this->admin->checkAccess('create');

        $reservaRepository = $this->em->getRepository('LogicBundle:Reserva');
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
        } else {
            $reserva = $reservaRepository->find($id);
            if ($reserva === null) {
                return $this->redirectToRoute('admin_logic_reserva_paso1', array('id' => 0));
            }
        }

        if ($reserva->getDisciplina()) {
            $disciplina = $reserva->getDisciplina()->getNombre();
            if ($disciplina != "BOLO") {
                $mostrarPaso5 = false;
            } else {
                $mostrarPaso5 = true;
            }
        } else {
            $mostrarPaso5 = true;
        }
        $reserva->setCompletada(true);
        $this->em->persist($reserva);
        $this->em->flush();


        $form = $this->createForm(ReservaType::class, $reserva, array('paso' => 6));
        $form->handleRequest($request);
        $listaDisciplinas = $this->em->getRepository('LogicBundle:Disciplina')->findAll();
        $listaDeEScenarios = $this->em->getRepository('LogicBundle:EscenarioDeportivo')->find(1);
        return $this->render('AdminBundle:Reservas/Pasos:reserva_pasoFinal.html.twig', [
                    'form' => $form->createView(),
                    'disciplinas' => $listaDisciplinas,
                    'idreserva' => $id,
                    'mostrarPaso5' => $mostrarPaso5
        ]);
    }

    /**
     * Reserva action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function asistenciaAction(Request $request, $id) {
        $container = $this->container;
        $em = $this->getDoctrine()->getManager();

        $reserva = $em->getRepository('LogicBundle:Reserva')->find($id);
        if (!$reserva) {
            return $this->redirect($this->generateUrl('admin_logic_reserva_list'));
        }

        $this->admin->checkAccess('list');
        if ($this->getUser()->hasRole('ROLE_GESTOR_ESCENARIO')) {
            $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        }

        $form = $this->createForm(AsistenciasReservaType::class, $reserva, [
            'em' => $em,
            'container' => $container
        ]);

        $asistentes = $em->getRepository("LogicBundle:UsuarioDivisionReserva")->buscarAsistentesReserva($reserva);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $asistencias = [];

            if ($form->isValid()) {
                $diaReserva = $em->getRepository("LogicBundle:DiaReserva")->buscarProgramacion($form->get('dias_semana')->getData(), $reserva);
                $fecha = $form->get("seleccion_dia_unico")->getData();

                if (key_exists("usuario", $request->get("asistentes"))) {
                    foreach ($request->get("asistentes")["usuario"] as $key => $asistencia) {
                        $usuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneById($asistencia);
                        $asistenciaEntity = $this->em->getRepository("LogicBundle:AsistenciaReserva")->buscarAsistente($usuario, $reserva, $fecha);
                        if (!$asistenciaEntity) {
                            $asistenciaEntity = new AsistenciaReserva();
                        }
                        $asistenciaEntity->setFecha(new DateTime($fecha));
                        $asistenciaEntity->setReserva($reserva);
                        $asistenciaEntity->setDiareserva($diaReserva);
                        $asistenciaEntity->setUsuario($usuario);

                        if (key_exists("asistio", $request->get("asistentes"))) {
                            if (key_exists($key, $request->get("asistentes")["asistio"])) {
                                $asistenciaEntity->setAsistio(true);
                            }
                        }
                        $em->persist($asistenciaEntity);
                    }
                }

                $this->addFlash('sonata_flash_success', $this->trans("mensaje.preinscripcion.asistentes.registrados"));
                $em->flush();
                $url = $this->admin->generateUrl('list');
                return $this->redirect($url);
            } else {
                if (key_exists("asistio", $request->get("asistentes"))) {
                    foreach ($request->get("asistentes")["asistio"] as $key => $value) {
                        $asistentes[$key]->setAsistio(true);
                    }
                }
            }
        }
// set the theme for the current Admin Form
        $this->setFormTheme($form->createView(), $this->admin->getFormTheme());

        return $this->render('AdminBundle:Reservas:asistencia.html.twig', array(
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'reserva' => $reserva,
                    'asistentes' => $asistentes
                        ), null);
    }

    /**
     * Reserva action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function cancelarReservaAction(Request $request, $id) {

        $container = $this->container;
        $em = $this->getDoctrine()->getManager();
        $reserva = $em->getRepository('LogicBundle:Reserva')->find($id);
        if (!$reserva) {
            return $this->redirect($this->generateUrl('admin_logic_reserva_list'));
        }
        $fechaInicio = $reserva->getFechaInicio()->format('Y-m-d');
        $fechaActual = new DateTime();
        $fechaInicio = strtotime($fechaInicio);
        $fechaActual = strtotime($fechaActual->format('Y-m-d'));
        $errorRechazarReserva = false;
        $completadoRechazarReserva = false;
        $completadoRechazarReserva = true;
        $reserva->setEstado('Rechazado');
        $em->persist($reserva);
        $em->flush();
        $user = $this->getUser();
        $userMail = $user->getEmail();
        $mailsGestores = array();

        if ($reserva->getUsuario()->getEmail() != null) {
            array_push($mailsGestores, $reserva->getUsuario()->getEmail());
        }

        if (count($mailsGestores) > 0) {
            $informacion = array('objeto' => $reserva, 'plantilla' => 'AdminBundle:Reservas:mails/mailRespuestaSolicitud.html.twig');
            $this->enviarCorreo($mailsGestores, $this->trans('correos.reserva_respuesta.asunto'), $informacion);
        }

        if ($completadoRechazarReserva == true) {
            $this->addFlash('sonata_flash_success', $this->trans('alerta.reservas_rechazadas'));
        }

        return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    private function setFormTheme(FormView $formView, $theme) {
        $twig = $this->get('twig');

        try {
            $twig
                    ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                    ->setTheme($formView, $theme);
        } catch (Twig_Error_Runtime $e) {
            $twig
                    ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                    ->renderer
                    ->setTheme($formView, $theme);
        }
    }

    public function motivoCancelacionAction(Request $request, $id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $reserva = new Reserva();
        } else {
            $reserva = $this->em->getRepository("LogicBundle:Reserva")->find($id);
        }
        $user = $this->getUser();
        $form = $this->createForm(MotivoCancelacionReservaType::class, $reserva, array(
            'em' => $this->getDoctrine()->getManager(),
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $motivo = $form->get('motivoCancelacion')->getData();
            if (count($motivo) == null) {
                $form->get("motivoCancelacion")->addError(
                        new FormError($this->trans->trans('motivoCancelacionError'))
                );
            }
            if ($form->isValid()) {
                $reserva->setMotivoCancelacion($motivo);
                $this->em->flush();
                return $this->redirectToRoute('admin_logic_reserva_list');
            }
        }
        return $this->render('AdminBundle:MotivoCancelacionReserva:formulario_motivo_cancelacion.html.twig', [
                    'form' => $form->createView(),
                    'idreserva' => $id,
                    'em' => $this->getDoctrine()->getManager()
        ]);
    }

    public function motivoCancelacionBatchAction(Request $request) {
        $request = $this->getRequest();
        $idReservas = $request->get('id');
        $reservas = array();
        $em = $this->getDoctrine()->getManager();
        $errorRechazarReserva = false;
        $completadoRechazarReserva = false;
        foreach ($idReservas as $idReserva) {
            $reserva = $this->em->getRepository("LogicBundle:Reserva")->find($idReserva);
            array_push($reservas, $reserva);
        }
        $form = $this->createForm(CancelarReservaBatchActionType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $motivo = $form->get('motivoCancelacion')->getData();
            if (!$motivo) {
                $form->get("motivoCancelacion")->addError(
                        new FormError($this->trans->trans('motivoCancelacionError'))
                );
            }
            if ($form->isValid()) {
                try {
                    foreach ($reservas as $reserva) {
                        $fechaInicio = $reserva->getFechaInicio()->format('Y-m-d');
                        $fechaActual = new DateTime();
                        $fechaInicio = strtotime($fechaInicio);
                        $fechaActual = strtotime($fechaActual->format('Y-m-d'));
                        $puedeCancelarReserva = false;

                        $completadoRechazarReserva = true;
                        $reserva->setEstado('Rechazado');
                        $reserva->setMotivoCancelacion($form->get('motivoCancelacion')->getData());
                        $this->em->persist($reserva);
                        $user = $this->getUser();
                        $userMail = $user->getEmail();
                        $mailsGestores = array();
                        if ($reserva->getUsuario()->getEmail() != null) {
                            array_push($mailsGestores, $reserva->getUsuario()->getEmail());
                        }
                        if (count($mailsGestores) > 0) {
                            $informacion = array('objeto' => $reserva, 'plantilla' => 'AdminBundle:Reservas:mails/mailRespuestaSolicitud.html.twig');
                            $this->enviarCorreo($mailsGestores, $this->trans('correos.reserva_respuesta.asunto'), $informacion);
                        }
                    }
                    if ($completadoRechazarReserva == true) {
                        $this->addFlash('sonata_flash_success', $this->trans('alerta.reservas_rechazadas'));
                    }


                    $this->em->flush();
                } catch (\Exception $e) {
                    $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

                    return new RedirectResponse(
                            $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                    );
                }

                return new RedirectResponse(
                        $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                );
            }
        }
        return $this->render('AdminBundle:MotivoCancelacionReserva:formulario_batch_rechazar.html.twig', [
                    'form' => $form->createView(),
                    'em' => $this->getDoctrine()->getManager()
        ]);
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
                    return $ex;
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
                return $ex;
            }
        }
    }

    public function todasLosDiasxD($inicio, $fin) {
        $fin = $fin->modify('+0 day');
        $intervalo = new DateInterval('P1D');
        $rangoFechas = new DatePeriod($inicio, $intervalo, $fin);
        $fechas = [];
        foreach ($rangoFechas as $fecha) {
            array_push($fechas, $fecha->format("Y-m-d"));
        }
        return $fechas;
    }

    public function validarInscritos($form) {
        $data = $form->getData();
        $servicio = $this->container->get('app.logic_twig_extension');
        $error = false;
        if (count($data->getDivisiones()) == 0) {
            $form->addError(
                    new FormError($this->trans->trans('error.reserva.division.vacio'))
            );
        }
        foreach ($data->getDivisiones() as $divisionReserva) {
            $reserva = $divisionReserva->getReserva();
            foreach ($divisionReserva->getDivision()->getTiposReservaEscenarioDeportivo() as $tipoReserva) {
                if ($tipoReserva->getTipoReserva()->getId() == $reserva->getTipoReserva()->getId()) {
                    foreach ($tipoReserva->getTipoReservaEscenarioDeportivoDivisiones() as $tipo) {
                        if ($divisionReserva->getDivision()->getId() == $tipo->getDivisionTipoReserva()->getId()) {
                            if (count($divisionReserva->getDivisionReservas()) < $tipo->getUsuariosMinimos()) {
                                $form->addError(
                                        new FormError($this->trans->trans('error.reserva.division.minimo', ["%division%" => $divisionReserva->getDivision()->getNombre(), '%minimo%' => $tipo->getUsuariosMinimos()]))
                                );
                            } else {
                                $total = 0;
                                foreach ($divisionReserva->getDivisionReservas() as $usuarioDivisionReserva) {
                                    $usuario = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneById($usuarioDivisionReserva->getNumeroIdentificacion());
                                    if ($usuario) {
                                        $total ++;
                                    }
                                }
                                if ($total < $tipo->getUsuariosMinimos()) {
                                    $form->addError(
                                            new FormError($this->trans->trans('error.reserva.division.minimo', ["%division%" => $divisionReserva->getDivision()->getNombre(), '%minimo%' => $tipo->getUsuariosMinimos()]))
                                    );
                                }
                            }
                        }
                    }
                }
            }
            foreach ($divisionReserva->getDivisionReservas() as $usuarioDivisionReserva) {
                $usuario = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneById($usuarioDivisionReserva->getNumeroIdentificacion());
                if ($usuario) {
                    $usuarioDivisionReserva->setUsuario($usuario);
                    $usuarioDivisionReserva->setDivisionReserva($divisionReserva);
                    $edad = $servicio->calcularEdad($usuario->getDateOfBirth());
                    if ($divisionReserva->getDivision()->getEdadMinima() > 0) {
                        if ($edad <= $divisionReserva->getDivision()->getEdadMinima()) {
                            $form->addError(
                                    new FormError($this->trans->trans('error.reserva.usuario.menor', ["%documento%" => $usuario->getNumeroIdentificacion(), '%edad%' => $divisionReserva->getDivision()->getEdadMinima()]))
                            );
                            $error = true;
                        }
                    }
                }
            }
        }
        if (!$error) {
            return true;
        }
        return false;
    }

    public function validarInscritosOrganizacion($reserva, $request, $form) {
        $servicio = $this->container->get('app.logic_twig_extension');
        $usuarios = [];
        $error = false;
        foreach ($reserva->getDivisiones() as $divisionReserva) {
            foreach ($divisionReserva->getDivisionReservas() as $usuarioDivisionReserva) {
                $this->em->remove($usuarioDivisionReserva);
                $this->em->flush();
            }
            foreach ($request->get('reserva')['divisiones'] as $usuariosDivision) {
                if ($usuariosDivision['division'] == $divisionReserva->getDivision()->getId()) {
                    if (array_key_exists('usuarios', $usuariosDivision)) {
                        foreach ($usuariosDivision['usuarios'] as $iduser) {
                            $usuario = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneById($iduser);
                            $edad = $servicio->calcularEdad($usuario->getDateOfBirth());
                            if ($divisionReserva->getDivision()->getEdadMinima() > 0) {
                                if ($edad <= $divisionReserva->getDivision()->getEdadMinima()) {
                                    $form->addError(
                                            new FormError($this->trans->trans('error.reserva.usuario.pequeno', ["%nombre%" => $usuario->nombreCompleto(), '%edad%' => $divisionReserva->getDivision()->getEdadMinima()]))
                                    );
                                    $error = true;
                                }
                            }
                            $usuarioDivisionReserva = new UsuarioDivisionReserva();
                            $usuarioDivisionReserva->setUsuario($usuario);
                            $usuarioDivisionReserva->setDivisionReserva($divisionReserva);
                            $divisionReserva->addDivisionReserva($usuarioDivisionReserva);
                            $this->em->persist($divisionReserva);
                        }
                    }
                    foreach ($divisionReserva->getDivision()->getTiposReservaEscenarioDeportivo() as $tipoReserva) {
                        if ($tipoReserva->getTipoReserva()->getId() == $reserva->getTipoReserva()->getId()) {
                            foreach ($tipoReserva->getTipoReservaEscenarioDeportivoDivisiones() as $tipo) {
                                if ($divisionReserva->getDivision()->getId() == $tipo->getDivisionTipoReserva()->getId()) {
                                    if (count($divisionReserva->getDivisionReservas()) < $tipo->getUsuariosMinimos()) {
                                        $form->addError(
                                                new FormError($this->trans->trans('error.reserva.division.minimo', ["%division%" => $divisionReserva->getDivision()->getNombre(), '%minimo%' => $tipo->getUsuariosMinimos()]))
                                        );
                                        $error = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!$error) {
            return true;
        }
        return false;
    }

    public function validarDisponibilidadDia($division, $tipo_reserva, $dia, $horaInicioReservaValida, $horaFinalReservaValida, $array_dias) {

        if ($division->getTiposReservaEscenarioDeportivo() != null) {
            foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                if ($tiposReservaEscenarioDeportivo->getTipoReserva()->getId() == $tipo_reserva) {
                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tiposReservaEscenarioDeportivo) {
                        if ($tiposReservaEscenarioDeportivo != null) {
                            if ($tiposReservaEscenarioDeportivo->getDivision() != null) {
                                if ($tiposReservaEscenarioDeportivo->getDivision()->getId() != null) {
                                    if ($division->getId() == $tiposReservaEscenarioDeportivo->getDivision()->getId()) {

                                        $divisionDiasPreviosReserva = $tiposReservaEscenarioDeportivo->getDiasPreviosReserva();

                                        $horaActual = new \DateTime();
                                        $dayAdd = "+";
                                        $dayAdd = $dayAdd . (string) $divisionDiasPreviosReserva . " days";
                                        $horaActual->modify($dayAdd);
                                        $fechaActualValidate = strtotime($horaActual->format('Ymd'));


                                        $datetime = new \DateTime();
                                        $date = explode('-', $dia);
                                        $datetime->setDate($date[0], $date[1], $date[2]);
                                        $fechaInicioValidate = strtotime($datetime->format('Ymd'));

                                        if ($fechaInicioValidate >= $fechaActualValidate) {
                                            $divisionValida = true;
                                        } else {
                                            $divisionValida = false;
                                            $error = 2;
                                            $division->setDisponibilidad(false);
                                            $division->setErrorDisponibilidad($divisionDiasPreviosReserva);
                                            $division->setNumeroErrorDisponibilidad($error);
                                            break;
                                        }
                                        if ($divisionValida == true) {

                                            $tiempoMinimoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMinimo();
                                            $tiempoMaximoTipoReserva = $tiposReservaEscenarioDeportivo->getTiempoMaximo();
                                            $bloqueTiempoTipoReserva = $tiposReservaEscenarioDeportivo->getBloqueTiempo();

                                            $tiempoMinimoTipoReserva = $tiempoMinimoTipoReserva * $bloqueTiempoTipoReserva;
                                            $tiempoMaximoTipoReserva = $tiempoMaximoTipoReserva * $bloqueTiempoTipoReserva;
                                            $datetime1 = new \DateTime('2018-01-01 ' . $horaInicioReservaValida . ':00');
                                            $datetime2 = new \DateTime('2018-01-01 ' . $horaFinalReservaValida . ':00');
                                            $interval = $datetime1->diff($datetime2);

                                            $horas = $interval->format("%H%");
                                            $minutos = $interval->format("%I%");
                                            $minutosReserva = ($horas * 60) + $minutos;


                                            if ($tiempoMinimoTipoReserva > $minutosReserva || $minutosReserva > $tiempoMaximoTipoReserva) {
                                                $error = 53;

                                                $translator = $this->get('translator');

                                                $horas = floor($tiempoMinimoTipoReserva / 60);
                                                $minutos = floor($tiempoMinimoTipoReserva - ($horas * 60));
                                                if ($minutos < 10) {
                                                    $minutos = "0" . $minutos;
                                                }
                                                $tiempoMinimoTipoReserva = $horas . ":" . $minutos;

                                                $horas = floor($tiempoMaximoTipoReserva / 60);
                                                $minutos = floor($tiempoMaximoTipoReserva - ($horas * 60));
                                                if ($minutos < 10) {
                                                    $minutos = "0" . $minutos;
                                                }
                                                $tiempoMaximoTipoReserva = $horas . ":" . $minutos;

                                                $mensaje = $translator->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error53', array('%dia%' => $array_dias[date('l', strtotime($dia))], '%tiempoMinimo%' => $tiempoMinimoTipoReserva, '%tiempoMaximo%' => $tiempoMaximoTipoReserva));
                                                $division->setDisponibilidad(false);
                                                $division->setErrorDisponibilidad($mensaje);
                                                $division->setNumeroErrorDisponibilidad($error);
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $division;
    }

    public function obtenerReservas($fechasAConsultar, $escenario, $tipo_reserva, $divisiones, $hora_inicial, $hora_final) {

        $em = $this->getDoctrine()->getManager();

        $fechasDisponibles = array();
        $fechasNoDisponibles = array();
        $fechasMantenimiento = array();
        $hora_inicial = strtotime($hora_inicial);
        $hora_final = strtotime($hora_final);

        foreach ($fechasAConsultar as $fecha) {

            $divisionValida = array();
            $divisionNoValida = array();
            $datetime = new \DateTime();
            $date = explode('-', $fecha);
            $datetime->setDate($date[0], $date[1], $date[2]);
            $fechaAComparar = $datetime->format('Y/m/d');
            $fechaAComparar = strtotime($fechaAComparar);
            $minimoHoras = 0;
            $maximoHoras = 0;
            $diasPreviosReserva = 0;
            $fechaActualAComparar = new \DateTime();
            $fechaActualAComparar = $fechaActualAComparar->format('Y/m/d');
            $fechaActualAComparar = strtotime($fechaActualAComparar);


            if ($fechaAComparar < $fechaActualAComparar) {
                $error = 1;
            } else {
                $agregoFecha = false;
                foreach ($divisiones as $division) {
                    $reservas = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                                    ->innerJoin('reserva.escenarioDeportivo', 'escenarioDeportivo')
                                    ->join('reserva.divisiones', 'd')
                                    ->where('escenarioDeportivo.id = :escenario_deportivo')
                                    ->andWhere('reserva.id != :reserva_id')
                                    ->andWhere('reserva.estado != :rechazado')
                                    ->andWhere('reserva.estado != :prereserva')
                                    ->andWhere('reserva.fechaInicio <= :fecha')
                                    ->andWhere('reserva.fechaFinal >= :fecha')
                                    ->andWhere('reserva.tipoReserva = :tipoReserva')
                                    ->andWhere('d.division = :division')
                                    ->orderBy("reserva.id", 'ASC')
                                    ->setParameter('escenario_deportivo', $escenario->getId() ?: 0)
                                    ->setParameter('rechazado', 'Rechazado')
                                    ->setParameter('prereserva', 'Pre-Reserva')
                                    ->setParameter('reserva_id', 0)
                                    ->setParameter('fecha', $fecha ?: 0)
                                    ->setParameter('tipoReserva', $tipo_reserva ?: 0)
                                    ->setParameter('division', $division->getId() ?: 0)
                                    ->getQuery()->getResult();

                    $totalUsuariosReserva = 0;
                    foreach ($reservas as $res) {
                        if ($res->getTipoReserva() != null) {
                            if ($res->getTipoReserva()->getNombre() == 'Mantenimiento') {
                                $datetime = new \DateTime();
                                $date = explode('-', $fecha);
                                $datetime->setDate($date[0], $date[1], $date[2]);
                                array_push($fechasMantenimiento, array('fecha' => $datetime->format('Y/m/d')));
                                $agregoFecha = true;
                                break;
                            }
                            $horaInicioRes = strtotime($res->getHoraInicial()->format('H:i'));
                            $horaFinRes = strtotime($res->getHoraFinal()->format('H:i'));


                            if (
                                    ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes) ||
                                    ($horaInicioRes <= $hora_inicial && $hora_inicial <= $horaFinRes) ||
                                    ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes) ||
                                    ($horaInicioRes <= $hora_final && $hora_final <= $horaFinRes ) ||
                                    ($hora_inicial <= $horaInicioRes && $horaInicioRes <= $hora_final ) ||
                                    ($hora_inicial <= $horaInicioRes && $horaInicioRes <= $hora_final ) ||
                                    ($hora_inicial <= $horaFinRes && $horaFinRes <= $hora_final ) ||
                                    ($hora_inicial <= $horaFinRes && $horaFinRes <= $hora_final )
                            ) {

                                if ($escenario->getTipoEscenario()->getNombre() == "Piscina") {

                                    $totalUsuariosReserva += 10;

                                    $maximoUsuariosEscenario = 0;

                                    if ($division->getTiposReservaEscenarioDeportivo() != null) {
                                        foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                                            if ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() != null) {
                                                foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tipoReservaDivisiones) {
                                                    $maximoUsuariosEscenario += $tipoReservaDivisiones->getUsuariosMaximos();
                                                }
                                            }
                                        }
                                    }

                                    if ($totalUsuariosReserva >= $maximoUsuariosEscenario) {
                                        $error = 50;

                                        $datetime = new \DateTime();
                                        $date = explode('-', $fecha);
                                        $datetime->setDate($date[0], $date[1], $date[2]);

                                        array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'diasPrevios' => $diasPreviosReserva));
                                        $agregoFecha = true;
                                        break;
                                    }
                                } else {
                                    $datetime = new \DateTime();
                                    $date = explode('-', $fecha);
                                    $datetime->setDate($date[0], $date[1], $date[2]);
                                    $error = 5;
                                    array_push($fechasNoDisponibles, array('fecha' => $datetime->format('Y/m/d'), 'error' => $error, 'diasPrevios' => $diasPreviosReserva));
                                    $agregoFecha = true;
                                    break;
                                }
                            } else {
                                $tiempoLim = true;
                            }
                        }
                    }

                    if ($agregoFecha == true) {
                        break;
                    }
                }
                if ($agregoFecha == false) {
                    $datetime = new \DateTime();
                    $date = explode('-', $fecha);
                    $datetime->setDate($date[0], $date[1], $date[2]);
                    array_push($fechasDisponibles, array('fecha' => $datetime->format('Y/m/d')));
                }
            }
        }
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['disponible'] = $fechasDisponibles;
        $respuesta['noDisponible'] = $fechasNoDisponibles;
        $respuesta['mantenimiento'] = $fechasMantenimiento;
        return $respuesta;
    }

    public function reservasPorEscenariosAction(Request $request) {
        $form = (array) json_decode($request->request->get('form'));

        $respuesta = array();
        if (!$form['reserva']->escenario_deportivo) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.escenario');
            return $this->json($respuesta);
        }
        $reserva = (array) $form['reserva'];
        if (!array_key_exists("tipoReserva", $reserva)) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.tipo_reserva');
            return $this->json($respuesta);
        }
        if (!$form['reserva']->fechaInicio) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.fecha');
            return $this->json($respuesta);
        } else if (!$form['reserva']->fechaFinal) {
            $form['reserva']->fechaFinal = $form['reserva']->fechaInicio;
        }
        $programo = false;
        $disponible = false;
        $escenarioDeportivo = $this->em->getRepository('LogicBundle:EscenarioDeportivo')->findOneById($form['reserva']->escenario_deportivo);
        if (!$escenarioDeportivo->getDivisiones()) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.divisiones');
            return $this->json($respuesta);
        }
        $tipoReserva = false;
        foreach ($escenarioDeportivo->getDivisiones() as $division) {
            if ($division->getTiposReservaEscenarioDeportivo() != null) {
                foreach ($division->getTiposReservaEscenarioDeportivo() as $tiposReservaEscenarioDeportivo) {
                    foreach ($tiposReservaEscenarioDeportivo->getTipoReservaEscenarioDeportivoDivisiones() as $tipo) {
                        if ($tipo->getTipoReservaEscenarioDeportivo()->getTipoReserva()->getId() == $reserva['tipoReserva']) {
                            $tipoReserva = true;
                        }
                    }
                }
            }
        }
        if (!$tipoReserva) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.tipo_reserva');
            return $this->json($respuesta);
        }
        $diasPrevios = $this->em->getRepository("LogicBundle:Reserva")->comprobarDiasPrevios($escenarioDeportivo, $form['reserva']->fechaInicio, $form['reserva']->programaciones);
        if ($diasPrevios['error']) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $diasPrevios["mensaje"];
            return $this->json($respuesta);
        }
        $horarioManana = $this->em->getRepository('LogicBundle:Reserva')->getHorarios($escenarioDeportivo, true);
        $horarioTarde = $this->em->getRepository('LogicBundle:Reserva')->getHorarios($escenarioDeportivo, false);
        $query = $this->em->getRepository('LogicBundle:ProgramacionReserva')->createQueryBuilder('p');
        $query->join("p.reserva", "r")
                ->where('r.escenarioDeportivo = :escenario')
                ->andWhere('r.fechaInicio BETWEEN :fechaInicial AND :fechaFinal')
                ->andWhere('r.fechaFinal BETWEEN :fechaInicial AND :fechaFinal')
                ->andWhere('r.estado != :rechazado')
                ->andWhere('r.estado != :prereserva')
                ->andWhere('r.completada != :completada')
                ->setParameter("escenario", $escenarioDeportivo->getId())
                ->setParameter("fechaInicial", $form['reserva']->fechaInicio)
                ->setParameter("fechaFinal", $form['reserva']->fechaFinal)
                ->setParameter("rechazado", 'Rechazado')
                ->setParameter("prereserva", 'Pre-reserva')
                ->setParameter("completada", true);
        $dias = [];
        foreach ($form['reserva']->programaciones as $programacion) {
            $dia = $this->em->getRepository('LogicBundle:Dia')->findOneById($programacion->dia);
            if (!in_array($dia->getId(), $dias)) {
                $dias[] = $dia->getId();
            }
            $inicioManana = $programacion->inicioManana;
            $finManana = $programacion->finManana;
            $inicioTarde = $programacion->inicioTarde;
            $finTarde = $programacion->finTarde;

            if ($form['idReservaOculto'] > 0) {
                $query->andWhere("r.id != :id")
                        ->setParameter("id", $form['idReservaOculto']);
            }
            $consultar = true;
            if ((($inicioManana && $finManana) || ($inicioTarde && $finTarde)) && ($inicioManana != "hh:mm" && $finManana != "hh:mm" && $inicioTarde != "hh:mm" && $finTarde != "hh:mm")) {

                $im = new \DateTime($inicioManana);
                $im->modify("+1 second");
                $fm = new \DateTime($finManana);
                $fm->modify("-1 second");
                $it = new \DateTime($inicioTarde);
                $it->modify("+1 second");
                $ft = new \DateTime($finTarde);
                $ft->modify("-1 second");
                if ($inicioManana && $finManana) {
                    $manana = $horarioManana[$dia->getId()];
                    $inicio = new \DateTime($manana["inicio"]);
                    $fin = new \DateTime($manana["fin"]);
                    if ($im > $fm) {
                        $consultar = false;
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.mayor', ['%dia%' => $dia->getNombre(), '%horario%' => 'mañana']);
                        return $this->json($respuesta);
                    } else {
                        $query->andWhere(':inicioManana BETWEEN p.inicioManana AND p.finManana OR :finManana BETWEEN p.inicioManana AND p.finManana')
                                ->andWhere('p.inicioManana IS NOT NULL AND p.finManana IS NOT NULL')
                                ->setParameter('inicioManana', $im)
                                ->setParameter('finManana', $fm);
                    }
                    if (!$manana["inicio"] && !$manana["fin"]) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.no_disponible', ['%dia%' => $dia->getNombre()]);
                        return $this->json($respuesta);
                    }
                    if ($im < $inicio) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.escenario', ['%dia%' => $dia->getNombre(), '%hora%' => $manana["inicio"], '%jornada%' => 'inicial de la mañana', "%condicion%" => "menor"]);
                        return $this->json($respuesta);
                    }
                    if ($fm > $fin) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.escenario', ['%dia%' => $dia->getNombre(), '%hora%' => $manana["fin"], '%jornada%' => 'final de la mañana', "%condicion%" => "mayor"]);
                        return $this->json($respuesta);
                    }
                }
                if (($inicioTarde && $finTarde)) {
                    $tarde = $horarioTarde[$dia->getId()];
                    $inicio = new \DateTime($tarde["inicio"]);
                    $fin = new \DateTime($tarde["fin"]);
                    if ($it > $ft) {
                        $consultar = false;
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.mayor', ['%dia%' => $dia->getNombre(), '%horario%' => 'tarde']);
                        return $this->json($respuesta);
                    } else {
                        $query->andWhere(':inicioTarde BETWEEN p.inicioTarde AND p.finTarde OR :finTarde BETWEEN p.inicioTarde AND p.finTarde')
                                ->andWhere('p.inicioTarde IS NOT NULL AND p.finTarde IS NOT NULL')
                                ->setParameter('inicioTarde', $it)
                                ->setParameter('finTarde', $ft);
                    }
                    if (!$tarde["inicio"] && !$tarde["fin"]) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.no_disponible', ['%dia%' => $dia->getNombre()]);
                        return $this->json($respuesta);
                    }
                    if ($it < $inicio) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.escenario', ['%dia%' => $dia->getNombre(), '%hora%' => $tarde["inicio"], '%jornada%' => 'incial de la tarde', "%condicion%" => "menor"]);
                        return $this->json($respuesta);
                    }
                    if ($ft > $fin) {
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = $this->trans->trans('error.programar.hora.escenario', ['%dia%' => $dia->getNombre(), '%hora%' => $tarde["fin"], '%jornada%' => 'final de la tarde', "%condicion%" => "menor"]);
                        return $this->json($respuesta);
                    }
                }
                $programo = true;
            }
        }
        $query->andWhere('p.dia IN (:dias)')
                ->setParameter("dias", $dias);
        $programacionReserva = $query->getQuery()->getResult();
        if (!$programacionReserva) {
            $disponible = true;
        }
        if (!$programo) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.minimo');
            return $this->json($respuesta);
        }
        if (!$disponible) {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.programar.disponible');
            return $this->json($respuesta);
        }
        $ofertas = $this->em->getRepository('LogicBundle:Oferta')->buscarCruceReservas($form['reserva']);
        if ($ofertas) {
            $respuesta['warning'] = true;
            $respuesta['mensaje'] = $this->trans->trans('error.cruce.oferta');
        }
        if (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN') && $this->getUser()->hasRole('ROLE_PERSONANATURAL')) {
            $reservas = $this->em->getRepository('LogicBundle:Reserva')->buscarReservasTipoEscenario($form['reserva']->escenario_deportivo, $this->getUser());
            if ($reservas) {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = $this->trans->trans('error.reserva.pendiente', ['%tipo%' => $reservas[0]->getEscenarioDeportivo()->getTipoEscenario()]);
                return $this->json($respuesta);
            }
        }
        $respuesta['error'] = false;
        $respuesta['inicio'] = $form['reserva']->fechaInicio;
        $respuesta['fin'] = $form['reserva']->fechaFinal;
        $respuesta['no_disponibles'] = $this->em->getRepository("LogicBundle:BloqueoEscenario")->consultarBloqueos($escenarioDeportivo, $form['reserva']->fechaInicio, $form['reserva']->fechaFinal);
        $respuesta['mantenimientos'] = $this->em->getRepository("LogicBundle:Reserva")->consultarReservas($form['idReservaOculto'], $escenarioDeportivo, $form['reserva']->fechaInicio, $form['reserva']->fechaFinal, true);
        return $this->json($respuesta);
    }

    public function obtenerInformacionEscenarioAction(Request $request) {
        $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->findOneById($request->request->get('escenario_id'));
        $html = $this->renderView('AdminBundle:Reservas\Pasos:informacion_escenario.html.twig', [
            'escenario' => $escenarioDeportivo,
            'dias' => ["lunes" => "Lunes", "martes" => "Martes", "miercoles" => "Miércoles", "jueves" => "Jueves", "viernes" => "Viernes", "sabado" => "Sábado", "domingo" => "Domingo"],
        ]);
        return $this->json($html);
    }

}
