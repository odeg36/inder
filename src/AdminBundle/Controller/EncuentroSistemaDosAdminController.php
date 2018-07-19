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
use LogicBundle\Entity\SistemaJuegoDos;
use LogicBundle\Entity\SancionEvento;
use LogicBundle\Form\EncuentroSistemaDosType;
use LogicBundle\Entity\EncuentroSistemaDos;
use LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol;
use LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros;
use LogicBundle\Entity\FaltasEncuentroSistemaDos;
use LogicBundle\Form\FaltasEncuentrosSistemaDosType;
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

class EncuentroSistemaDosAdminController extends CRUDController {

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
        $idSistemaJuego = $this->em->getRepository("LogicBundle:SistemaJuegoDos")->find($sistemaJuego);

        //buscamos el evento para saber que tipo de cupo es "individual"/"equipos"
        $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);

        //aca obtenemos para validar mas adelante el tipo de cupo
        $tipoCupo = $evento->getCupo();

        //aca obtenemos la diciplina
        $diciplina = $evento->getDisciplina()->getNombre();



        if ($diciplina == "Voleibol" || $diciplina == "VOLEIBOL") {

            $puntosJuegoLimpio = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                    ->createQueryBuilder('c')
                    ->join('LogicBundle:SancionEvento', 'sanciones')
                    ->select('sum(sanciones.puntajeJuegoLimpio) as totalJuegoLimpio, IDENTITY(c.equipoEvento) as jugador')
                    ->where('c.identificadorSistemaJuego = :identificadorSistemaJuego')
                    ->setParameter('identificadorSistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosGanados = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as ganador')
                    ->where('c.setsAfavor > c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $ganados = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalGanados, IDENTITY(c.equipoEvento) as ganados')
                    ->where('c.setsAfavor > c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosPerdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as perdedor')
                    ->where('c.setsAfavor < c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $perdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalPerdidos, IDENTITY(c.equipoEvento) as perdidos')
                    ->where('c.setsAfavor < c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $encuentrosEmpatados = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as empate')
                    ->where('c.setsAfavor = c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $empatados = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalEmpatados, IDENTITY(c.equipoEvento) as empates')
                    ->where('c.setsAfavor = c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentros = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as partidosJugados, IDENTITY(c.equipoEvento) as jugados')
                    ->where('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $tablaPosicion = array();

            foreach ($encuentros as $jugados) {
                if (!isset($tablaPosicion[$jugados['jugados']])) {
                    $tablaPosicion[$jugados['jugados']] = [];
                }
                if (!isset($tablaPosicion[$jugados['jugados']]['partidos'])) {
                    $tablaPosicion[$jugados['jugados']]['partidos'] = $jugados['partidosJugados'];
                } else {
                    $tablaPosicion[$jugados['jugados']]['partidos'] += $jugados['partidosJugados'];
                }
            }


            foreach ($ganados as $gg) {
                if (!isset($tablaPosicion[$gg['ganados']])) {
                    $tablaPosicion[$gg['ganados']] = [];
                }
                if (!isset($tablaPosicion[$gg['ganados']]['partidosganados'])) {
                    $tablaPosicion[$gg['ganados']]['partidosganados'] = $gg['totalGanados'];
                } else {
                    $tablaPosicion[$gg['ganados']]['partidosganados'] += $gg['totalGanados'];
                }
            }

            foreach ($perdidos as $pp) {
                if (!isset($tablaPosicion[$pp['perdidos']])) {
                    $tablaPosicion[$pp['perdidos']] = [];
                }
                if (!isset($tablaPosicion[$pp['perdidos']]['partidosperdidos'])) {
                    $tablaPosicion[$pp['perdidos']]['partidosperdidos'] = $pp['totalPerdidos'];
                } else {
                    $tablaPosicion[$pp['perdidos']]['partidosperdidos'] += $pp['totalPerdidos'];
                }
            }


            foreach ($empatados as $pe) {
                if (!isset($tablaPosicion[$pe['empates']])) {
                    $tablaPosicion[$pe['empates']] = [];
                }
                if (!isset($tablaPosicion[$pe['empates']]['partidosempatados'])) {
                    $tablaPosicion[$pe['empates']]['partidosempatados'] = $pp['totalEmpatados'];
                } else {
                    $tablaPosicion[$pe['empates']]['partidosempatados'] += $pp['totalEmpatados'];
                }
            }


            foreach ($encuentrosGanados as $ganador) {
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

            //por aca ando

            foreach ($puntosJuegoLimpio as $jl) {
                if (!isset($tablaPosicion[$jl['jugador']])) {
                    $tablaPosicion[$jl['jugador']] = [];
                }
                if (!isset($tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'])) {
                    $tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'] = $idSistemaJuego->getPuntosJuegoLimpio() - $jl['totalJuegoLimpio'];
                } else {
                    $tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'] += $idSistemaJuego->getPuntosJuegoLimpio() - $jl['totalJuegoLimpio'];
                }
            }


            foreach ($encuentrosEmpatados as $empate) {
                if (!isset($tablaPosicion[$empate['empate']])) {
                    $tablaPosicion[$empate['empate']] = [];
                }
                if (!isset($tablaPosicion[$empate['empate']]['puntos'])) {
                    $tablaPosicion[$empate['empate']]['puntos'] = $empate['total'] * $idSistemaJuego->getPuntosEmpate();
                } else {
                    $tablaPosicion[$empate['empate']]['puntos'] += $empate['total'] * $idSistemaJuego->getPuntosEmpate();
                }
            }


            foreach ($tablaPosicion as $id => $datos) {
                $equipo = $this->em->getRepository("LogicBundle:EquipoEvento")->find($id);
                $tablaPosicion[$id]['nombre'] = $equipo->getNombre();
            }


            $puntos = array();
            foreach ($tablaPosicion as $key => $row) {
                $puntos[$key] = $row['puntos'];
            }


            $tablaReal = array_multisort($puntos, SORT_DESC, $tablaPosicion);


            return $tablaPosicion;
        } else {


            $puntosJuegoLimpio = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                    ->createQueryBuilder('c')
                    ->innerJoin('LogicBundle:SancionEvento', 'sanciones', 'WITH', 'c.identificadorSistemaJuego = :identificadorSistemaJuego')
                    ->select('sum(sanciones.puntajeJuegoLimpio) as totalJuegoLimpio, IDENTITY(c.equipoEvento) as jugador')
                    ->setParameter('identificadorSistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosGanados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as ganador')
                    ->where('c.puntosAfavor > c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $ganados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalGanados, IDENTITY(c.equipoEvento) as ganados')
                    ->where('c.puntosAfavor > c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosPerdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as perdedor')
                    ->where('c.puntosAfavor < c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $perdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalPerdidos, IDENTITY(c.equipoEvento) as perdidos')
                    ->where('c.puntosAfavor < c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $encuentrosEmpatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as empate')
                    ->where('c.puntosAfavor = c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $empatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalEmpatados, IDENTITY(c.equipoEvento) as empates')
                    ->where('c.puntosAfavor = c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentros = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as partidosJugados, IDENTITY(c.equipoEvento) as jugados')
                    ->where('c.identificadorSistemaJuego = :sistemaJuego')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $tablaPosicion = array();

            foreach ($encuentros as $jugados) {
                if (!isset($tablaPosicion[$jugados['jugados']])) {
                    $tablaPosicion[$jugados['jugados']] = [];
                }
                if (!isset($tablaPosicion[$jugados['jugados']]['partidos'])) {
                    $tablaPosicion[$jugados['jugados']]['partidos'] = $jugados['partidosJugados'];
                } else {
                    $tablaPosicion[$jugados['jugados']]['partidos'] += $jugados['partidosJugados'];
                }
            }


            foreach ($ganados as $gg) {
                if (!isset($tablaPosicion[$gg['ganados']])) {
                    $tablaPosicion[$gg['ganados']] = [];
                }
                if (!isset($tablaPosicion[$gg['ganados']]['partidosganados'])) {
                    $tablaPosicion[$gg['ganados']]['partidosganados'] = $gg['totalGanados'];
                } else {
                    $tablaPosicion[$gg['ganados']]['partidosganados'] += $gg['totalGanados'];
                }
            }

            foreach ($perdidos as $pp) {
                if (!isset($tablaPosicion[$pp['perdidos']])) {
                    $tablaPosicion[$pp['perdidos']] = [];
                }
                if (!isset($tablaPosicion[$pp['perdidos']]['partidosperdidos'])) {
                    $tablaPosicion[$pp['perdidos']]['partidosperdidos'] = $pp['totalPerdidos'];
                } else {
                    $tablaPosicion[$pp['perdidos']]['partidosperdidos'] += $pp['totalPerdidos'];
                }
            }


            foreach ($empatados as $pe) {
                if (!isset($tablaPosicion[$pe['empates']])) {
                    $tablaPosicion[$pe['empates']] = [];
                }
                if (!isset($tablaPosicion[$pe['empates']]['partidosempatados'])) {
                    $tablaPosicion[$pe['empates']]['partidosempatados'] = $pp['totalEmpatados'];
                } else {
                    $tablaPosicion[$pe['empates']]['partidosempatados'] += $pp['totalEmpatados'];
                }
            }


            foreach ($encuentrosGanados as $ganador) {
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


            foreach ($puntosJuegoLimpio as $jl) {
                if (!isset($tablaPosicion[$jl['jugador']])) {
                    $tablaPosicion[$jl['jugador']] = [];
                }
                if (!isset($tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'])) {
                    $tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'] = $idSistemaJuego->getPuntosJuegoLimpio() - $jl['totalJuegoLimpio'];
                } else {
                    $tablaPosicion[$jl['jugador']]['puntosJuegoLimpio'] += $idSistemaJuego->getPuntosJuegoLimpio() - $jl['totalJuegoLimpio'];
                }
            }


            foreach ($encuentrosEmpatados as $empate) {
                if (!isset($tablaPosicion[$empate['empate']])) {
                    $tablaPosicion[$empate['empate']] = [];
                }
                if (!isset($tablaPosicion[$empate['empate']]['puntos'])) {
                    $tablaPosicion[$empate['empate']]['puntos'] = $empate['total'] * $idSistemaJuego->getPuntosEmpate();
                } else {
                    $tablaPosicion[$empate['empate']]['puntos'] += $empate['total'] * $idSistemaJuego->getPuntosEmpate();
                }
            }


            foreach ($tablaPosicion as $id => $datos) {
                $equipo = $this->em->getRepository("LogicBundle:EquipoEvento")->find($id);
                $tablaPosicion[$id]['nombre'] = $equipo->getNombre();
            }


            $puntos = array();
            foreach ($tablaPosicion as $key => $row) {
                $puntos[$key] = $row['puntos'];
            }


            $tablaReal = array_multisort($puntos, SORT_DESC, $tablaPosicion);




            return $tablaPosicion;
        }
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
            $encuentro = new EncuentroSistemaDos();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaDos")->find($idEncuentro);
        }


        if (count($encuentro->getSistemaJuegoDos()->getTipoSistema())) {
            $idEvento = $encuentro->getSistemaJuegoDos()->getEvento()->getId();
        }

        if ($encuentro->getSistemaJuegoDos()->getTipoSistema() == "Liga") {
            $tipoSistema = 4;
        }

        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);
        }
        $cupo = $evento->getCupo();
        $jugadores = $evento->getJugadorEventos();
        $disciplina = $evento->getDisciplina()->getNombre();



        $jugadoresEvento = array();
        $equipos = null;
        $equiposEvento = array();
        $idJugador1 = 0;
        //$idJugador1=$idJugadores[0];
        $idJugador1 = $encuentro->getCompetidorUno();
        $idJugador2 = $encuentro->getCompetidorDos();

        if ($cupo == "Equipos") {

            $nombreJugador1 = $encuentro->getCompetidorUno()->getNombre();
            $nombreJugador2 = $encuentro->getCompetidorDos()->getNombre();


            $faltaJugador1 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('sancionEvento')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaDos', 'fej', 'WITH', 'sancionEvento.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaDos = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idJugador1->getId())
                    ->getQuery()
                    ->getResult()
            ;

            $faltaJugador2 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('sancionEvento')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaDos', 'fej', 'WITH', 'sancionEvento.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaDos = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idJugador2->getId())
                    ->getQuery()
                    ->getResult();
        }

        $resultadoJugador1;
        $resultadoJugador2;
        $validarEdicion = false;

        if ($disciplina == "Voleibol" || $diciplina == "VOLEIBOL") {

            $resultadoJugador1 = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaDos = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorUno())
                    ->getQuery()
                    ->getResult();


            $resultadoJugador2 = $this->em->getRepository("LogicBundle:ResultadosEncuentrosSistemaDosVoleibol")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaDos = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorDos())
                    ->getQuery()
                    ->getResult();

            if ($resultadoJugador1 != null || $resultadoJugador2 != null) {
                $validarEdicion = true;
            }
        } else {

            $resultadoJugador1 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaDos = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorUno())
                    ->getQuery()
                    ->getResult();


            $resultadoJugador2 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaDosOtros")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaDos = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorDos())
                    ->getQuery()
                    ->getResult();

            if ($resultadoJugador1 != null || $resultadoJugador2 != null) {
                $validarEdicion = true;
            }
        }

        $sanciones = $this->em->getRepository("LogicBundle:SancionEvento")
                ->createQueryBuilder('r')
                ->where('r.evento = :evento')
                ->setParameter('evento', $evento)
                ->getQuery()
                ->getResult();

        if ($sanciones == null || $sanciones == '') {
            $sanciones = 0;
        }

        $form = $this->createForm(FaltasEncuentrosSistemaDosType::class, $encuentro, array(
            'sanciones' => $sanciones,
            'faltasJugador1' => $faltaJugador1,
            'faltasJugador2' => $faltaJugador2,
            'disciplina' => $disciplina,
            'resultadoJugador1' => current($resultadoJugador1),
            'resultadoJugador2' => current($resultadoJugador2),
            'validarEdicion' => $validarEdicion,
        ));

        $form->setData($faltaJugador1, $faltaJugador2);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {


                if ($encuentro->getTipoDeEncuentro() == "Voleibol" || $diciplina == "VOLEIBOL") {

                    $resultadoVoleibol = new ResultadosEncuentrosSistemaDosVoleibol();

                    $resultadoVoleibol->setEncuentroSistemaDos($encuentro);
                    $resultadoVoleibol->setEquipoEvento($encuentro->getCompetidorUno());
                    $resultadoVoleibol->setPuntosJuegoLimpio($encuentro->getSistemaJuegoDos()->getPuntosJuegoLimpio());
                    $resultadoVoleibol->setSetUno($form->get('setUno')->getData());
                    $resultadoVoleibol->setSetDos($form->get('setDos')->getData());
                    $resultadoVoleibol->setSetTres($form->get('setTres')->getData());
                    $resultadoVoleibol->setSetCuatro($form->get('setCuatro')->getData());
                    $resultadoVoleibol->setSetCinco($form->get('setCinco')->getData());
                    $resultadoVoleibol->setSetsAfavor($form->get('setAfavor')->getData());
                    $resultadoVoleibol->setSetsEnContra($form->get('setEnContra')->getData());
                    $resultadoVoleibol->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());

                    $this->em->persist($resultadoVoleibol);
                    $this->em->flush();


                    $resultadoVoleibolOtro = new ResultadosEncuentrosSistemaDosVoleibol();

                    $resultadoVoleibolOtro->setEncuentroSistemaDos($encuentro);
                    $resultadoVoleibolOtro->setEquipoEvento($encuentro->getCompetidorDos());
                    $resultadoVoleibolOtro->setPuntosJuegoLimpio($encuentro->getSistemaJuegoDos()->getPuntosJuegoLimpio());
                    $resultadoVoleibolOtro->setSetUno($form->get('setUno2')->getData());
                    $resultadoVoleibolOtro->setSetDos($form->get('setDos2')->getData());
                    $resultadoVoleibolOtro->setSetTres($form->get('setTres2')->getData());
                    $resultadoVoleibolOtro->setSetCuatro($form->get('setCuatro2')->getData());
                    $resultadoVoleibolOtro->setSetCinco($form->get('setCinco2')->getData());
                    $resultadoVoleibolOtro->setSetsAfavor($form->get('setAfavor2')->getData());
                    $resultadoVoleibolOtro->setSetsEnContra($form->get('setEnContra2')->getData());
                    $resultadoVoleibolOtro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());

                    $this->em->persist($resultadoVoleibolOtro);
                    $this->em->flush();


                    $datosFalta = $request->request->get('faltas_encuentros_sistema_dos');

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
                    if (count($datosJugador1)) {
                        foreach ($datosJugador1 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $encuentro->getCompetidorUno())
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaDos = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroSistemaDos();
                                $faltasEncuentro->setEncuentroSistemaDos($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorUno());
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }//exit();
                            $this->em->flush();
                        }
                    }

                    if (count($datosJugador2)) {
                        foreach ($datosJugador2 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                                    ->createQueryBuilder('r')
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaDos = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $encuentro->getCompetidorDos())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroSistemaDos();
                                $faltasEncuentro->setEncuentroSistemaDos($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorDos());
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }
                }


                if ($encuentro->getTipoDeEncuentro() != "Voleibol" || $diciplina != "VOLEIBOL") {


                    $resultado = new ResultadosEncuentroSistemaDosOtros();

                    $resultado->setEncuentroSistemaDos($encuentro);
                    $resultado->setEquipoEvento($encuentro->getCompetidorUno());
                    $resultado->setPuntosJuegoLimpio($encuentro->getSistemaJuegoDos()->getPuntosJuegoLimpio());
                    $resultado->setPuntosAfavor($form->get('puntosAfavor')->getData());
                    $resultado->setPuntosEnContra($form->get('puntosEnContra')->getData());
                    $resultado->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());

                    $this->em->persist($resultado);
                    $this->em->flush();


                    $resultadoOtro = new ResultadosEncuentroSistemaDosOtros();

                    $resultadoOtro->setEncuentroSistemaDos($encuentro);
                    $resultadoOtro->setEquipoEvento($encuentro->getCompetidorDos());
                    $resultadoOtro->setPuntosJuegoLimpio($encuentro->getSistemaJuegoDos()->getPuntosJuegoLimpio());
                    $resultadoOtro->setPuntosAfavor($form->get('puntosAfavor2')->getData());
                    $resultadoOtro->setPuntosEnContra($form->get('puntosEnContra2')->getData());
                    $resultadoOtro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());


                    $this->em->persist($resultadoOtro);
                    $this->em->flush();



                    $datosFalta = $request->request->get('faltas_encuentros_sistema_dos');

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


                    if (count($datosJugador1)) {

                        foreach ($datosJugador1 as $faltas) {

                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $encuentro->getCompetidorUno())
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaDos = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroSistemaDos();
                                $faltasEncuentro->setEncuentroSistemaDos($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorUno());
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());

                                $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $this->em->persist($faltasEncuentro);
                            }//exit();
                            $this->em->flush();
                        }
                    }

                    if (count($datosJugador2)) {


                        foreach ($datosJugador2 as $faltas) {
                            $encontrarFalta = $this->em->getRepository("LogicBundle:SancionEvento")->find($faltas['faltasEncuentroJugador']);

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaDos")
                                    ->createQueryBuilder('r')
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaDos = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $encuentro->getCompetidorDos())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroSistemaDos();
                                $faltasEncuentro->setEncuentroSistemaDos($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorDos());
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoDos()->getId());
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }
                }


                return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $encuentro->getSistemaJuegoDos()->getEvento()->getId(), 'tipo' => $tipoSistema));
            }
        }



        return $this->render('AdminBundle:Liga:create_resultado.html.twig', array(
                    'form' => $form->createView(),
                    'nombreJugador1' => $nombreJugador1,
                    'nombreJugador2' => $nombreJugador2,
                    'eventoId' => $encuentro->getSistemaJuegoDos()->getEvento()->getId(),
                    'tipoDeSistemaDeJuego' => $tipoSistema,
                    'disciplina' => $disciplina,
        ));
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


        if ($evento->getCupo() != "Equipos") {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('alerta') == null) {
            $alertaSis = 1;
        } else {

            $alertaSis = 0;
        }


        $tipoDeSistema = $request->get('tipo');

        if ($tipoDeSistema == 4) {

            $sistemaGuardar = "Liga";
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

        $sistemaJuegoDos = new SistemaJuegoDos();

        //declaracion para el form con el type donde se realizan los campos
        //esto debemos pasarlo al sonata admin en el array
        $form = $this->createForm(EncuentroSistemaDosType::class, $sistemaJuegoDos, array(
            'em' => $this->container,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            if ($form->isValid()) {
                $sistemaJuegoDos->setTipoSistema($sistemaGuardar);
                $sistemaJuegoDos->setEvento($evento);

                $this->em->persist($sistemaJuegoDos);
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
            }
        }

        $sistemaJuegoDos = $this->em->getRepository("LogicBundle:SistemaJuegoDos")
                        ->createQueryBuilder('sistemaJuegoDos')
                        ->where('sistemaJuegoDos.evento = :evento')
                        ->andWhere('sistemaJuegoDos.tipoSistema = :tipo')
                        ->setParameter('evento', $evento->getId())
                        ->setParameter('tipo', $sistemaGuardar)
                        ->getQuery()->getOneOrNullResult();


        if (!$sistemaJuegoDos) {
            $guardado = 0;
        } else {
            $guardado = 1;
        }


        if ($sistemaJuegoDos != null) {
            $sistemaId = $sistemaJuegoDos->getId();

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


        // set the theme for the current Admin Form
        //$this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
                    'action' => 'list',
                    'form' => $formView,
                    'idSistemaJuego' => $sistemaId,
                    'eventoId' => $evento->getId(),
                    'nombreEvento' => $evento->getNombre(),
                    'tipoDeSistema' => $tipoDeSistema,
                    'guardado' => $guardado,
                    'alertaSis' => $alertaSis,
                    'tablaPosiciones' => $tablaPosiciones,
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

        $sisjuego = $em->getRepository('LogicBundle:SistemaJuegoDos')->find($request->get('sistemaJuegoDos'));


        if ($sisjuego == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('sistemaJuegoDos') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($request->get('tipoDeSistemaDeJuego') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }



        $existeEquipos = $this->em->getRepository("LogicBundle:EquipoEvento")
                        ->createQueryBuilder('equipoEvento')
                        ->where('equipoEvento.evento = :evento')
                        ->setParameter('evento', $sisjuego->getEvento()->getId())
                        ->getQuery()->getResult();

        if ($existeEquipos == null) {
            $this->addFlash('sonata_flash_error', 'No hay equipos Asociados al evento');

            return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
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

                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData());
                    $submittedObject->setcompetidorDos($form->get('competidorDos')->getData());
                    $submittedObject->setSistemaJuegoDos($sisjuego);
                    $submittedObject->setTipoDeEncuentro($form->get('tipoDeEncuentro')->getData());


                    if ($form->get('competidorUno')->getData() == $form->get('competidorDos')->getData()) {
                        $this->addFlash('sonata_flash_error', "los equipos son los mismos");
                        return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
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
                        $reserva->setDisciplina($sisjuego->getEvento()->getDisciplina());
                        $reserva->setTipoReserva($tipoReserva);

                        $this->em->persist($reserva);
                        $this->em->flush();
                    }


                    if ($form->get("puntoAtencion")->getData() != null) {

                        $puntoAtencion = $this->em->getRepository("LogicBundle:PuntoAtencion")->find($form->get("puntoAtencion")->getData());

                        $fechaInicial = $form->get("fecha")->getData();

                        $fechaFinal = $form->get("fecha")->getData();

                        $horaInicial = $form->get("hora")->getData();

                        $horaFinal = $form->get("hora")->getData();

                        $tipoReserva = $this->em->getRepository("LogicBundle:TipoReserva")
                                        ->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :evento')
                                        ->setParameter('evento', 'Evento')
                                        ->getQuery()->getOneOrNullResult();

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
                    return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $sisjuego->getEvento()->getId(), 'tipo' => $request->get('tipoDeSistemaDeJuego')));
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


        if ($sisjuego->getTipoSistema() == "Liga") {
            $tipo = 4;
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



        if ($object->getSistemaJuegoDos()->getTipoSistema() == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($object->getSistemaJuegoDos()->getTipoSistema() == "Liga") {
            $tipo = 4;
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

                    $submittedObject->setCompetidorUno($form->get('competidorUno')->getData());
                    $submittedObject->setCompetidorDos($form->get('competidorDos')->getData());

                    if ($form->get('competidorUno')->getData() == $form->get('competidorDos')->getData()) {
                        $this->addFlash('sonata_flash_error', "los equipos son los mismos");
                        return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $object->getSistemaJuegoDos()->getEvento()->getId(), 'tipo' => $tipo));
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
                    return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $object->getSistemaJuegoDos()->getEvento()->getId(), 'tipo' => $tipo));
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
                    'sistemaJuegoId' => $object->getSistemaJuegoDos()->getId(),
                    'eventoId' => $object->getSistemaJuegoDos()->getEvento()->getId(),
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

        if ($object->getSistemaJuegoDos()->getTipoSistema() == null || $object->getSistemaJuegoDos()->getTipoSistema() != "Liga") {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }

        $em = $this->getDoctrine()->getManager();


        if ($object->getSistemaJuegoDos()->getTipoSistema() == "Liga") {
            $tipo = 4;
        }

        $eventoId = $object->getSistemaJuegoDos()->getEvento()->getId();

        $em->remove($object);
        $em->flush();

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                        'result' => 'ok',
                            ), 200, array());
        }

        $this->addFlash('sonata_flash_error', $this->trans('formulario_escalera.encuentro_eliminado'));

        return $this->redirectToRoute('admin_logic_encuentrosistemados_list', array('id' => $eventoId, 'tipo' => $tipo));
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
