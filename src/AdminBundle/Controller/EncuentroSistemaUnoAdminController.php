<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\UsuarioPersonaNaturalType;
use AdminBundle\ValidData\Validaciones;
use AdminBundle\ValidData\ValidarDatos;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\InfoComplementariaUserType;
use Doctrine\Common\Inflector\Inflector;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\Marker;
use LogicBundle\Entity\Asistencia;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\TipoReserva;
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\SistemaJuegoUno;
use LogicBundle\Form\EncuentroSistemaUnoType;
use LogicBundle\Entity\EncuentroSistemaUno;
use LogicBundle\Entity\FaltasEncuentroJugador;
use LogicBundle\Form\FaltasEncuentroJugadorType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use ReflectionClass;
use DateTime;
use ReflectionMethod;
use RuntimeException;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig_Error_Runtime;

class EncuentroSistemaUnoAdminController extends CRUDController {

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
     * posicionesEncuentro action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function getPoscisiones($idEvento, $sistemaJuego) {

        //me permite conocer el sistema de juego relacionado, para saber los puntajes configurados
        $idSistemaJuego = $this->em->getRepository("LogicBundle:SistemaJuegoUno")->find($sistemaJuego);

        //buscamos el evento para saber que tipo de cupo es "individual"/"equipos"
        $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);

        //aca obtenemos para validar mas adelante el tipo de cupo
        $tipoCupo = $evento->getCupo();

        //procedemos a  obtener todos los encuentros del tipo de sistema que encontramos
        //buscamos resultados dependiendo de la disciplina

