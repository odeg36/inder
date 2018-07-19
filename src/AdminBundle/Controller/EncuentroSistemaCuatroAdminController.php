<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
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
use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\TipoReserva;
use LogicBundle\Entity\SistemaJuegoCuatro;
use LogicBundle\Entity\GruposEncuentroSistemaCuatro;
use LogicBundle\Entity\SancionEvento;
use LogicBundle\Form\EncuentroSistemaCuatroType;
use LogicBundle\Form\GruposEncuentroSistemaCuatroType;
use LogicBundle\Entity\EncuentroSistemaCuatro;
use LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol;
use LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros;
use LogicBundle\Entity\FaltasEncuentroSistemaCuatro;
use LogicBundle\Form\ProgramarEncuentrosType;
use LogicBundle\Form\FaltasEncuentrosSistemaCuatroType;
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

class EncuentroSistemaCuatroAdminController extends CRUDController {

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
    public function getPoscisiones($idEvento, $sistemaJuego, $parametro) {


        //me permite conocer el sistema de juego relacionado, para saber los puntajes configurados
        $idSistemaJuego = $this->em->getRepository("LogicBundle:SistemaJuegoCuatro")->find($sistemaJuego);

        //buscamos el evento para saber que tipo de cupo es "individual"/"equipos"
        $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);



        //aca obtenemos para validar mas adelante el tipo de cupo
        $tipoCupo = $evento->getCupo();

        //aca obtenemos la diciplina
        $diciplina = $evento->getDisciplina()->getNombre();


