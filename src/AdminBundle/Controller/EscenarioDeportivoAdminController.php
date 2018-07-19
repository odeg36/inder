<?php

namespace AdminBundle\Controller;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Event\Event;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\ArchivoEscenario;
use LogicBundle\Entity\DisciplinasEscenarioDeportivo;
use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo;
use LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo;
use LogicBundle\Entity\TendenciaEscenarioDeportivo;
use LogicBundle\Entity\TipoReservaEscenarioDeportivo;
use LogicBundle\Entity\UsuarioEscenarioDeportivo;
use LogicBundle\Form\EscenarioDeportivoType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormView;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class EscenarioDeportivoAdminController extends CRUDController {

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
        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
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
        $user = $this->getUser();
        $idUsuario = $user->getId();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $ROLE_USER = $user->hasRole('ROLE_USER');
        if ($ROLE_GESTOR_ESCENARIO) {
            if ($id == null) {
                $id = 0;
            } else {
                $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
                $users = array();
                foreach ($escenarioDeportivo->getUsuarioEscenarioDeportivos() as $usuario) {
                    $user = $usuario->getUsuario();
                    $userId = $user->getId();
                    array_push($users, $userId);
                }
                if (in_array($idUsuario, $users)) {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => $id, 'edit' => true));
                }
            }
        }
        if ($ROLE_SUPER_ADMIN) {
            if ($id == null) {
                $id = 0;
            }
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => $id, 'edit' => true));
        }
        return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
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
        $ROLE_FORMADOR = $user->hasRole('ROLE_FORMADOR');

        $mostrarInfoTecnica = false;
        if ($ROLE_SUPER_ADMIN == true || $ROLE_FORMADOR == true) {
            $mostrarInfoTecnica = true;
        }
        //$mostrarInfoTecnica = true;
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $escenarioDeportivo = new EscenarioDeportivo();
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
        }

        //$escenarioDeportivo->setNormaEscenario(strip_tags($escenarioDeportivo->getNormaEscenario()));


        return $this->render('AdminBundle:EscenarioDeportivo:mostrar_escenario_deportivo.html.twig', [
                    'idEscenarioDeportivo' => $id,
                    'escenarioDeportivo' => $escenarioDeportivo,
                    'mostrarInfoTecnica' => $mostrarInfoTecnica,
                    'nombreEscenario' => $escenarioDeportivo->getNombre()
        ]);
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
    public function deleteAction($id = null) {

        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');

        if ($ROLE_GESTOR_ESCENARIO || $ROLE_SUPER_ADMIN) {
            $puedeEliminarEscenario = true;
            $em = $this->getDoctrine()->getManager();
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            $eliminarEscenario = true;
            //******************************************** Check Reserva  ********************************************
            $reservas = $this->em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                            ->where('reserva.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($reservas) > 0) {
                $eliminarEscenario = false;
            }
            //******************************************** Check Oferta  ********************************************
            $oferta = $this->em->getRepository('LogicBundle:Oferta')->createQueryBuilder('oferta')
                            ->where('oferta.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($oferta) > 0) {
                $eliminarEscenario = false;
            }
            //******************************************** Check ArchivoEscenario  ********************************************
            $archivoEscenario = $this->em->getRepository('LogicBundle:ArchivoEscenario')->createQueryBuilder('archivoEscenario')
                            ->where('archivoEscenario.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($archivoEscenario) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check division  ********************************************
            $division = $this->em->getRepository('LogicBundle:Division')->createQueryBuilder('division')
                            ->where('division.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($division) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check tipoReservaEscenarioDeportivo  ********************************************
            $tipoReservaEscenarioDeportivo = $this->em->getRepository('LogicBundle:TipoReservaEscenarioDeportivo')->createQueryBuilder('tipoReservaEscenarioDeportivo')
                            ->where('tipoReservaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($tipoReservaEscenarioDeportivo) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check disciplinasEscenarioDeportivo  ********************************************
            $disciplinasEscenarioDeportivo = $this->em->getRepository('LogicBundle:DisciplinasEscenarioDeportivo')->createQueryBuilder('disciplinasEscenarioDeportivo')
                            ->where('disciplinasEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($disciplinasEscenarioDeportivo) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check usuarioEscenarioDeportivo  ********************************************
            $usuarioEscenarioDeportivo = $this->em->getRepository('LogicBundle:UsuarioEscenarioDeportivo')->createQueryBuilder('usuarioEscenarioDeportivo')
                            ->where('usuarioEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($usuarioEscenarioDeportivo) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check evento  ********************************************
            $evento = $this->em->getRepository('LogicBundle:Evento')->createQueryBuilder('evento')
                            ->where('evento.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($evento) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check encuentroSistemaUno  ********************************************
            $encuentroSistemaUno = $this->em->getRepository('LogicBundle:EncuentroSistemaUno')->createQueryBuilder('encuentroSistemaUno')
                            ->where('encuentroSistemaUno.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($encuentroSistemaUno) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check encuentroSistemaDos  ********************************************
            $encuentroSistemaDos = $this->em->getRepository('LogicBundle:EncuentroSistemaDos')->createQueryBuilder('encuentroSistemaDos')
                            ->where('encuentroSistemaDos.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($encuentroSistemaDos) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check encuentroSistemaTres  ********************************************
            $encuentroSistemaTres = $this->em->getRepository('LogicBundle:EncuentroSistemaTres')->createQueryBuilder('encuentroSistemaTres')
                            ->where('encuentroSistemaTres.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($encuentroSistemaTres) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check encuentroSistemaCuatro  ********************************************
            $encuentroSistemaCuatro = $this->em->getRepository('LogicBundle:EncuentroSistemaCuatro')->createQueryBuilder('encuentroSistemaCuatro')
                            ->where('encuentroSistemaCuatro.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($encuentroSistemaCuatro) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check escenarioCategoriaInfraestructura  ********************************************
            $escenarioCategoriaInfraestructura = $this->em->getRepository('LogicBundle:EscenarioCategoriaInfraestructura')->createQueryBuilder('escenarioCategoriaInfraestructura')
                            ->where('escenarioCategoriaInfraestructura.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($escenarioCategoriaInfraestructura) > 0) {
                $eliminarEscenario = false;
            }

            //******************************************** Check escenarioCategoriaAmbiental  ********************************************
            $escenarioCategoriaAmbiental = $this->em->getRepository('LogicBundle:EscenarioCategoriaAmbiental')->createQueryBuilder('escenarioCategoriaAmbiental')
                            ->where('escenarioCategoriaAmbiental.escenarioDeportivo = :escenarioDeportivo')
                            ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                            ->getQuery()->getResult();
            if (count($escenarioCategoriaAmbiental) > 0) {
                $eliminarEscenario = false;
            }
            if ($eliminarEscenario == true) {
                $em->remove($escenarioDeportivo);
                $em->flush();
                $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.escenario_deportivo_eliminado'));
                return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
            } else {
                $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.error_eliminar'));
                return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
            }
        } else {
            $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.error_eliminar'));
            return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
        }
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionDelete(ProxyQueryInterface $selectedModelQuery, Request $request = null) {
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');

        try {
            $errorEliminarEscenario = false;
            $completadoEliminarEscenario = false;
            foreach ($selectedModels as $selectedModel) {
                if ($ROLE_GESTOR_ESCENARIO || $ROLE_SUPER_ADMIN) {
                    $puedeEliminarEscenario = true;
                    $escenarioDeportivo = $selectedModel;
                    $eliminarEscenario = true;
                    //******************************************** Check Reserva  ********************************************
                    $reservas = $this->em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
                                    ->where('reserva.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($reservas) > 0) {
                        $eliminarEscenario = false;
                    }
                    //******************************************** Check Oferta  ********************************************
                    $oferta = $this->em->getRepository('LogicBundle:Oferta')->createQueryBuilder('oferta')
                                    ->where('oferta.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($oferta) > 0) {
                        $eliminarEscenario = false;
                    }
                    //******************************************** Check ArchivoEscenario  ********************************************
                    $archivoEscenario = $this->em->getRepository('LogicBundle:ArchivoEscenario')->createQueryBuilder('archivoEscenario')
                                    ->where('archivoEscenario.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($archivoEscenario) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check division  ********************************************
                    $division = $this->em->getRepository('LogicBundle:Division')->createQueryBuilder('division')
                                    ->where('division.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($division) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check tipoReservaEscenarioDeportivo  ********************************************
                    $tipoReservaEscenarioDeportivo = $this->em->getRepository('LogicBundle:TipoReservaEscenarioDeportivo')->createQueryBuilder('tipoReservaEscenarioDeportivo')
                                    ->where('tipoReservaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($tipoReservaEscenarioDeportivo) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check disciplinasEscenarioDeportivo  ********************************************
                    $disciplinasEscenarioDeportivo = $this->em->getRepository('LogicBundle:DisciplinasEscenarioDeportivo')->createQueryBuilder('disciplinasEscenarioDeportivo')
                                    ->where('disciplinasEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($disciplinasEscenarioDeportivo) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check usuarioEscenarioDeportivo  ********************************************
                    $usuarioEscenarioDeportivo = $this->em->getRepository('LogicBundle:UsuarioEscenarioDeportivo')->createQueryBuilder('usuarioEscenarioDeportivo')
                                    ->where('usuarioEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($usuarioEscenarioDeportivo) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check evento  ********************************************
                    $evento = $this->em->getRepository('LogicBundle:Evento')->createQueryBuilder('evento')
                                    ->where('evento.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($evento) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check encuentroSistemaUno  ********************************************
                    $encuentroSistemaUno = $this->em->getRepository('LogicBundle:EncuentroSistemaUno')->createQueryBuilder('encuentroSistemaUno')
                                    ->where('encuentroSistemaUno.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($encuentroSistemaUno) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check encuentroSistemaDos  ********************************************
                    $encuentroSistemaDos = $this->em->getRepository('LogicBundle:EncuentroSistemaDos')->createQueryBuilder('encuentroSistemaDos')
                                    ->where('encuentroSistemaDos.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($encuentroSistemaDos) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check encuentroSistemaTres  ********************************************
                    $encuentroSistemaTres = $this->em->getRepository('LogicBundle:EncuentroSistemaTres')->createQueryBuilder('encuentroSistemaTres')
                                    ->where('encuentroSistemaTres.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($encuentroSistemaTres) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check encuentroSistemaCuatro  ********************************************
                    $encuentroSistemaCuatro = $this->em->getRepository('LogicBundle:EncuentroSistemaCuatro')->createQueryBuilder('encuentroSistemaCuatro')
                                    ->where('encuentroSistemaCuatro.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($encuentroSistemaCuatro) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check escenarioCategoriaInfraestructura  ********************************************
                    $escenarioCategoriaInfraestructura = $this->em->getRepository('LogicBundle:EscenarioCategoriaInfraestructura')->createQueryBuilder('escenarioCategoriaInfraestructura')
                                    ->where('escenarioCategoriaInfraestructura.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($escenarioCategoriaInfraestructura) > 0) {
                        $eliminarEscenario = false;
                    }

                    //******************************************** Check escenarioCategoriaAmbiental  ********************************************
                    $escenarioCategoriaAmbiental = $this->em->getRepository('LogicBundle:EscenarioCategoriaAmbiental')->createQueryBuilder('escenarioCategoriaAmbiental')
                                    ->where('escenarioCategoriaAmbiental.escenarioDeportivo = :escenarioDeportivo')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId() ?: 0)
                                    ->getQuery()->getResult();
                    if (count($escenarioCategoriaAmbiental) > 0) {
                        $eliminarEscenario = false;
                    }

                    if ($eliminarEscenario == true) {
                        $this->em->remove($escenarioDeportivo);
                        $this->em->flush();

                        $completadoEliminarEscenario = true;
                        //return $this->redirectToRoute('admin_logic_escenariodeportivo_list');                */
                    } else {
                        $errorEliminarEscenario = true;
                        //return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
                    }
                } else {
                    $errorEliminarEscenario = true;
                    //$this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.error_eliminar'));
                    //return $this->redirectToRoute('admin_logic_escenariodeportivo_list');
                }
            }
            if ($completadoEliminarEscenario == true) {
                $this->addFlash('sonata_flash_success', $this->trans('alerta.escenario_deportivo_eliminado'));
            }

            if ($errorEliminarEscenario == true) {
                $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.error_eliminar'));
            }

            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }

        //$this->addFlash('sonata_flash_success', $this->trans('alerta.reservas_rechazadas'));

        return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    function addpaso1Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;

        $tipoEscenario = null;

        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }

        if ($id == 0) {
            $escenarioDeportivo = new EscenarioDeportivo();
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
        }

        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 1,
            'escenarioId' => $escenarioDeportivo->getId(),
            'escenarioTipoDireccion' => $escenarioDeportivo->getTipoDireccion(),
            'request' => $request,
            'em' => $this->getDoctrine()->getManager(),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tipoEscenario = $form->get("tipo_escenario")->getData();
            if ($form->isValid()) {
                $direccion = '';
                if ($form->get("tipoDireccion")->getData() != null) {
                    if ($form->get("tipoDireccion")->getData()->getNombre() == "Barrio") {
                        $form->getData()->setDireccion($form->get('direccion')->getData());
                    } else {
                        $form->getData()->setDireccion($form->get('direccionComuna')->getData());
                    }
                } else {
                    $tipoDireccion = $this->em->getRepository('LogicBundle:TipoDireccion')->findBy(array('nombre' => 'Barrio'));

                    if (count($tipoDireccion)) {
                        $tipoDireccion = $tipoDireccion[0];
                    }
                    $form->getData()->setTipoDireccion($tipoDireccion);
                    if ($tipoDireccion->getNombre() == "Barrio") {
                        $form->getData()->setDireccion($form->get('direccion')->getData());
                    } else {
                        $form->getData()->setDireccion($form->get('direccionComuna')->getData());
                    }
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                if ($escenarioDeportivo->getBarrio()->getComuna() != null) {
                    $codigoEscenario = $escenarioDeportivo->getBarrio()->getComuna()->getId() . "-" . $escenarioDeportivo->getTipoEscenario()->getId() . "-" . $escenarioDeportivo->getId();
                } else {
                    $codigoEscenario = $escenarioDeportivo->getBarrio()->getId() . "-" . $escenarioDeportivo->getTipoEscenario()->getId() . "-" . $escenarioDeportivo->getId();
                }
                $escenarioDeportivo->setCodigoEscenario($codigoEscenario);
                $this->em->persist($escenarioDeportivo);
                $this->em->flush();
                if ($editarEscenario != null) {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso2', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                } else {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso2', array('id' => $escenarioDeportivo->getId()));
                }
            }
        }
        $direccionEscenarioDeportivo = $escenarioDeportivo->getDireccion();


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;


        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }



        $direccionEscenarioDeportivo = str_replace(' ', '_', $direccionEscenarioDeportivo);
        return $this->render('AdminBundle:EscenarioDeportivo/Pasos:paso_1.html.twig', array(
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'id_mapa' => 'mapa_escenario',
                    'idescenario' => $id,
                    'nombreEscenario' => $escenarioDeportivo->getNombre(),
                    'codigoEscenario' => $escenarioDeportivo->getCodigoEscenario(),
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'direccionEscenarioDeportivo' => $direccionEscenarioDeportivo
        ));
    }

    function addpaso2Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }
        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 2,
            'escenarioId' => $escenarioDeportivo->getId(),
            'em' => $this->container
        ));
        $form->handleRequest($request);

        /* Tipo de reserva original data */
        $originalTipoReservaEscenarioDeportivo = [];
        foreach ($escenarioDeportivo->getTipoReservaEscenarioDeportivos() as $key => $tipoReservaEscenarioDeportivo) {
            $originalTipoReservaEscenarioDeportivo[] = ["tipoReserva" => $tipoReservaEscenarioDeportivo->getTipoReserva(), "tipoReservaEscenarioDeportivo" => $tipoReservaEscenarioDeportivo];
        }

        /* Disciplinas original data */
        $originalDisciplinasEscenarioDeportivo = [];
        foreach ($escenarioDeportivo->getDisciplinasEscenarioDeportivos() as $key => $disciplinasEscenarioDeportivo) {
            $originalDisciplinasEscenarioDeportivo[] = ["disciplina" => $disciplinasEscenarioDeportivo->getDisciplina(), "disciplinasEscenarioDeportivo" => $disciplinasEscenarioDeportivo];
        }

        /* Tendencias original data */
        $originalTendenciaEscenarioDeportivo = [];
        foreach ($escenarioDeportivo->getTendenciaEscenarioDeportivos() as $key => $tendenciaEscenarioDeportivo) {
            $originalTendenciaEscenarioDeportivo[] = ["tendencia" => $tendenciaEscenarioDeportivo->getTendencia(), "tendenciaEscenarioDeportivo" => $tendenciaEscenarioDeportivo];
        }

        /* Usuario principal y secundarios original data */
        $originalUsuarioEscenarioDeportivoSecundarios = [];
        $originalUsuarioEscenarioDeportivoPrincipal = null;
        foreach ($escenarioDeportivo->getUsuarioEscenarioDeportivos() as $key => $usuarioEscenarioDeportivo) {
            if ($usuarioEscenarioDeportivo->getPrincipal() === true) {
                $originalUsuarioEscenarioDeportivoPrincipal = $usuarioEscenarioDeportivo;
            } else {
                $originalUsuarioEscenarioDeportivoSecundarios[] = ["usuario" => $usuarioEscenarioDeportivo->getUsuario(), "usuarioEscenarioDeportivo" => $usuarioEscenarioDeportivo];
            }
        }
        if ($form->isSubmitted()) {
            $tiene_restricciones = $form->get("tiene_restricciones")->getData();

            if (!$tiene_restricciones) {
                $tipo_reserva = $form->get("tipo_reserva")->getData();
                $disciplina = $form->get("disciplina")->getData();
                $tendencia = $form->get("tendencia")->getData();

                if (count($tipo_reserva) == 0 && count($disciplina) == 0 && count($tendencia) == 0) {
                    $form->get("tendencia")->addError(
                            new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_restricciones'))
                    );
                }
            }
            if ($form->get("hora_inicial_lunes")->getData() == null &&
                    $form->get("hora_final_lunes")->getData() == null &&
                    $form->get("hora_inicial_martes")->getData() == null &&
                    $form->get("hora_final_martes")->getData() == null &&
                    $form->get("hora_inicial_miercoles")->getData() == null &&
                    $form->get("hora_final_miercoles")->getData() == null &&
                    $form->get("hora_inicial_jueves")->getData() == null &&
                    $form->get("hora_final_jueves")->getData() == null &&
                    $form->get("hora_inicial_viernes")->getData() == null &&
                    $form->get("hora_final_viernes")->getData() == null &&
                    $form->get("hora_inicial_sabado")->getData() == null &&
                    $form->get("hora_final_sabado")->getData() == null &&
                    $form->get("hora_inicial_domingo")->getData() == null &&
                    $form->get("hora_final_domingo")->getData() == null &&
                    $form->get("hora_inicial2_lunes")->getData() == null &&
                    $form->get("hora_final2_lunes")->getData() == null &&
                    $form->get("hora_inicial2_martes")->getData() == null &&
                    $form->get("hora_final2_martes")->getData() == null &&
                    $form->get("hora_inicial2_miercoles")->getData() == null &&
                    $form->get("hora_final2_miercoles")->getData() == null &&
                    $form->get("hora_inicial2_jueves")->getData() == null &&
                    $form->get("hora_final2_jueves")->getData() == null &&
                    $form->get("hora_inicial2_viernes")->getData() == null &&
                    $form->get("hora_final2_viernes")->getData() == null &&
                    $form->get("hora_inicial2_sabado")->getData() == null &&
                    $form->get("hora_final2_sabado")->getData() == null &&
                    $form->get("hora_inicial2_domingo")->getData() == null &&
                    $form->get("hora_final2_domingo")->getData() == null
            ) {
                $form->get("hora_inicial_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_horarios'))
                );

                $form->get("hora_final_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_horarios'))
                );
            }


            if ($form->get("hora_inicial_lunes")->getData() == $form->get("hora_final_lunes")->getData() && $form->get("hora_inicial_lunes")->getData() != null) {

                $form->get("hora_inicial_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_lunes")->getData() == $form->get("hora_final2_lunes")->getData() && $form->get("hora_inicial2_lunes")->getData() != null) {

                $form->get("hora_inicial2_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_martes")->getData() == $form->get("hora_final_martes")->getData() && $form->get("hora_inicial_martes")->getData() != null) {

                $form->get("hora_inicial_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_martes")->getData() == $form->get("hora_final2_martes")->getData() && $form->get("hora_inicial2_martes")->getData() != null) {

                $form->get("hora_inicial2_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_miercoles")->getData() == $form->get("hora_final_miercoles")->getData() && $form->get("hora_inicial_miercoles")->getData() != null) {

                $form->get("hora_inicial_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_miercoles")->getData() == $form->get("hora_final2_miercoles")->getData() && $form->get("hora_inicial2_miercoles")->getData() != null) {

                $form->get("hora_inicial2_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_jueves")->getData() == $form->get("hora_final_jueves")->getData() && $form->get("hora_inicial_jueves")->getData() != null) {

                $form->get("hora_inicial_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_jueves")->getData() == $form->get("hora_final2_jueves")->getData() && $form->get("hora_inicial2_jueves")->getData() != null) {

                $form->get("hora_inicial2_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_viernes")->getData() == $form->get("hora_final_viernes")->getData() && $form->get("hora_inicial_viernes")->getData() != null) {

                $form->get("hora_inicial_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }

            if ($form->get("hora_inicial2_viernes")->getData() == $form->get("hora_final2_viernes")->getData() && $form->get("hora_inicial2_viernes")->getData() != null) {

                $form->get("hora_inicial2_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_sabado")->getData() == $form->get("hora_final_sabado")->getData() && $form->get("hora_inicial_sabado")->getData() != null) {

                $form->get("hora_inicial_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_sabado")->getData() == $form->get("hora_final2_sabado")->getData() && $form->get("hora_inicial2_sabado")->getData() != null) {

                $form->get("hora_inicial2_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_domingo")->getData() == $form->get("hora_final_domingo")->getData() && $form->get("hora_inicial_domingo")->getData() != null) {

                $form->get("hora_inicial_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial2_domingo")->getData() == $form->get("hora_final2_domingo")->getData() && $form->get("hora_inicial2_domingo")->getData() != null) {

                $form->get("hora_inicial2_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );

                $form->get("hora_final2_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                );
            }


            if ($form->get("hora_inicial_lunes")->getData() != null && $form->get("hora_final_lunes")->getData() == null ||
                    $form->get("hora_inicial_lunes")->getData() == null && $form->get("hora_final_lunes")->getData() != null) {

                $form->get("hora_inicial_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_lunes")->getData() != null && $form->get("hora_final2_lunes")->getData() == null ||
                    $form->get("hora_inicial2_lunes")->getData() == null && $form->get("hora_final2_lunes")->getData() != null) {

                $form->get("hora_inicial2_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_lunes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial_martes")->getData() != null && $form->get("hora_final_martes")->getData() == null ||
                    $form->get("hora_inicial_martes")->getData() == null && $form->get("hora_final_martes")->getData() != null) {

                $form->get("hora_inicial_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }



            if ($form->get("hora_inicial2_martes")->getData() != null && $form->get("hora_final2_martes")->getData() == null ||
                    $form->get("hora_inicial2_martes")->getData() == null && $form->get("hora_final2_martes")->getData() != null) {

                $form->get("hora_inicial2_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_martes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial_miercoles")->getData() != null && $form->get("hora_final_miercoles")->getData() == null ||
                    $form->get("hora_inicial_miercoles")->getData() == null && $form->get("hora_final_miercoles")->getData() != null) {

                $form->get("hora_inicial_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_miercoles")->getData() != null && $form->get("hora_final2_miercoles")->getData() == null ||
                    $form->get("hora_inicial_miercoles")->getData() == null && $form->get("hora_final_miercoles")->getData() != null) {

                $form->get("hora_inicial2_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_miercoles")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial_jueves")->getData() != null && $form->get("hora_final_jueves")->getData() == null ||
                    $form->get("hora_inicial_jueves")->getData() == null && $form->get("hora_final_jueves")->getData() != null) {

                $form->get("hora_inicial_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_jueves")->getData() != null && $form->get("hora_final2_jueves")->getData() == null ||
                    $form->get("hora_inicial2_jueves")->getData() == null && $form->get("hora_final2_jueves")->getData() != null) {

                $form->get("hora_inicial2_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_jueves")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }

            if ($form->get("hora_inicial_viernes")->getData() != null && $form->get("hora_final_viernes")->getData() == null ||
                    $form->get("hora_inicial_viernes")->getData() == null && $form->get("hora_final_viernes")->getData() != null) {

                $form->get("hora_inicial_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_viernes")->getData() != null && $form->get("hora_final2_viernes")->getData() == null ||
                    $form->get("hora_inicial2_viernes")->getData() == null && $form->get("hora_final2_viernes")->getData() != null) {

                $form->get("hora_inicial2_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_viernes")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }



            if ($form->get("hora_inicial_sabado")->getData() != null && $form->get("hora_final_sabado")->getData() == null ||
                    $form->get("hora_inicial_sabado")->getData() == null && $form->get("hora_final_sabado")->getData() != null) {

                $form->get("hora_inicial_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_sabado")->getData() != null && $form->get("hora_final2_sabado")->getData() == null ||
                    $form->get("hora_inicial2_sabado")->getData() == null && $form->get("hora_final2_sabado")->getData() != null) {

                $form->get("hora_inicial2_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_sabado")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial_domingo")->getData() != null && $form->get("hora_final_domingo")->getData() == null ||
                    $form->get("hora_inicial_domingo")->getData() == null && $form->get("hora_final_domingo")->getData() != null) {

                $form->get("hora_inicial_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }


            if ($form->get("hora_inicial2_domingo")->getData() != null && $form->get("hora_final2_domingo")->getData() == null ||
                    $form->get("hora_inicial_domingo")->getData() == null && $form->get("hora_final2_domingo")->getData() != null) {

                $form->get("hora_inicial2_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );

                $form->get("hora_final2_domingo")->addError(
                        new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                );
            }



            if ($form->get("hora_inicial_lunes")->getData() != null && $form->get("hora_final_lunes")->getData() != null) {

                $horaInicioLunes = date("H:i", strtotime($form->get("hora_inicial_lunes")->getData()));
                $horaFinalLunes = date("H:i", strtotime($form->get("hora_final_lunes")->getData()));

                if ($horaInicioLunes > $horaFinalLunes) {

                    $form->get("hora_inicial_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_lunes")->getData() != null && $form->get("hora_final2_lunes")->getData() != null) {

                $horaInicioLunes = date("H:i", strtotime($form->get("hora_inicial2_lunes")->getData()));
                $horaFinalLunes = date("H:i", strtotime($form->get("hora_final2_lunes")->getData()));

                if ($horaInicioLunes > $horaFinalLunes) {

                    $form->get("hora_inicial2_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_martes")->getData() != null && $form->get("hora_final_martes")->getData() != null) {

                $horaInicioMartes = date("H:i", strtotime($form->get("hora_inicial_martes")->getData()));
                $horaFinalMartes = date("H:i", strtotime($form->get("hora_final_martes")->getData()));

                if ($horaInicioMartes > $horaFinalMartes) {

                    $form->get("hora_inicial_martes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_martes")->getData() != null && $form->get("hora_final2_martes")->getData() != null) {

                $horaInicioMartes = date("H:i", strtotime($form->get("hora_inicial2_martes")->getData()));
                $horaFinalMartes = date("H:i", strtotime($form->get("hora_final2_martes")->getData()));

                if ($horaInicioMartes > $horaFinalMartes) {

                    $form->get("hora_inicial2_martes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_miercoles")->getData() != null && $form->get("hora_final_miercoles")->getData() != null) {

                $horaInicioMiercoles = date("H:i", strtotime($form->get("hora_inicial_miercoles")->getData()));
                $horaFinalMiercoles = date("H:i", strtotime($form->get("hora_final_miercoles")->getData()));

                if ($horaInicioMiercoles > $horaFinalMiercoles) {

                    $form->get("hora_inicial_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_miercoles")->getData() != null && $form->get("hora_final2_miercoles")->getData() != null) {

                $horaInicioMiercoles = date("H:i", strtotime($form->get("hora_inicial2_miercoles")->getData()));
                $horaFinalMiercoles = date("H:i", strtotime($form->get("hora_final2_miercoles")->getData()));

                if ($horaInicioMiercoles > $horaFinalMiercoles) {

                    $form->get("hora_inicial2_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_jueves")->getData() != null && $form->get("hora_final_jueves")->getData() != null) {

                $horaInicioJueves = date("H:i", strtotime($form->get("hora_inicial_jueves")->getData()));
                $horaFinalJueves = date("H:i", strtotime($form->get("hora_final_jueves")->getData()));

                if ($horaInicioJueves > $horaFinalJueves) {

                    $form->get("hora_inicial_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_jueves")->getData() != null && $form->get("hora_final2_jueves")->getData() != null) {

                $horaInicioJueves = date("H:i", strtotime($form->get("hora_inicial2_jueves")->getData()));
                $horaFinalJueves = date("H:i", strtotime($form->get("hora_final2_jueves")->getData()));

                if ($horaInicioJueves > $horaFinalJueves) {

                    $form->get("hora_inicial2_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_viernes")->getData() != null && $form->get("hora_final_viernes")->getData() != null) {

                $horaInicioViernes = date("H:i", strtotime($form->get("hora_inicial_viernes")->getData()));
                $horaFinalViernes = date("H:i", strtotime($form->get("hora_final_viernes")->getData()));

                if ($horaInicioViernes > $horaFinalViernes) {

                    $form->get("hora_inicial_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_viernes")->getData() != null && $form->get("hora_final2_viernes")->getData() != null) {

                $horaInicioViernes = date("H:i", strtotime($form->get("hora_inicial2_viernes")->getData()));
                $horaFinalViernes = date("H:i", strtotime($form->get("hora_final2_viernes")->getData()));

                if ($horaInicioViernes > $horaFinalViernes) {

                    $form->get("hora_inicial2_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_sabado")->getData() != null && $form->get("hora_final_sabado")->getData() != null) {

                $horaInicioSabado = date("H:i", strtotime($form->get("hora_inicial_sabado")->getData()));
                $horaFinalSabado = date("H:i", strtotime($form->get("hora_final_sabado")->getData()));

                if ($horaInicioSabado > $horaFinalSabado) {

                    $form->get("hora_inicial_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_sabado")->getData() != null && $form->get("hora_final2_sabado")->getData() != null) {

                $horaInicioSabado = date("H:i", strtotime($form->get("hora_inicial2_sabado")->getData()));
                $horaFinalSabado = date("H:i", strtotime($form->get("hora_final2_sabado")->getData()));

                if ($horaInicioSabado > $horaFinalSabado) {

                    $form->get("hora_inicial2_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial_domingo")->getData() != null && $form->get("hora_final_domingo")->getData() != null) {

                $horaInicioSabado = date("H:i", strtotime($form->get("hora_inicial_domingo")->getData()));
                $horaFinalSabado = date("H:i", strtotime($form->get("hora_final_domingo")->getData()));

                if ($horaInicioSabado > $horaFinalSabado) {

                    $form->get("hora_inicial_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_domingo")->getData() != null && $form->get("hora_final2_domingo")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial2_domingo")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final2_domingo")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial2_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                    );
                }
            }

            //intermedios para el domingo

            if ($form->get("hora_inicial_domingo")->getData() != null && $form->get("hora_inicial2_domingo")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_domingo")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_domingo")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_domingo")->getData() != null && $form->get("hora_inicial2_domingo")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_domingo")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_domingo")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }

            //intermedios para el sabado


            if ($form->get("hora_inicial_sabado")->getData() != null && $form->get("hora_inicial2_sabado")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_sabado")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_sabado")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_sabado")->getData() != null && $form->get("hora_inicial2_sabado")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_sabado")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_sabado")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }


            //intermedios para el viernes

            if ($form->get("hora_inicial_viernes")->getData() != null && $form->get("hora_inicial2_viernes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_viernes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_viernes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_viernes")->getData() != null && $form->get("hora_inicial2_viernes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_viernes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_viernes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }

            //intermedios para el jueves

            if ($form->get("hora_inicial_jueves")->getData() != null && $form->get("hora_inicial2_jueves")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_jueves")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_jueves")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_jueves")->getData() != null && $form->get("hora_inicial2_jueves")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_jueves")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_jueves")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }

            //intermedios para el miercoles


            if ($form->get("hora_inicial_miercoles")->getData() != null && $form->get("hora_inicial2_miercoles")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_miercoles")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_miercoles")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_miercoles")->getData() != null && $form->get("hora_inicial2_miercoles")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_miercoles")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_miercoles")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }

            //intermedios martes

            if ($form->get("hora_inicial_martes")->getData() != null && $form->get("hora_inicial2_martes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_martes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_martes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_martes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_martes")->getData() != null && $form->get("hora_inicial2_martes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_martes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_martes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }

            //intermedios lunes

            if ($form->get("hora_inicial_lunes")->getData() != null && $form->get("hora_inicial2_lunes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_inicial_lunes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_lunes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_inicial_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                    );
                }
            }

            if ($form->get("hora_final_lunes")->getData() != null && $form->get("hora_inicial2_lunes")->getData() != null) {

                $horaInicioDomingo = date("H:i", strtotime($form->get("hora_final_lunes")->getData()));
                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_lunes")->getData()));

                if ($horaInicioDomingo > $horaFinalDomingo) {

                    $form->get("hora_final_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                    );
                }
            }


            //horarios para la mañana
            if ($form->get("hora_final_lunes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_lunes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_martes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_martes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_martes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_miercoles")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_miercoles")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_jueves")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_jueves")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_viernes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_viernes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_sabado")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_sabado")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            if ($form->get("hora_final_domingo")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_final_domingo")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo > $horaControl) {

                    $form->get("hora_final_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                    );
                }
            }


            //horarios para la tarde
            if ($form->get("hora_inicial2_lunes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_lunes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_lunes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_martes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_martes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_martes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_miercoles")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_miercoles")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_miercoles")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_jueves")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_jueves")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_jueves")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_viernes")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_viernes")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_viernes")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_sabado")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_sabado")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_sabado")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }


            if ($form->get("hora_inicial2_domingo")->getData() != null) {

                $horaFinalDomingo = date("H:i", strtotime($form->get("hora_inicial2_domingo")->getData()));
                $horaControl = date("H:i", strtotime("12:00"));

                if ($horaFinalDomingo < $horaControl) {

                    $form->get("hora_inicial2_domingo")->addError(
                            new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                    );
                }
            }

            if ($form->isValid()) {
                /* Tipo de reserva */
                $tipoReservasId = [];
                foreach ($form->get("tipo_reserva")->getData() as $key => $tipoReserva) {

                    $tipoReservaEscenarioDeportivo = $this->em->getRepository("LogicBundle:TipoReservaEscenarioDeportivo")
                                    ->createQueryBuilder('tipoReservaEscenarioDeportivo')
                                    ->where('tipoReservaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo ')
                                    ->andWhere('tipoReservaEscenarioDeportivo.tipoReserva = :tipoReserva')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                    ->setParameter('tipoReserva', $tipoReserva->getId())
                                    ->getQuery()->getOneOrNullResult();

                    if (!$tipoReservaEscenarioDeportivo) {
                        $tipoReservaEscenarioDeportivo = new TipoReservaEscenarioDeportivo();
                    }
                    $tipoReservaEscenarioDeportivo->setTipoReserva($tipoReserva);
                    $tipoReservaEscenarioDeportivo->setEscenarioDeportivo($form->getData());
                    $this->em->persist($tipoReservaEscenarioDeportivo);

                    array_push($tipoReservasId, $tipoReserva->getId());
                }
                foreach ($originalTipoReservaEscenarioDeportivo as $orgTipoReservaEscenarioDeportivo) {
                    if (array_search($orgTipoReservaEscenarioDeportivo['tipoReserva']->getId(), $tipoReservasId) === false) {
                        $this->em->remove($orgTipoReservaEscenarioDeportivo['tipoReservaEscenarioDeportivo']);
                    }
                }

                /* Disciplinas */
                $disciplinasId = [];
                foreach ($form->get("disciplina")->getData() as $key => $disciplina) {

                    $disciplinasEscenarioDeportivo = $this->em->getRepository("LogicBundle:DisciplinasEscenarioDeportivo")
                                    ->createQueryBuilder('disciplinasEscenarioDeportivo')
                                    ->where('disciplinasEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo ')
                                    ->andWhere('disciplinasEscenarioDeportivo.disciplina = :disciplina')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                    ->setParameter('disciplina', $disciplina->getId())
                                    ->getQuery()->getOneOrNullResult();

                    if (!$disciplinasEscenarioDeportivo) {
                        $disciplinasEscenarioDeportivo = new DisciplinasEscenarioDeportivo();
                    }
                    $disciplinasEscenarioDeportivo->setDisciplina($disciplina);
                    $disciplinasEscenarioDeportivo->setEscenarioDeportivo($form->getData());
                    $this->em->persist($disciplinasEscenarioDeportivo);

                    array_push($disciplinasId, $disciplina->getId());
                }
                foreach ($originalDisciplinasEscenarioDeportivo as $orgDisciplinasEscenarioDeportivo) {
                    if (array_search($orgDisciplinasEscenarioDeportivo['disciplina']->getId(), $disciplinasId) === false) {
                        $this->em->remove($orgDisciplinasEscenarioDeportivo ['disciplinasEscenarioDeportivo']);
                    }
                }

                /* Tendencias */
                $tendenciasId = [];
                foreach ($form->get("tendencia")->getData() as $key => $tendencia) {

                    $tendenciasEscenarioDeportivo = $this->em->getRepository("LogicBundle:TendenciaEscenarioDeportivo")
                                    ->createQueryBuilder('tendenciaEscenarioDeportivo')
                                    ->where('tendenciaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                                    ->andWhere('tendenciaEscenarioDeportivo.tendencia = :tendencia')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                    ->setParameter('tendencia', $tendencia->getId())
                                    ->getQuery()->getOneOrNullResult();

                    if (!$tendenciasEscenarioDeportivo) {
                        $tendenciasEscenarioDeportivo = new TendenciaEscenarioDeportivo();
                    }
                    $tendenciasEscenarioDeportivo->setTendencia($tendencia);
                    $tendenciasEscenarioDeportivo->setEscenarioDeportivo($form->getData());
                    $this->em->persist($tendenciasEscenarioDeportivo);

                    array_push($tendenciasId, $tendencia->getId());
                }
                foreach ($originalTendenciaEscenarioDeportivo as $orgTendenciaEscenarioDeportivo) {
                    if (array_search($orgTendenciaEscenarioDeportivo['tendencia']->getId(), $tendenciasId) === false) {
                        $this->em->remove($orgTendenciaEscenarioDeportivo ['tendenciaEscenarioDeportivo']);
                    }
                }

                /* Usuarios sencundarios */
                $usuariosId = [];
                foreach ($form->get("usuario_secundario")->getData() as $key => $usuario) {

                    $usuarioEscenarioDeportivo = $this->em->getRepository("LogicBundle:UsuarioEscenarioDeportivo")
                                    ->createQueryBuilder('usuarioEscenarioDeportivo')
                                    ->where('usuarioEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo ')
                                    ->andWhere('usuarioEscenarioDeportivo.usuario = :usuario')
                                    ->andWhere('usuarioEscenarioDeportivo.principal != :principal')
                                    ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                    ->setParameter('usuario', $usuario->getId())
                                    ->setParameter('principal', true)
                                    ->getQuery()->getOneOrNullResult();

                    if (!$usuarioEscenarioDeportivo) {
                        $usuarioEscenarioDeportivo = new UsuarioEscenarioDeportivo();
                    }
                    $usuarioEscenarioDeportivo->setUsuario($usuario);
                    $usuarioEscenarioDeportivo->setEscenarioDeportivo($form->getData());
                    $usuarioEscenarioDeportivo->setPrincipal(false);
                    $this->em->persist($usuarioEscenarioDeportivo);

                    array_push($usuariosId, $usuario->getId());
                }
                foreach ($originalUsuarioEscenarioDeportivoSecundarios as $orgUsuarioEscenarioDeportivoSecundarios) {
                    if (array_search($orgUsuarioEscenarioDeportivoSecundarios['usuario']->getId(), $usuariosId) === false) {
                        $this->em->remove($orgUsuarioEscenarioDeportivoSecundarios['usuarioEscenarioDeportivo']);
                    }
                }

                /*  Usuario principal  */
                if ($originalUsuarioEscenarioDeportivoPrincipal) {
                    $this->em->remove($originalUsuarioEscenarioDeportivoPrincipal);
                }
                $usuario_principal = $form->get("usuario_principal")->getData();
                $usuarioEscenarioDeportivo = new UsuarioEscenarioDeportivo();
                $usuarioEscenarioDeportivo->setUsuario($usuario_principal);
                $usuarioEscenarioDeportivo->setEscenarioDeportivo($form->getData());
                $usuarioEscenarioDeportivo->setPrincipal(true);
                $this->em->persist($usuarioEscenarioDeportivo);

                $this->em->persist($form->getData());
                try {
                    $this->em->flush();
                    if ($editarEscenario != null) {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso3', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                    } else {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso3', array('id' => $escenarioDeportivo->getId()));
                    }
                } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                    if (strpos($e->getMessage(), 'disciplina_division')) {
                        $form->get("disciplina")->addError(
                                new FormError($this->trans->trans('error.eliminar.disciplina'))
                        );
                    }
                }
            }
        }


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;


        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }



        return $this->render('AdminBundle:EscenarioDeportivo/Pasos:paso_2.html.twig', array(
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'idescenario' => $id,
                    'nombreEscenario' => $escenarioDeportivo->getNombre()
        ));
    }

    function addpaso3Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }

        if ($escenarioDeportivo->getImagenEscenarioDividido() != null) {
            $imagenEscenarioDividido = $escenarioDeportivo->getImagenEscenarioDividido();
            $escenarioDividido = $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $escenarioDeportivo->getImagenEscenarioDividido();
        } else {
            $escenarioDividido = null;
            $imagenEscenarioDividido = null;
        }

        $tiposReservas = array();
        $divisionesEscenario = array();
        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 3,
            'escenarioId' => $escenarioDeportivo->getId(),
            'em' => $this->container,
            'tiposReservas' => $tiposReservas,
            'divisionesEscenario' => $divisionesEscenario
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $nuevasDivisiones = array();
            foreach ($form->get('divisiones') as $formdivisiones) {
                $division = $formdivisiones->getData();
                array_push($nuevasDivisiones, $division->getId());
            }

            foreach ($divisionesEscenario as $divisionValidar) {
                if ($divisionValidar->getDisponibilidad() == false) {
                    if (in_array($divisionValidar->getId(), $nuevasDivisiones) == false) {

                        $mensaje = $this->trans->trans('formulario_escenario_deportivo.labels.paso_tres.division.no_puede_eliminar_division', array('%division%' => $divisionValidar->getNombre()));
                        $form->get('divisiones')->addError(
                                new FormError($this->trans->trans($mensaje))
                        );
                    }
                }
            }
            foreach ($form->get('divisiones') as $formdivisiones) {
                $divisionesObject = $formdivisiones->getData();
                if ($divisionesObject) {

                    if ($formdivisiones->get("hora_inicial_lunes")->getData() == null &&
                            $formdivisiones->get("hora_final_lunes")->getData() == null &&
                            $formdivisiones->get("hora_inicial_martes")->getData() == null &&
                            $formdivisiones->get("hora_final_martes")->getData() == null &&
                            $formdivisiones->get("hora_inicial_miercoles")->getData() == null &&
                            $formdivisiones->get("hora_final_miercoles")->getData() == null &&
                            $formdivisiones->get("hora_inicial_jueves")->getData() == null &&
                            $formdivisiones->get("hora_final_jueves")->getData() == null &&
                            $formdivisiones->get("hora_inicial_viernes")->getData() == null &&
                            $formdivisiones->get("hora_final_viernes")->getData() == null &&
                            $formdivisiones->get("hora_inicial_sabado")->getData() == null &&
                            $formdivisiones->get("hora_final_sabado")->getData() == null &&
                            $formdivisiones->get("hora_inicial_domingo")->getData() == null &&
                            $formdivisiones->get("hora_final_domingo")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_lunes")->getData() == null &&
                            $formdivisiones->get("hora_final2_lunes")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_martes")->getData() == null &&
                            $formdivisiones->get("hora_final2_martes")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_miercoles")->getData() == null &&
                            $formdivisiones->get("hora_final2_miercoles")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_jueves")->getData() == null &&
                            $formdivisiones->get("hora_final2_jueves")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_viernes")->getData() == null &&
                            $formdivisiones->get("hora_final2_viernes")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_sabado")->getData() == null &&
                            $formdivisiones->get("hora_final2_sabado")->getData() == null &&
                            $formdivisiones->get("hora_inicial2_domingo")->getData() == null &&
                            $formdivisiones->get("hora_final2_domingo")->getData() == null
                    ) {
                        $formdivisiones->get("hora_inicial_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_horarios'))
                        );

                        $formdivisiones->get("hora_final_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_horarios'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_lunes")->getData() == $formdivisiones->get("hora_final_lunes")->getData() && $formdivisiones->get("hora_inicial_lunes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_lunes")->getData() == $formdivisiones->get("hora_final2_lunes")->getData() && $formdivisiones->get("hora_inicial2_lunes")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_martes")->getData() == $formdivisiones->get("hora_final_martes")->getData() && $formdivisiones->get("hora_inicial_martes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_martes")->getData() == $formdivisiones->get("hora_final2_martes")->getData() && $formdivisiones->get("hora_inicial2_martes")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_miercoles")->getData() == $formdivisiones->get("hora_final_miercoles")->getData() && $formdivisiones->get("hora_inicial_miercoles")->getData() != null) {

                        $formdivisiones->get("hora_inicial_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_miercoles")->getData() == $formdivisiones->get("hora_final2_miercoles")->getData() && $formdivisiones->get("hora_inicial2_miercoles")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_jueves")->getData() == $formdivisiones->get("hora_final_jueves")->getData() && $formdivisiones->get("hora_inicial_jueves")->getData() != null) {

                        $formdivisiones->get("hora_inicial_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_jueves")->getData() == $formdivisiones->get("hora_final2_jueves")->getData() && $formdivisiones->get("hora_inicial2_jueves")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_viernes")->getData() == $formdivisiones->get("hora_final_viernes")->getData() && $formdivisiones->get("hora_inicial_viernes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }

                    if ($formdivisiones->get("hora_inicial2_viernes")->getData() == $formdivisiones->get("hora_final2_viernes")->getData() && $formdivisiones->get("hora_inicial2_viernes")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_sabado")->getData() == $formdivisiones->get("hora_final_sabado")->getData() && $formdivisiones->get("hora_inicial_sabado")->getData() != null) {

                        $formdivisiones->get("hora_inicial_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_sabado")->getData() == $formdivisiones->get("hora_final2_sabado")->getData() && $formdivisiones->get("hora_inicial2_sabado")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_domingo")->getData() == $formdivisiones->get("hora_final_domingo")->getData() && $formdivisiones->get("hora_inicial_domingo")->getData() != null) {

                        $formdivisiones->get("hora_inicial_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_domingo")->getData() == $formdivisiones->get("hora_final2_domingo")->getData() && $formdivisiones->get("hora_inicial2_domingo")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );

                        $formdivisiones->get("hora_final2_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo_igual'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_lunes")->getData() != null && $formdivisiones->get("hora_final_lunes")->getData() == null ||
                            $formdivisiones->get("hora_inicial_lunes")->getData() == null && $formdivisiones->get("hora_final_lunes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_lunes")->getData() != null && $formdivisiones->get("hora_final2_lunes")->getData() == null ||
                            $formdivisiones->get("hora_inicial2_lunes")->getData() == null && $formdivisiones->get("hora_final2_lunes")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_lunes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_martes")->getData() != null && $formdivisiones->get("hora_final_martes")->getData() == null ||
                            $formdivisiones->get("hora_inicial_martes")->getData() == null && $formdivisiones->get("hora_final_martes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }



                    if ($formdivisiones->get("hora_inicial2_martes")->getData() != null && $formdivisiones->get("hora_final2_martes")->getData() == null ||
                            $formdivisiones->get("hora_inicial2_martes")->getData() == null && $formdivisiones->get("hora_final2_martes")->getData() != null) {

                        $form->get("hora_inicial2_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $form->get("hora_final2_martes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_miercoles")->getData() != null && $formdivisiones->get("hora_final_miercoles")->getData() == null ||
                            $formdivisiones->get("hora_inicial_miercoles")->getData() == null && $formdivisiones->get("hora_final_miercoles")->getData() != null) {

                        $form->get("hora_inicial_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $form->get("hora_final_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_miercoles")->getData() != null && $formdivisiones->get("hora_final2_miercoles")->getData() == null ||
                            $formdivisiones->get("hora_inicial_miercoles")->getData() == null && $formdivisiones->get("hora_final_miercoles")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_miercoles")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_jueves")->getData() != null && $formdivisiones->get("hora_final_jueves")->getData() == null ||
                            $formdivisiones->get("hora_inicial_jueves")->getData() == null && $formdivisiones->get("hora_final_jueves")->getData() != null) {

                        $formdivisiones->get("hora_inicial_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_jueves")->getData() != null && $formdivisiones->get("hora_final2_jueves")->getData() == null ||
                            $formdivisiones->get("hora_inicial2_jueves")->getData() == null && $formdivisiones->get("hora_final2_jueves")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_jueves")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }

                    if ($formdivisiones->get("hora_inicial_viernes")->getData() != null && $formdivisiones->get("hora_final_viernes")->getData() == null ||
                            $formdivisiones->get("hora_inicial_viernes")->getData() == null && $formdivisiones->get("hora_final_viernes")->getData() != null) {

                        $formdivisiones->get("hora_inicial_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_viernes")->getData() != null && $formdivisiones->get("hora_final2_viernes")->getData() == null ||
                            $formdivisiones->get("hora_inicial2_viernes")->getData() == null && $formdivisiones->get("hora_final2_viernes")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_viernes")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }



                    if ($formdivisiones->get("hora_inicial_sabado")->getData() != null && $formdivisiones->get("hora_final_sabado")->getData() == null ||
                            $formdivisiones->get("hora_inicial_sabado")->getData() == null && $formdivisiones->get("hora_final_sabado")->getData() != null) {

                        $formdivisiones->get("hora_inicial_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_sabado")->getData() != null && $formdivisiones->get("hora_final2_sabado")->getData() == null ||
                            $formdivisiones->get("hora_inicial2_sabado")->getData() == null && $formdivisiones->get("hora_final2_sabado")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_sabado")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial_domingo")->getData() != null && $formdivisiones->get("hora_final_domingo")->getData() == null ||
                            $formdivisiones->get("hora_inicial_domingo")->getData() == null && $formdivisiones->get("hora_final_domingo")->getData() != null) {

                        $formdivisiones->get("hora_inicial_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }


                    if ($formdivisiones->get("hora_inicial2_domingo")->getData() != null && $formdivisiones->get("hora_final2_domingo")->getData() == null ||
                            $formdivisiones->get("hora_inicial_domingo")->getData() == null && $formdivisiones->get("hora_final2_domingo")->getData() != null) {

                        $formdivisiones->get("hora_inicial2_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );

                        $formdivisiones->get("hora_final2_domingo")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_dos.error_campo'))
                        );
                    }



                    if ($formdivisiones->get("hora_inicial_lunes")->getData() != null && $formdivisiones->get("hora_final_lunes")->getData() != null) {

                        $horaInicioLunes = date("H:i", strtotime($formdivisiones->get("hora_inicial_lunes")->getData()));
                        $horaFinalLunes = date("H:i", strtotime($formdivisiones->get("hora_final_lunes")->getData()));

                        if ($horaInicioLunes > $horaFinalLunes) {

                            $formdivisiones->get("hora_inicial_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_lunes")->getData() != null && $formdivisiones->get("hora_final2_lunes")->getData() != null) {

                        $horaInicioLunes = date("H:i", strtotime($formdivisiones->get("hora_inicial2_lunes")->getData()));
                        $horaFinalLunes = date("H:i", strtotime($formdivisiones->get("hora_final2_lunes")->getData()));

                        if ($horaInicioLunes > $horaFinalLunes) {

                            $formdivisiones->get("hora_inicial2_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_martes")->getData() != null && $formdivisiones->get("hora_final_martes")->getData() != null) {

                        $horaInicioMartes = date("H:i", strtotime($formdivisiones->get("hora_inicial_martes")->getData()));
                        $horaFinalMartes = date("H:i", strtotime($formdivisiones->get("hora_final_martes")->getData()));

                        if ($horaInicioMartes > $horaFinalMartes) {

                            $formdivisiones->get("hora_inicial_martes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_martes")->getData() != null && $formdivisiones->get("hora_final2_martes")->getData() != null) {

                        $horaInicioMartes = date("H:i", strtotime($formdivisiones->get("hora_inicial2_martes")->getData()));
                        $horaFinalMartes = date("H:i", strtotime($formdivisiones->get("hora_final2_martes")->getData()));

                        if ($horaInicioMartes > $horaFinalMartes) {

                            $formdivisiones->get("hora_inicial2_martes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_miercoles")->getData() != null && $formdivisiones->get("hora_final_miercoles")->getData() != null) {

                        $horaInicioMiercoles = date("H:i", strtotime($formdivisiones->get("hora_inicial_miercoles")->getData()));
                        $horaFinalMiercoles = date("H:i", strtotime($formdivisiones->get("hora_final_miercoles")->getData()));

                        if ($horaInicioMiercoles > $horaFinalMiercoles) {

                            $formdivisiones->get("hora_inicial_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_miercoles")->getData() != null && $formdivisiones->get("hora_final2_miercoles")->getData() != null) {

                        $horaInicioMiercoles = date("H:i", strtotime($formdivisiones->get("hora_inicial2_miercoles")->getData()));
                        $horaFinalMiercoles = date("H:i", strtotime($formdivisiones->get("hora_final2_miercoles")->getData()));

                        if ($horaInicioMiercoles > $horaFinalMiercoles) {

                            $formdivisiones->get("hora_inicial2_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_jueves")->getData() != null && $formdivisiones->get("hora_final_jueves")->getData() != null) {

                        $horaInicioJueves = date("H:i", strtotime($formdivisiones->get("hora_inicial_jueves")->getData()));
                        $horaFinalJueves = date("H:i", strtotime($formdivisiones->get("hora_final_jueves")->getData()));

                        if ($horaInicioJueves > $horaFinalJueves) {

                            $formdivisiones->get("hora_inicial_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_jueves")->getData() != null && $formdivisiones->get("hora_final2_jueves")->getData() != null) {

                        $horaInicioJueves = date("H:i", strtotime($formdivisiones->get("hora_inicial2_jueves")->getData()));
                        $horaFinalJueves = date("H:i", strtotime($formdivisiones->get("hora_final2_jueves")->getData()));

                        if ($horaInicioJueves > $horaFinalJueves) {

                            $formdivisiones->get("hora_inicial2_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_viernes")->getData() != null && $formdivisiones->get("hora_final_viernes")->getData() != null) {

                        $horaInicioViernes = date("H:i", strtotime($formdivisiones->get("hora_inicial_viernes")->getData()));
                        $horaFinalViernes = date("H:i", strtotime($formdivisiones->get("hora_final_viernes")->getData()));

                        if ($horaInicioViernes > $horaFinalViernes) {

                            $formdivisiones->get("hora_inicial_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_viernes")->getData() != null && $formdivisiones->get("hora_final2_viernes")->getData() != null) {

                        $horaInicioViernes = date("H:i", strtotime($formdivisiones->get("hora_inicial2_viernes")->getData()));
                        $horaFinalViernes = date("H:i", strtotime($formdivisiones->get("hora_final2_viernes")->getData()));

                        if ($horaInicioViernes > $horaFinalViernes) {

                            $formdivisiones->get("hora_inicial2_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_sabado")->getData() != null && $formdivisiones->get("hora_final_sabado")->getData() != null) {

                        $horaInicioSabado = date("H:i", strtotime($formdivisiones->get("hora_inicial_sabado")->getData()));
                        $horaFinalSabado = date("H:i", strtotime($formdivisiones->get("hora_final_sabado")->getData()));

                        if ($horaInicioSabado > $horaFinalSabado) {

                            $formdivisiones->get("hora_inicial_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_sabado")->getData() != null && $formdivisiones->get("hora_final2_sabado")->getData() != null) {

                        $horaInicioSabado = date("H:i", strtotime($formdivisiones->get("hora_inicial2_sabado")->getData()));
                        $horaFinalSabado = date("H:i", strtotime($formdivisiones->get("hora_final2_sabado")->getData()));

                        if ($horaInicioSabado > $horaFinalSabado) {

                            $formdivisiones->get("hora_inicial2_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial_domingo")->getData() != null && $formdivisiones->get("hora_final_domingo")->getData() != null) {

                        $horaInicioSabado = date("H:i", strtotime($formdivisiones->get("hora_inicial_domingo")->getData()));
                        $horaFinalSabado = date("H:i", strtotime($formdivisiones->get("hora_final_domingo")->getData()));

                        if ($horaInicioSabado > $horaFinalSabado) {

                            $formdivisiones->get("hora_inicial_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial1_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_domingo")->getData() != null && $formdivisiones->get("hora_final2_domingo")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_domingo")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final2_domingo")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial2_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial2_mayor'))
                            );
                        }
                    }

                    //intermedios para el domingo

                    if ($formdivisiones->get("hora_inicial_domingo")->getData() != null && $formdivisiones->get("hora_inicial2_domingo")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_domingo")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_domingo")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_domingo")->getData() != null && $formdivisiones->get("hora_inicial2_domingo")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_domingo")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_domingo")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }

                    //intermedios para el sabado


                    if ($formdivisiones->get("hora_inicial_sabado")->getData() != null && $formdivisiones->get("hora_inicial2_sabado")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_sabado")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_sabado")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_sabado")->getData() != null && $formdivisiones->get("hora_inicial2_sabado")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_sabado")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_sabado")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }


                    //intermedios para el viernes

                    if ($formdivisiones->get("hora_inicial_viernes")->getData() != null && $formdivisiones->get("hora_inicial2_viernes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_viernes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_viernes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_viernes")->getData() != null && $formdivisiones->get("hora_inicial2_viernes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_viernes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_viernes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }

                    //intermedios para el jueves

                    if ($formdivisiones->get("hora_inicial_jueves")->getData() != null && $formdivisiones->get("hora_inicial2_jueves")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_jueves")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_jueves")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_jueves")->getData() != null && $formdivisiones->get("hora_inicial2_jueves")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_jueves")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_jueves")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }

                    //intermedios para el miercoles


                    if ($formdivisiones->get("hora_inicial_miercoles")->getData() != null && $formdivisiones->get("hora_inicial2_miercoles")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_miercoles")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_miercoles")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_miercoles")->getData() != null && $formdivisiones->get("hora_inicial2_miercoles")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_miercoles")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_miercoles")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }

                    //intermedios martes

                    if ($formdivisiones->get("hora_inicial_martes")->getData() != null && $formdivisiones->get("hora_inicial2_martes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_martes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_martes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_martes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_martes")->getData() != null && $formdivisiones->get("hora_inicial2_martes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_martes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_martes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }

                    //intermedios lunes

                    if ($formdivisiones->get("hora_inicial_lunes")->getData() != null && $formdivisiones->get("hora_inicial2_lunes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial_lunes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_lunes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_inicial_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial3_mayor'))
                            );
                        }
                    }

                    if ($formdivisiones->get("hora_final_lunes")->getData() != null && $formdivisiones->get("hora_inicial2_lunes")->getData() != null) {

                        $horaInicioDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_lunes")->getData()));
                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_lunes")->getData()));

                        if ($horaInicioDomingo > $horaFinalDomingo) {

                            $formdivisiones->get("hora_final_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial4_mayor'))
                            );
                        }
                    }


                    //horarios para la mañana
                    if ($formdivisiones->get("hora_final_lunes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_lunes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_martes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_martes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_martes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_miercoles")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_miercoles")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_jueves")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_jueves")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_viernes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_viernes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_sabado")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_sabado")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_final_domingo")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_final_domingo")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo > $horaControl) {

                            $formdivisiones->get("hora_final_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial5_mayor'))
                            );
                        }
                    }


                    //horarios para la tarde
                    if ($formdivisiones->get("hora_inicial2_lunes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_lunes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_lunes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_martes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_martes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_martes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_miercoles")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_miercoles")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_miercoles")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_jueves")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_jueves")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_jueves")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_viernes")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_viernes")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_viernes")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }


                    if ($formdivisiones->get("hora_inicial2_sabado")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_sabado")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_sabado")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }
                    if ($formdivisiones->get("hora_inicial2_domingo")->getData() != null) {

                        $horaFinalDomingo = date("H:i", strtotime($formdivisiones->get("hora_inicial2_domingo")->getData()));
                        $horaControl = date("H:i", strtotime("12:00"));

                        if ($horaFinalDomingo < $horaControl) {

                            $formdivisiones->get("hora_inicial2_domingo")->addError(
                                    new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_hora_inicial6_mayor'))
                            );
                        }
                    }
                    foreach ($divisionesObject->getTipoReservaEscenarioDeportivoDivisiones() as $tipoReserva) {
                        if ($tipoReserva->getDiasPreviosReserva() < 0) {
                            $formdivisiones->get("dias_previos_reserva")->addError(
                                    new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_tres.division.error_negativo'))
                            );
                        }
                    }
                    if ($divisionesObject->getEdadMinima() < 0) {
                        $formdivisiones->get("edad_minima")->addError(
                                new FormError($this->trans->trans('formulario_escenario_deportivo.labels.paso_tres.division.error_negativo'))
                        );
                    }
                    if ($divisionesObject->getEdadMinima() == null) {
                        $divisionesObject->setEdadMinima(0);
                    }
                }
            }

            if ($form->isValid()) {
                foreach ($form->get('divisiones') as $formdivisiones) {

                    $divisionesObject = $formdivisiones->getData();

                    /* Tipos reserva original data */
                    $originalTiposReservaEscenarioDeportivo = [];

                    if ($divisionesObject->getTiposReservaEscenarioDeportivo() != null) {
                        foreach ($divisionesObject->getTiposReservaEscenarioDeportivo() as $key => $tiposReservaEscenarioDeportivo) {
                            $originalTiposReservaEscenarioDeportivo[] = ["tipoReserva" => $tiposReservaEscenarioDeportivo->getTipoReserva(), "tiposReservaEscenarioDeportivo" => $tiposReservaEscenarioDeportivo];
                        }

                        foreach ($originalTiposReservaEscenarioDeportivo as $orgTiposReservaEscenarioDeportivo) {
                            $divisionesObject->removeTipoReservaEscenarioDeportivo($orgTiposReservaEscenarioDeportivo ['tiposReservaEscenarioDeportivo']);
                        }
                    }
                    if ($formdivisiones->get('tipoReservaEscenarioDeportivoDivisiones')->getData()) {
                        foreach ($formdivisiones->get('tipoReservaEscenarioDeportivoDivisiones') as $tipoReserva) {
                            $tipoReservaEscenarioDeportivo = $this->em->getRepository("LogicBundle:TipoReservaEscenarioDeportivo")
                                            ->createQueryBuilder('tipoReservaEscenarioDeportivo')
                                            ->where('tipoReservaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo ')
                                            ->andWhere('tipoReservaEscenarioDeportivo.tipoReserva = :tipoReserva ')
                                            ->setParameter('escenarioDeportivo', $form->getData()->getId())
                                            ->setParameter('tipoReserva', $tipoReserva->get('tipoReserva')->getData()->getId())
                                            ->getQuery()->getOneOrNullResult();

                            if ($divisionesObject->getTiposReservaEscenarioDeportivo() != null) {
                                if ($divisionesObject->getId()) {
                                    $arrayTiposReserva = $divisionesObject->getTiposReservaEscenarioDeportivo()->getValues();
                                } else {
                                    $arrayTiposReserva = $divisionesObject->getTiposReservaEscenarioDeportivo();
                                }
                                if (!in_array($tipoReservaEscenarioDeportivo, $arrayTiposReserva)) {
                                    $divisionesObject->addTipoReservaEscenarioDeportivo($tipoReservaEscenarioDeportivo);
                                }
                            } else {
                                $divisionesObject->addTipoReservaEscenarioDeportivo($tipoReservaEscenarioDeportivo);
                            }

                            if ($tipoReservaEscenarioDeportivo != null) {
                                $tipoReserva->getData()->setTipoReservaEscenarioDeportivo($tipoReservaEscenarioDeportivo);
                                $tipoReserva->getData()->setDivision($divisionesObject);
                                $this->em->persist($tipoReserva->getData());
                            }
                        }
                    }

                    /* Escenario */
                    $divisionesObject->setEscenarioDeportivo($escenarioDeportivo);

                    /* Disciplinas original data */
                    $originalDisciplinasEscenarioDeportivo = [];
                    foreach ($divisionesObject->getDisciplinasEscenarioDeportivo() as $key => $disciplinasEscenarioDeportivo) {
                        $originalDisciplinasEscenarioDeportivo[] = ["disciplina" => $disciplinasEscenarioDeportivo->getDisciplina(), "disciplinasEscenarioDeportivo" => $disciplinasEscenarioDeportivo];
                    }

                    /* Tendencias original data */
                    $originalTendenciaEscenarioDeportivo = [];
                    foreach ($divisionesObject->getTendenciasEscenarioDeportivo() as $key => $tendenciaEscenarioDeportivo) {
                        $originalTendenciaEscenarioDeportivo[] = ["tendencia" => $tendenciaEscenarioDeportivo->getTendencia(), "tendenciaEscenarioDeportivo" => $tendenciaEscenarioDeportivo];
                    }


                    /* Disciplinas */
                    foreach ($originalDisciplinasEscenarioDeportivo as $orgDisciplinasEscenarioDeportivo) {
                        $divisionesObject->removeDisciplinasEscenarioDeportivo($orgDisciplinasEscenarioDeportivo ['disciplinasEscenarioDeportivo']);
                    }
                    foreach ($formdivisiones->get("disciplina")->getData() as $key => $disciplina) {
                        $disciplinasEscenarioDeportivo = $this->em->getRepository("LogicBundle:DisciplinasEscenarioDeportivo")
                                        ->createQueryBuilder('disciplinasEscenarioDeportivo')
                                        ->where('disciplinasEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo ')
                                        ->andWhere('disciplinasEscenarioDeportivo.disciplina = :disciplina')
                                        ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                        ->setParameter('disciplina', $disciplina->getId())
                                        ->getQuery()->getOneOrNullResult();

                        if ($disciplinasEscenarioDeportivo == null) {
                            $disciplinasEscenarioDeportivo = new DisciplinasEscenarioDeportivo();
                            $disciplinasEscenarioDeportivo->setDisciplina($disciplina);
                            $disciplinasEscenarioDeportivo->setEscenarioDeportivo($escenarioDeportivo);
                            $this->em->persist($disciplinasEscenarioDeportivo);
                            $this->em->flush();
                        }
                        $divisionesObject->addDisciplinasEscenarioDeportivo($disciplinasEscenarioDeportivo);
                        $this->em->persist($disciplinasEscenarioDeportivo);
                    }


                    /* Tendencias */
                    foreach ($originalTendenciaEscenarioDeportivo as $orgTendenciaEscenarioDeportivo) {
                        $divisionesObject->removeTendenciaEscenarioDeportivo($orgTendenciaEscenarioDeportivo ['tendenciaEscenarioDeportivo']);
                    }
                    foreach ($formdivisiones->get("tendencia")->getData() as $key => $tendencia) {
                        $tendenciasEscenarioDeportivo = $this->em->getRepository("LogicBundle:TendenciaEscenarioDeportivo")
                                        ->createQueryBuilder('tendenciaEscenarioDeportivo')
                                        ->where('tendenciaEscenarioDeportivo.escenarioDeportivo = :escenarioDeportivo')
                                        ->andWhere('tendenciaEscenarioDeportivo.tendencia = :tendencia')
                                        ->setParameter('escenarioDeportivo', $escenarioDeportivo->getId())
                                        ->setParameter('tendencia', $tendencia->getId())
                                        ->getQuery()->getOneOrNullResult();

                        $divisionesObject->addTendenciaEscenarioDeportivo($tendenciasEscenarioDeportivo);
                        $this->em->persist($tendenciasEscenarioDeportivo);
                    }
                }

                if ($form->get('imagenEscenarioDividido')->getData() == null) {
                    $form->getData()->setImagenEscenarioDividido($imagenEscenarioDividido);
                }
                $this->em->persist($form->getData());
                $this->em->flush();
                if ($editarEscenario != null) {
                    if ($editarEscenarioCompleto == true) {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                    } else {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                    }
                } else {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId()));
                }
            }
        }



        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;


        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }
        return $this->render('AdminBundle:EscenarioDeportivo/Pasos:paso_3.html.twig', array(
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'tieneProfundidad' => false,
                    'idescenario' => $id,
                    'nombreEscenario' => $escenarioDeportivo->getNombre(),
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'escenarioDividido' => $escenarioDividido
        ));
    }

    function addpaso4Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }

        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 4,
            'escenarioId' => $escenarioDeportivo->getId(),
            'escenarioDeportivo' => $escenarioDeportivo,
            'em' => $this->container
        ));



        $form->handleRequest($request);

        $planos = [];
        foreach ($escenarioDeportivo->getArchivoEscenarios() as $archivoEscenarios) {
            if ($archivoEscenarios->getType() == "plano") {
                array_push($planos, $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $archivoEscenarios->getFile());
            }
        }

        if (empty($planos)) {
            $planos = null;
        }

        $imagenes = [];
        foreach ($escenarioDeportivo->getArchivoEscenarios() as $archivoEscenarios) {
            if ($archivoEscenarios->getType() == "imagen") {
                array_push($imagenes, $this->container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $archivoEscenarios->getFile());
            }
        }

        if (empty($imagenes)) {
            $imagenes = null;
        }

        if ($form->get("planos")->getData() != null) {
            foreach ($escenarioDeportivo->getArchivoEscenarios() as $key => $archivo) {
                if ($archivo->getType() == "plano") {
                    $this->em->remove($archivo);
                }
            }
        }

        if ($form->get("imagenes")->getData() != null) {
            foreach ($escenarioDeportivo->getArchivoEscenarios() as $key => $archivo) {
                if ($archivo->getType() == "imagen") {
                    $this->em->remove($archivo);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->get("planos")->getData() as $key => $planos) {
                $archivoEscenario = new ArchivoEscenario();
                $archivoEscenario->setEscenarioDeportivo($escenarioDeportivo);

                if (filesize($planos) == false) {

                    // $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.archivo'));
                    /* $form->get("matricula")->addError(
                      new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error2'))
                      ); */
                    if ($editarEscenario != null) {
                        ///return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId(), 'edit' => true));                    
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId(), 'error' => true, 'edit' => true));
                    } else {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId(), 'error' => true));
                    }
                } else {

                    $archivoEscenario->setType("plano");
                    $archivoEscenario->setFile($planos);


                    $this->em->persist($archivoEscenario);
                }
            }

            foreach ($form->get("imagenes")->getData() as $key => $imagenes) {
                $archivoEscenario = new ArchivoEscenario();
                $archivoEscenario->setEscenarioDeportivo($escenarioDeportivo);

                if (filesize($imagenes) == false) {
                    // $this->addFlash('sonata_flash_error', $this->trans('formulario_escenario_deportivo.archivo'));
                    /* $form->get("matricula")->addError(
                      new FormError($this->trans->trans('formulario_reserva.labels.paso_uno.error_disponibilidad_error2'))
                      ); */
                    if ($editarEscenario != null) {
                        ///return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId(), 'edit' => true));                    
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId(), 'error' => true, 'edit' => true));
                    } else {
                        return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso4', array('id' => $escenarioDeportivo->getId(), 'error' => true));
                    }
                } else {

                    $archivoEscenario->setType("imagen");
                    $archivoEscenario->setFile($imagenes);

                    $this->em->persist($archivoEscenario);
                }
            }

            if ($form->get("eliminarPlanos")->getData() == true) {
                foreach ($escenarioDeportivo->getArchivoEscenarios() as $key => $archivo) {
                    if ($archivo->getType() == "plano") {
                        $this->em->remove($archivo);
                    }
                }
            }

            if ($form->get("eliminarImagenes")->getData() == true) {
                foreach ($escenarioDeportivo->getArchivoEscenarios() as $key => $archivo) {
                    if ($archivo->getType() == "imagen") {
                        $this->em->remove($archivo);
                    }
                }
            }

            $this->em->persist($form->getData());
            $this->em->flush();




            //validacion para mostrar pasos 
            $validacionTipoEscenario = 0;


            if ($escenarioDeportivo != null) {
                if ($escenarioDeportivo->getTipoEscenario() != null) {
                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                        $validacionTipoEscenario = 1;
                    }


                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                        $validacionTipoEscenario = 1;
                    }

                    if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                        $validacionTipoEscenario = 1;
                    }
                }
            }

            if ($validacionTipoEscenario == 0) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId()));
            }


            /* return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' =>$escenarioDeportivo->getId())); */
//            if ($editarEscenario != null) {                    
//                ///return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId(), 'edit' => true));                    
//                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId(), 'edit' => true));
//            }else{
//                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId()));
//            }



            if ($editarEscenario != null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
            } else {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId()));
            }
            //return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId()));
        }


        $errorFormulario = $request->get('error');

        if ($errorFormulario != null) {

            $errorTamaño = 1;
        } else {

            $errorTamaño = 0;
        }


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;


        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }

        return $this->render('AdminBundle:EscenarioDeportivo/Pasos:paso_4.html.twig', array(
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'idescenario' => $id,
                    'errorTamaño' => $errorTamaño,
                    'tieneAcueducto' => $escenarioDeportivo->getTieneAcueducto(),
                    'tieneEnergia' => $escenarioDeportivo->getTieneEnergia(),
                    'tieneTelefono' => $escenarioDeportivo->getTieneTelefono(),
                    'tieneIluminacion' => $escenarioDeportivo->getTieneIluminacion(),
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'imagenesEscenario' => $imagenes,
                    'planosEscenario' => $planos,
                    'nombreEscenario' => $escenarioDeportivo->getNombre(),
                    'codigoEscenario' => $escenarioDeportivo->getCodigoEscenario()
        ));
    }

    function addpaso41Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }

        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 41,
            'escenarioId' => $escenarioDeportivo->getId(),
            'tipoEscenario' => $escenarioDeportivo->getTipoEscenario(),
            'em' => $this->container
        ));

        $form->handleRequest($request);
        $campos = [];

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $escenario = $form->getData();
                $this->em->persist($escenario);
                $this->em->flush();

                $campos = $request->request->get("form");

                if ($campos) {
                    foreach ($campos as $key => $valor) {
                        $datos = explode("_", $key);
                        $escenarioCategoriaSubCategoriaInfraestructura = null;
                        if (count($datos) >= 3) {
                            $escenarioCategoriaInfraestructura = $form->get("escenarioCategoriaInfraestructuras")->getData()[$datos[0]];
                            if ($escenarioCategoriaInfraestructura) {
                                $escenarioCategoriaSubCategoriaInfraestructura = $escenarioCategoriaInfraestructura->getEscenarioCategoriaSubCategoriaInfraestructuras()[$datos[1]];
                            }
                        }
                        if ($escenarioCategoriaSubCategoriaInfraestructura) {
                            if ($escenarioCategoriaSubCategoriaInfraestructura->getSubcategoriaInfraestructura()) {
                                $subCategoria = $escenarioCategoriaSubCategoriaInfraestructura->getSubcategoriaInfraestructura();
                                foreach ($subCategoria->getCampoInfraestructuras() as $key => $campoEntity) {
                                    if ($campoEntity->getId() == $datos[2]) {
                                        $escenarioSubCategoriaInfraestructuraCampo = $this->em->getRepository("LogicBundle:EscenarioSubCategoriaInfraestructuraCampo")->buscarValorAlmacenado($escenarioCategoriaSubCategoriaInfraestructura->getId(), $campoEntity->getId(), $subCategoria->getId());
                                        if (!$escenarioSubCategoriaInfraestructuraCampo) {
                                            $escenarioSubCategoriaInfraestructuraCampo = new EscenarioSubCategoriaInfraestructuraCampo();
                                        }

                                        $escenarioSubCategoriaInfraestructuraCampo->setCampoInfraestructura($campoEntity);
                                        $escenarioSubCategoriaInfraestructuraCampo->setEscenarioCategoriaSubCategoria($escenarioCategoriaSubCategoriaInfraestructura);
                                        $escenarioSubCategoriaInfraestructuraCampo->setValor($valor);

                                        $this->em->persist($escenarioSubCategoriaInfraestructuraCampo);
                                        $this->em->flush();
                                    }
                                }
                            }
                        }
                    }
                }

                //return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso42', array('id' =>$escenarioDeportivo->getId()));
                if ($editarEscenario != null) {
                    ///return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso41', array('id' =>$escenarioDeportivo->getId(), 'edit' => true));                    
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso42', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                } else {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso42', array('id' => $escenarioDeportivo->getId()));
                }
            } else {
                $campos = $request->request->get("form");
            }
        }


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;

        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }

        return $this->renderWithExtraParams('AdminBundle:EscenarioDeportivo/Pasos:paso_4_1.html.twig', [
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'idescenario' => $id,
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'nombreEscenario' => $escenarioDeportivo->getNombre(),
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'campos' => $campos
                        ], null);
    }

    function addpaso42Action(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);
            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }
        $form = $this->createForm(EscenarioDeportivoType::class, $escenarioDeportivo, array(
            'paso' => 42,
            'escenarioId' => $escenarioDeportivo->getId(),
            'em' => $this->container
        ));

        $form->handleRequest($request);
        $campos = [];

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $escenario = $form->getData();
                $this->em->persist($escenario);
                $this->em->flush();

                $campos = $request->request->get("form");

                if ($campos) {
                    foreach ($campos as $key => $valor) {
                        $datos = explode("_", $key);
                        $escenarioSubCategoriaAmbiental = null;
                        if (count($datos) >= 3) {
                            $escenarioCategoriaAmbiental = $form->get("escenarioCategoriaAmbientales")->getData()[$datos[0]];
                            if ($escenarioCategoriaAmbiental) {
                                $escenarioSubCategoriaAmbiental = $escenarioCategoriaAmbiental->getEscenarioSubCategoriaAmbientales()[$datos[1]];
                            }
                        }

                        if ($escenarioSubCategoriaAmbiental) {
                            if ($escenarioSubCategoriaAmbiental->getSubcategoriaAmbiental()) {
                                $subCategoria = $escenarioSubCategoriaAmbiental->getSubcategoriaAmbiental();
                                foreach ($subCategoria->getCampoAmbientales() as $key => $campoEntity) {
                                    if ($campoEntity->getId() == $datos[2]) {
                                        $escenarioSubCategoriaAmbientalCampo = $this->em->getRepository("LogicBundle:EscenarioSubCategoriaAmbientalCampo")->buscarValorAlmacenado($escenarioSubCategoriaAmbiental->getId(), $campoEntity->getId(), $subCategoria->getId());
                                        if (!$escenarioSubCategoriaAmbientalCampo) {
                                            $escenarioSubCategoriaAmbientalCampo = new EscenarioSubCategoriaAmbientalCampo();
                                        }

                                        $escenarioSubCategoriaAmbientalCampo->setCampoAmbiental($campoEntity);
                                        $escenarioSubCategoriaAmbientalCampo->setEscenarioSubCategoriaAmbiental($escenarioSubCategoriaAmbiental);
                                        $escenarioSubCategoriaAmbientalCampo->setValor($valor);

                                        $this->em->persist($escenarioSubCategoriaAmbientalCampo);
                                        $this->em->flush();
                                    }
                                }
                            }
                        }
                    }
                }

                if ($editarEscenario != null) {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId(), 'edit' => true));
                } else {
                    return $this->redirectToRoute('admin_logic_escenariodeportivo_escenarioTerminado', array('id' => $escenarioDeportivo->getId()));
                }
            } else {
                $campos = $request->request->get("form");
            }
        }


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;

        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }

        return $this->renderWithExtraParams('AdminBundle:EscenarioDeportivo/Pasos:paso_4_2.html.twig', [
                    'action' => 'edit',
                    'form' => $form->createView(),
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'idescenario' => $id,
                    'nombreEscenario' => $escenarioDeportivo->getNombre(),
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'campos' => $campos
                        ], null);
    }

    function escenarioTerminadoAction(Request $request, $id) {
        $editarEscenario = $request->get('edit');
        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $editarEscenarioCompleto = false;
        if ($ROLE_SUPER_ADMIN) {
            $editarEscenarioCompleto = true;
        }
        if ($id == 0) {
            return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
        } else {
            $escenarioDeportivo = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($id);

            if ($escenarioDeportivo === null) {
                return $this->redirectToRoute('admin_logic_escenariodeportivo_addpaso1', array('id' => 0));
            }
        }


        //validacion para mostrar pasos 
        $validacionTipoEscenario = 0;

        if ($escenarioDeportivo != null) {
            if ($escenarioDeportivo->getTipoEscenario() != null) {
                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "CHORRITOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "GIMNASIOS") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "JUEGOS INFANTILES") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "LUDOTEKA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISCINA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE SKATE") {
                    $validacionTipoEscenario = 1;
                }


                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PISTA DE TROTE") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "PLACA POLIDEPORTIVA") {
                    $validacionTipoEscenario = 1;
                }

                if ($escenarioDeportivo->getTipoEscenario()->getNombre() == "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS") {
                    $validacionTipoEscenario = 1;
                }
            }
        }


        return $this->renderWithExtraParams('AdminBundle:EscenarioDeportivo:escenarioterminado.html.twig', [
                    'action' => 'edit',
                    'editarEscenario' => $editarEscenario,
                    'editarEscenarioCompleto' => $editarEscenarioCompleto,
                    'validacionTipoEscenario' => $validacionTipoEscenario,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'idescenario' => $id,
                    'nombreEscenario' => $escenarioDeportivo->getNombre()
                        ], null);
    }

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
        //$this->setFormTheme($formView, $this->admin->getFilterTheme());

        $user = $this->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        if ($ROLE_SUPER_ADMIN == true || $ROLE_GESTOR_ESCENARIO == true) {
            $puedeCrearEscenario = true;
        } else {
            $puedeCrearEscenario = false;
        }

        return $this->renderWithExtraParams($this->admin->getTemplate('list'), [
                    'action' => 'list',
                    'form' => $formView,
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'puedeCrearEscenario' => $puedeCrearEscenario,
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
                        ], null);
    }

}
