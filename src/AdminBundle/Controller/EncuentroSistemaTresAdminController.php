<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\InfoComplementariaUserType;
use Doctrine\Common\Inflector\Inflector;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\Asistencia;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\Reserva;
use AdminBundle\ValidData\Validaciones;
use AdminBundle\ValidData\ValidarDatos;
use LogicBundle\Entity\TipoReserva;
//use LogicBundle\Entity\SistemaJuegoUno;
//use LogicBundle\Form\EncuentroSistemaUnoType;
use LogicBundle\Entity\EncuentroSistemaTres;
use LogicBundle\Entity\FaltasEncuentroSistemaTres;
use LogicBundle\Form\FaltasEncuentroSistemaTresType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use ReflectionClass;
use DateTime;
use ReflectionMethod;
use RuntimeException;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;

class EncuentroSistemaTresAdminController extends CRUDController {

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
     * resultadoEncuentro action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function resultadoEncuentroAction() {
        $request = $this->getRequest();
        $idEncuentro = $request->get('idEncuentro');
        $tipoDeSistema = $request->get('tipo');
        if ($idEncuentro == 0) {
            $encuentro = new EncuentroSistemaTres();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")->find($idEncuentro);
        }
        if (count($encuentro->getTipoSistema())) {
            $idEvento = $encuentro->getEvento()->getId();
        }
        if ($encuentro->getTipoSistema() == "Eliminatoria") {
            $tipoSistema = 5;
        }
        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);
        }
        if (count($evento->getEquipoEventos()) % 2) {
            $this->addFlash('sonata_flash_error', "Se Necesita un equipo de más para generar la siguiente fase de eliminatoria");
            return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $evento->getId(), 'tipo' => $tipoSistema));
        }
        $cupo = $evento->getCupo();
        $jugadores = $evento->getJugadorEventos();
        $jugadoresEvento = array();
        $equipos = null;
        $equiposEvento = array();
        $idJugador1 = 0;
        $idJugador1 = $encuentro->getCompetidorUno();
        $idJugador2 = $encuentro->getCompetidorDos();

        if ($cupo == "Equipos") {
            $idEquipoEvento1 = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador1);
            $idEquipoEvento2 = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador2);
            $nombreJugador1 = $idEquipoEvento1->getNombre();
            $nombreJugador2 = $idEquipoEvento2->getNombre();

            $faltaJugador1 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaTres', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaTres = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idEquipoEvento1->getId())
                    ->getQuery()
                    ->getResult()
            ;
            if (count($encuentro->getPuntosCompetidorUno())) {
                $golesEquipo1 = $encuentro->getPuntosCompetidorUno();
                $validacionEdicion = true;
            } else {
                $golesEquipo1 = null;
                $validacionEdicion = false;
            }
            if (count($encuentro->getPuntosCompetidorDos())) {
                $golesEquipo2 = $encuentro->getPuntosCompetidorDos();
                $validacionEdicion = true;
            } else {
                $golesEquipo2 = null;
                $validacionEdicion = false;
            }
            $faltaJugador2 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaTres', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaTres = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idEquipoEvento2->getId())
                    ->getQuery()
                    ->getResult();
        }


        $form = $this->createForm(FaltasEncuentroSistemaTresType::class, $encuentro, array(
            'faltasJugador1' => $faltaJugador1,
            'faltasJugador2' => $faltaJugador2,
            'golesEquipo1' => $golesEquipo1,
            'golesEquipo2' => $golesEquipo2,
            'validarEdicion' => $validacionEdicion,
            'disciplina' => $evento->getDisciplina()->getNombre(),
        ));
        $form->setData($faltaJugador1, $faltaJugador2);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($evento->getDisciplina()->getNombre() == "Voleibol" || $evento->getDisciplina()->getNombre() == "VOLEIBOL") {
                    $encuentro->setPuntosCompetidorUno($form->get("setsAfavor")->getData());
                    $encuentro->setPuntosCompetidorDos($form->get("setsAfavor2")->getData());
                }

                $this->em->persist($encuentro);
                $this->em->flush();

                $datosFalta = $request->request->get('faltas_encuentro_sistema_tres');
                if (isset($datosFalta['faltasEncuentroJugador1'])) {
                    $datosJugador1 = $datosFalta['faltasEncuentroJugador1'];
                } else {
                    $datosJugador1 = null;
                }
                if (isset($datosFalta['faltasEncuentroJugador2'])) {
                    $datosJugador2 = $datosFalta['faltasEncuentroJugador2'];
                } else {
                    $datosJugador2 = null;
                }
                if ($cupo == "Equipos") {
                    if (count($datosJugador1)) {
                        $encuentroSistemaTres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")->find($idEncuentro);

                        $idEquipoEvento = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador1);

                        foreach ($datosJugador1 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $idEquipoEvento)
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaTres = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroSistemaTres();
                                $faltasEncuentro->setEncuentroSistemaTres($encuentro);
                                $faltasEncuentro->setEquipoEvento($idEquipoEvento);

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }//exit();
                            $this->em->flush();
                        }
                    }
                    if (count($datosJugador2)) {

                        $encuentroSistemaTres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")->find($idEncuentro);
                        $idEquipoEvento = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador2);
                        foreach ($datosJugador2 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaTres = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $idEquipoEvento->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();
                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroSistemaTres();
                                $faltasEncuentro->setEncuentroSistemaTres($encuentroSistemaTres);
                                $faltasEncuentro->setEquipoEvento($idEquipoEvento);
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }
                    //validar que solo se partido de ida
                    if ($encuentro->getTipoJuego() == true) {
                        if ($encuentro->getLlave() % 2) {
                            $equipoGanador2 = null;
                            $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->andWhere('r.evento = :evento')
                                    ->andWhere('r.llave = :llave')
                                    ->andWhere('r.ronda = :ronda')
                                    ->setParameter('evento', $encuentro->getEvento()->getId())
                                    ->setParameter('ronda', $encuentro->getRonda())
                                    ->setParameter('llave', ($encuentro->getLlave() + 1))
                                    ->getQuery()
                                    ->getResult();
                            $existeEncuentro = current($existeEncuentro);
                            if ($encuentro->getPuntosCompetidorUno() > $encuentro->getPuntosCompetidorDos()) {
                                $equipoGanador = $encuentro->getCompetidorUno();
                            } else if ($encuentro->getPuntosCompetidorDos() > $encuentro->getPuntosCompetidorUno()) {
                                $equipoGanador = $encuentro->getCompetidorDos();
                            }
                            if ($existeEncuentro) {
                                if ($existeEncuentro->getPuntosCompetidorUno() > $existeEncuentro->getPuntosCompetidorDos()) {
                                    $equipoGanador2 = $existeEncuentro->getCompetidorUno();
                                } else if ($existeEncuentro->getPuntosCompetidorDos() > $existeEncuentro->getPuntosCompetidorUno()) {
                                    $equipoGanador2 = $existeEncuentro->getCompetidorDos();
                                }
                            } else {
                                $equipoGanador2 = null;
                            }
                            if ($equipoGanador2 != null) {
                                $llaveAsignada = ($encuentro->getLlave() + 1) / 2;
                                $sistemaTres = new EncuentroSistemaTres();
                                $sistemaTres->setCompetidorUno($equipoGanador);
                                $sistemaTres->setcompetidorDos($equipoGanador2);
                                $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                $sistemaTres->setEvento($encuentro->getEvento());
                                $sistemaTres->setLlave($llaveAsignada);
                                $sistemaTres->setRonda($encuentro->getRonda() + 1);
                                ;
                                $this->em->persist($sistemaTres);
                                $this->em->flush();
                            }
                        } else {
                            $equipoGanador2 = null;
                            $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->andWhere('r.evento = :evento')
                                    ->andWhere('r.llave = :llave')
                                    ->andWhere('r.ronda = :ronda')
                                    ->setParameter('evento', $encuentro->getEvento()->getId())
                                    ->setParameter('llave', ($encuentro->getLlave() - 1))
                                    ->setParameter('ronda', $encuentro->getRonda())
                                    ->getQuery()
                                    ->getResult();
                            $existeEncuentro = current($existeEncuentro);
                            if ($encuentro->getPuntosCompetidorUno() > $encuentro->getPuntosCompetidorDos()) {
                                $equipoGanador = $encuentro->getCompetidorUno();
                            } else if ($encuentro->getPuntosCompetidorDos() > $encuentro->getPuntosCompetidorUno()) {
                                $equipoGanador = $encuentro->getCompetidorDos();
                            }
                            if ($existeEncuentro) {
                                if ($existeEncuentro->getPuntosCompetidorUno() > $existeEncuentro->getPuntosCompetidorDos()) {
                                    $equipoGanador2 = $existeEncuentro->getCompetidorUno();
                                } else if ($existeEncuentro->getPuntosCompetidorDos() > $existeEncuentro->getPuntosCompetidorUno()) {
                                    $equipoGanador2 = $existeEncuentro->getCompetidorDos();
                                }
                            } else {
                                $equipoGanador2 = null;
                            }
                            if ($equipoGanador2 != null) {
                                $llaveAsignada = $encuentro->getLlave() / 2;
                                $sistemaTres = new EncuentroSistemaTres();
                                $sistemaTres->setCompetidorUno($equipoGanador);
                                $sistemaTres->setcompetidorDos($equipoGanador2);
                                $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                $sistemaTres->setEvento($encuentro->getEvento());
                                $sistemaTres->setLlave($llaveAsignada);
                                $sistemaTres->setRonda($encuentro->getRonda() + 1);
                                $this->em->persist($sistemaTres);
                                $this->em->flush();
                            }
                        }

                        //tipo de juego de ida y vuelta
                    } else if ($encuentro->getTipoJuego() == false) {
                        if ($encuentro->getLlave() % 2) {
                            $existePartido = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->andWhere('r.evento = :evento')
                                    ->andWhere('r.llave = :llave')
                                    ->andWhere('r.ronda = :ronda')
                                    ->andWhere('r.competidorDos = :competidorDos')
                                    ->andWhere('r.competidorUno = :competidorUno')
                                    ->setParameter('evento', $encuentro->getEvento()->getId())
                                    ->setParameter('ronda', $encuentro->getRonda())
                                    ->setParameter('llave', ($encuentro->getLlave()))
                                    ->setParameter('competidorUno', $encuentro->getCompetidorDos())
                                    ->setParameter('competidorDos', $encuentro->getCompetidorUno())
                                    ->getQuery()
                                    ->getResult();

                            $existePartido = current($existePartido);
                            if (!$existePartido) {
                                $sistemaTres = new EncuentroSistemaTres();
                                $sistemaTres->setCompetidorUno($encuentro->getCompetidorDos());
                                $sistemaTres->setcompetidorDos($encuentro->getCompetidorUno());
                                $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                $sistemaTres->setLlave($encuentro->getLlave());
                                $sistemaTres->setRonda($encuentro->getRonda());
                                $sistemaTres->setEvento($encuentro->getEvento());
                                $this->em->persist($sistemaTres);
                                $this->em->flush();
                            } else {
                                $equipoGanador2 = null;
                                $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                        ->createQueryBuilder('r')
                                        ->andWhere('r.evento = :evento')
                                        ->andWhere('r.llave = :llave')
                                        ->andWhere('r.ronda = :ronda')
                                        ->andWhere('r.puntos_competidor_uno != :puntosCompetidorUno')
                                        ->andWhere('r.puntos_competidor_dos != :puntosCompetidorDos')
                                        ->setParameter('evento', $encuentro->getEvento()->getId())
                                        ->setParameter('ronda', $encuentro->getRonda())
                                        ->setParameter('llave', ($encuentro->getLlave() + 1))
                                        ->setParameter('puntosCompetidorUno', 'null')
                                        ->setParameter('puntosCompetidorDos', 'null')
                                        ->getQuery()
                                        ->getResult();
                                if ($existeEncuentro) {
                                    $encuentroIda = $existeEncuentro[0];
                                    $encuentroVuelta = $existeEncuentro[1];

                                    if ($encuentroIda->getPuntosCompetidorUno() > $encuentroIda->getPuntosCompetidorDos()) {
                                        $puntosGanadorIda = ($encuentroIda->getPuntosCompetidorUno() - $encuentroIda->getPuntosCompetidorDos());
                                        $equipoGanadorIda = $encuentroIda->getCompetidorUno();
                                    } else if ($encuentroIda->getPuntosCompetidorDos() > $encuentroIda->getPuntosCompetidorUno()) {
                                        $puntosGanadorIda = ($encuentroIda->getPuntosCompetidorDos() - $encuentroIda->getPuntosCompetidorUno());
                                        $equipoGanadorIda = $encuentroIda->getCompetidorDos();
                                    }
                                    if ($encuentroVuelta->getPuntosCompetidorUno() > $encuentroVuelta->getPuntosCompetidorDos()) {
                                        $puntosGanadorVuelta = ($encuentroVuelta->getPuntosCompetidorUno() - $encuentroVuelta->getPuntosCompetidorDos());
                                        $equipoGanadorVuelta = $encuentroVuelta->getCompetidorUno();
                                    } else if ($encuentroVuelta->getPuntosCompetidorDos() > $encuentroVuelta->getPuntosCompetidorUno()) {
                                        $puntosGanadorVuelta = ($encuentroVuelta->getPuntosCompetidorDos() - $encuentroVuelta->getPuntosCompetidorUno());
                                        $equipoGanadorVuelta = $encuentroVuelta->getCompetidorDos();
                                    }
                                } else {
                                    $encuentroIda = null;
                                    $encuentroVuelta = null;
                                }
                                if ($encuentroIda && $encuentroVuelta) {
                                    if ($puntosGanadorIda > $puntosGanadorVuelta) {
                                        $equipoGanador2 = $equipoGanadorIda;
                                    } else {
                                        $equipoGanador2 = $equipoGanadorVuelta;
                                    }
                                    if ($encuentro->getPuntosCompetidorUno() > $encuentro->getPuntosCompetidorDos()) {
                                        $equipoGanador = $encuentro->getCompetidorUno();
                                    } else if ($encuentro->getPuntosCompetidorDos() > $encuentro->getPuntosCompetidorUno()) {
                                        $equipoGanador = $encuentro->getCompetidorDos();
                                    }
                                } else {
                                    $equipoGanador2 = null;
                                }
                                if ($equipoGanador2 != null) {
                                    $llaveAsignada = ($encuentro->getLlave() + 1) / 2;
                                    $sistemaTres = new EncuentroSistemaTres();
                                    $sistemaTres->setCompetidorUno($equipoGanador);
                                    $sistemaTres->setcompetidorDos($equipoGanador2);
                                    $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                    $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                    $sistemaTres->setEvento($encuentro->getEvento());
                                    $sistemaTres->setLlave($llaveAsignada);
                                    $sistemaTres->setRonda($encuentro->getRonda() + 1);
                                    ;
                                    $this->em->persist($sistemaTres);
                                    $this->em->flush();
                                }
                            }
                        } else {
                            $existePartido = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                    ->createQueryBuilder('r')
                                    ->andWhere('r.evento = :evento')
                                    ->andWhere('r.llave = :llave')
                                    ->andWhere('r.ronda = :ronda')
                                    ->andWhere('r.competidorDos = :competidorDos')
                                    ->andWhere('r.competidorUno = :competidorUno')
                                    ->setParameter('evento', $encuentro->getEvento()->getId())
                                    ->setParameter('ronda', $encuentro->getRonda())
                                    ->setParameter('llave', ($encuentro->getLlave()))
                                    ->setParameter('competidorUno', $encuentro->getCompetidorDos())
                                    ->setParameter('competidorDos', $encuentro->getCompetidorUno())
                                    ->getQuery()
                                    ->getResult();

                            $existePartido = current($existePartido);
                            if (!$existePartido) {
                                $sistemaTres = new EncuentroSistemaTres();
                                $sistemaTres->setCompetidorUno($encuentro->getCompetidorDos());
                                $sistemaTres->setcompetidorDos($encuentro->getCompetidorUno());
                                $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                $sistemaTres->setLlave($encuentro->getLlave());
                                $sistemaTres->setRonda($encuentro->getRonda());
                                $sistemaTres->setEvento($encuentro->getEvento());
                                $this->em->persist($sistemaTres);
                                $this->em->flush();
                            } else {
                                $equipoGanador2 = null;
                                $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                                        ->createQueryBuilder('r')
                                        ->andWhere('r.evento = :evento')
                                        ->andWhere('r.llave = :llave')
                                        ->andWhere('r.ronda = :ronda')
                                        ->andWhere('r.puntos_competidor_uno != :puntosCompetidorUno')
                                        ->andWhere('r.puntos_competidor_dos != :puntosCompetidorDos')
                                        ->setParameter('evento', $encuentro->getEvento()->getId())
                                        ->setParameter('llave', ($encuentro->getLlave() - 1))
                                        ->setParameter('ronda', $encuentro->getRonda())
                                        ->setParameter('puntosCompetidorUno', 'null')
                                        ->setParameter('puntosCompetidorDos', 'null')
                                        ->getQuery()
                                        ->getResult();

                                if ($existeEncuentro) {
                                    $encuentroIda = $existeEncuentro[0];
                                    $encuentroVuelta = $existeEncuentro[1];
                                    if ($encuentroIda->getPuntosCompetidorUno() > $encuentroIda->getPuntosCompetidorDos()) {
                                        $puntosGanadorIda = ($encuentroIda->getPuntosCompetidorUno() - $encuentroIda->getPuntosCompetidorDos());
                                        $equipoGanadorIda = $encuentroIda->getCompetidorUno();
                                    } else if ($encuentroIda->getPuntosCompetidorDos() > $encuentroIda->getPuntosCompetidorUno()) {
                                        $puntosGanadorIda = ($encuentroIda->getPuntosCompetidorDos() - $encuentroIda->getPuntosCompetidorUno());
                                        $equipoGanadorIda = $encuentroIda->getCompetidorDos();
                                    }
                                    if ($encuentroVuelta->getPuntosCompetidorUno() > $encuentroVuelta->getPuntosCompetidorDos()) {
                                        $puntosGanadorVuelta = ($encuentroVuelta->getPuntosCompetidorUno() - $encuentroVuelta->getPuntosCompetidorDos());
                                        $equipoGanadorVuelta = $encuentroVuelta->getCompetidorUno();
                                    } else if ($encuentroVuelta->getPuntosCompetidorDos() > $encuentroVuelta->getPuntosCompetidorUno()) {
                                        $puntosGanadorVuelta = ($encuentroVuelta->getPuntosCompetidorDos() - $encuentroVuelta->getPuntosCompetidorUno());
                                        $equipoGanadorVuelta = $encuentroVuelta->getCompetidorDos();
                                    }
                                } else {
                                    $encuentroIda = null;
                                    $encuentroVuelta = null;
                                }
                                if ($encuentroIda && $encuentroVuelta) {
                                    if ($puntosGanadorIda > $puntosGanadorVuelta) {
                                        $equipoGanador2 = $equipoGanadorIda;
                                    } else {
                                        $equipoGanador2 = $equipoGanadorVuelta;
                                    }
                                    if ($encuentro->getPuntosCompetidorUno() > $encuentro->getPuntosCompetidorDos()) {
                                        $equipoGanador = $encuentro->getCompetidorUno();
                                    } else if ($encuentro->getPuntosCompetidorDos() > $encuentro->getPuntosCompetidorUno()) {
                                        $equipoGanador = $encuentro->getCompetidorDos();
                                    }
                                } else {
                                    $equipoGanador2 = null;
                                }
                                if ($equipoGanador2 != null) {
                                    $llaveAsignada = $encuentro->getLlave() / 2;
                                    $sistemaTres = new EncuentroSistemaTres();
                                    $sistemaTres->setCompetidorUno($equipoGanador);
                                    $sistemaTres->setcompetidorDos($equipoGanador2);
                                    $sistemaTres->setTipoJuego($encuentro->getTipoJuego());
                                    $sistemaTres->setTipoSistema($encuentro->getTipoSistema());
                                    $sistemaTres->setEvento($encuentro->getEvento());
                                    $sistemaTres->setLlave($llaveAsignada);
                                    $sistemaTres->setRonda($encuentro->getRonda() + 1);
                                    $this->em->persist($sistemaTres);
                                    $this->em->flush();
                                }
                            }
                        }
                    }
                }

                return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $encuentro->getEvento()->getId(), 'tipo' => $tipoSistema));
            }
        }
        if ($encuentro == null) {
            $tablaPosiciones = 0;
        }
        if ($evento->getDisciplina()->getNombre() == "Voleibol" || $evento->getDisciplina()->getNombre() == "VOLEIBOL") {
            return $this->render('AdminBundle:SistemaJuegoTres:create_resultadoVoleibol.html.twig', array(
                        'form' => $form->createView(),
                        'nombreJugador1' => $nombreJugador1,
                        'nombreJugador2' => $nombreJugador2,
                        'eventoId' => $encuentro->getEvento()->getId(),
                        'tipoDeSistemaDeJuego' => $tipoSistema,
                        'encuentros' => 0
            ));
        } else {

            return $this->render('AdminBundle:SistemaJuegoTres:create_resultado.html.twig', array(
                        'form' => $form->createView(),
                        'nombreJugador1' => $nombreJugador1,
                        'nombreJugador2' => $nombreJugador2,
                        'eventoId' => $encuentro->getEvento()->getId(),
                        'tipoDeSistemaDeJuego' => $tipoSistema,
                        'encuentros' => 0
            ));
        }
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
        $em = $this->getDoctrine()->getManager();
        $evento = $em->getRepository('LogicBundle:Evento')->find($request->get('evento'));
        $tipoDeSistemaDeJuego = $request->get('tipoDeSistemaDeJuego');
        $templateKey = 'edit';
        if (count($evento->getEquipoEventos()) % 2) {
            $this->addFlash('sonata_flash_error', "Ingrese un equipo más para realizar la eliminatoria");
            return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistemaDeJuego));
        }

        $existeEquipos = $this->em->getRepository("LogicBundle:EquipoEvento")
                        ->createQueryBuilder('equipoEvento')
                        ->where('equipoEvento.evento = :evento')
                        ->setParameter('evento', $evento->getId())
                        ->getQuery()->getResult();

        if ($existeEquipos == null) {
            $this->addFlash('sonata_flash_error', 'No hay equipos Asociados al evento');
            return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistemaDeJuego));
        }


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
                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                try {
                    $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                            ->createQueryBuilder('r')
                            ->andWhere('r.evento = :evento')
                            ->andWhere('r.competidorUno = :competidorUno')
                            ->andWhere('r.competidorDos = :competidorDos')
                            ->setParameter('evento', $evento)
                            ->setParameter('competidorUno', $form->get('competidorUno')->getData())
                            ->setParameter('competidorDos', $form->get('competidorDos')->getData())
                            ->getQuery()
                            ->getResult();
                    if ($existeEncuentro) {
                        $this->addFlash('sonata_flash_error', "los equipos ya estan en una llave");
                        return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistemaDeJuego));
                    }
                    $submittedObject->setEvento($evento);
                    $submittedObject->setRonda(1);
                    $submittedObject->setTipoSistema('Eliminatoria');
                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData());
                    $submittedObject->setcompetidorDos($form->get('competidorDos')->getData());
                    if ($form->get('competidorUno')->getData() == $form->get('competidorDos')->getData()) {
                        $this->addFlash('sonata_flash_error', "los equipos son los mismos");
                        return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $newObject->getEvento()->getId(), 'tipo' => $tipo));
                    }
                    $reserva = new Reserva();
                    $user = $this->getUser();
                    if ($form->get("escenarioDeportivo")->getData() != null) {
                        $escenario = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($form->get("escenarioDeportivo")->getData());
                        $division = $this->em->getRepository("LogicBundle:Division")->find($form->get("division")->getData());
                        $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                        ->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :evento')
                                        ->setParameter('evento', 'Evento')
                                        ->getQuery()->getOneOrNullResult();
                        $fechaInicial = $form->get("fecha")->getData();
                        $fechaFinal = $form->get("fecha")->getData();
                        $horaInicial = $form->get("hora")->getData();
                        $horaFinal = $form->get("hora")->getData();
                        $reserva->setFechaInicio($fechaInicial);
                        $reserva->setFechaFinal($fechaFinal);
                        $reserva->setHoraInicial($horaInicial);
                        $reserva->setHoraFinal($horaFinal);
                        $reserva->setEstado('Pendiente');
                        $reserva->setDivision($division);
                        $reserva->setEscenarioDeportivo($escenario);
                        $reserva->setUsuario($user);
                        $reserva->setDisciplina($evento->getDisciplina());
                        $reserva->setTipoReserva($tipoReserva);
                        $this->em->persist($reserva);
                        $this->em->flush();
                    }
                    if ($form->get("puntoAtencion")->getData() != null) {
                        $puntoAtencion = $this->em->getRepository("LogicBundle:PuntoAtencion")->find($form->get("puntoAtencion")->getData());
                        $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                        ->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :evento')
                                        ->setParameter('evento', 'Evento')
                                        ->getQuery()->getOneOrNullResult();
                        $fechaInicial = $form->get("fecha")->getData();
                        $fechaFinal = $form->get("fecha")->getData();
                        $horaInicial = $form->get("hora")->getData();
                        $horaFinal = $form->get("hora")->getData();
                        $reserva->setFechaInicio($fechaInicial);
                        $reserva->setFechaFinal($fechaFinal);
                        $reserva->setHoraInicial($horaInicial);
                        $reserva->setHoraFinal($horaFinal);
                        $reserva->setEstado('Pendiente');
                        $reserva->setPuntoAtencion($puntoAtencion);
                        $reserva->setUsuario($user);
                        $reserva->setDisciplina($evento->getDisciplina());
                        $reserva->setTipoReserva($tipoReserva);
                        $this->em->persist($reserva);
                        $this->em->flush();
                    }



                    $newObject = $this->admin->create($submittedObject);
                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result' => 'ok',
                                        ), 200, array());
                    }
                    $this->addFlash('sonata_flash_success', $this->trans('formulario_escalera.encuentro_add'));
                    return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $newObject->getEvento()->getId(), 'tipo' => $tipoDeSistemaDeJuego));
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);
                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', 'Error');
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
                    'tipoDeSistemaDeJuego' => $tipoDeSistemaDeJuego,
                    'sistemaJuegoId' => 0,
                    'objectId' => null,
                    'eventoId' => $evento->getId(),
                    'tablaPosiciones' => 0,
                    'encuentros' => 0
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
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $encuentroSistemaTres = $request->get('id');
        if ($encuentroSistemaTres == 0) {
            $encuentroSistemaTres = new EncuentroSistemaTres();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")->find($encuentroSistemaTres);
        }
        $puntoAtencionValidar = $encuentro->getPuntoAtencion();
        $escenarioValidar = $encuentro->getEscenarioDeportivo();
        $templateKey = 'edit';
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        $em = $this->getDoctrine()->getManager();
        if ($object->getTipoSistema() == 'Eliminatoria') {
            $tipo = 5;
        }
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
                    $reserva = new Reserva();
                    $user = $this->getUser();
                    $existeEncuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                            ->createQueryBuilder('r')
                            ->andWhere('r.evento = :evento')
                            ->andWhere('r.competidorUno = :competidorUno')
                            ->andWhere('r.competidorDos = :competidorDos')
                            ->setParameter('evento', $encuentro->getEvento()->getId())
                            ->setParameter('competidorUno', $form->get('competidorUno')->getData())
                            ->setParameter('competidorDos', $form->get('competidorDos')->getData())
                            ->getQuery()
                            ->getOneOrNullResult();

                    if ($existeEncuentro && $puntoAtencionValidar != null || $escenarioValidar != null) {
                        $this->addFlash('sonata_flash_error', "los equipos ya estan en una llave");
                        return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $object->getEvento()->getId(), 'tipo' => $tipo));
                    }
                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData());
                    $submittedObject->setcompetidorDos($form->get('competidorDos')->getData());
                    if ($form->get('competidorUno')->getData() == $form->get('competidorDos')->getData()) {
                        $this->addFlash('sonata_flash_error', "los equipos son los mismos");
                        return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $object->getEvento()->getId(), 'tipo' => $tipo));
                    }
                    $reserva = new Reserva();

                    $user = $this->getUser();

                    if ($form->get("escenarioDeportivo")->getData() != null) {
                        $escenario = $this->em->getRepository("LogicBundle:EscenarioDeportivo")->find($form->get("escenarioDeportivo")->getData());
                        $division = $this->em->getRepository("LogicBundle:Division")->find($form->get("division")->getData());
                        $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                        ->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :evento')
                                        ->setParameter('evento', 'Evento')
                                        ->getQuery()->getOneOrNullResult();
                        $fechaInicial = $form->get("fecha")->getData();
                        $fechaFinal = $form->get("fecha")->getData();
                        $horaInicial = $form->get("hora")->getData();
                        $horaFinal = $form->get("hora")->getData();
                        $reserva->setFechaInicio($fechaInicial);
                        $reserva->setFechaFinal($fechaFinal);
                        $reserva->setHoraInicial($horaInicial);
                        $reserva->setHoraFinal($horaFinal);
                        $reserva->setEstado('Pendiente');
                        $reserva->setDivision($division);
                        $reserva->setEscenarioDeportivo($escenario);
                        $reserva->setUsuario($user);
                        $reserva->setDisciplina($encuentro->getEvento()->getDisciplina());
                        $reserva->setTipoReserva($tipoReserva);
                        $this->em->persist($reserva);
                        $this->em->flush();
                    }
                    if ($form->get("puntoAtencion")->getData() != null) {
                        $puntoAtencion = $this->em->getRepository("LogicBundle:PuntoAtencion")->find($form->get("puntoAtencion")->getData());
                        $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                        ->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :evento')
                                        ->setParameter('evento', 'Evento')
                                        ->getQuery()->getOneOrNullResult();
                        $fechaInicial = $form->get("fecha")->getData();
                        $fechaFinal = $form->get("fecha")->getData();
                        $horaInicial = $form->get("hora")->getData();
                        $horaFinal = $form->get("hora")->getData();
                        $reserva->setFechaInicio($fechaInicial);
                        $reserva->setFechaFinal($fechaFinal);
                        $reserva->setHoraInicial($horaInicial);
                        $reserva->setHoraFinal($horaFinal);
                        $reserva->setEstado('Pendiente');
                        $reserva->setPuntoAtencion($puntoAtencion);
                        $reserva->setUsuario($user);
                        $reserva->setDisciplina($encuentro->getEvento()->getDisciplina());
                        $reserva->setTipoReserva($tipoReserva);
                        $this->em->persist($reserva);
                        $this->em->flush();
                    }

                    $existingObject = $this->admin->update($submittedObject);
                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $objectId,
                                    'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                                        ], 200, []);
                    }


                    $this->addFlash('sonata_flash_success', $this->trans('formulario_escalera.encuentro_editado'));

                    // redirect to edit mode
                    return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $object->getEvento()->getId(), 'tipo' => $tipo));
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

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'edit',
                    'form' => $formView,
                    'object' => $existingObject,
                    'tipoDeSistemaDeJuego' => $tipo,
                    'sistemaJuegoId' => 0,
                    'objectId' => $objectId,
                    'eventoId' => $object->getEvento()->getId(),
                    'encuentros' => 0
                        ), null);
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

        if ($object->getTipoSistema() == null || $object->getTipoSistema() != "Eliminatoria") {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }

        $em = $this->getDoctrine()->getManager();


        if ($object->getTipoSistema() == "Eliminatoria") {
            $tipo = 5;
        }

        $eventoId = $object->getEvento()->getId();

        $em->remove($object);
        $em->flush();

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                        'result' => 'ok',
                            ), 200, array());
        }

        $this->addFlash('sonata_flash_error', $this->trans('formulario_escalera.encuentro_eliminado'));

        return $this->redirectToRoute('admin_logic_encuentrosistematres_list', array('id' => $eventoId, 'tipo' => $tipo));
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
        if ($request->get('ronda') != 0) {
            //buscamos el evento
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($request->get('id'));
            $ronda = $request->get('ronda');


            //me permite conocer el sistema de juego relacionado, para saber los puntajes configurados
            $encuentros = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                    ->createQueryBuilder('encuentro')
                    ->where('encuentro.evento = :evento')
                    ->andWhere('encuentro.ronda = :ronda')
                    ->setParameter('evento', $evento)
                    ->setParameter('ronda', $ronda)
                    ->getQuery()
                    ->getResult();
            $tipoSistema = current($encuentros);


            $encuentrosTres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                            ->createQueryBuilder('encuentroSistemaTres')
                            ->where('encuentroSistemaTres.evento = :evento')
                            ->setParameter('evento', $evento->getId())
                            ->getQuery()->getResult();


            if (count($encuentrosTres) == 0) {

                $guardado = 0;
            } else {
                $guardado = 1;
            }

            if ($request->get('alerta') == null) {
                $alertaSis = 1;
            } else {

                $alertaSis = 0;
            }


            $datagrid = $this->admin->getDatagrid();

            $formView = $datagrid->getForm()->createView();

            // set the theme for the current Admin Form
            //$this->setFormTheme($formView, $this->admin->getFilterTheme());

            return $this->render($this->admin->getTemplate('list'), array(
                        'action' => 'list',
                        'form' => $formView,
                        'idSistemaJuego' => 0,
                        'eventoId' => $evento->getId(),
                        'nombreEvento' => $evento->getNombre(),
                        'tipoDeSistema' => $tipoSistema->getTipoSistema(),
                        'tablaPosiciones' => 0,
                        'rondas' => $ronda,
                        'encuentros' => $encuentros,
                        'guardado' => $guardado,
                        'alertaSis' => $alertaSis,
                        //parametro del formulario que voy a dibujar
                        //nunca olvidar despues de form colocar el createView
                        'datagrid' => $datagrid,
                        'csrf_token' => $this->getCsrfToken('sonata.batch'),
                        'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                        $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                        $this->admin->getExportFormats(),
                            ), null);
        }
        $em = $this->getDoctrine()->getManager();
        $evento = $em->getRepository('LogicBundle:Evento')->find($request->get('id'));


        if ($evento == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('tipo') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        $tipoDeSistema = $request->get('tipo');

        if ($tipoDeSistema == 5) {

            $sistemaGuardar = "Eliminatoria";
        } else {
            $sistemaGuardar = null;
        }


        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }



        $totalEquipos = count($evento->getEquipoEventos());


        $rondasNecesaria = 0;


        if ($totalEquipos == 8 || $totalEquipos == 16 || $totalEquipos == 32) {
            
        } else {
            $this->addFlash('sonata_flash_error', $this->trans('formulario_eliminatoria.cantidad'));

            return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 1));
        }


        if ($totalEquipos > 0) {
            while ($totalEquipos != 1) {
                $totalEquipos = $totalEquipos / 2;
                $rondasNecesaria++;
            }
        } {
            $rondasNecesaria = 0;
        }


        $encuentrosTres = $this->em->getRepository("LogicBundle:EncuentroSistemaTres")
                        ->createQueryBuilder('encuentroSistemaTres')
                        ->where('encuentroSistemaTres.evento = :evento')
                        ->setParameter('evento', $evento->getId())
                        ->getQuery()->getResult();


        if (count($encuentrosTres) == 0) {
            $guardado = 0;
        } else {
            $guardado = 1;
        }

        $datagrid = $this->admin->getDatagrid();

        $formView = $datagrid->getForm()->createView();

        if ($request->get('alerta') == null) {
            $alertaSis = 1;
        } else {

            $alertaSis = 0;
        }
        return $this->render($this->admin->getTemplate('list'), array(
                    'action' => 'list',
                    'form' => $formView,
                    'idSistemaJuego' => 0,
                    'eventoId' => $evento->getId(),
                    'nombreEvento' => $evento->getNombre(),
                    'tipoDeSistema' => $tipoDeSistema,
                    'tablaPosiciones' => 0,
                    'rondas' => $rondasNecesaria,
                    'encuentros' => 0,
                    'guardado' => $guardado,
                    'alertaSis' => $alertaSis,
                    //parametro del formulario que voy a dibujar
                    //nunca olvidar despues de form colocar el createView
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
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

}