        if ($diciplina == "Voleibol" || $diciplina == "VOLEIBOL") {

            $puntosJuegoLimpio = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                    ->createQueryBuilder('c')
                    ->innerJoin('LogicBundle:SancionEvento', 'sanciones', 'WITH', 'c.sancionEvento = sanciones.id')
                    ->select('sum(sanciones.puntajeJuegoLimpio) as totalJuegoLimpio, IDENTITY(c.equipoEvento) as jugador')
                    ->where('c.identificadorSistemaJuego = :identificadorSistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('identificadorSistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosGanados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as ganador')
                    ->where('c.setsAfavor > c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $ganados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalGanados, IDENTITY(c.equipoEvento) as ganados')
                    ->where('c.setsAfavor > c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosPerdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as perdedor')
                    ->where('c.setsAfavor < c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $perdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalPerdidos, IDENTITY(c.equipoEvento) as perdidos')
                    ->where('c.setsAfavor < c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $encuentrosEmpatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as empate')
                    ->where('c.setsAfavor = c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $empatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalEmpatados, IDENTITY(c.equipoEvento) as empates')
                    ->where('c.setsAfavor = c.setsEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentros = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as partidosJugados, IDENTITY(c.equipoEvento) as jugados')
                    ->where('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();
        } else {


            $puntosJuegoLimpio = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                    ->createQueryBuilder('c')
                    ->innerJoin('LogicBundle:SancionEvento', 'sanciones', 'WITH', 'c.sancionEvento = sanciones.id')
                    ->select('sum(sanciones.puntajeJuegoLimpio) as totalJuegoLimpio, IDENTITY(c.equipoEvento) as jugador')
                    ->where('c.identificadorSistemaJuego = :identificadorSistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('identificadorSistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            //esto es para el pull

            $encuentrosGanados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as ganador')
                    ->where('c.puntosAfavor > c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $ganados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalGanados, IDENTITY(c.equipoEvento) as ganados')
                    ->where('c.puntosAfavor > c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentrosPerdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as perdedor')
                    ->where('c.puntosAfavor < c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $perdidos = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalPerdidos, IDENTITY(c.equipoEvento) as perdidos')
                    ->where('c.puntosAfavor < c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();

            $encuentrosEmpatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as total, IDENTITY(c.equipoEvento) as empate')
                    ->where('c.puntosAfavor = c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $empatados = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as totalEmpatados, IDENTITY(c.equipoEvento) as empates')
                    ->where('c.puntosAfavor = c.puntosEnContra')
                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();


            $encuentros = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('c')
                    ->select('count(c.equipoEvento) as partidosJugados, IDENTITY(c.equipoEvento) as jugados')
                    ->where('c.identificadorSistemaJuego = :sistemaJuego')
                    ->andWhere('c.grupo = :grupo')
                    ->setParameter('sistemaJuego', $idSistemaJuego->getId())
                    ->setParameter('grupo', $parametro)
                    ->groupBy('c.equipoEvento')
                    ->getQuery()
                    ->getResult();
        }

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
            $tablaPosicion[$id]['idEquipo'] = $id;
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

        // formulario_play_off

        if ($request->get('alerta') == null) {
            $alertaSis = 1;
        } else {

            $alertaSis = 0;
        }


        $totalEquipos = count($evento->getEquipoEventos());

        if ($totalEquipos == 16) {
            
        } else {
            $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.cantidad'));

            return $this->redirectToRoute('admin_logic_encuentrosistemauno_list', array('id' => $evento->getId(), 'tipo' => 1));
        }


        if ($request->get('tipo') == null) {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        if ($evento->getCupo() != "Equipos") {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }


        $tipoDeSistema = $request->get('tipo');

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

        $sistemaJuegoCuatro = new SistemaJuegoCuatro();
        $gruposEncuentroSistemaCuatro = new GruposEncuentroSistemaCuatro();

        //declaracion para el form con el type donde se realizan los campos
        //esto debemos pasarlo al sonata admin en el array
        $form = $this->createForm(EncuentroSistemaCuatroType::class, $sistemaJuegoCuatro, array(
            'em' => $this->container,
        ));

        $form2 = $this->createForm(GruposEncuentroSistemaCuatroType::class, $gruposEncuentroSistemaCuatro, array(
            'em' => $this->container,
            'eventoId' => $evento->getId()
        ));

        $form->handleRequest($request);
        $form2->handleRequest($request);

        if ($form->isSubmitted()) {


            if ($form->isValid()) {
                $sistemaJuegoCuatro->setTipoSistema($sistemaGuardar);
                $sistemaJuegoCuatro->setEvento($evento);

                $this->em->persist($sistemaJuegoCuatro);
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
            }
        } else if ($form2->isSubmitted()) {

            if ($form2->isValid()) {



                if ($form2->get("equipoEvento")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x != 1) {
                            if ($form2->get("equipoEvento")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento2")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento2")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 2 && $x != 1) {
                            if ($form2->get("equipoEvento2")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento3")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento3")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 3 && $x != 1) {
                            if ($form2->get("equipoEvento3")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento4")->getData()->getId() != null) {

                    for ($x = 1; $x <= 16; $x++) {


                        if ($x == 1) {
                            if ($form2->get("equipoEvento4")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 4 && $x != 1) {
                            if ($form2->get("equipoEvento4")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));
                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento5")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento5")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 5 && $x != 1) {
                            if ($form2->get("equipoEvento5")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento6")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento6")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 6 && $x != 1) {
                            if ($form2->get("equipoEvento6")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }


                if ($form2->get("equipoEvento7")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento7")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 7 && $x != 1) {
                            if ($form2->get("equipoEvento7")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }


                if ($form2->get("equipoEvento8")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento8")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 8 && $x != 1) {
                            if ($form2->get("equipoEvento8")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }


                if ($form2->get("equipoEvento9")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento9")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 9 && $x != 1) {
                            if ($form2->get("equipoEvento9")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento10")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento10")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 10 && $x != 1) {
                            if ($form2->get("equipoEvento10")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento11")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento11")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 11 && $x != 1) {
                            if ($form2->get("equipoEvento11")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento12")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento12")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }

                        if ($x != 12 && $x != 1) {
                            if ($form2->get("equipoEvento12")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento13")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento13")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }


                        if ($x != 13 && $x != 1) {
                            if ($form2->get("equipoEvento13")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento14")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento14")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }


                        if ($x != 14 && $x != 1) {
                            if ($form2->get("equipoEvento14")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento15")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {
                        if ($x == 1) {
                            if ($form2->get("equipoEvento15")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }


                        if ($x != 15 && $x != 1) {
                            if ($form2->get("equipoEvento15")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }

                if ($form2->get("equipoEvento16")->getData()->getId() != null) {
                    for ($x = 1; $x <= 16; $x++) {

                        if ($x == 1) {
                            if ($form2->get("equipoEvento16")->getData()->getId() == $form2->get("equipoEvento")->getData()->getId()) {
                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }


                        if ($x != 16 && $x != 1) {
                            if ($form2->get("equipoEvento16")->getData()->getId() == $form2->get("equipoEvento" . $x)->getData()->getId()) {

                                $this->addFlash('sonata_flash_error', $this->trans('formulario_play_off.error'));

                                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
                            }
                        }
                    }
                }


                $grupos = array(
                    array(
                        'grupo' => 'A',
                        'identificador' => 'A1',
                    ),
                    array(
                        'grupo' => 'A',
                        'identificador' => 'A2',
                    ),
                    array(
                        'grupo' => 'A',
                        'identificador' => 'A3',
                    ),
                    array(
                        'grupo' => 'A',
                        'identificador' => 'A4',
                    ),
                    array(
                        'grupo' => 'B',
                        'identificador' => 'B1',
                    ),
                    array(
                        'grupo' => 'B',
                        'identificador' => 'B2',
                    ),
                    array(
                        'grupo' => 'B',
                        'identificador' => 'B3',
                    ),
                    array(
                        'grupo' => 'B',
                        'identificador' => 'B4',
                    ),
                    array(
                        'grupo' => 'C',
                        'identificador' => 'C1',
                    ),
                    array(
                        'grupo' => 'C',
                        'identificador' => 'C2',
                    ),
                    array(
                        'grupo' => 'C',
                        'identificador' => 'C3',
                    ),
                    array(
                        'grupo' => 'C',
                        'identificador' => 'C4',
                    ),
                    array(
                        'grupo' => 'D',
                        'identificador' => 'D1',
                    ),
                    array(
                        'grupo' => 'D',
                        'identificador' => 'D2',
                    ),
                    array(
                        'grupo' => 'D',
                        'identificador' => 'D3',
                    ),
                    array(
                        'grupo' => 'D',
                        'identificador' => 'D4',
                    )
                );

                $sisJuegoCuatro = $this->em->getRepository("LogicBundle:SistemaJuegoCuatro")
                                ->createQueryBuilder('sistemaJuegoCuatro')
                                ->where('sistemaJuegoCuatro.evento = :evento')
                                ->andWhere('sistemaJuegoCuatro.tipoSistema = :tipo')
                                ->setParameter('evento', $evento->getId())
                                ->setParameter('tipo', $sistemaGuardar)
                                ->getQuery()->getOneOrNullResult();

                $i = 1;
                $dataGrupos = array();
                foreach ($grupos as $grupo) {
                    $gruposEncuentroSistemaCuatro = new GruposEncuentroSistemaCuatro();
                    $nombre = 'equipoEvento';
                    if ($i > 1) {
                        $nombre .= $i;
                    }
                    $equipo = $form2->get($nombre)->getData();
                    $gruposEncuentroSistemaCuatro->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $gruposEncuentroSistemaCuatro->setEquipoEvento($equipo);
                    $gruposEncuentroSistemaCuatro->setNombre($grupo["grupo"]);
                    $gruposEncuentroSistemaCuatro->setIdentificador($grupo["identificador"]);

                    $this->em->persist($gruposEncuentroSistemaCuatro);
                    $this->em->flush();

                    $dataGrupos[$grupo["identificador"]] = $equipo;

                    $i++;
                }
                $grupos = array("A", "B", "C", "D");
                foreach ($grupos as $grupo) {

                    $equipo1 = $dataGrupos[$grupo . "1"];
                    $equipo2 = $dataGrupos[$grupo . "2"];
                    $equipo3 = $dataGrupos[$grupo . "3"];
                    $equipo4 = $dataGrupos[$grupo . "4"];

                    $encuentro = new EncuentroSistemaCuatro();
                    $encuentro->setCompetidorUno($equipo1);
                    $encuentro->setCompetidorDos($equipo2);
                    $encuentro->setFase("grupos");
                    $encuentro->setGrupo($grupo);
                    $encuentro->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro);

                    $encuentro2 = new EncuentroSistemaCuatro();
                    $encuentro2->setCompetidorUno($equipo3);
                    $encuentro2->setCompetidorDos($equipo4);
                    $encuentro2->setFase("grupos");
                    $encuentro2->setGrupo($grupo);
                    $encuentro2->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro2);

                    $encuentro3 = new EncuentroSistemaCuatro();
                    $encuentro3->setCompetidorUno($equipo1);
                    $encuentro3->setCompetidorDos($equipo3);
                    $encuentro3->setFase("grupos");
                    $encuentro3->setGrupo($grupo);
                    $encuentro3->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro3);

                    $encuentro4 = new EncuentroSistemaCuatro();
                    $encuentro4->setCompetidorUno($equipo2);
                    $encuentro4->setCompetidorDos($equipo4);
                    $encuentro4->setFase("grupos");
                    $encuentro4->setGrupo($grupo);
                    $encuentro4->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro4);

                    $encuentro5 = new EncuentroSistemaCuatro();
                    $encuentro5->setCompetidorUno($equipo1);
                    $encuentro5->setCompetidorDos($equipo4);
                    $encuentro5->setFase("grupos");
                    $encuentro5->setGrupo($grupo);
                    $encuentro5->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro5);

                    $encuentro6 = new EncuentroSistemaCuatro();
                    $encuentro6->setCompetidorUno($equipo2);
                    $encuentro6->setCompetidorDos($equipo3);
                    $encuentro6->setFase("grupos");
                    $encuentro6->setGrupo($grupo);
                    $encuentro6->setSistemaJuegoCuatro($sisJuegoCuatro);
                    $this->em->persist($encuentro6);

                    $this->em->flush();
                }

                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoDeSistema));
            }
        }

        $sistemaJuegoCuatro = $this->em->getRepository("LogicBundle:SistemaJuegoCuatro")
                        ->createQueryBuilder('sistemaJuegoCuatro')
                        ->where('sistemaJuegoCuatro.evento = :evento')
                        ->andWhere('sistemaJuegoCuatro.tipoSistema = :tipo')
                        ->setParameter('evento', $evento->getId())
                        ->setParameter('tipo', $sistemaGuardar)
                        ->getQuery()->getOneOrNullResult();

        if (count($sistemaJuegoCuatro) == 0) {

            $guardado = 0;
        } else {
            $guardado = 1;
        }


        $pocisiones = array();
        if ($sistemaJuegoCuatro != null) {

            $sistemaId = $sistemaJuegoCuatro->getId();

            $tablaPosicionesA = $this->getPoscisiones($request->get('id'), $sistemaId, "A");
            $tablaPosicionesB = $this->getPoscisiones($request->get('id'), $sistemaId, "B");
            $tablaPosicionesC = $this->getPoscisiones($request->get('id'), $sistemaId, "C");
            $tablaPosicionesD = $this->getPoscisiones($request->get('id'), $sistemaId, "D");


            if (count($tablaPosicionesA) > 0 && count($tablaPosicionesB) > 0 && count($tablaPosicionesC) > 0 && count($tablaPosicionesD) > 0) {
                array_push($pocisiones, $tablaPosicionesA, $tablaPosicionesB, $tablaPosicionesC, $tablaPosicionesD);
            }
        } else {

            $sistemaId = 0;
        }

        $datagrid = $this->admin->getDatagrid();

        $formView = $datagrid->getForm()->createView();


        // set the theme for the current Admin Form
        //$this->setFormTheme($formView, $this->admin->getFilterTheme());

        $sistemaJuegoGrupo = $this->em->getRepository("LogicBundle:GruposEncuentroSistemaCuatro")
                        ->createQueryBuilder('GruposEncuentroSistemaCuatro')
                        ->where('GruposEncuentroSistemaCuatro.sistemaJuegoCuatro = :sistema_juego_cuatro')
                        ->setParameter('sistema_juego_cuatro', $sistemaId)
                        ->getQuery()->getResult();

        return $this->render($this->admin->getTemplate('list'), array(
                    'action' => 'list',
                    'form' => $formView,
                    'idSistemaJuego' => $sistemaId,
                    'eventoId' => $evento->getId(),
                    'nombreEvento' => $evento->getNombre(),
                    'tipoDeSistema' => $tipoDeSistema,
                    'pocisiones' => $pocisiones,
                    'guardado' => $guardado,
                    'alertaSis' => $alertaSis,
                    'sistemaJuegoGrupo' => $sistemaJuegoGrupo,
                    'formSitemaJuegoUno' => $form->createView(),
                    'formGruposJuegoCuatro' => $form2->createView(),
                    'datagrid' => $datagrid,
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                    $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                    $this->admin->getExportFormats(),
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

        if ($object->getSistemaJuegoCuatro()->getTipoSistema() == null || $object->getSistemaJuegoCuatro()->getTipoSistema() != "PlayOff") {
            return $this->redirect($this->generateUrl('admin_logic_evento_configuracion', array('id' => 0)));
        }

        $em = $this->getDoctrine()->getManager();


        if ($object->getSistemaJuegoCuatro()->getTipoSistema() == "PlayOff") {
            $tipo = 6;
        }

        $eventoId = $object->getSistemaJuegoCuatro()->getEvento()->getId();

        $em->remove($object);
        $em->flush();

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                        'result' => 'ok',
                            ), 200, array());
        }

        $this->addFlash('sonata_flash_error', $this->trans('formulario_escalera.encuentro_eliminado'));

        return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $eventoId, 'tipo' => $tipo));
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

    /**
     * resultadoEncuentro action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function resultadoEncuentroCuatroAction() {


        $request = $this->getRequest();


        $idEncuentro = $request->get('idEncuentro');
        $tipoDeSistema = $request->get('tipo');

        if ($idEncuentro == 0) {
            $encuentro = new EncuentroSistemaCuatro();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")->find($idEncuentro);
        }

        $idEvento = 0;
        if (count($encuentro->getSistemaJuegoCuatro()->getTipoSistema())) {
            $idEvento = $encuentro->getSistemaJuegoCuatro()->getEvento()->getId();
        }

        if ($encuentro->getSistemaJuegoCuatro()->getTipoSistema() == "PlayOff") {
            $tipoSistema = 6;
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

        $idJugador1 = $encuentro->getCompetidorUno();
        $idJugador2 = $encuentro->getCompetidorDos();

        if ($cupo == "Equipos") {

            $nombreJugador1 = $encuentro->getCompetidorUno()->getNombre();
            $nombreJugador2 = $encuentro->getCompetidorDos()->getNombre();

            $faltaJugador1 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('sancionEvento')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaCuatro', 'fej', 'WITH', 'sancionEvento.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaCuatro = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idJugador1->getId())
                    ->getQuery()
                    ->getResult()
            ;

            $faltaJugador2 = $this->em->getRepository("LogicBundle:SancionEvento")
                    ->createQueryBuilder('sancionEvento')
                    ->innerJoin('LogicBundle:FaltasEncuentroSistemaCuatro', 'fej', 'WITH', 'sancionEvento.id = fej.sancionEvento')
                    ->where('fej.encuentroSistemaCuatro = :encuentro')
                    ->andWhere('fej.equipoEvento = :jugadorEvento')
                    ->andWhere('sancionEvento.id = fej.sancionEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $idJugador2->getId())
                    ->getQuery()
                    ->getResult();
        }

        $resultadoJugador1;
        $resultadoJugador2;
        $validarEdicion = false;

        if ($disciplina == "Voleibol" || $diciplina == "VOLEIBOL") {
            $resultadoJugador1 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaCuatro = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorUno())
                    ->getQuery()
                    ->getResult();


            $resultadoJugador2 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaCuatro = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorDos())
                    ->getQuery()
                    ->getResult();

            if ($resultadoJugador1 != null || $resultadoJugador2 != null) {
                $validarEdicion = true;
            }
        } else {

            $resultadoJugador1 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaCuatro = :encuentro')
                    ->andWhere('resultado.equipoEvento = :jugadorEvento')
                    ->setParameter('encuentro', $encuentro->getId())
                    ->setParameter('jugadorEvento', $encuentro->getCompetidorUno())
                    ->getQuery()
                    ->getResult();


            $resultadoJugador2 = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                    ->createQueryBuilder('resultado')
                    ->where('resultado.encuentroSistemaCuatro = :encuentro')
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


        $form = $this->createForm(FaltasEncuentrosSistemaCuatroType::class, $encuentro, array(
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


                if ($disciplina == "Voleibol" || $diciplina == "VOLEIBOL") {

                    $habilitarFase = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                            ->createQueryBuilder('c')
                            ->select('count(c.id) as total')
                            ->getQuery()
                            ->getSingleScalarResult();

                    $grupoCompetidorUno = $this->em->getRepository("LogicBundle:GruposEncuentroSistemaCuatro")
                            ->createQueryBuilder('r')
                            ->where('r.equipoEvento = :equipo')
                            ->setParameter('equipo', $encuentro->getCompetidorUno())
                            ->getQuery()
                            ->getOneOrNullResult();


                    $grupoCompetidorDos = $this->em->getRepository("LogicBundle:GruposEncuentroSistemaCuatro")
                            ->createQueryBuilder('r')
                            ->where('r.equipoEvento = :equipo')
                            ->setParameter('equipo', $encuentro->getCompetidorDos())
                            ->getQuery()
                            ->getOneOrNullResult();


                    $resultadoVoleibol = new ResultadosEncuentroSistemaCuatroVoleibol();

                    $resultadoVoleibol->setEncuentroSistemaCuatro($encuentro);
                    $resultadoVoleibol->setEquipoEvento($encuentro->getCompetidorUno());
                    $resultadoVoleibol->setPuntosJuegoLimpio($encuentro->getSistemaJuegoCuatro()->getPuntosJuegoLimpio());
                    $resultadoVoleibol->setSetUno($form->get('setUno')->getData());
                    $resultadoVoleibol->setSetDos($form->get('setDos')->getData());
                    $resultadoVoleibol->setSetTres($form->get('setTres')->getData());
                    $resultadoVoleibol->setSetCuatro($form->get('setCuatro')->getData());
                    $resultadoVoleibol->setSetCinco($form->get('setCinco')->getData());
                    $resultadoVoleibol->setSetsAfavor($form->get('setAfavor')->getData());
                    $resultadoVoleibol->setSetsEnContra($form->get('setEnContra')->getData());
                    $resultadoVoleibol->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());


                    $resultadoVoleibolOtro = new ResultadosEncuentroSistemaCuatroVoleibol();

                    $resultadoVoleibolOtro->setEncuentroSistemaCuatro($encuentro);
                    $resultadoVoleibolOtro->setEquipoEvento($encuentro->getCompetidorDos());
                    $resultadoVoleibolOtro->setPuntosJuegoLimpio($encuentro->getSistemaJuegoCuatro()->getPuntosJuegoLimpio());
                    $resultadoVoleibolOtro->setSetUno($form->get('setUno2')->getData());
                    $resultadoVoleibolOtro->setSetDos($form->get('setDos2')->getData());
                    $resultadoVoleibolOtro->setSetTres($form->get('setTres2')->getData());
                    $resultadoVoleibolOtro->setSetCuatro($form->get('setCuatro2')->getData());
                    $resultadoVoleibolOtro->setSetCinco($form->get('setCinco2')->getData());
                    $resultadoVoleibolOtro->setSetsAfavor($form->get('setAfavor2')->getData());
                    $resultadoVoleibolOtro->setSetsEnContra($form->get('setEnContra2')->getData());
                    $resultadoVoleibolOtro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());


                    if ($habilitarFase < 48) {
                        $resultadoVoleibol->setGrupo($grupoCompetidorUno->getNombre());
                        $resultadoVoleibolOtro->setGrupo($grupoCompetidorDos->getNombre());
                    }

                    if ($habilitarFase >= 48 && $habilitarFase < 56) {
                        $resultadoVoleibol->setGrupo("Cuartos");
                        $resultadoVoleibolOtro->setGrupo("Cuartos");
                    }


                    if ($habilitarFase >= 56 && $habilitarFase < 60) {
                        $resultadoVoleibol->setGrupo("Semi final");
                        $resultadoVoleibolOtro->setGrupo("Semi final");
                    }


                    if ($habilitarFase >= 60) {
                        $resultadoVoleibol->setGrupo("Final");
                        $resultadoVoleibolOtro->setGrupo("Final");
                    }

                    $this->em->persist($resultadoVoleibol);
                    $this->em->persist($resultadoVoleibolOtro);
                    $this->em->flush();


                    $datosFalta = $request->request->get('faltas_encuentros_sistema_cuatro');

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

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $encuentro->getCompetidorUno())
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaCuatro = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroSistemaCuatro();
                                $faltasEncuentro->setEncuentroSistemaCuatro($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorUno());
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());
                                $faltasEncuentro->setGrupo($grupoCompetidorUno->getNombre());

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

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                                    ->createQueryBuilder('r')
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaCuatro = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $encuentro->getCompetidorDos())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroSistemaCuatro();
                                $faltasEncuentro->setEncuentroSistemaCuatro($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorDos());
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());
                                $faltasEncuentro->setGrupo($grupoCompetidorDos->getNombre());

                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }


                    $habilitarFase = 0;
                    $habilitarFase = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                            ->createQueryBuilder('c')
                            ->select('count(c.id) as total')
                            ->getQuery()
                            ->getSingleScalarResult();

                    if ($habilitarFase == 48) {
                        $grupos = array(
                            array('A', 'B'),
                            array('C', 'D')
                        );

                        $em = $this->getDoctrine()->getManager();

                        foreach ($grupos as $grupo) {
                            $tablaPosicionesA = $this->getPoscisiones($evento->getId(), $encuentro->getSistemaJuegoCuatro()->getId(), $grupo[0]);
                            $A1 = $tablaPosicionesA[0];
                            $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($A1['idEquipo']);
                            $A2 = $tablaPosicionesA[1];
                            $equipoA2 = $em->getRepository('LogicBundle:EquipoEvento')->find($A2['idEquipo']);

                            $tablaPosicionesB = $this->getPoscisiones($evento->getId(), $encuentro->getSistemaJuegoCuatro()->getId(), $grupo[1]);
                            $B1 = $tablaPosicionesB[0];
                            $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($B1['idEquipo']);
                            $B2 = $tablaPosicionesB[1];
                            $equipoB2 = $em->getRepository('LogicBundle:EquipoEvento')->find($B2['idEquipo']);

                            $llave1 = new EncuentroSistemaCuatro;
                            $llave1->setCompetidorUno($equipoA1);
                            $llave1->setCompetidorDos($equipoB2);
                            $llave1->setGrupo($grupo[0] . "1" . $grupo[1] . "2");
                            $llave1->setFase("Cuartos de final");
                            $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                            $llave2 = new EncuentroSistemaCuatro;
                            $llave2->setCompetidorUno($equipoB1);
                            $llave2->setCompetidorDos($equipoA2);
                            $llave2->setGrupo($grupo[1] . "1" . $grupo[0] . "2");
                            $llave2->setFase("Cuartos de final");
                            $llave2->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());


                            $this->em->persist($llave2);
                            $this->em->persist($llave1);
                            $this->em->flush();
                        }
                    }


                    if ($habilitarFase == 56) {

                        $em = $this->getDoctrine()->getManager();

                        $encuentros = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")
                                ->createQueryBuilder('r')
                                ->where('r.sistemaJuegoCuatro = :sistemaJuegoCuatro')
                                ->andWhere('r.fase = :fase')
                                ->setParameter('sistemaJuegoCuatro', $encuentro->getSistemaJuegoCuatro())
                                ->setParameter('fase', "Cuartos de final")
                                ->getQuery()
                                ->getResult();

                        $equiposGanadores;

                        foreach ($encuentros as $encuentro) {

                            $ganadores = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                                    ->createQueryBuilder('c')
                                    ->select('IDENTITY(c.equipoEvento) as ganador')
                                    ->where('c.setsAfavor > c.setsEnContra')
                                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                                    ->andWhere('c.encuentroSistemaCuatro = :encuentro')
                                    ->setParameter('sistemaJuego', $encuentro->getSistemaJuegoCuatro()->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->groupBy('c.equipoEvento')
                                    ->getQuery()
                                    ->getResult();


                            $equiposGanadores[] = $ganadores[0]['ganador'];
                        }


                        $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[0]);

                        $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[1]);

                        $equipoC1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[2]);

                        $equipoD1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[3]);

                        $llave1 = new EncuentroSistemaCuatro;
                        $llave1->setCompetidorUno($equipoA1);
                        $llave1->setCompetidorDos($equipoB1);
                        $llave1->setGrupo("Semi");
                        $llave1->setFase("Semi final");
                        $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $llave2 = new EncuentroSistemaCuatro;
                        $llave2->setCompetidorUno($equipoC1);
                        $llave2->setCompetidorDos($equipoD1);
                        $llave2->setGrupo("Semi");
                        $llave2->setFase("Semi final");
                        $llave2->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $this->em->persist($llave1);
                        $this->em->persist($llave2);
                        $this->em->flush();
                    }

                    if ($habilitarFase == 60) {
                        $em = $this->getDoctrine()->getManager();

                        $encuentros = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")
                                ->createQueryBuilder('r')
                                ->where('r.sistemaJuegoCuatro = :sistemaJuegoCuatro')
                                ->andWhere('r.fase = :fase')
                                ->setParameter('sistemaJuegoCuatro', $encuentro->getSistemaJuegoCuatro())
                                ->setParameter('fase', "Semi final")
                                ->getQuery()
                                ->getResult();

                        $equiposGanadores;

                        foreach ($encuentros as $encuentro) {

                            $ganadores = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroVoleibol")
                                    ->createQueryBuilder('c')
                                    ->select('IDENTITY(c.equipoEvento) as ganador')
                                    ->where('c.setsAfavor > c.setsEnContra')
                                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                                    ->andWhere('c.encuentroSistemaCuatro = :encuentro')
                                    ->setParameter('sistemaJuego', $encuentro->getSistemaJuegoCuatro()->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->groupBy('c.equipoEvento')
                                    ->getQuery()
                                    ->getResult();

                            $equiposGanadores[] = $ganadores[0]['ganador'];
                        }


                        $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[0]);

                        $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[1]);

                        $llave1 = new EncuentroSistemaCuatro;
                        $llave1->setCompetidorUno($equipoA1);
                        $llave1->setCompetidorDos($equipoB1);
                        $llave1->setGrupo("Final");
                        $llave1->setFase("Final");
                        $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $this->em->persist($llave1);
                        $this->em->flush();
                    }
                }


                if ($disciplina != "Voleibol" || $diciplina != "VOLEIBOL") {

                    $habilitarFase = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                            ->createQueryBuilder('c')
                            ->select('count(c.id) as total')
                            ->getQuery()
                            ->getSingleScalarResult();

                    $grupoCompetidorUno = $this->em->getRepository("LogicBundle:GruposEncuentroSistemaCuatro")
                            ->createQueryBuilder('r')
                            ->where('r.equipoEvento = :equipo')
                            ->setParameter('equipo', $encuentro->getCompetidorUno())
                            ->getQuery()
                            ->getOneOrNullResult();


                    $grupoCompetidorDos = $this->em->getRepository("LogicBundle:GruposEncuentroSistemaCuatro")
                            ->createQueryBuilder('r')
                            ->where('r.equipoEvento = :equipo')
                            ->setParameter('equipo', $encuentro->getCompetidorDos())
                            ->getQuery()
                            ->getOneOrNullResult();

                    $resultado = new ResultadosEncuentroSistemaCuatroOtros();
                    $resultado->setEncuentroSistemaCuatro($encuentro);
                    $resultado->setEquipoEvento($encuentro->getCompetidorUno());
                    $resultado->setPuntosJuegoLimpio($encuentro->getSistemaJuegoCuatro()->getPuntosJuegoLimpio());
                    $resultado->setPuntosAfavor($form->get('puntosAfavor')->getData());
                    $resultado->setPuntosEnContra($form->get('puntosEnContra')->getData());
                    $resultado->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());


                    $resultadoOtro = new ResultadosEncuentroSistemaCuatroOtros();
                    $resultadoOtro->setEncuentroSistemaCuatro($encuentro);
                    $resultadoOtro->setEquipoEvento($encuentro->getCompetidorDos());
                    $resultadoOtro->setPuntosJuegoLimpio($encuentro->getSistemaJuegoCuatro()->getPuntosJuegoLimpio());
                    $resultadoOtro->setPuntosAfavor($form->get('puntosAfavor2')->getData());
                    $resultadoOtro->setPuntosEnContra($form->get('puntosEnContra2')->getData());
                    $resultadoOtro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());

                    if ($habilitarFase < 48) {
                        $resultado->setGrupo($grupoCompetidorUno->getNombre());
                        $resultadoOtro->setGrupo($grupoCompetidorDos->getNombre());
                    }

                    if ($habilitarFase >= 48 && $habilitarFase < 56) {
                        $resultado->setGrupo("Cuartos");
                        $resultadoOtro->setGrupo("Cuartos");
                    }


                    if ($habilitarFase >= 56 && $habilitarFase < 60) {
                        $resultado->setGrupo("Semi final");
                        $resultadoOtro->setGrupo("Semi final");
                    }


                    if ($habilitarFase >= 60) {
                        $resultado->setGrupo("Final");
                        $resultadoOtro->setGrupo("Final");
                    }

                    $this->em->persist($resultadoOtro);
                    $this->em->persist($resultado);
                    $this->em->flush();

                    $datosFalta = $request->request->get('faltas_encuentros_sistema_cuatro');

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

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                                    ->createQueryBuilder('r')
                                    ->setParameter('user', $encuentro->getCompetidorUno())
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaCuatro = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {
                                $faltasEncuentro = new FaltasEncuentroSistemaCuatro();
                                $faltasEncuentro->setEncuentroSistemaCuatro($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorUno());
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());
                                $faltasEncuentro->setGrupo($grupoCompetidorUno->getNombre());

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

                            $existeEncuentro = $this->em->getRepository("LogicBundle:FaltasEncuentroSistemaCuatro")
                                    ->createQueryBuilder('r')
                                    ->where('r.equipoEvento = :user')
                                    ->andWhere('r.encuentroSistemaCuatro = :encuentro')
                                    ->andWhere('r.sancionEvento = :sancionEvento')
                                    ->setParameter('user', $encuentro->getCompetidorDos())
                                    ->setParameter('encuentro', $encuentro)
                                    ->setParameter('sancionEvento', $encontrarFalta)
                                    ->getQuery()
                                    ->getResult();

                            if (!$existeEncuentro) {

                                $faltasEncuentro = new FaltasEncuentroSistemaCuatro();
                                $faltasEncuentro->setEncuentroSistemaCuatro($encuentro);
                                $faltasEncuentro->setEquipoEvento($encuentro->getCompetidorDos());
                                $faltasEncuentro->setSancionEvento($encontrarFalta);
                                $faltasEncuentro->setIdentificadorSistemaJuego($encuentro->getSistemaJuegoCuatro()->getId());
                                $faltasEncuentro->setGrupo($grupoCompetidorDos->getNombre());
                                $this->em->persist($faltasEncuentro);
                            }
                        }
                        $this->em->flush();
                    }

                    $habilitarFase = 0;
                    $habilitarFase = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                            ->createQueryBuilder('c')
                            ->select('count(c.id) as total')
                            ->getQuery()
                            ->getSingleScalarResult();

                    if ($habilitarFase == 48) {
                        $grupos = array(
                            array('A', 'B'),
                            array('C', 'D')
                        );

                        $em = $this->getDoctrine()->getManager();

                        foreach ($grupos as $grupo) {
                            $tablaPosicionesA = $this->getPoscisiones($evento->getId(), $encuentro->getSistemaJuegoCuatro()->getId(), $grupo[0]);
                            $A1 = $tablaPosicionesA[0];
                            $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($A1['idEquipo']);
                            $A2 = $tablaPosicionesA[1];
                            $equipoA2 = $em->getRepository('LogicBundle:EquipoEvento')->find($A2['idEquipo']);

                            $tablaPosicionesB = $this->getPoscisiones($evento->getId(), $encuentro->getSistemaJuegoCuatro()->getId(), $grupo[1]);
                            $B1 = $tablaPosicionesB[0];
                            $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($B1['idEquipo']);
                            $B2 = $tablaPosicionesB[1];
                            $equipoB2 = $em->getRepository('LogicBundle:EquipoEvento')->find($B2['idEquipo']);

                            $llave1 = new EncuentroSistemaCuatro;
                            $llave1->setCompetidorUno($equipoA1);
                            $llave1->setCompetidorDos($equipoB2);
                            $llave1->setGrupo($grupo[0] . "1" . $grupo[1] . "2");
                            $llave1->setFase("Cuartos de final");
                            $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                            $llave2 = new EncuentroSistemaCuatro;
                            $llave2->setCompetidorUno($equipoB1);
                            $llave2->setCompetidorDos($equipoA2);
                            $llave2->setGrupo($grupo[1] . "1" . $grupo[0] . "2");
                            $llave2->setFase("Cuartos de final");
                            $llave2->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());


                            $this->em->persist($llave2);
                            $this->em->persist($llave1);
                            $this->em->flush();
                        }
                    }


                    if ($habilitarFase == 56) {

                        $em = $this->getDoctrine()->getManager();

                        $encuentros = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")
                                ->createQueryBuilder('r')
                                ->where('r.sistemaJuegoCuatro = :sistemaJuegoCuatro')
                                ->andWhere('r.fase = :fase')
                                ->setParameter('sistemaJuegoCuatro', $encuentro->getSistemaJuegoCuatro())
                                ->setParameter('fase', "Cuartos de final")
                                ->getQuery()
                                ->getResult();

                        $equiposGanadores;

                        foreach ($encuentros as $encuentro) {

                            $ganadores = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                                    ->createQueryBuilder('c')
                                    ->select('IDENTITY(c.equipoEvento) as ganador')
                                    ->where('c.puntosAfavor > c.puntosEnContra')
                                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                                    ->andWhere('c.encuentroSistemaCuatro = :encuentro')
                                    ->setParameter('sistemaJuego', $encuentro->getSistemaJuegoCuatro()->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->groupBy('c.equipoEvento')
                                    ->getQuery()
                                    ->getResult();


                            $equiposGanadores[] = $ganadores[0]['ganador'];
                        }


                        $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[0]);

                        $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[1]);

                        $equipoC1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[2]);

                        $equipoD1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[3]);

                        $llave1 = new EncuentroSistemaCuatro;
                        $llave1->setCompetidorUno($equipoA1);
                        $llave1->setCompetidorDos($equipoB1);
                        $llave1->setGrupo("Semi");
                        $llave1->setFase("Semi final");
                        $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $llave2 = new EncuentroSistemaCuatro;
                        $llave2->setCompetidorUno($equipoC1);
                        $llave2->setCompetidorDos($equipoD1);
                        $llave2->setGrupo("Semi");
                        $llave2->setFase("Semi final");
                        $llave2->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $this->em->persist($llave1);
                        $this->em->persist($llave2);
                        $this->em->flush();
                    }

                    if ($habilitarFase == 60) {
                        $em = $this->getDoctrine()->getManager();

                        $encuentros = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")
                                ->createQueryBuilder('r')
                                ->where('r.sistemaJuegoCuatro = :sistemaJuegoCuatro')
                                ->andWhere('r.fase = :fase')
                                ->setParameter('sistemaJuegoCuatro', $encuentro->getSistemaJuegoCuatro())
                                ->setParameter('fase', "Semi final")
                                ->getQuery()
                                ->getResult();

                        $equiposGanadores;

                        foreach ($encuentros as $encuentro) {

                            $ganadores = $this->em->getRepository("LogicBundle:ResultadosEncuentroSistemaCuatroOtros")
                                    ->createQueryBuilder('c')
                                    ->select('IDENTITY(c.equipoEvento) as ganador')
                                    ->where('c.puntosAfavor > c.puntosEnContra')
                                    ->andWhere('c.identificadorSistemaJuego = :sistemaJuego')
                                    ->andWhere('c.encuentroSistemaCuatro = :encuentro')
                                    ->setParameter('sistemaJuego', $encuentro->getSistemaJuegoCuatro()->getId())
                                    ->setParameter('encuentro', $encuentro)
                                    ->groupBy('c.equipoEvento')
                                    ->getQuery()
                                    ->getResult();

                            $equiposGanadores[] = $ganadores[0]['ganador'];
                        }


                        $equipoA1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[0]);

                        $equipoB1 = $em->getRepository('LogicBundle:EquipoEvento')->find($equiposGanadores[1]);

                        $llave1 = new EncuentroSistemaCuatro;
                        $llave1->setCompetidorUno($equipoA1);
                        $llave1->setCompetidorDos($equipoB1);
                        $llave1->setGrupo("Final");
                        $llave1->setFase("Final");
                        $llave1->setSistemaJuegoCuatro($encuentro->getSistemaJuegoCuatro());

                        $this->em->persist($llave1);
                        $this->em->flush();
                    }
                }




                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $encuentro->getSistemaJuegoCuatro()->getEvento()->getId(), 'tipo' => $tipoSistema));
            }
        }


        return $this->render('AdminBundle:PlayOff:create_resultado.html.twig', array(
                    'form' => $form->createView(),
                    'nombreJugador1' => $nombreJugador1,
                    'nombreJugador2' => $nombreJugador2,
                    'eventoId' => $encuentro->getSistemaJuegoCuatro()->getEvento()->getId(),
                    'tipoDeSistemaDeJuego' => $tipoSistema,
                    'disciplina' => $disciplina,
        ));
    }

    /**
     * programarEncuentro action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function programarEncuentroCuatroAction() {


        $request = $this->getRequest();

        $idEncuentro = $request->get('idEncuentro');

        if ($idEncuentro == 0) {
            $encuentro = new EncuentroSistemaDos();
        } else {
            $encuentro = $this->em->getRepository("LogicBundle:EncuentroSistemaCuatro")->find($idEncuentro);
        }


        if (count($encuentro->getSistemaJuegoCuatro()->getTipoSistema())) {
            $idEvento = $encuentro->getSistemaJuegoCuatro()->getEvento()->getId();
        }

        if ($encuentro->getSistemaJuegoCuatro()->getTipoSistema() == "PlayOff") {

            $tipoSistema = 6;
        }


        if ($idEvento == 0) {
            $evento = new Evento();
        } else {
            $evento = $this->em->getRepository("LogicBundle:Evento")->find($idEvento);
        }


        $jugadores = $evento->getJugadorEventos();
        $disciplina = $evento->getDisciplina()->getNombre();
        $validarEdicion = false;


        $form = $this->createForm(ProgramarEncuentrosType::class, $encuentro, array(
            'disciplina' => $disciplina,
            'eventoId' => $encuentro->getSistemaJuegoCuatro()->getEvento()->getId(),
            'encuentro' => $encuentro,
            'tipoDeSistemaDeJuego' => $tipoSistema,
            'validarEdicion' => $validarEdicion,
        ));


        $form->setData($disciplina);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $encuentro->setHora($form->get('hora')->getData());
                $encuentro->setFecha($form->get('fecha')->getData());

                $reserva = new Reserva();

                $user = $this->getUser();

                if ($form->get('puntoAtencion')->getData() != null || $form->get('puntoAtencion')->getData() != '') {
                    $encuentro->setPuntoAtencion($form->get('puntoAtencion')->getData());

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
                } else {
                    $encuentro->setEscenarioDeportivo($form->get('escenarioDeportivo')->getData());

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
                }

                $this->em->persist($encuentro);
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_encuentrosistemacuatro_list', array('id' => $evento->getId(), 'tipo' => $tipoSistema));
            }
        }

        return $this->render('AdminBundle:PlayOff:create_programar.html.twig', array(
                    'form' => $form->createView(),
                    'eventoId' => $encuentro->getSistemaJuegoCuatro()->getEvento()->getId(),
                    'tipoDeSistemaDeJuego' => $tipoSistema,
                    'disciplina' => $disciplina,
        ));
    }

}
