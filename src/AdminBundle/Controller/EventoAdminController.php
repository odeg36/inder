<?php

namespace AdminBundle\Controller;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use LogicBundle\Entity\CampoFormularioEvento;
use LogicBundle\Entity\CarneEvento;
use LogicBundle\Entity\EquipoEvento;
use LogicBundle\Entity\Evento;
use LogicBundle\Entity\EventoRol;
use LogicBundle\Entity\InformacionExtraUsuario;
use LogicBundle\Entity\JugadorEvento;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\Sancion;
use LogicBundle\Entity\SancionEvento;
use LogicBundle\Form\CarneType;
use LogicBundle\Form\EquipoEventoType;
use LogicBundle\Form\EventoType;
use LogicBundle\Form\ParticipanteEventoType;
use LogicBundle\Form\SancionesType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;
use function dump;
use function GuzzleHttp\json_encode;

class EventoAdminController extends CRUDController {

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
        return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
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

        return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => $id));
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
        if (null !== $preResponse) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->renderWithExtraParams($this->admin->getTemplate('list'), [
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
                        ], null);
    }

    /**
     * Show action.
     *
     * @param int|string|null $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function showAction($id = null) {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->admin->checkAccess('show', $object);

        $preResponse = $this->preShow($request, $object);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        return $this->renderWithExtraParams($this->admin->getTemplate('show'), [
                    'action' => 'show',
                    'object' => $object,
                    'elements' => $this->admin->getShow(),
                        ], null);
    }

    function configuracionAction(Request $request, $id) {
        if ($id == 0) {
            $evento = new Evento();
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
        }
        $personaporsexo = false;
        if ($evento->getNumeroMujeres() != null || $evento->getNumeroHombres() != null) {
            $personaporsexo = true;
        }
        $limitanteporedad = false;
        if ($evento->getEdadMayorQue() != null || $evento->getEdadMenorQue() != null) {
            $limitanteporedad = true;
        }
        $rol = $evento->getEventoRoles();
        $categoriaConsultada = array();
        $subCategoriaConsultada = array();
        foreach ($evento->getCategoriaSubcategorias() as $subcategoria) {
            foreach ($subcategoria->getSubcategorias() as $subcategoria2) {
                array_push($subCategoriaConsultada, $subcategoria2);
            }
            $categoriaConsultada[$subcategoria->getId()] = $subCategoriaConsultada;
        }

        $originalEventoRoles = new ArrayCollection();
        foreach ($evento->getEventoRoles() as $eventoRoles) {
            $originalEventoRoles[] = $eventoRoles->getUsuario();
        }

        $form = $this->createForm(EventoType::class, $evento, array(
            'paso' => 1,
            'personaporsexo' => $personaporsexo,
            'subcategorias' => $subCategoriaConsultada,
            'limitanteporedad' => $limitanteporedad,
            'eventoroles' => $originalEventoRoles,
            'em' => $this->container,
        ));


        $form->handleRequest($request);
        $tipoDesistema = $this->encuentraSistema($id);

        $imagen = $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $evento->getImagen();

        if ($form->isSubmitted()) {
            $disciplinaForm = $form->get("disciplina")->getData();
            $data = $request->request->all();
            $dataEvento = current($data);
            $eventoObject = $form->getData();
            if ($form->get('horaInicial')->getData() == "" || $form->get('horaInicial')->getData() == "Ingresar hora") {
                $form->get("horaInicial")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                );
            }

            if ($form->get('horaFinal')->getData() == "" || $form->get('horaInicial')->getData() == "Ingresar hora") {
                $form->get("horaFinal")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                );
            }

            if ($form->get('cupo')->getData() == "Equipos") {
                if ($form->get('numeroEquipos')->getData() == "") {
                    $form->get("numeroEquipos")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('numeroEquipos')->getData() < 0) {
                    $form->get("numeroEquipos")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                if ($form->get('participantesEquipoMinimo')->getData() == "") {
                    $form->get("participantesEquipoMinimo")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('participantesEquipoMinimo')->getData() < 0) {
                    $form->get("participantesEquipoMinimo")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                if ($form->get('participantesEquipoMaximo')->getData() == "") {
                    $form->get("participantesEquipoMaximo")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('participantesEquipoMaximo')->getData() < 0) {
                    $form->get("participantesEquipoMaximo")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }

                if ($form->get('participantesEquipoMinimo')->getData() > $form->get('participantesEquipoMaximo')->getData()) {
                    $form->get("participantesEquipoMinimo")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_mayor'))
                    );
                }
            }

            if ($form->get('cupo')->getData() == "Individual") {
                if ($form->get('numeroCupos')->getData() == "") {
                    $form->get("numeroCupos")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('numeroCupos')->getData() < 0) {
                    $form->get("numeroCupos")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
            }

            if ($form->get('personaporsexo')->getData() == true) {
                if ($form->get('numeroMujeres')->getData() == "") {
                    $form->get("numeroMujeres")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('numeroMujeres')->getData() < 0) {
                    $form->get("numeroMujeres")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                if ($form->get('numeroHombres')->getData() == "") {
                    $form->get("numeroHombres")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('numeroHombres')->getData() < 0) {
                    $form->get("numeroHombres")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                $personaporsexo = true;
            } else {
                $personaporsexo = false;
            }

            if ($form->get('limitanteporedad')->getData() == true) {
                if ($form->get('edadMayorQue')->getData() == "") {
                    $form->get("edadMayorQue")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('edadMayorQue')->getData() < 0) {
                    $form->get("edadMayorQue")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                if ($form->get('edadMenorQue')->getData() == "") {
                    $form->get("edadMenorQue")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('edadMenorQue')->getData() < 0) {
                    $form->get("edadMenorQue")->addError(
                            new FormError($this->trans->trans('formulario_evento.no_negativo'))
                    );
                }
                if ($form->get('edadMayorQue')->getData() > $form->get('edadMenorQue')->getData()) {
                    $form->get("edadMenorQue")->addError(
                            new FormError($this->trans->trans('formulario_evento.error.menorque'))
                    );
                }
                $limitanteporedad = true;
            } else {
                $limitanteporedad = false;
            }

            if ($evento->getFechaInicial() > $evento->getFechaFinal()) {
                $form->get("fechaInicial")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_fecha_inicial_mayor'))
                );
            }

            if ($evento->getFechaInicialInscripcion() > $evento->getFechaFinalInscripcion()) {
                $form->get("fechaInicialInscripcion")->addError(
                        new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_fecha_inicial_mayor'))
                );
            } else if ($evento->getFechaInicialInscripcion() == $evento->getFechaFinalInscripcion()) {
                $horaInicial = $form->get("horaInicial")->getData();
                $horaFinal = $form->get("horaFinal")->getData();
                $horaInicioformatoMilitar = date("H:i", strtotime($horaInicial));
                $horaFinalformatoMilitar = date("H:i", strtotime($horaFinal));
                if (!$horaInicial) {
                    $form->get("horaInicial")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_uno.error_vacio'))
                    );
                }
                if (!$horaFinal) {
                    $form->get("horaInicial")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_uno.error_vacio'))
                    );
                }
                if ($horaInicioformatoMilitar > $horaFinalformatoMilitar) {
                    $form->get("horaInicial")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_uno.error_hora_inicial_mayor'))
                    );
                }
            }
            $fechaActual = new DateTime();
            if ($form->get("fechaInicialInscripcion")->getData() != null) {
                $oldEvent = null;
                if ($id) {
                    $sql = "SELECT * FROM evento WHERE id = $id";
                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $oldEvent = $stmt->fetchAll()[0];
                    $fechaInicial = $oldEvent['fechainicial'];
                    $fechaFinal = $oldEvent['fechafinal'];
                    $inicioInscripciones = $oldEvent['fecha_inicial_inscripcion'];
                    $finInscripciones = $oldEvent['fecha_final_inscripcion'];
                }
                $fechaInicialInscripcion = $form->get("fechaInicialInscripcion")->getData()->format('Y-m-d');
                $fechaFinalInscripcion = $form->get("fechaFinalInscripcion")->getData()->format('Y-m-d');
                $fechaActualAComparar = new DateTime();
                $fechaActualAComparar = $fechaActualAComparar->format('Y-m-d');
                $fechainicialFormulario = $form->get("fechaInicial")->getData()->format('Y-m-d');
                $fechaFinalFormulario = $form->get("fechaFinal")->getData()->format('Y-m-d');


                if (($oldEvent == null || $inicioInscripciones != $fechaInicialInscripcion) && $fechaInicialInscripcion < $fechaActualAComparar) {
                    $form->get("fechaInicialInscripcion")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.campo'))
                    );
                }

                if (($oldEvent == null || $finInscripciones != $fechaFinalInscripcion) && $fechaFinalInscripcion < $fechaActualAComparar) {
                    $form->get("fechaFinalInscripcion")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.campo'))
                    );
                }


                if (($oldEvent == null || $fechaInicial != $fechainicialFormulario ) && $fechainicialFormulario < $fechaActualAComparar) {
                    $form->get("fechaInicial")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.campo'))
                    );
                }

                if (($oldEvent == null || $fechaFinal != $fechaFinalFormulario) && $fechaFinalFormulario < $fechaActualAComparar) {
                    $form->get("fechaFinal")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.campo'))
                    );
                }


                if ($fechainicialFormulario < $fechaInicialInscripcion) {
                    $form->get("fechaInicialInscripcion")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.inscripcion'))
                    );
                }

                if ($fechaFinalFormulario < $fechaFinalInscripcion) {
                    $form->get("fechaFinalInscripcion")->addError(
                            new FormError($this->trans->trans('formulario_evento.labels.error.inscripcion'))
                    );
                }
            }


            $em = $this->getDoctrine()->getManager();
            foreach ($form->get('usuarios')->getData() as $key => $usuarioRol) {
                if ($usuarioRol->getNumeroIdentificacion() != null && $usuarioRol->getTipoIdentificacion() != null) {

                    $usuario = $usuarioRol->getNumeroIdentificacion();

                    $usuarioConsultado = $this->em->getRepository("ApplicationSonataUserBundle:User")
                                    ->createQueryBuilder('usuario')
                                    ->where('usuario.numeroIdentificacion = :numero')
                                    ->setParameter('numero', $usuario)
                                    ->getQuery()->getOneOrNullResult();

                    if ($usuarioConsultado == null) {
                        $mensaje = $this->trans->trans('jugadorNoexisteUno');
                        $mensaje = $mensaje . " " . $usuario;
                        $mensaje = $mensaje . " " . $this->trans->trans('jugadorNoexisteDos');
                        $form->get("usuarios")->addError(new FormError($mensaje));
                    } else {
                        if (!$usuarioConsultado->getId()) {
                            $form->get("usuarios")->addError(
                                    new FormError($this->trans->trans('formulario_evento.error.usuario_no_existe'))
                            );
                        }
                    }
                } else {
                    $form->get("usuarios")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_uno.error_vacio'))
                    );
                }
            }

            if ($form->isValid()) {

                $arrayRolEventoForm = [];

                foreach ($form->get('usuarios')->getData() as $key => $usuarioRol) {


                    $usuario = $usuarioRol->getNumeroIdentificacion();

                    $usuarioConsultado = $this->em->getRepository("ApplicationSonataUserBundle:User")
                                    ->createQueryBuilder('usuario')
                                    ->where('usuario.numeroIdentificacion = :numero')
                                    ->setParameter('numero', $usuario)
                                    ->getQuery()->getOneOrNullResult();

                    if ($usuarioConsultado == null) {
                        $mensaje = $this->trans->trans('jugadorNoexisteUno');
                        $mensaje = $mensaje . " " . $usuario;
                        $mensaje = $mensaje . " " . $this->trans->trans('jugadorNoexisteDos');
                        $form->get("usuarios")->addError(new FormError($mensaje));
                    }

                    $eventoRol = $this->em->getRepository("LogicBundle:EventoRol")
                                    ->createQueryBuilder('eventoRol')
                                    ->where('eventoRol.evento = :evento')
                                    ->andWhere('eventoRol.usuario = :usuario')
                                    ->setParameter('evento', $evento->getId())
                                    ->setParameter('usuario', $usuarioConsultado->getId())
                                    ->getQuery()->getOneOrNullResult();

                    if (!$eventoRol) {
                        $eventoRol = new EventoRol();
                    }

                    $eventoRol->setEvento($evento);
                    $eventoRol->setUsuario($usuarioConsultado);
                    $eventoRol->setRol('Juez');
                    $em->persist($eventoRol);
                    $em->flush();

                    array_push($arrayRolEventoForm, $eventoRol->getId());
                }

                if ($form->get('direccion')->getData() !== null) {
                    $puntoAtencion = new PuntoAtencion();
                    $puntoAtencion->setDireccion($form->get('direccion')->getData());
                    $puntoAtencion->setBarrio($form->get('barrio')->getData());
                    $puntoAtencion->setLatitud($form->get('latitud')->getData());
                    $puntoAtencion->setLongitud($form->get('longitud')->getData());
                    $evento->setPuntoAtencion($puntoAtencion);
                    $this->em->persist($puntoAtencion);


                    $reservaPuntoAtencion = new Reserva();

                    $user = $this->getUser();

                    if ($disciplinaForm != null) {
                        $disciplina = $this->em->getRepository("LogicBundle:Disciplina")->find($disciplinaForm);
                        $reservaPuntoAtencion->setDisciplina($disciplina);
                    }

                    $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                    ->createQueryBuilder('tipoReserva')
                                    ->where('tipoReserva.nombre = :evento')
                                    ->setParameter('evento', 'Evento')
                                    ->getQuery()->getOneOrNullResult();

                    $fechaInicial = $form->get("fechaInicial")->getData();

                    $fechaFinal = $form->get("fechaFinal")->getData();

                    $horaInicial = $form->get("horaInicial")->getData();

                    $horaFinal = $form->get("horaFinal")->getData();

                    $reservaPuntoAtencion->setFechaInicio($fechaInicial);
                    $reservaPuntoAtencion->setFechaFinal($fechaFinal);
                    $reservaPuntoAtencion->setHoraInicial($horaInicial);
                    $reservaPuntoAtencion->setHoraFinal($horaFinal);
                    $reservaPuntoAtencion->setEstado('Pendiente');
                    $reservaPuntoAtencion->setPuntoAtencion($puntoAtencion);
                    $reservaPuntoAtencion->setUsuario($user);
                    $reservaPuntoAtencion->setTipoReserva($tipoReserva);


                    $this->em->persist($reservaPuntoAtencion);
                }

                foreach ($form->get('categoriaSubcategorias')->getData() as $subcategoria) {
                    $nuevasSubcategorias = array();
                    foreach ($subcategoria->getSubcategorias() as $subcategoria2) {
                        array_push($nuevasSubcategorias, $subcategoria2->getId());
                        if (!in_array($subcategoria2, $subCategoriaConsultada)) {
                            $subcategoria2->addCategoriaSubcategoria($subcategoria);
                            $this->em->persist($subcategoria2);
                        }
                    }
                    if ($subcategoria->getId() != null) {
                        if (array_key_exists($subcategoria->getId(), $categoriaConsultada)) {
                            foreach ($categoriaConsultada[$subcategoria->getId()] as $eliminarSubCategoria) {
                                if (in_array($eliminarSubCategoria->getId(), $nuevasSubcategorias) === false) {
                                    $subcategoria->removeSubcategoria($eliminarSubCategoria);
                                    $eliminarSubCategoria->removeCategoriaSubcategoria($subcategoria);
                                    $em->flush();
                                }
                            }
                        }
                    }
                }

                if ($form->get('escenarioDeportivo')->getData() != null) {
                    $reserva = new Reserva();

                    $user = $this->getUser();

                    $escenario = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($form->get("escenarioDeportivo")->getData());

                    $division = $this->em->getRepository("LogicBundle:Division")->find($form->get("division")->getData());

                    if ($disciplinaForm != null) {
                        $disciplina = $this->em->getRepository("LogicBundle:Disciplina")->find($disciplinaForm);
                        $reserva->setDisciplina($disciplina);
                    }

                    $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                    ->createQueryBuilder('tipoReserva')
                                    ->where('tipoReserva.nombre = :evento')
                                    ->setParameter('evento', 'Evento')
                                    ->getQuery()->getOneOrNullResult();

                    $fechaInicial = $form->get("fechaInicial")->getData();

                    $fechaFinal = $form->get("fechaFinal")->getData();

                    $horaInicial = $form->get("horaInicial")->getData();
                    $horaFinal = $form->get("horaFinal")->getData();

                    $reserva->setFechaInicio($fechaInicial);
                    $reserva->setFechaFinal($fechaFinal);
                    $reserva->setHoraInicial($horaInicial);
                    $reserva->setHoraFinal($horaFinal);
                    $reserva->setEstado('Pendiente');
                    $reserva->setDivision($division);
                    $reserva->setEscenarioDeportivo($escenario);
                    $reserva->setUsuario($user);
                    $reserva->setTipoReserva($tipoReserva);

                    $evento->setEscenarioDeportivo($escenario);
                    $this->em->persist($evento);
                    $this->em->persist($reserva);
                }


                if ($form->get('puntoAtencion')->getData() !== null) {


                    $reservaPuntoAtencion = new Reserva();

                    $user = $this->getUser();

                    $puntoAtencion = $this->em->getRepository("LogicBundle:PuntoAtencion")->find($form->get("puntoAtencion")->getData());

                    if ($disciplinaForm != null) {
                        $disciplina = $this->em->getRepository("LogicBundle:Disciplina")->find($disciplinaForm);
                        $reservaPuntoAtencion->setDisciplina($disciplina);
                    }

                    $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                    ->createQueryBuilder('tipoReserva')
                                    ->where('tipoReserva.nombre = :evento')
                                    ->setParameter('evento', 'Evento')
                                    ->getQuery()->getOneOrNullResult();

                    $fechaInicial = $form->get("fechaInicial")->getData();

                    $fechaFinal = $form->get("fechaFinal")->getData();

                    $horaInicial = $form->get("horaInicial")->getData();

                    $horaFinal = $form->get("horaFinal")->getData();

                    $reservaPuntoAtencion->setFechaInicio($fechaInicial);
                    $reservaPuntoAtencion->setFechaFinal($fechaFinal);
                    $reservaPuntoAtencion->setHoraInicial($horaInicial);
                    $reservaPuntoAtencion->setHoraFinal($horaFinal);
                    $reservaPuntoAtencion->setEstado('Pendiente');
                    $reservaPuntoAtencion->setPuntoAtencion($puntoAtencion);
                    $reservaPuntoAtencion->setUsuario($user);
                    $reservaPuntoAtencion->setTipoReserva($tipoReserva);

                    $evento->setPuntoAtencion($puntoAtencion);
                    $this->em->persist($evento);
                    $this->em->persist($reservaPuntoAtencion);
                }


                $this->em->persist($form->getData());
                $this->em->flush();
                $this->addFlash('sonata_flash_success', $this->trans('formulario_evento.labels.exito.eventoguardado'));
                return $this->redirectToRoute('admin_logic_evento_carne', array('id' => $evento->getId()));
            }
        }

        return $this->render('AdminBundle:Evento/Tabs:configuracion.html.twig', array(
                    'form' => $form->createView(),
                    'idevento' => $id,
                    'cupos' => $evento->getCupo(),
                    'personaporsexo' => $personaporsexo,
                    'limitanteporedad' => $limitanteporedad,
                    'tipoSistema' => $tipoDesistema,
                    'evento' => $evento,
                    'nombreEvento' => $evento->getNombre(),
                    'imagenEvento' => $imagen
        ));
    }

    function carneAction(Request $request, $id) {
        $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
        if (!$evento) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        }

        $carneEvento = $evento->getCarne();
        if (!$evento->getCarne()) {
            $carneEvento = new CarneEvento();
            $carneEvento->setEvento($evento);
        }
        $imagenCarne = $carneEvento->getFile();
        $form = $this->createForm(CarneType::class, $carneEvento, array(
            'container' => $this->container,
            'evento' => $evento
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->validarCarne($form);
            if ($form->isValid()) {
                $carne = $form->getData();
                if(!$form->get("file")->getData() && $imagenCarne){
                    $carne->setFile($imagenCarne);
                }
                $this->em->persist($carne);
                $this->em->flush();
                $this->addFlash('sonata_flash_success', $this->trans('formulario_evento.labels.exito.carne'));
                return $this->redirectToRoute('admin_logic_evento_inscripcion', array('id' => $evento->getId()));
            }
        }

        return $this->render('AdminBundle:Evento/Tabs:carne.html.twig', array(
                    'form' => $form->createView(),
                    'evento' => $evento,
        ));
    }

    function validarCarne($form) {
        $seleccion = false;
        if ($form->get("mostrarNombre")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarEquipo")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarEvento")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarColegio")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarComuna")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarFechaNacimiento")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarDeporte")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarRama")->getData()) {
            $seleccion = true;
        }
        if ($form->get("mostrarRol")->getData()) {
            $seleccion = true;
        }
        if (!$seleccion) {
            $this->addFlash('sonata_flash_error', $this->trans('error.seleccion.minima'));
            $form->addError(new FormError($this->trans->trans('error.seleccion.minima')));
        }
    }

    function inscripcionAction(Request $request, $id) {

        $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
        if (!$evento) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        }

        $form = $this->createForm(EventoType::class, $evento, array(
            'paso' => 2,
            'em' => $this->container,
        ));

        $form->handleRequest($request);

        $originalInscripcionPublica = [];
        $originalPreInscripcionPublica = [];
        $originalFormularioGanador = [];
        $originalFormularioRecambios = [];

        $originalInscripcionPublicaEvento = [];
        $originalPreInscripcionPublicaEvento = [];
        $originalFormularioGanadorEvento = [];
        $originalFormularioRecambiosEvento = [];
        foreach ($evento->getCampoFormularioEventos() as $inscripcionPublica) {
            if ($inscripcionPublica->getPertenece() == "Inscripcion Publica") {
                $originalInscripcionPublica[] = ["campoFormularioEvento" => $inscripcionPublica->getCampoEvento()->getId(), "inscripcionPublica" => $inscripcionPublica];
                $originalInscripcionPublicaEvento[] = ["campoFormularioEvento" => $inscripcionPublica->getCampoEvento()->getNombre()];
            } else if ($inscripcionPublica->getPertenece() == "Pre-Inscripcion Publica") {
                $originalPreInscripcionPublica[] = ["campoFormularioEvento" => $inscripcionPublica->getCampoEvento()->getId(), "preInscripcionPublica" => $inscripcionPublica];
            } else if ($inscripcionPublica->getPertenece() == "Formulario Ganador") {
                $originalFormularioGanador[] = ["campoFormularioEvento" => $inscripcionPublica->getCampoEvento()->getId(), "formularioGanador" => $inscripcionPublica];
            } else if ($inscripcionPublica->getPertenece() == "Formulario Recambio") {
                $originalFormularioRecambios[] = ["campoFormularioEvento" => $inscripcionPublica->getCampoEvento()->getId(), "formularioRecambios" => $inscripcionPublica];
            }
        }

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $formInscripcionPublica = array();
            // Validar Inscripcion Publica
            if ($evento->getTieneInscripcionPublica() == true) {
                $arrayCampoFormularioEvento = [];
                foreach ($form->get('checkTieneInscripcionPublica')->getData() as $key => $inscripcionPublica) {
                    $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                    ->createQueryBuilder('campoFormularioEvento')
                                    ->where('campoFormularioEvento.evento = :evento')
                                    ->andWhere('campoFormularioEvento.campoEvento = :campoEvento')
                                    ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                    ->setParameter('evento', $evento->getId())
                                    ->setParameter('campoEvento', $inscripcionPublica->getId())
                                    ->setParameter('pertenece', "Inscripcion Publica")
                                    ->getQuery()->getOneOrNullResult();

                    if (!$campoFormularioEvento) {
                        $campoFormularioEvento = new CampoFormularioEvento();
                    }
                    $campoFormularioEvento->setEvento($evento);
                    $campoFormularioEvento->setCampoEvento($inscripcionPublica);
                    $campoFormularioEvento->setPertenece('Inscripcion Publica');
                    $em->persist($campoFormularioEvento);
                    $em->flush();

                    array_push($arrayCampoFormularioEvento, $inscripcionPublica->getId());
                }
                foreach ($originalInscripcionPublica as $oriInscripcionPublica) {
                    if (array_search($oriInscripcionPublica['campoFormularioEvento'], $arrayCampoFormularioEvento) === false) {
                        $em->remove($oriInscripcionPublica['inscripcionPublica']);
                        $em->flush();
                    }
                }
            } else {
                $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                ->createQueryBuilder('campoFormularioEvento')
                                ->where('campoFormularioEvento.evento = :evento')
                                ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                ->setParameter('evento', $evento->getId())
                                ->setParameter('pertenece', "Inscripcion Publica")
                                ->getQuery()->getResult();

                foreach ($campoFormularioEvento as $campoEliminar) {
                    $em->remove($campoEliminar);
                    $em->flush();
                }
            }

            // Validar Pre-Inscripcion Publica
            if ($evento->getTienePreinscripcionPublica() == true) {
                $arrayCampoFormularioEvento = [];
                foreach ($form->get('checkTienePreinscripcionPublica')->getData() as $key => $inscripcionPublica) {
                    $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                    ->createQueryBuilder('campoFormularioEvento')
                                    ->where('campoFormularioEvento.evento = :evento')
                                    ->andWhere('campoFormularioEvento.campoEvento = :campoEvento')
                                    ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                    ->setParameter('evento', $evento->getId())
                                    ->setParameter('campoEvento', $inscripcionPublica->getId())
                                    ->setParameter('pertenece', "Pre-Inscripcion Publica")
                                    ->getQuery()->getOneOrNullResult();

                    if (!$campoFormularioEvento) {
                        $campoFormularioEvento = new CampoFormularioEvento();
                    }
                    $campoFormularioEvento->setEvento($evento);
                    $campoFormularioEvento->setCampoEvento($inscripcionPublica);
                    $campoFormularioEvento->setPertenece('Pre-Inscripcion Publica');
                    $em->persist($campoFormularioEvento);
                    $em->flush();

                    array_push($arrayCampoFormularioEvento, $inscripcionPublica->getId());
                }
                foreach ($originalPreInscripcionPublica as $oriInscripcionPublica) {
                    if (array_search($oriInscripcionPublica['campoFormularioEvento'], $arrayCampoFormularioEvento) === false) {
                        $em->remove($oriInscripcionPublica['preInscripcionPublica']);
                        $em->flush();
                    }
                }
            } else {
                $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                ->createQueryBuilder('campoFormularioEvento')
                                ->where('campoFormularioEvento.evento = :evento')
                                ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                ->setParameter('evento', $evento->getId())
                                ->setParameter('pertenece', "Pre-Inscripcion Publica")
                                ->getQuery()->getResult();

                foreach ($campoFormularioEvento as $campoEliminar) {
                    $em->remove($campoEliminar);
                    $em->flush();
                }
            }

            // Validar Formulario Ganador
            if ($evento->getTieneFormularioGanador() == true) {
                $arrayCampoFormularioEvento = [];
                foreach ($form->get('checkTieneFormularioGanador')->getData() as $key => $inscripcionPublica) {
                    $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                    ->createQueryBuilder('campoFormularioEvento')
                                    ->where('campoFormularioEvento.evento = :evento')
                                    ->andWhere('campoFormularioEvento.campoEvento = :campoEvento')
                                    ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                    ->setParameter('evento', $evento->getId())
                                    ->setParameter('campoEvento', $inscripcionPublica->getId())
                                    ->setParameter('pertenece', "Formulario Ganador")
                                    ->getQuery()->getOneOrNullResult();


                    if (!$campoFormularioEvento) {
                        $campoFormularioEvento = new CampoFormularioEvento();
                    }
                    $campoFormularioEvento->setEvento($evento);
                    $campoFormularioEvento->setCampoEvento($inscripcionPublica);
                    $campoFormularioEvento->setPertenece('Formulario Ganador');
                    $em->persist($campoFormularioEvento);
                    $em->flush();

                    array_push($arrayCampoFormularioEvento, $inscripcionPublica->getId());
                }
                foreach ($originalFormularioGanador as $oriInscripcionPublica) {
                    if (array_search($oriInscripcionPublica['campoFormularioEvento'], $arrayCampoFormularioEvento) === false) {
                        $em->remove($oriInscripcionPublica['formularioGanador']);
                        $em->flush();
                    }
                }
            } else {
                $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                ->createQueryBuilder('campoFormularioEvento')
                                ->where('campoFormularioEvento.evento = :evento')
                                ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                ->setParameter('evento', $evento->getId())
                                ->setParameter('pertenece', "Formulario Ganador")
                                ->getQuery()->getResult();

                foreach ($campoFormularioEvento as $campoEliminar) {
                    $em->remove($campoEliminar);
                    $em->flush();
                }
            }

            // Validar Formulario Recambio
            if ($evento->getTieneFormularioRecambios() == true) {
                $arrayCampoFormularioEvento = [];
                foreach ($form->get('checkTieneFormularioRecambios')->getData() as $key => $inscripcionPublica) {
                    $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                    ->createQueryBuilder('campoFormularioEvento')
                                    ->where('campoFormularioEvento.evento = :evento')
                                    ->andWhere('campoFormularioEvento.campoEvento = :campoEvento')
                                    ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                    ->setParameter('evento', $evento->getId())
                                    ->setParameter('campoEvento', $inscripcionPublica->getId())
                                    ->setParameter('pertenece', "Formulario Recambio")
                                    ->getQuery()->getOneOrNullResult();

                    if (!$campoFormularioEvento) {
                        $campoFormularioEvento = new CampoFormularioEvento();
                    }
                    $campoFormularioEvento->setEvento($evento);
                    $campoFormularioEvento->setCampoEvento($inscripcionPublica);
                    $campoFormularioEvento->setPertenece('Formulario Recambio');
                    $em->persist($campoFormularioEvento);
                    $em->flush();

                    array_push($arrayCampoFormularioEvento, $inscripcionPublica->getId());
                }
                foreach ($originalFormularioRecambios as $oriInscripcionPublica) {
                    if (array_search($oriInscripcionPublica['campoFormularioEvento'], $arrayCampoFormularioEvento) === false) {
                        $em->remove($oriInscripcionPublica['formularioRecambios']);
                        $em->flush();
                    }
                }
            } else {
                $campoFormularioEvento = $this->em->getRepository("LogicBundle:CampoFormularioEvento")
                                ->createQueryBuilder('campoFormularioEvento')
                                ->where('campoFormularioEvento.evento = :evento')
                                ->andWhere('campoFormularioEvento.pertenece = :pertenece')
                                ->setParameter('evento', $evento->getId())
                                ->setParameter('pertenece', "Formulario Recambio")
                                ->getQuery()->getResult();

                foreach ($campoFormularioEvento as $campoEliminar) {
                    $em->remove($campoEliminar);
                    $em->flush();
                }
            }

            $em->persist($evento);
            $em->flush();

            return $this->redirectToRoute('admin_logic_evento_equiposParticipantes', array('id' => $evento->getId()));
        }

        $originalInscripcionPublicaEvento = json_encode($originalInscripcionPublicaEvento);

        return $this->render('AdminBundle:Evento/Tabs:inscripcion.html.twig', array(
                    'form' => $form->createView(),
                    'idevento' => $id,
                    'evento' => $evento,
                    'nombreEvento' => $evento->getNombre(),
                    'inscripcionPublica' => $evento->getTieneInscripcionPublica(),
                    'preInscripcionPublica' => $evento->getTienePreinscripcionPublica(),
                    'formularioGanador' => $evento->getTieneFormularioGanador(),
                    'formularioRecambios' => $evento->getTieneFormularioRecambios(),
                    'originalInscripcionPublica' => $originalInscripcionPublicaEvento
        ));
    }

    function equiposParticipantesAction(Request $request, $id) {

        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
            if ($evento === null) {
                return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
            }
        }

        if ($evento->getCupo() == 'Equipos') {
            return $this->redirectToRoute('admin_logic_equipoevento_list', array('id' => $evento->getId()));
        } else {
            return $this->redirectToRoute('admin_logic_jugadorevento_list', array('id' => $evento->getId()));
        }
    }

    function sancionesAction(Request $request, $id) {

        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
            if ($evento === null) {
                return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
            }
        }

        $sancionEventoEditar;
        if ($request->get('editar') != 0) {
            //sancion evento actualizar para  mostrar sacion evento
            $sancionEvento = $this->em->getRepository("LogicBundle:SancionEvento")->find($request->get('editar'));
            //aca encuentro la sancion que voy a setear para editar
            $sancionEventoEditar = $sancionEvento->getSancion();
            //puntaje 
            $puntaje = $sancionEvento->getPuntajeJuegoLimpio();
            $existente = "Existente";
        } else {
            $sancionEventoEditar = null;
            $existente = "Nuevo";
            $puntaje = null;
        }

        $sancionEvento = new SancionEvento();

        $form = $this->createForm(SancionesType::class, $sancionEvento, array(
            'em' => $this->container,
            'sancionEventoEditar' => $sancionEventoEditar,
            'existente' => $existente,
            'puntos' => $puntaje,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('seleccion')->getData() == "Existente") {
                if ($form->get('sancion')->getData() == "") {
                    $form->get("sancion")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('puntaje_juego_limpio')->getData() == "") {
                    $form->get("puntaje_juego_limpio")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
            } else if ($form->get('seleccion')->getData() == "Nuevo") {
                if ($form->get('nombre')->getData() == "") {
                    $form->get("nombre")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('tipo_falta')->getData() == "") {
                    $form->get("tipo_falta")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('puntaje_juego_limpio')->getData() == "") {
                    $form->get("puntaje_juego_limpio")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
                if ($form->get('descripcion')->getData() == "") {
                    $form->get("descripcion")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.no_vacio'))
                    );
                }
            }

            if ($form->isValid()) {
                if ($form->get('seleccion')->getData() == "Nuevo") {

                    $sancion = new Sancion();
                    $sancion->setNombre($form->get('nombre')->getData());
                    $sancion->setDescripcion($form->get('descripcion')->getData());
                    $sancion->setTipoFalta($form->get('tipo_falta')->getData());

                    $this->em->persist($sancion);

                    $sancionEvento = new SancionEvento();
                    $sancionEvento->setPuntajeJuegoLimpio($form->get('puntaje_juego_limpio')->getData());
                    $sancionEvento->setEvento($evento);
                    $sancionEvento->setSancion($sancion);

                    $this->em->persist($sancionEvento);
                    $this->em->flush();

                    return $this->redirectToRoute('admin_logic_evento_sanciones', array('id' => $evento->getId()));
                } else if ($form->get('seleccion')->getData() == "Existente") {

                    $sancionObjeto = $form->get('sancion')->getData();
                    $sancionEvento = $this->em->getRepository("LogicBundle:SancionEvento")
                                    ->createQueryBuilder('sancionEvento')
                                    ->where('sancionEvento.sancion = :sancion')
                                    ->andWhere('sancionEvento.evento = :evento')
                                    ->setParameter('sancion', $sancionObjeto)
                                    ->setParameter('evento', $evento)
                                    ->getQuery()->getOneOrNullResult();

                    if (!$sancionEvento) {
                        $sancionEvento = new SancionEvento();
                    }

                    $sancionEvento->setPuntajeJuegoLimpio($form->get('puntaje_juego_limpio')->getData());
                    $sancionEvento->setEvento($evento);
                    $sancionEvento->setSancion($sancionObjeto);

                    $this->em->persist($sancionEvento);
                    $this->em->flush();

                    return $this->redirectToRoute('admin_logic_evento_sanciones', array('id' => $evento->getId()));
                }
            }
        }
        //obtiene todas las sanciones relacionadas a un evento y las pasa a twig para hacer un foreach
        $sancionesMostrar = $evento->getSancionEvento();

        if (count($sancionesMostrar) == 0) {

            $validadorsancionesMostrar = 0;
        } else {

            $validadorsancionesMostrar = count($sancionesMostrar);
        }


        return $this->render('AdminBundle:Evento/EventoSanciones:eventoSanciones.html.twig', array(
                    'form' => $form->createView(),
                    'idevento' => $id,
                    'nombreEvento' => $evento->getNombre(),
                    'sancionesMostrar' => $sancionesMostrar,
                    'validadorsancionesMostrar' => $validadorsancionesMostrar,
        ));
    }

    function clasificacionCalendarioAction(Request $request, $id) {
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
            if ($evento === null) {
                return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
            }
        }
        //el siguiente codigo es para verificar a donde va si esta guardado el evento y el sistema de juego
        $sisUno = $this->em->getRepository("LogicBundle:SistemaJuegoUno")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $evento)
                        ->getQuery()->getOneOrNullResult();

        if ($sisUno) {
            if ($sisUno->getTipoSistema() == "Escalera") {
                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 1));
            }

            if ($sisUno->getTipoSistema() == "Piramide") {

                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 2));
            }

            if ($sisUno->getTipoSistema() == "Chimenea") {
                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 3));
            }
        }


        $sisDos = $this->em->getRepository("LogicBundle:SistemaJuegoDos")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $evento)
                        ->getQuery()->getOneOrNullResult();


        if ($sisDos) {
            return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $evento->getId(), 'tipo' => 4));
        }


        $sisCuatro = $this->em->getRepository("LogicBundle:SistemaJuegoCuatro")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $evento)
                        ->getQuery()->getOneOrNullResult();


        if ($sisCuatro) {
            return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => 6));
        }


        $sistres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $evento)
                        ->getQuery()->getResult();

        if ($sistres) {
            return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $evento->getId(), 'tipo' => 5));
        }



        if ($evento->getEscenarioDeportivo() != null || $evento->getPuntoAtencion() != null) {
            return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 0));
        } else {
            return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 1));
        }
    }

    function carnesAction(Request $request, $id) {
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($id);
            if ($evento === null) {
                return $this->redirectToRoute('admin_logic_evento_configuracion', array('id' => 0));
            }
        }


        return $this->redirectToRoute('admin_logic_jugadorevento_listaCarnes', array(
                    'id' => $evento->getId(),
                    'documentacion' => true)
        );
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

    function encuentraSistema($id) {

        $sisUno = $this->em->getRepository("LogicBundle:SistemaJuegoUno")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $id)
                        ->getQuery()->getOneOrNullResult();

        if ($sisUno) {
            return $sisUno->getTipoSistema();
        }


        $sisDos = $this->em->getRepository("LogicBundle:SistemaJuegoDos")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $id)
                        ->getQuery()->getOneOrNullResult();


        if ($sisDos) {
            return $sisDos->getTipoSistema();
        }


        $sisCuatro = $this->em->getRepository("LogicBundle:SistemaJuegoCuatro")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $id)
                        ->getQuery()->getOneOrNullResult();


        if ($sisCuatro) {
            return $sisCuatro->getTipoSistema();
        }


        $sistres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                        ->createQueryBuilder('c')
                        ->where('c.evento = :evento')
                        ->setParameter('evento', $id)
                        ->getQuery()->getResult();

        if ($sistres) {
            return "Eliminatoria";
        }
    }

    function inscripcionUsuarioNaturalAction(Request $request) {
        $request = $this->getRequest();
        $id = $request->get("idEvento");
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $evento = $this->em->getRepository('LogicBundle:Evento')->find($id);

        if ($evento != null) {

            $fechaInicialInscripcion = $evento->getFechaInicialInscripcion()->format('Y-m-d');
            $fechaInicialInscripcion = strtotime($fechaInicialInscripcion);

            $fechaFinalInscripcion = $evento->getFechaFinalInscripcion()->format('Y-m-d');
            $fechaFinalInscripcion = strtotime($fechaFinalInscripcion);

            $fechaActualAComparar = new DateTime();
            $fechaActualAComparar = $fechaActualAComparar->format('Y-m-d');
            $fechaActualAComparar = strtotime($fechaActualAComparar);


            if ($fechaInicialInscripcion <= $fechaActualAComparar && $fechaActualAComparar <= $fechaFinalInscripcion) {
                foreach ($evento->getJugadorEventos() as $jugadores) {
                    if ($jugadores->getUsuarioJugadorEvento() == $this->getUser()) {
                        $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.existente"));
                        return $this->redirectToRoute('admin_logic_evento_list');
                    }
                }

                if ($evento->getCupo() == "Individual") {
                    if (count($evento->getJugadorEventos()) <= $evento->getNumeroCupos()) {
                        if ($this->getUser()->getdateOfBirth() != null) {
                            $currentDate = new DateTime('now');
                            $birthDay = $this->getUser()->getdateOfBirth();
                            $interval = $birthDay->diff($currentDate);
                            $edad = $interval->format('%y');

                            //validacion para la edad minima del evento
                            if ($evento->getEdadMayorQue() != null) {
                                if ($edad >= $evento->getEdadMayorQue()) {
                                    
                                } else {
                                    $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorEdadMayor"));
                                    return $this->redirectToRoute('admin_logic_evento_list');
                                }
                            }

                            //validacion para edad maxima del evento
                            if ($evento->getEdadMenorQue() != null) {
                                if ($edad <= $evento->getEdadMenorQue()) {
                                    
                                } else {
                                    $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorEdadMenor"));
                                    return $this->redirectToRoute('admin_logic_evento_list');
                                }
                            }
                        } else {
                            $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorEdadVacia"));
                            return $this->redirectToRoute('admin_logic_evento_list');
                        }
                    } else {
                        $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorCupo"));
                        return $this->redirectToRoute('admin_logic_evento_list');
                    }

                    $jugadorEvento = new JugadorEvento();

                    $form = $this->createForm(ParticipanteEventoType::class, $jugadorEvento, array(
                        'em' => $this->container,
                        'usuario' => $user,
                        'evento' => $evento
                    ));

                    $form->handleRequest($request);
                    if ($form->isSubmitted()) {

                        $jugadorEventoQuery = $em->getRepository("LogicBundle:JugadorEvento")
                                        ->createQueryBuilder('jugador')
                                        ->where('jugador.evento = :evento_id')
                                        ->andWhere('jugador.usuarioJugadorEvento = :id_usuario')
                                        ->setParameter('evento_id', $id)
                                        ->setParameter('id_usuario', $this->getUser()->getId())
                                        ->getQuery()->getOneOrNullResult();

                        if ($jugadorEventoQuery != null) {
                            $form->get("usuarioJugadorEvento")->addError(
                                    new FormError($this->trans->trans('formulario_evento.existente'))
                            );
                        }
                        if ($form->isValid()) {
                            $usuario = $this->getUser();
                            $info = $em->getRepository("LogicBundle:InformacionExtraUsuario")
                                            ->createQueryBuilder('info')
                                            ->where('info.usuario = :usuario')
                                            ->setParameter('usuario', $usuario)
                                            ->getQuery()->getResult();
                            if ($form->get("observacion")->getData() != null) {
                                $observacion = $form->get("observacion")->getData();
                                $jugadorEvento->setObservacion($observacion);
                            }
                            if ($info == null) {
                                $informacionExtraObjeto = $form->get('informacionExtraUsuario')->getData();
                                $estatura = $informacionExtraObjeto->getEstatura();
                                $peso = $informacionExtraObjeto->getPeso();
                                $masaCorporal = $informacionExtraObjeto->getIndiceMasaCorporal();
                                $consumeBebidas = $informacionExtraObjeto->getConsumeBebidasAlcoholicas();
                                $desplazado = $informacionExtraObjeto->getDesplazado();
                                $tipoDesplazado = $informacionExtraObjeto->getTipoDesplazado();
                                $barrio = $informacionExtraObjeto->getBarrio();
                                $padeceEnfermedades = $informacionExtraObjeto->getPadeceEnfermedadesCronicas();
                                $consumeMedicamentos = $informacionExtraObjeto->getConsumeMedicamentos();
                                $tipoSangre = $informacionExtraObjeto->getTipoSangre();
                                $medioTransporte = $informacionExtraObjeto->getMedioTransporte();
                                $puntoRecoleccion = $informacionExtraObjeto->getPuntoRecoleccion();
                                $establecimientoEducativo = $informacionExtraObjeto->getEstablecimientoEducativo();
                                $nivelEscolaridad = $informacionExtraObjeto->getNivelEscolaridad();
                                $estrato = $informacionExtraObjeto->getEstrato();
                                $nombreClub = $informacionExtraObjeto->getNombreClubEquipo();
                                $nombreCarro = $informacionExtraObjeto->getNombreCarro();
                                $rama = $informacionExtraObjeto->getRama();
                                $rol = $informacionExtraObjeto->getRol();
                                $categoria = $informacionExtraObjeto->getCategoria();
                                $ocupacion = $informacionExtraObjeto->getOcupacion();
                                $jefeHogar = $informacionExtraObjeto->getJefeCabezaHogar();
                                $telefono = $informacionExtraObjeto->getTelefonoContacto();
                                $gradoCursa = $informacionExtraObjeto->getGradoCursa();
                                $tallaPantalon = $informacionExtraObjeto->getTallaPantalon();
                                $tallaCamisa = $informacionExtraObjeto->getTallaCamisa();
                                $tallaZapatos = $informacionExtraObjeto->getTallaZapatos();
                                $numeroMatricula = $informacionExtraObjeto->getNumeroMatricula();
                                $municipio = $informacionExtraObjeto->getMunicipio();
                                $direccion = $informacionExtraObjeto->getDireccion();
                                $sexo = $informacionExtraObjeto->getSexo();
                                $discapacitado = $informacionExtraObjeto->getDiscapacitado();
                                $tipoDiscapacidad = $informacionExtraObjeto->getTipoDiscapacidad();
                                $subTipoDiscapacidad = $informacionExtraObjeto->getSubDiscapacidad();
                                $adjuntarDocumentos = $informacionExtraObjeto->getAdjuntarDocumentos();
                                $correoElectronico = $informacionExtraObjeto->getCorreoElectronico();
                                $fechaNacimiento = $informacionExtraObjeto->getFechaNacimiento();
                                $fuma = $informacionExtraObjeto->getFuma();
                                $licenciaCiclismo = $informacionExtraObjeto->getLicenciaCiclismo();
                                $perteneceLGBTI = $informacionExtraObjeto->getPerteneceLGBTI();

                                $informacionExtra = new InformacionExtraUsuario;
                                $informacionExtra->setUsuario($usuario);

                                if ($estatura != null) {
                                    $informacionExtra->setEstatura($estatura);
                                }
                                if ($peso != null) {
                                    $informacionExtra->setPeso($peso);
                                }
                                if ($masaCorporal != null) {
                                    $informacionExtra->setIndiceMasaCorporal($masaCorporal);
                                }
                                if ($consumeBebidas != null) {
                                    $informacionExtra->setConsumeBebidasAlcoholicas($consumeBebidas);
                                }
                                if ($desplazado != null) {
                                    $informacionExtra->setDesplazado($desplazado);
                                }
                                if ($padeceEnfermedades != null) {
                                    $informacionExtra->setPadeceEnfermedadesCronicas($padeceEnfermedades);
                                }
                                if ($consumeMedicamentos != null) {
                                    $informacionExtra->setConsumeMedicamentos($consumeMedicamentos);
                                }
                                if ($tipoSangre != null) {
                                    $informacionExtra->setTipoSangre($tipoSangre);
                                }
                                if ($medioTransporte != null) {
                                    $informacionExtra->setMedioTransporte($medioTransporte);
                                }
                                if ($puntoRecoleccion != null) {
                                    $informacionExtra->setPuntoRecoleccion($puntoRecoleccion);
                                }
                                if ($nombreClub != null) {
                                    $informacionExtra->setNombreClubEquipo($nombreClub);
                                }
                                if ($nombreCarro != null) {
                                    $informacionExtra->setNombreCarro($nombreCarro);
                                }
                                if ($rama != null) {
                                    $informacionExtra->setRama($rama);
                                }
                                if ($rol != null) {
                                    $informacionExtra->setRol($rol);
                                }
                                if ($categoria != null) {
                                    $informacionExtra->setCategoria($categoria);
                                }
                                if ($jefeHogar != null) {
                                    $informacionExtra->setJefeCabezaHogar($jefeHogar);
                                }
                                if ($telefono != null) {
                                    $informacionExtra->setTelefonoContacto($telefono);
                                }
                                if ($gradoCursa != null) {
                                    $informacionExtra->setGradoCursa($gradoCursa);
                                }
                                if ($tallaPantalon != null) {
                                    $informacionExtra->setTallaPantalon($tallaPantalon);
                                }
                                if ($tallaCamisa != null) {
                                    $informacionExtra->setTallaCamisa($tallaCamisa);
                                }
                                if ($tallaZapatos != null) {
                                    $informacionExtra->setTallaZapatos($tallaZapatos);
                                }
                                if ($numeroMatricula != null) {
                                    $informacionExtra->setNumeroMatricula($numeroMatricula);
                                }
                                if ($direccion != null) {
                                    $informacionExtra->setDireccion($direccion);
                                }
                                if ($sexo != null) {
                                    $informacionExtra->setSexo($sexo);
                                }
                                if ($discapacitado != null) {
                                    $informacionExtra->setDiscapacitado($discapacitado);
                                }
                                if ($tipoDiscapacidad != null) {
                                    $informacionExtra->setTipoDiscapacidad($tipoDiscapacidad);
                                }
                                if ($subTipoDiscapacidad != null) {
                                    $informacionExtra->setSubDiscapacidad($subTipoDiscapacidad);
                                }
                                if ($adjuntarDocumentos != null) {
                                    $informacionExtra->setAdjuntarDocumentos($adjuntarDocumentos);
                                }
                                if ($correoElectronico != null) {
                                    $informacionExtra->setCorreoElectronico($correoElectronico);
                                }
                                if ($fechaNacimiento != null) {
                                    $informacionExtra->setFechaNacimiento($fechaNacimiento);
                                }
                                if ($fuma != null) {
                                    $informacionExtra->setFuma($fuma);
                                }
                                if ($licenciaCiclismo != null) {
                                    $informacionExtra->setLicenciaCiclismo($licenciaCiclismo);
                                }
                                if ($perteneceLGBTI != null) {
                                    $informacionExtra->setPerteneceLGBTI($perteneceLGBTI);
                                }
                                if ($tipoDesplazado != null) {
                                    $informacionExtra->setTipoDesplazado($tipoDesplazado);
                                }
                                if ($barrio != null) {
                                    $informacionExtra->setBarrio($barrio);
                                }
                                if ($establecimientoEducativo != null) {
                                    $informacionExtra->setEstablecimientoEducativo($establecimientoEducativo);
                                }
                                if ($nivelEscolaridad != null) {
                                    $informacionExtra->setNivelEscolaridad($nivelEscolaridad);
                                }
                                if ($estrato != null) {
                                    $informacionExtra->setEstrato($estrato);
                                }
                                if ($ocupacion != null) {
                                    $informacionExtra->setOcupacion($ocupacion);
                                }
                                if ($municipio != null) {
                                    $informacionExtra->setMunicipio($municipio);
                                }

                                $this->em->persist($informacionExtra);
                            }

                            if ($evento->getTieneInscripcionPublica() == true) {
                                $jugadorEvento->setEstado("Exitoso");
                                $jugadorEvento->setUsuarioJugadorEvento($usuario);
                                $jugadorEvento->setEvento($evento);
                                $this->em->persist($form->getData());
                                $this->em->flush();

                                $this->addFlash('sonata_flash_success', $this->trans("formulario_evento.datosAlcorreo"));
                                $this->addFlash('sonata_flash_success', $this->trans("formulario_evento.satisfactorio"));
                                return $this->redirectToRoute('admin_logic_evento_list');
                            } elseif ($evento->getTienePreinscripcionPublica() == true) {
                                $jugadorEvento->setEstado("Pendiente");
                                $jugadorEvento->setUsuarioJugadorEvento($usuario);
                                $jugadorEvento->setEvento($evento);
                                $this->em->persist($form->getData());
                                $this->em->flush();

                                $this->addFlash('sonata_flash_success', $this->trans("formulario_evento.datosAlcorreo"));
                                $this->addFlash('sonata_flash_success', $this->trans("formulario_evento.presatisfactorio"));
                                return $this->redirectToRoute('admin_logic_evento_list');
                            } else {
                                $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorIncripcionEvento"));
                                return $this->redirectToRoute('admin_logic_evento_list');
                            }
                        }
                    }
                    return $this->render('AdminBundle:Evento/Jugadores:participante_persona_natural.html.twig', array(
                                'form' => $form->createView(),
                    ));
                } else {
                    $participantesEquipo = $evento->getParticipantesEquipoMaximo();
                    $numeroEquipos = $evento->getNumeroEquipos();
                    $maximosParticipantes = ($participantesEquipo * $numeroEquipos);
                    if (count($evento->getJugadorEventos()) < $maximosParticipantes) {
                        if (count($evento->getEquipoEventos()) < $evento->getNumeroEquipos()) {
                            return $this->redirectToRoute('admin_logic_evento_inscripcionEquipoUsuarioNatural', array('idEvento' => $id));
                        } else {
                            $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorEquiposEvento"));
                            return $this->redirectToRoute('admin_logic_evento_list');
                        }
                    } else {
                        $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorJugadoresEvento"));
                        return $this->redirectToRoute('admin_logic_evento_list');
                    }
                }
            } else {
                $this->addFlash('sonata_flash_error', $this->trans("formulario_evento.errorRango"));
                return $this->redirectToRoute('admin_logic_evento_list');
            }
        } else {
            $this->addFlash('sonata_flash_error', 'Evento no encontrado');
            return $this->redirectToRoute('admin_logic_evento_list');
        }
    }

    function inscripcionEquipoUsuarioNaturalAction(Request $request) {
        $request = $this->getRequest();
        $id = $request->get("idEvento");
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $evento = $this->em->getRepository('LogicBundle:Evento')->find($id);

        $equipo = new EquipoEvento();
        $form = $this->createForm(EquipoEventoType::class, $equipo, array(
            'em' => $this->container,
            'usuario' => $user,
            'evento' => $evento
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validarEquipo($evento, $form);
            if ($form->isValid()) {
                $equipoEvento = $form->getData();
                $publica = false;

                foreach ($evento->getCampoFormularioEventos() as $value) {
                    $ins = $value->getPertenece();
                    if ($ins == "Inscripcion Publica") {
                        $publica = true;
                        break;
                    }
                }

                if ($publica == true) {
                    $equipoEvento->setEstado(1);
                } else {
                    $equipoEvento->setEstado(0);
                }

                $equipoEvento->setEvento($evento);
                $this->em->persist($equipoEvento);

                foreach ($form->get('jugadorEventos') as $jugadorEvento) {
                    $jugadorEquipoEvento = new JugadorEvento();
                    $usuario = $jugadorEvento->get("usuarioJugadorEvento")->getData();

                    $info = $em->getRepository("LogicBundle:InformacionExtraUsuario")
                                    ->createQueryBuilder('info')
                                    ->where('info.usuario = :usuario')
                                    ->setParameter('usuario', $usuario)
                                    ->getQuery()->getResult();

                    $jugadorEquipoEvento->setUsuarioJugadorEvento($usuario);
                    $jugadorEquipoEvento->setEvento($evento);
                    $jugadorEquipoEvento->setEquipoEvento($equipoEvento);

                    if ($jugadorEvento->get("observacion")->getData() != null) {
                        $observacion = $jugadorEvento->get("observacion")->getData();
                        $jugadorEquipoEvento->setObservacion($observacion);
                    }

                    if ($publica == true) {
                        $jugadorEquipoEvento->setEstado('Aprobado');
                    } else {
                        $jugadorEquipoEvento->setEstado('Pendiente');
                    }

                    if ($info == null) {
                        $informacionExtraObjeto = $jugadorEvento->get('informacionExtraUsuario')->getData();

                        $estatura = $informacionExtraObjeto->getEstatura();
                        $peso = $informacionExtraObjeto->getPeso();
                        $masaCorporal = $informacionExtraObjeto->getIndiceMasaCorporal();
                        $consumeBebidas = $informacionExtraObjeto->getConsumeBebidasAlcoholicas();
                        $desplazado = $informacionExtraObjeto->getDesplazado();
                        $tipoDesplazado = $informacionExtraObjeto->getTipoDesplazado();
                        $barrio = $informacionExtraObjeto->getBarrio();
                        $padeceEnfermedades = $informacionExtraObjeto->getPadeceEnfermedadesCronicas();
                        $consumeMedicamentos = $informacionExtraObjeto->getConsumeMedicamentos();
                        $tipoSangre = $informacionExtraObjeto->getTipoSangre();
                        $medioTransporte = $informacionExtraObjeto->getMedioTransporte();
                        $puntoRecoleccion = $informacionExtraObjeto->getPuntoRecoleccion();
                        $establecimientoEducativo = $informacionExtraObjeto->getEstablecimientoEducativo();
                        $nivelEscolaridad = $informacionExtraObjeto->getNivelEscolaridad();
                        $estrato = $informacionExtraObjeto->getEstrato();
                        $nombreClub = $informacionExtraObjeto->getNombreClubEquipo();
                        $nombreCarro = $informacionExtraObjeto->getNombreCarro();
                        $rama = $informacionExtraObjeto->getRama();
                        $rol = $informacionExtraObjeto->getRol();
                        $categoria = $informacionExtraObjeto->getCategoria();
                        $ocupacion = $informacionExtraObjeto->getOcupacion();
                        $jefeHogar = $informacionExtraObjeto->getJefeCabezaHogar();
                        $telefono = $informacionExtraObjeto->getTelefonoContacto();
                        $gradoCursa = $informacionExtraObjeto->getGradoCursa();
                        $tallaPantalon = $informacionExtraObjeto->getTallaPantalon();
                        $tallaCamisa = $informacionExtraObjeto->getTallaCamisa();
                        $tallaZapatos = $informacionExtraObjeto->getTallaZapatos();
                        $numeroMatricula = $informacionExtraObjeto->getNumeroMatricula();
                        $municipio = $informacionExtraObjeto->getMunicipio();
                        $direccion = $informacionExtraObjeto->getDireccion();
                        $sexo = $informacionExtraObjeto->getSexo();
                        $discapacitado = $informacionExtraObjeto->getDiscapacitado();
                        $tipoDiscapacidad = $informacionExtraObjeto->getTipoDiscapacidad();
                        $subTipoDiscapacidad = $informacionExtraObjeto->getSubDiscapacidad();
                        $adjuntarDocumentos = $informacionExtraObjeto->getAdjuntarDocumentos();
                        $correoElectronico = $informacionExtraObjeto->getCorreoElectronico();
                        $fechaNacimiento = $informacionExtraObjeto->getFechaNacimiento();
                        $fuma = $informacionExtraObjeto->getFuma();
                        $licenciaCiclismo = $informacionExtraObjeto->getLicenciaCiclismo();
                        $perteneceLGBTI = $informacionExtraObjeto->getPerteneceLGBTI();

                        $informacionExtra = new InformacionExtraUsuario;
                        $informacionExtra->setUsuario($usuario);

                        if ($estatura != null) {
                            $informacionExtra->setEstatura($estatura);
                        }
                        if ($peso != null) {
                            $informacionExtra->setPeso($peso);
                        }
                        if ($masaCorporal != null) {
                            $informacionExtra->setIndiceMasaCorporal($masaCorporal);
                        }
                        if ($consumeBebidas != null) {
                            $informacionExtra->setConsumeBebidasAlcoholicas($consumeBebidas);
                        }
                        if ($desplazado != null) {
                            $informacionExtra->setDesplazado($desplazado);
                        }
                        if ($padeceEnfermedades != null) {
                            $informacionExtra->setPadeceEnfermedadesCronicas($padeceEnfermedades);
                        }
                        if ($consumeMedicamentos != null) {
                            $informacionExtra->setConsumeMedicamentos($consumeMedicamentos);
                        }
                        if ($tipoSangre != null) {
                            $informacionExtra->setTipoSangre($tipoSangre);
                        }
                        if ($medioTransporte != null) {
                            $informacionExtra->setMedioTransporte($medioTransporte);
                        }
                        if ($puntoRecoleccion != null) {
                            $informacionExtra->setPuntoRecoleccion($puntoRecoleccion);
                        }
                        if ($nombreClub != null) {
                            $informacionExtra->setNombreClubEquipo($nombreClub);
                        }
                        if ($nombreCarro != null) {
                            $informacionExtra->setNombreCarro($nombreCarro);
                        }
                        if ($rama != null) {
                            $informacionExtra->setRama($rama);
                        }
                        if ($rol != null) {
                            $informacionExtra->setRol($rol);
                        }
                        if ($categoria != null) {
                            $informacionExtra->setCategoria($categoria);
                        }
                        if ($jefeHogar != null) {
                            $informacionExtra->setJefeCabezaHogar($jefeHogar);
                        }
                        if ($telefono != null) {
                            $informacionExtra->setTelefonoContacto($telefono);
                        }
                        if ($gradoCursa != null) {
                            $informacionExtra->setGradoCursa($gradoCursa);
                        }
                        if ($tallaPantalon != null) {
                            $informacionExtra->setTallaPantalon($tallaPantalon);
                        }
                        if ($tallaCamisa != null) {
                            $informacionExtra->setTallaCamisa($tallaCamisa);
                        }
                        if ($tallaZapatos != null) {
                            $informacionExtra->setTallaZapatos($tallaZapatos);
                        }
                        if ($numeroMatricula != null) {
                            $informacionExtra->setNumeroMatricula($numeroMatricula);
                        }
                        if ($direccion != null) {
                            $informacionExtra->setDireccion($direccion);
                        }
                        if ($sexo != null) {
                            $informacionExtra->setSexo($sexo);
                        }
                        if ($discapacitado != null) {
                            $informacionExtra->setDiscapacitado($discapacitado);
                        }
                        if ($tipoDiscapacidad != null) {
                            $informacionExtra->setTipoDiscapacidad($tipoDiscapacidad);
                        }
                        if ($subTipoDiscapacidad != null) {
                            $informacionExtra->setSubDiscapacidad($subTipoDiscapacidad);
                        }
                        if ($adjuntarDocumentos != null) {
                            $informacionExtra->setAdjuntarDocumentos($adjuntarDocumentos);
                        }
                        if ($correoElectronico != null) {
                            $informacionExtra->setCorreoElectronico($correoElectronico);
                        }
                        if ($fechaNacimiento != null) {
                            $informacionExtra->setFechaNacimiento($fechaNacimiento);
                        }
                        if ($fuma != null) {
                            $informacionExtra->setFuma($fuma);
                        }
                        if ($licenciaCiclismo != null) {
                            $informacionExtra->setLicenciaCiclismo($licenciaCiclismo);
                        }
                        if ($perteneceLGBTI != null) {
                            $informacionExtra->setPerteneceLGBTI($perteneceLGBTI);
                        }
                        if ($tipoDesplazado != null) {
                            $informacionExtra->setTipoDesplazado($tipoDesplazado);
                        }
                        if ($barrio != null) {
                            $informacionExtra->setBarrio($barrio);
                        }
                        if ($establecimientoEducativo != null) {
                            $informacionExtra->setEstablecimientoEducativo($establecimientoEducativo);
                        }
                        if ($nivelEscolaridad != null) {
                            $informacionExtra->setNivelEscolaridad($nivelEscolaridad);
                        }
                        if ($estrato != null) {
                            $informacionExtra->setEstrato($estrato);
                        }
                        if ($ocupacion != null) {
                            $informacionExtra->setOcupacion($ocupacion);
                        }
                        if ($municipio != null) {
                            $informacionExtra->setMunicipio($municipio);
                        }

                        $this->em->persist($informacionExtra);
                    }
                    $this->em->persist($jugadorEquipoEvento);
                }

                $this->em->flush();

                $this->addFlash('sonata_flash_success', $this->trans("formulario_evento.presatisfactorio"));
                return $this->redirectToRoute('admin_logic_evento_list');
            }
        }

        return $this->render('AdminBundle:Evento/Equipos:equipo_persona_natural.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    function validarEquipo($evento, $form) {
        $em = $this->getDoctrine()->getManager();
        $participantesEquipo = $evento->getParticipantesEquipoMaximo();
        $numeroEquipos = $evento->getNumeroEquipos();
        $maximosParticipantes = ($participantesEquipo * $numeroEquipos);
        if (count($form->get('jugadorEventos')) > $maximosParticipantes) {
            $form->get("nombre")->addError(
                    new FormError($this->trans->trans('formulario_evento.errorCantidadJugadores', array(
                        '%jugadoresMaximos%' => $evento->getParticipantesEquipoMaximo()
                    )))
            );
        }

        if (count($form->get('jugadorEventos')) < $evento->getParticipantesEquipoMinimo()) {
            $form->get("nombre")->addError(
                    new FormError($this->trans->trans('formulario_evento.errorCantidadJugadoresMinimos', array(
                        '%jugadoresMinimos%' => $evento->getParticipantesEquipoMinimo()
                    )))
            );
        }

        foreach ($form->get('jugadorEventos') as $key => $jugadorEvento) {
            $usuario = $jugadorEvento->get("usuarioJugadorEvento")->getData();
            if ($usuario == null) {
                $jugadorEvento->get("usuarioJugadorEvento")->addError(
                        new FormError($this->trans->trans('jugadorNoexiste'))
                );
            } else {
                $jugadorEventoQuery = $em->getRepository("LogicBundle:JugadorEvento")
                                ->createQueryBuilder('jugador')
                                ->where('jugador.evento = :evento_id')
                                ->andWhere('jugador.usuarioJugadorEvento = :id_usuario')
                                ->setParameter('evento_id', $evento)
                                ->setParameter('id_usuario', $usuario)
                                ->getQuery()->getOneOrNullResult();

                if ($jugadorEventoQuery != null) {
                    $jugadorEvento->get("usuarioJugadorEvento")->addError(
                            new FormError($this->trans->trans('jugadorYaAsociado'))
                    );
                }
            }
        }
        $jugador = $form->get("jugadorEventos")->getData()[1];
    }

}