        if ($evento->getDisciplina()->getNombre() == "VOLEIBOL" || $evento->getDisciplina()->getNombre() == "Voleibol") {
            $encuentrosGanadosCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as ganador')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor < c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();

            $encuentrosGanadosCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as ganador')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor > c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosGanador = array_merge($encuentrosGanadosCompetidorUno, $encuentrosGanadosCompetidorDos);


            $encuentrosPerdidosCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as perdedor')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor > c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();
            $encuentrosPerdidosCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as perdedor')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor < c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosPerdidos = array_merge($encuentrosPerdidosCompetidorUno, $encuentrosPerdidosCompetidorDos);


            $encuentrosEmpateCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as empate')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor = c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();

            $encuentrosEmpateCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as empate')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.setsAfavor = c.setsEnContra')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosEmpate = array_merge($encuentrosEmpateCompetidorUno, $encuentrosEmpateCompetidorDos);
        } else {

            $encuentrosGanadosCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as ganador')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 < c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();
            $encuentrosGanadosCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as ganador')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 > c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosGanador = array_merge($encuentrosGanadosCompetidorUno, $encuentrosGanadosCompetidorDos);


            $encuentrosPerdidosCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as perdedor')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 > c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();
            $encuentrosPerdidosCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as perdedor')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 < c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosPerdidos = array_merge($encuentrosPerdidosCompetidorUno, $encuentrosPerdidosCompetidorDos);


            $encuentrosEmpateCompetidorUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorDos) as total, c.competidorDos as empate')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 = c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorDos')
                    ->getQuery()
                    ->getResult();
            $encuentrosEmpateCompetidorDos = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")
                    ->createQueryBuilder('c')
                    ->select('count(c.competidorUno) as total, c.competidorUno as empate')
                    ->where('c.sistemaJuegoUno = :sistemaJuego')
                    ->andWhere('c.puntos_competidor_1 = c.puntos_competidor_2')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.competidorUno')
                    ->getQuery()
                    ->getResult();
            $encuentrosEmpate = array_merge($encuentrosEmpateCompetidorUno, $encuentrosEmpateCompetidorDos);
        }

        $tablaPosicion = array();
        foreach ($encuentrosGanador as $ganador) {
            if (!isset($tablaPosicion[$ganador['ganador']])) {
                $tablaPosicion[$ganador['ganador']] = [];
            }
            if (!isset($tablaPosicion[$ganador['ganador']]['puntos'])) {
                $tablaPosicion[$ganador['ganador']]['puntos'] = $ganador['total'] * $idSistemaJuego->getPuntosVictoria();
            } else {
                $tablaPosicion[$ganador['ganador']]['puntos'] += $ganador['total'] * $idSistemaJuego->getPuntosVictoria();
            }
        }

        foreach ($encuentrosPerdidos as $perdedor) {
            if (!isset($tablaPosicion[$perdedor['perdedor']])) {
                $tablaPosicion[$perdedor['perdedor']] = [];
            }
            if (!isset($tablaPosicion[$perdedor['perdedor']]['puntos'])) {
                $tablaPosicion[$perdedor['perdedor']]['puntos'] = $perdedor['total'] * $idSistemaJuego->getPuntosDerrota();
            } else {
                $tablaPosicion[$perdedor['perdedor']]['puntos'] += $perdedor['total'] * $idSistemaJuego->getPuntosDerrota();
            }
        }

        foreach ($encuentrosEmpate as $empate) {
            if (!isset($tablaPosicion[$empate['empate']])) {
                $tablaPosicion[$empate['empate']] = [];
            }
            if (!isset($tablaPosicion[$empate['empate']]['puntos'])) {
                $tablaPosicion[$empate['empate']]['puntos'] = $empate['total'] * $idSistemaJuego->getPuntosEmpate();
            } else {
                $tablaPosicion[$empate['empate']]['puntos'] += $empate['total'] * $idSistemaJuego->getPuntosEmpate();
            }
        }


        if ($tipoCupo == "Individual") {
            foreach ($tablaPosicion as $id => $datos) {
                $usuario = $this->em->getRepository("LogicBundle:JugadorEvento")->find($id)->getUsuarioJugadorEvento();
                $tablaPosicion[$id]['nombre'] = $usuario->getFirstName() . ' ' . $usuario->getLastName();
            }
        }

        if ($tipoCupo == "Equipos") {
            foreach ($tablaPosicion as $id => $datos) {
                $tablaPosicion[$id]['nombre'] = $this->em->getRepository("LogicBundle:EquipoEvento")->find($id)->getNombre();
            }
        }



        $puntos = array();
        foreach ($tablaPosicion as $key => $row) {
            $puntos[$key] = $row['puntos'];
        }


        $tablaReal = array_multisort($puntos, SORT_DESC, $tablaPosicion);


        return $tablaPosicion;
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
            $encuentro = new EncuentroSistemaUno();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")->find($idEncuentro);
        }


        if (count($encuentro->getSistemaJuegoUno()->getTipoSistema())) {
            $idEvento = $encuentro->getSistemaJuegoUno()->getEvento()->getId();
        }
        if ($encuentro->getSistemaJuegoUno()->getTipoSistema() == "Escalera") {

            $tipoSistema = 1;
        } else if ($encuentro->getSistemaJuegoUno()->getTipoSistema() == "Piramide") {

            $tipoSistema = 2;
        } else if ($encuentro->getSistemaJuegoUno()->getTipoSistema() == "Chimenea") {

            $tipoSistema = 3;
        }


        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);
        }
        $cupo = $evento->getCupo();
        $jugadores = $evento->getJugadorEventos();
        $jugadoresEvento = array();
        $equipos = null;
        $equiposEvento = array();
        $idJugador1 = 0;
        $idJugador1 = $encuentro->getCompetidorUno();
        $idJugador2 = $encuentro->getCompetidorDos();


        if ($cupo == "Individual") {


            $idJugadorEvento1 = $this->em->getRepository("LogicBundle:JugadorEvento")->findOneById($idJugador1);

            $idJugadorEvento2 = $this->em->getRepository("LogicBundle:JugadorEvento")->findOneById($idJugador2);


            $idJugadorEven1 = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneById($idJugadorEvento1->getUsuarioJugadorEvento()->getId());
            $idJugadorEven2 = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneById($idJugadorEvento2->getUsuarioJugadorEvento()->getId());
            foreach ($jugadores as $jugador) {
                $nombreJugador1 = $idJugadorEven1->getFirstName();
                $nombreJugador2 = $idJugadorEven2->getFirstName();
            }



            $faltaJugador1 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroJugador', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->andWhere('fej.encuentroSistemaUno = :encuentro')
                    ->andWhere('fej.jugadorEvento = :jugadorEvento')
                    ->setParameters([
                        'encuentro' => $encuentro->getId(),
                        'jugadorEvento' => $idEquipoEvento1->getId(),
                    ])
                    ->getQuery()
                    ->getResult();

            ////hola soy un comentario para el pull


            $faltaJugador2 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroJugador', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->andWhere('fej.encuentroSistemaUno = :encuentro')
                    ->andWhere('fej.jugadorEvento = :jugadorEvento')
                    ->setParameters([
                        'encuentro' => $encuentro->getId(),
                        'jugadorEvento' => $idEquipoEvento2->getId(),
                    ])
                    ->getQuery()
                    ->getResult();
        } else if ($cupo == "Equipos") {
            $idEquipoEvento1 = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador1);
            $idEquipoEvento2 = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador2);
            $nombreJugador1 = $idEquipoEvento1->getNombre();
            $nombreJugador2 = $idEquipoEvento2->getNombre();
            $faltaJugador1 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroJugador', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaUno = :encuentro')
                    ->andWhere('fej.jugadorEvento = :jugadorEvento')
                    ->setParameters([
                        'encuentro' => $encuentro->getId(),
                        'jugadorEvento' => $idEquipoEvento1->getId(),
                    ])
                    ->getQuery()
                    ->getResult();
            $faltaJugador2 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('faltas')
                    ->innerJoin('LogicBundle:FaltasEncuentroJugador', 'fej', 'WITH', 'faltas.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaUno = :encuentro')
                    ->andWhere('fej.jugadorEvento = :jugadorEvento')
                    ->setParameters([
                        'encuentro' => $encuentro->getId(),
                        'jugadorEvento' => $idEquipoEvento2->getId(),
                    ])
                    ->getQuery()
                    ->getResult();
        }


        $form = $this->createForm(FaltasEncuentroJugadorType::class, $encuentro, array(
            'faltasJugador1' => $faltaJugador1,
            'faltasJugador2' => $faltaJugador2,
            'disciplina' => $evento->getDisciplina()->getNombre(),
        ));
        $form->setData($faltaJugador1, $faltaJugador2);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->em->persist($encuentro);
                $this->em->flush();
                $datosFalta = $request->request->get('faltas_encuentro_jugador');
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
                if ($cupo == "Individual") {
                    if (count($datosJugador1)) {
                        $encuentroSistemaUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")->find($idEncuentro);
                        foreach ($datosJugador1 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroJugador")
                                    ->createQueryBuilder('r')
                                    ->where('r.jugadorEvento = :user')
                                    ->andWhere('r.encuentroSistemaUno = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $idJugadorEvento1->getId())
                                    ->setParameter('encuentro', $encuentro->getId())
                                    ->setParameter('sancionEvento', $encontrarFalta->getId())
                                    ->getQuery()
                                    ->getResult();
                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroJugador();
                                $faltasEncuentro->setEncuentroSistemaUno($encuentroSistemaUno);
                                $faltasEncuentro->setJugadorEvento($idJugadorEvento1->getId());

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);

                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }
                            $this->em->flush();
                        }
                    }
                    if (count($datosJugador2)) {
                        $encuentroSistemaUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")->find($idEncuentro);

                        foreach ($datosJugador2 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroJugador")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $idJugadorEvento2->getId())
                                    ->where('r.jugadorEvento = :user')
                                    ->setParameter('encuentro', $encuentro)
                                    ->andWhere('r.encuentroSistemaUno = :encuentro')
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroJugador();

                                $faltasEncuentro->setEncuentroSistemaUno($encuentroSistemaUno);
                                $faltasEncuentro->setJugadorEvento($idJugadorEvento2->getId());

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }
                } else if ($cupo == "Equipos") {
                    if (count($datosJugador1)) {
                        $encuentroSistemaUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")->find($idEncuentro);

                        $idEquipoEvento = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador1);



                        foreach ($datosJugador1 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);


                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroJugador")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $idEquipoEvento)
                                    ->where('r.jugadorEvento = :user')
                                    ->andWhere('r.encuentroSistemaUno = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroJugador();
                                $faltasEncuentro->setEncuentroSistemaUno($encuentroSistemaUno);
                                $faltasEncuentro->setJugadorEvento($idEquipoEvento->getId());

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);


                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }//exit();
                            $this->em->flush();
                        }
                    }
                    if (count($datosJugador2)) {

                        $encuentroSistemaUno = $this->em->getRepository("LogicBundle:EncuentroSistemaUno")->find($idEncuentro);

                        $idEquipoEvento = $this->em->getRepository("LogicBundle:EquipoEvento")->findOneById($idJugador2);

                        foreach ($datosJugador2 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroJugador")
                                    ->createQueryBuilder('r')
                                    ->where('r.jugadorEvento = :user')
                                    ->andWhere('r.encuentroSistemaUno = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $idEquipoEvento->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroJugador();


                                $faltasEncuentro->setEncuentroSistemaUno($encuentroSistemaUno);
                                $faltasEncuentro->setJugadorEvento($idEquipoEvento->getId());


                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }
                }

                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $encuentro->getSistemaJuegoUno()->getEvento()->getId(), 'tipo' => $tipoSistema));
            }
        }

        if ($evento->getDisciplina()->getNombre() == "Voleibol" || $evento->getDisciplina()->getNombre() == "VOLEIBOL") {

            return $this->render('AdminBundle:Escalera:create_resultado_voleibol.html.twig', array(
                        'form' => $form->createView(),
                        'nombreJugador1' => $nombreJugador1,
                        'nombreJugador2' => $nombreJugador2,
                        'eventoId' => $encuentro->getSistemaJuegoUno()->getEvento()->getId(),
                        'tipoDeSistemaDeJuego' => $tipoSistema,
            ));
        } else {

            return $this->render('AdminBundle:Escalera:create_resultado.html.twig', array(
                        'form' => $form->createView(),
                        'nombreJugador1' => $nombreJugador1,
                        'nombreJugador2' => $nombreJugador2,
                        'eventoId' => $encuentro->getSistemaJuegoUno()->getEvento()->getId(),
                        'tipoDeSistemaDeJuego' => $tipoSistema,
            ));
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


        $em = $this->getDoctrine()->getManager();
        $evento = $em->getRepository('LogicBundle:Evento')->find($request->get('id'));

        if ($evento == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('tipo') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        $tipoDeSistema = $request->get('tipo');



        if ($tipoDeSistema == 0) {

            $sistemaGuardar = "Ninguno";
        }


        if ($tipoDeSistema == 1) {

            $sistemaGuardar = "Escalera";
        }

        if ($tipoDeSistema == 2) {

            $sistemaGuardar = "Piramide";
        }

        if ($tipoDeSistema == 3) {

            $sistemaGuardar = "Chimenea";
        }


        if ($tipoDeSistema == 4) {

            $sistemaGuardar = "Liga";
        }


        if ($tipoDeSistema == 5) {

            $sistemaGuardar = "Eliminatoria";
        }

        if ($tipoDeSistema == 6) {

            $sistemaGuardar = "PlayOff";
        }




        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        //creo el formulario de la entidad que necesito

        $sistemaJuegoUno = new SistemaJuegoUno();

        //declaracion para el form con el type donde se realizan los campos
        //esto debemos pasarlo al sonata admin en el array
        $form = $this->createForm(EncuentroSistemaUnoType::class, $sistemaJuegoUno, array(
            'em' => $this->container,
            'evento' => $evento
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            if ($form->isValid()) {
                $sistemaJuegoUno->setTipoSistema($sistemaGuardar);
                $sistemaJuegoUno->setEvento($evento);

                $this->em->persist($sistemaJuegoUno);
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
            }
        }

        $sistemaJuegoUno = $this->em->getRepository("LogicBundle:SistemaJuegoUno")
                        ->createQueryBuilder('sistemaJuegoUno')
                        ->where('sistemaJuegoUno.evento = :evento')
                        ->andWhere('sistemaJuegoUno.tipoSistema = :tipo')
                        ->setParameter('evento', $evento->getId())
                        ->setParameter('tipo', $sistemaGuardar)
                        ->getQuery()->getOneOrNullResult();



        if (!$sistemaJuegoUno) {
            $guardado = 0;
        } else {
            $guardado = 1;
        }


        if ($sistemaJuegoUno != null) {
            $sistemaId = $sistemaJuegoUno->getId();
            $tablaPosiciones = $this->getPoscisiones($request->get('id'), $sistemaId);

            if (!$tablaPosiciones) {
                $tablaPosiciones = 0;
            }
        } else {
            $sistemaId = 0;
            $tablaPosiciones = 0;
        }


        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();


        if ($tipoDeSistema == 0) {
            if ($evento->getEscenarioDeportivo() != null) {
                $instalaciones = $evento->getEscenarioDeportivo();
            }

            if ($evento->getPuntoAtencion() != null) {
                $instalaciones = $evento->getPuntoAtencion();
            }
        } else {

            $instalaciones = null;
        }


        if ($request->get('alerta') == null) {
            $alertaSis = 1;
        } else {

            $alertaSis = 0;
        }

        // set the theme for the current Admin Form
        //$this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
                    'action' => 'list',
                    'form' => $formView,
                    'idSistemaJuego' => $sistemaId,
                    'eventoId' => $evento->getId(),
                    'nombreEvento' => $evento->getNombre(),
                    'instalaciones' => $instalaciones,
                    'tipoDeSistema' => $tipoDeSistema,
                    'tablaPosiciones' => $tablaPosiciones,
                    'guardado' => $guardado,
                    'alertaSis' => $alertaSis,
                    'formSitemaJuegoUno' => $form->createView(),
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
        $em = $this->getDoctrine()->getManager();


        $sisjuego = $em->getRepository('LogicBundle:SistemaJuegoUno')->find($request->get('sistemaJuegoUno'));


        if (!$sisjuego) {
            return $this->redirect($this->generateUrl('admin_logic_encuentrosistemauno_list'));
        }


        if ($request->get('sistemaJuegoUno') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('tipoDeSistemaDeJuego') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($sisjuego->getEvento()->getCupo() == "Individual") {
            $jugadores = $em->getRepository('LogicBundle:JugadorEvento')
                    ->createQueryBuilder('jugadorEvento')
                    ->Where('jugadorEvento.evento = :evento')
                    ->setParameter('evento', $sisjuego->getEvento()->getId())
                    ->getQuery()
                    ->getResult();

            if ($jugadores == null) {
                $this->addFlash('sonata_flash_error', 'No hay jugadores asociados al evento');

                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
            }
        }

        if ($sisjuego->getEvento()->getCupo() == "Equipos") {
            $existeEquipos = $this->em->getRepository("LogicBundle:EquipoEvento")
                            ->createQueryBuilder('equipoEvento')
                            ->where('equipoEvento.evento = :evento')
                            ->setParameter('evento', $sisjuego->getEvento()->getId())
                            ->getQuery()->getResult();

            if ($existeEquipos == null) {
                $this->addFlash('sonata_flash_error', 'No hay equipos asociados al evento');

                return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
            }
        }

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

                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData()->getId());
                    $submittedObject->setcompetidorDos($form->get('competidorDos')->getData()->getId());
                    $submittedObject->setSistemaJuegoUno($sisjuego);


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
                        $reserva->setDisciplina($sisjuego->getEvento()->getDisciplina());
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
                        $reserva->setDisciplina($sisjuego->getEvento()->getDisciplina());
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

                    // redirect to edit mode
                    //return $this->redirectTo($newObject);
                    return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
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

        $sistemaJuegoUnoId = $sisjuego->getId();




        if ($sisjuego->getTipoSistema() == "Escalera") {
            $tipo = 1;
        }

        if ($sisjuego->getTipoSistema() == "Piramide") {
            $tipo = 2;
        }

        if ($sisjuego->getTipoSistema() == "Chimenea") {
            $tipo = 3;
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $newObject,
                    'tipoDeSistemaDeJuego' => $request->get('tipoDeSistemaDeJuego'),
                    'sistemaJuegoId' => $sistemaJuegoUnoId,
                    'objectId' => null,
                    'eventoId' => $sisjuego->getEvento()->getId(),
                    'tipo' => $tipo,
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
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        $em = $this->getDoctrine()->getManager();


        if ($object->getSistemaJuegoUno()->getTipoSistema() == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Escalera") {
            $tipo = 1;
        }

        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Piramide") {
            $tipo = 2;
        }

        if ($object->getSistemaJuegoUno()->getTipoSistema() == "Chimenea") {
            $tipo = 3;
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

                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData()->getId());
                    $submittedObject->setCompetidorDos($form->get('competidorDos')->getData()->getId());


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
                    return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $object->getSistemaJuegoUno()->getEvento()->getId(), 'tipo' => $tipo));
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
                    'tipoDeSistemaDeJuego' => $tipo,
                    'sistemaJuegoId' => $object->getSistemaJuegoUno()->getId(),
                    'eventoId' => $object->getSistemaJuegoUno()->getEvento()->getId(),
                    'tipo' => $tipo,
                        ], null);
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


        $this->addFlash('sonata_flash_error', $this->trans('formulario_escalera.encuentro_eliminado'));


        return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $eventoId, 'tipo' => $tipo));
    }

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



            return new RedirectResponse($this->admin->generateUrl('list', ['id' => $this->getRequest()->get('id'), 'tipo' => $this->getRequest()->get('tipo')]));
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


            $this->addFlash('sonata_flash_success', $this->trans('formulario_escalera.encuentro_eliminado'));
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash(
                    'sonata_flash_error', $this->trans('flash_batch_delete_error', [], 'SonataAdminBundle')
            );
        }

        return new RedirectResponse($this->admin->generateUrl(
                        'list', ['id' => $this->getRequest()->get('id'), 'tipo' => $this->getRequest()->get('tipo')]
        ));
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
