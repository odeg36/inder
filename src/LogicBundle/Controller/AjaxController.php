<?php

namespace LogicBundle\Controller;

use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Entity\CampoAmbiental;
use LogicBundle\Entity\CampoInfraestructura;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Utils\BuscarFechas;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use LogicBundle\Entity\TipoReserva;

class AjaxController extends Controller {

    protected $em = null;
    protected $container = null;
    protected $trans = null;

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
        $this->trans = $container->get("translator");
    }

    /**
     * @Route("/tipoDireccion", name="ajax_tipo_direccion", options={"expose"=true})
     */
    public function tipoDireccion(Request $request) {
        $id = $request->request->get('idTipoDireccion');
        $em = $this->getDoctrine()->getManager();
        $tipoDireccion = $em->getRepository('LogicBundle:TipoDireccion')->find($id);
        if ($tipoDireccion) {
            $respuesta = array();
            array_push($respuesta, array('id' => $tipoDireccion->getId(), 'nombre' => $tipoDireccion->getNombre()));
            return $this->json(array('resultado' => $respuesta, 'usuarios_tipo_direccion' => true));
        }
        return $this->json(array('usuarios_tipo_direccion' => false));
    }

    /**
     * @Route("/barriosPorMunicipio", name="ajax_barrios_por_municipio", options={"expose"=true})
     */
    public function barriosPorMunicipioAction(Request $request) {
        $municipio = $request->request->get('municipio_id') != "" ? $request->request->get('municipio_id') : 0;
        $vereda = $request->request->get('vereda');
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
        $qb
                ->join('m.barrios', 'b')
                ->andWhere('b.esVereda = :es_vereda')
                ->andWhere('m.id = :municipio')
                ->setParameter('es_vereda', true)
                ->setParameter('municipio', $municipio)
        ;
        $query = $qb->getQuery();
        $municipioObject = $query->getOneOrNullResult();
        $preguntar_vereda = $municipioObject ? true : false;
        if ($vereda == "true" && $preguntar_vereda) {
            $barrios = $em->getRepository('LogicBundle:Barrio')->findBy(array('municipio' => $municipio, 'esVereda' => true, 'habilitado' => true), array('nombre' => 'ASC'));
        } else {
            $barrios = $em->getRepository('LogicBundle:Barrio')->findBy(array('municipio' => $municipio, 'habilitado' => true), array('nombre' => 'ASC'));
        }
        $respuesta = ['barrios' => [], 'preguntar_vereda' => false];
        $respuesta['preguntar_vereda'] = $preguntar_vereda;
        foreach ($barrios as $barrio) {
            array_push($respuesta['barrios'], array('id' => $barrio->getId(), 'nombre' => $barrio->getNombreConComuna()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/comunasPorMunicipio", name="ajax_comunas_por_municipio", options={"expose"=true})
     */
    public function comunasPorMunicipioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $municipio = $request->request->get('municipio_id') != "" ? $request->request->get('municipio_id') : 0;
        $comunas = $em->getRepository('LogicBundle:Comuna')->findBy(array('municipio' => $municipio), array('nombre' => 'ASC'));
        $respuesta = [];
        foreach ($comunas as $comuna) {
            array_push($respuesta, array('id' => $comuna->getId(), 'nombre' => $comuna->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/divisionesEscenario", name="ajax_divisiones_escenario", options={"expose"=true})
     */
    public function divisionesEscenarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $escenario = $request->request->get('escenario_id') != "" ? $request->request->get('escenario_id') : 0;
        $divisiones = $em->getRepository('LogicBundle:Division')->findBy(array('escenarioDeportivo' => $escenario), array('nombre' => 'ASC'));
        $respuesta = [];
        foreach ($divisiones as $division) {
            array_push($respuesta, array('id' => $division->getId(), 'nombre' => $division->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/barriosPorMunicipioMedellin", name="ajax_barrios_por_municipio_medellin", options={"expose"=true})
     */
    public function barriosPorMunicipioMedellinAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $municipio = $em->getRepository('LogicBundle:Municipio')->createQueryBuilder('municipio')
                        ->where('municipio.nombre = :nombre')
                        ->setParameter('nombre', 'Medellín')
                        ->getQuery()->getOneOrNullResult();

        $municipio = $municipio->getId();
        $barrios = $em->getRepository('LogicBundle:Barrio')->findBy(array('municipio' => $municipio), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($barrios as $barrio) {
            array_push($respuesta, array('id' => $barrio->getId(), 'nombre' => $barrio->getNombreConComuna()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/barriosPorComuna", name="ajax_barrios_por_comuna", options={"expose"=true})
     */
    public function barriosPorComunaAction(Request $request) {
        $comuna = $request->request->get('comuna_id');
        $comuna = $comuna != "" ? $comuna : 0;
        $em = $this->getDoctrine()->getManager();
        $barrios = $em->getRepository('LogicBundle:Barrio')->findBy(array('comuna' => $comuna), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($barrios as $barrio) {
            array_push($respuesta, array('id' => $barrio->getId(), 'nombre' => $barrio));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/datosEscenario", name="ajax_datos_escenario", options={"expose"=true})
     */
    public function datosEscenarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $escenario = $em->getRepository('LogicBundle:EscenarioDeportivo')->findOneBy(['id' => $request->request->get('escenario_id')]);
        $data['info'] = $escenario->getInformacionReserva();
        return $this->json($data);
    }

    /**
     * @Route("/escenariosPorBarrio", name="ajax_escenarios_por_barrio", options={"expose"=true})
     */
    public function escenariosPorBarrioAction(Request $request) {
        $barrio = $request->request->get('barrio_id');
        $barrio = $barrio != "" ? $barrio : 0;
        $em = $this->getDoctrine()->getManager();
        $escenarios = $em->getRepository('LogicBundle:EscenarioDeportivo')->findBy(array('barrio' => $barrio), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($escenarios as $escenario) {

            if (count($escenario->getTendenciaEscenarioDeportivos()) > 0 || count($escenario->getDisciplinasEscenarioDeportivos()) > 0) {
                if (count($escenario->getDivisiones()) > 0) {
                    $img = "icono-escenario.svg";
                    foreach ($escenario->getArchivoEscenarios() as $file) {
                        if ($file->getType() == "imagen") {
                            $img = $file->getFile();
                            break;
                        }
                    }
                    if ($escenario->getImagenEscenarioDividido() == null) {
                        $imagenEscenario = "icono-escenario.svg";
                    } else {
                        $imagenEscenario = $escenario->getImagenEscenarioDividido();
                    }
                    array_push($respuesta, array(
                        'id' => $escenario->getId(),
                        'file' => $img,
                        'nombre' => $escenario->getNombre(),
                        'latitud' => $escenario->getLatitud(),
                        'longitud' => $escenario->getLongitud(),
                        'telefono' => $escenario->getTelefono(),
                        'direccion' => $escenario->getDireccion(),
                        'lunesMañana' => $escenario->getHoraInicialLunes() . " - " . $escenario->getHoraFinalLunes(),
                        'lunesTarde' => $escenario->getHoraInicial2Lunes() . " - " . $escenario->getHoraFinal2Lunes(),
                        'martesMañana' => $escenario->getHoraInicialMartes() . " - " . $escenario->getHoraFinalMartes(),
                        'martesTarde' => $escenario->getHoraInicial2Martes() . " - " . $escenario->getHoraFinal2Martes(),
                        'miercolesMañana' => $escenario->getHoraInicialMiercoles() . " - " . $escenario->getHoraFinalMiercoles(),
                        'miercolesTarde' => $escenario->getHoraInicial2Miercoles() . " - " . $escenario->getHoraFinal2Miercoles(),
                        'juevesMañana' => $escenario->getHoraInicialJueves() . " - " . $escenario->getHoraFinalJueves(),
                        'juevesTarde' => $escenario->getHoraInicial2Jueves() . " - " . $escenario->getHoraFinal2Jueves(),
                        'viernesMañana' => $escenario->getHoraInicialViernes() . " - " . $escenario->getHoraFinalViernes(),
                        'viernesTarde' => $escenario->getHoraInicial2Viernes() . " - " . $escenario->getHoraFinal2Viernes(),
                        'sabadoMañana' => $escenario->getHoraInicialSabado() . " - " . $escenario->getHoraFinalSabado(),
                        'sabadoTarde' => $escenario->getHoraInicial2Sabado() . " - " . $escenario->getHoraFinal2Sabado(),
                        'domingoMañana' => $escenario->getHoraInicialDomingo() . " - " . $escenario->getHoraFinalDomingo(),
                        'domingoTarde' => $escenario->getHoraInicial2Domingo() . " - " . $escenario->getHoraFinal2Domingo(),
                        'imagen_escenario_dividido' => $imagenEscenario,
                        'barrio_id' => $escenario->getBarrio()->getId(),
                        'barrio_nombre' => $escenario->getBarrio()->getNombre(),
                        'informacion_reserva' => $escenario->getInformacionReserva()
                    ));
                }
            }
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/escenariosPorBarrioSinRestriccion", name="ajax_escenarios_por_barrio_sin_restriccion", options={"expose"=true})
     */
    public function escenariosPorBarrioSinRestriccion(Request $request) {
        $barrio = $request->request->get('barrio_id');
        $barrio = $barrio != "" ? $barrio : 0;
        $em = $this->getDoctrine()->getManager();
        $escenarios = $em->getRepository('LogicBundle:EscenarioDeportivo')->findBy(array('barrio' => $barrio), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($escenarios as $escenario) {
            if (count($escenario->getTendenciaEscenarioDeportivos()) > 0 || count($escenario->getDisciplinasEscenarioDeportivos()) > 0) {
                $img = "icono-escenario.svg";
                foreach ($escenario->getArchivoEscenarios() as $file) {
                    if ($file->getType() == "imagen") {
                        $img = $file->getFile();
                        break;
                    }
                }
                if ($escenario->getImagenEscenarioDividido() == null) {
                    $imagenEscenario = "icono-escenario.svg";
                } else {
                    $imagenEscenario = $escenario->getImagenEscenarioDividido();
                }
                array_push($respuesta, array(
                    'id' => $escenario->getId(),
                    'file' => $img,
                    'nombre' => $escenario->getNombre(),
                    'latitud' => $escenario->getLatitud(),
                    'longitud' => $escenario->getLongitud(),
                    'telefono' => $escenario->getTelefono(),
                    'direccion' => $escenario->getDireccion(),
                    'lunesMañana' => $escenario->getHoraInicialLunes() . " - " . $escenario->getHoraFinalLunes(),
                    'lunesTarde' => $escenario->getHoraInicial2Lunes() . " - " . $escenario->getHoraFinal2Lunes(),
                    'martesMañana' => $escenario->getHoraInicialMartes() . " - " . $escenario->getHoraFinalMartes(),
                    'martesTarde' => $escenario->getHoraInicial2Martes() . " - " . $escenario->getHoraFinal2Martes(),
                    'miercolesMañana' => $escenario->getHoraInicialMiercoles() . " - " . $escenario->getHoraFinalMiercoles(),
                    'miercolesTarde' => $escenario->getHoraInicial2Miercoles() . " - " . $escenario->getHoraFinal2Miercoles(),
                    'juevesMañana' => $escenario->getHoraInicialJueves() . " - " . $escenario->getHoraFinalJueves(),
                    'juevesTarde' => $escenario->getHoraInicial2Jueves() . " - " . $escenario->getHoraFinal2Jueves(),
                    'viernesMañana' => $escenario->getHoraInicialViernes() . " - " . $escenario->getHoraFinalViernes(),
                    'viernesTarde' => $escenario->getHoraInicial2Viernes() . " - " . $escenario->getHoraFinal2Viernes(),
                    'sabadoMañana' => $escenario->getHoraInicialSabado() . " - " . $escenario->getHoraFinalSabado(),
                    'sabadoTarde' => $escenario->getHoraInicial2Sabado() . " - " . $escenario->getHoraFinal2Sabado(),
                    'domingoMañana' => $escenario->getHoraInicialDomingo() . " - " . $escenario->getHoraFinalDomingo(),
                    'domingoTarde' => $escenario->getHoraInicial2Domingo() . " - " . $escenario->getHoraFinal2Domingo(),
                    'imagen_escenario_dividido' => $imagenEscenario,
                    'barrio_id' => $escenario->getBarrio()->getId(),
                    'barrio_nombre' => $escenario->getBarrio()->getNombre(),
                    'informacion_reserva' => $escenario->getInformacionReserva()
                ));
            }
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/tododEscenariosPorBarrio", name="ajax_todos_escenarios_por_barrio", options={"expose"=true})
     */
    public function todosEscenariosPorBarrioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $escenarios = $em->getRepository('LogicBundle:EscenarioDeportivo')->createQueryBuilder('escenario')
                        ->where('escenario.id != :escenario_deportivo')
                        ->orderBy("escenario.nombre", 'ASC')
                        ->setParameter('escenario_deportivo', 0)
                        ->getQuery()->getResult();
        $respuesta = array();

        foreach ($escenarios as $escenario) {
            if (count($escenario->getTendenciaEscenarioDeportivos()) > 0 || count($escenario->getDisciplinasEscenarioDeportivos()) > 0) {
                if (count($escenario->getDivisiones()) > 0) {
                    $img = "icono-escenario.svg";
                    foreach ($escenario->getArchivoEscenarios() as $file) {
                        if ($file->getType() == "imagen") {
                            $img = $file->getFile();
                            break;
                        }
                    }
                    if ($escenario->getImagenEscenarioDividido() == null) {
                        $imagenEscenario = "icono-escenario.svg";
                    } else {
                        $imagenEscenario = $escenario->getImagenEscenarioDividido();
                    }
                    array_push($respuesta, array(
                        'id' => $escenario->getId(),
                        'file' => $img,
                        'nombre' => $escenario->getNombre(),
                        'latitud' => $escenario->getLatitud(),
                        'longitud' => $escenario->getLongitud(),
                        'telefono' => $escenario->getTelefono(),
                        'direccion' => $escenario->getDireccion(),
                        'lunesMañana' => $escenario->getHoraInicialLunes() . " - " . $escenario->getHoraFinalLunes(),
                        'lunesTarde' => $escenario->getHoraInicial2Lunes() . " - " . $escenario->getHoraFinal2Lunes(),
                        'martesMañana' => $escenario->getHoraInicialMartes() . " - " . $escenario->getHoraFinalMartes(),
                        'martesTarde' => $escenario->getHoraInicial2Martes() . " - " . $escenario->getHoraFinal2Martes(),
                        'miercolesMañana' => $escenario->getHoraInicialMiercoles() . " - " . $escenario->getHoraFinalMiercoles(),
                        'miercolesTarde' => $escenario->getHoraInicial2Miercoles() . " - " . $escenario->getHoraFinal2Miercoles(),
                        'juevesMañana' => $escenario->getHoraInicialJueves() . " - " . $escenario->getHoraFinalJueves(),
                        'juevesTarde' => $escenario->getHoraInicial2Jueves() . " - " . $escenario->getHoraFinal2Jueves(),
                        'viernesMañana' => $escenario->getHoraInicialViernes() . " - " . $escenario->getHoraFinalViernes(),
                        'viernesTarde' => $escenario->getHoraInicial2Viernes() . " - " . $escenario->getHoraFinal2Viernes(),
                        'sabadoMañana' => $escenario->getHoraInicialSabado() . " - " . $escenario->getHoraFinalSabado(),
                        'sabadoTarde' => $escenario->getHoraInicial2Sabado() . " - " . $escenario->getHoraFinal2Sabado(),
                        'domingoMañana' => $escenario->getHoraInicialDomingo() . " - " . $escenario->getHoraFinalDomingo(),
                        'domingoTarde' => $escenario->getHoraInicial2Domingo() . " - " . $escenario->getHoraFinal2Domingo(),
                        'imagen_escenario_dividido' => $imagenEscenario,
                        'barrio_id' => $escenario->getBarrio()->getId(),
                        'barrio_nombre' => $escenario->getBarrio()->__toString(),
                        'informacion_reserva' => $escenario->getInformacionReserva()
                    ));
                }
            }
        }
        return $this->json($respuesta);
    }

    //disciplinas por escenarios ajax

    /**
     * @Route("/disciplinasPorEscenarios", name="ajax_disciplinas_por_escenarios", options={"expose"=true})
     */
    public function disciplinasPorEscenariosAction(Request $request) {
        $escenario = $request->request->get('escenario_id');
        $escenario = $escenario != "" ? $escenario : 0;
        $escenario = $request->request->get('escenario_id');
        $em = $this->getDoctrine()->getManager();
        $disciplinas = $em->getRepository('LogicBundle:Disciplina')->createQueryBuilder('disciplina')
                        ->innerJoin('disciplina.disciplinas', 'disciplinas')
                        ->innerJoin('disciplinas.escenarioDeportivo', 'escenarioDeportivo')
                        ->where('escenarioDeportivo.id = :escenario_deportivo')
                        ->orderBy("disciplina.nombre", 'DESC')
                        ->setParameter('escenario_deportivo', $escenario ?: 0)
                        ->getQuery()->getResult();
        $respuesta = array();
        foreach ($disciplinas as $disci) {
            array_push($respuesta, array('id' => $disci->getId(), 'nombre' => $disci->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/usuarioPorId", name="ajax_usuario_por_id", options={"expose"=true})
     */
    public function usuarioPorId(Request $request) {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $existeUsuario = $em->getRepository('ApplicationSonataUserBundle:User')->findBy(
                array(
                    'id' => $id
        ));

        if ($existeUsuario) {
            $respuesta = array();
            foreach ($existeUsuario as $usuario) {
                $edad = null;
                if ($usuario->getDateOfBirth()) {
                    $currentDate = new \DateTime('now');
                    $birthDay = $usuario->getDateOfBirth();

                    $interval = $birthDay->diff($currentDate);
                    $edad = $interval->format('%y');
                }
                array_push($respuesta, array('id' => $usuario->getId(), 'nombre' => $usuario->nombreCompleto(), 'edad' => $edad));
            }
            return $this->json(array('resultado' => $respuesta, 'usuario_existe' => true));
        }
        return $this->json(array('mensaje' => 'Este usuario no existe en la base de datos', 'usuario_existe' => false));
    }

    /**
     * @Route("/subcategoriaInfraestructuraPorCategoriaId", name="subcategoria_infraestructura_por_categoria_id", options={"expose"=true})
     */
    public function subcategoriaInfraestructuraPorCategoriaId(Request $request) {
        $categoriaInfraestructura = $request->get('categoriaInfraestructura');
        $em = $this->getDoctrine()->getManager();
        $subcategoriaInfraestructura = $em->getRepository('LogicBundle:SubcategoriaInfraestructura')->createQueryBuilder('subcategoriaInfraestructura')
                        ->where('subcategoriaInfraestructura.categoriaInfraestructura = :categoriaInfra')
                        ->setParameter('categoriaInfra', $categoriaInfraestructura ?: 0)
                        ->getQuery()->getResult();

        if ($subcategoriaInfraestructura) {
            $respuesta = array();
            foreach ($subcategoriaInfraestructura as $subcategoriaInfra) {

                array_push($respuesta, array('id' => $subcategoriaInfra->getId(), 'nombre' => $subcategoriaInfra->getNombre()));
            }
            return $this->json(array('resultado' => $respuesta, 'existe_subcategoria' => true));
        } else {
            return $this->json(array('existe_subcategoria' => false));
        }
    }

    /**
     * @Route("/subcategoriaAmbientalPorCategoriaId", name="subcategoria_ambiental_por_categoria_id", options={"expose"=true})
     */
    public function subcategoriaAmbientalPorCategoriaId(Request $request) {
        $categoriaAmbiental = $request->get('categoriaAmbiental');
        $em = $this->getDoctrine()->getManager();
        $subcategoriaAmbiental = $em->getRepository('LogicBundle:SubcategoriaAmbiental')->createQueryBuilder('subcategoriaAmbiental')
                        ->where('subcategoriaAmbiental.categoriaAmbiental = :categoriaAmbiental')
                        ->setParameter('categoriaAmbiental', $categoriaAmbiental ?: 0)
                        ->getQuery()->getResult();
        if ($subcategoriaAmbiental) {
            $respuesta = array();
            foreach ($subcategoriaAmbiental as $subcategoriaAmbi) {
                array_push($respuesta, array('id' => $subcategoriaAmbi->getId(), 'nombre' => $subcategoriaAmbi->getNombre()));
            }
            return $this->json(array('resultado' => $respuesta, 'existe_subcategoria' => true));
        }
        return $this->json(array('existe_subcategoria' => false));
    }

    /**
     * @Route("/usuarioPorTipoDocumento", name="ajax_usuario_por_tipo_documento", options={"expose"=true})
     */
    public function usuarioPorTipoDocumento(Request $request) {
        $id = $request->request->get('idTipoDocumento');
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('ApplicationSonataUserBundle:User')->findBy(
                array(
                    'tipoIdentificacion' => $id
        ));
        if ($usuarios) {
            $respuesta = array();
            foreach ($usuarios as $usuario) {
                array_push($respuesta, array('id' => $usuario->getId(), 'numeroIdentificacion' => $usuario->getNumeroIdentificacion()));
            }

            return $this->json(array('resultado' => $respuesta, 'usuarios_existe' => true));
        }
        return $this->json(array('usuarios_existe' => false));
    }

    /**
     * @Route("/usuarioPorTipoNumeroDocumento", name="ajax_usuario_por_numero_tipo_documento", options={"expose"=true})
     */
    public function usuarioPorNumeroTipoDocumento(Request $request) {
        $idTipoDocumento = $request->request->get('idTipoDocumento');
        $numeroDocumento = $request->request->get('numeroDocumento');
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('ApplicationSonataUserBundle:User')->createQueryBuilder('user')
                ->where('user.tipoIdentificacion = :tipoIdentificacion')
                ->andWhere('user.numeroIdentificacion = :numeroIdentificacion')
                ->setParameter('tipoIdentificacion', $idTipoDocumento ?: 0)
                ->setParameter('numeroIdentificacion', $numeroDocumento)
                ->getQuery()
                ->setMaxResults(20)
                ->getResult();
        if ($usuarios) {
            $respuesta = array();
            foreach ($usuarios as $usuario) {
                array_push($respuesta, array('id' => $usuario->getNumeroIdentificacion(), 'nombre' => $usuario->nombreCompleto()));
            }
            return $this->json(array('resultado' => $respuesta, 'usuarios_existe' => true, 'query' => $numeroDocumento));
        }
        return $this->json(array('resultado' => array(), 'usuarios_existe' => false, 'query' => ''));
    }

    /**
     * @Route("/usuarioPorTipoNumeroDocumentoAutocomplete", name="ajax_usuario_por_numero_tipo_documento_autocomplete", options={"expose"=true})
     */
    public function usuarioPorNumeroTipoDocumentoAutocomplete(Request $request) {
        $idTipoDocumento = $request->request->get('idTipoDocumento');
        $numeroDocumento = $request->request->get('numeroDocumento');
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('ApplicationSonataUserBundle:User')->createQueryBuilder('user')
                ->where('user.tipoIdentificacion = :tipoIdentificacion')
                ->andWhere('user.numeroIdentificacion LIKE :numeroIdentificacion')
                ->setParameter('tipoIdentificacion', $idTipoDocumento ?: 0)
                ->setParameter('numeroIdentificacion', '%' . $numeroDocumento . '%')
                ->getQuery()
                ->setMaxResults(20)
                ->getResult();
        if ($usuarios) {
            $respuesta = array();
            foreach ($usuarios as $usuario) {
                array_push($respuesta, $usuario->getNumeroIdentificacion());
            }
            return $this->json(array('resultado' => $respuesta, 'usuarios_existe' => true, 'query' => $numeroDocumento));
        }
        return $this->json(array('resultado' => array(), 'usuarios_existe' => false, 'query' => ''));
    }

    /**
     * @Route("/usuarioPorNumeroDocumentoTipo", name="ajax_usuario_por_numero_documento_tipo", options={"expose"=true})
     */
    public function usuarioPorNumeroDocumentoTipo(Request $request) {
        $idTipoDocumento = $request->request->get('idTipoDocumento');
        $numeroDocumento = $request->request->get('numeroDocumento');
        $em = $this->getDoctrine()->getManager();
        $existeUsuario = $em->getRepository('ApplicationSonataUserBundle:User')->findBy(
                array(
                    'tipoIdentificacion' => $idTipoDocumento,
                    'numeroIdentificacion' => $numeroDocumento
        ));

        if ($existeUsuario) {
            $respuesta = array();
            foreach ($existeUsuario as $usuario) {
                array_push($respuesta, array('id' => $usuario->getId(), 'nombre' => $usuario->nombreCompleto()));
            }
            return $this->json(array('resultado' => $respuesta, 'usuario_existe' => true));
        }
        return $this->json(array('mensaje' => 'Este usuario no existe en la base de datos', 'usuario_existe' => false));
    }

    /**
     * @Route("/aprobarReserva", name="ajax_aprobar_reserva", options={"expose"=true})
     */
    public function aprobarReserva(Request $request) {
        $estadoReserva = $request->request->get('estadoReserva');
        $idReserva = $request->request->get('idReserva');
        $em = $this->getDoctrine()->getManager();
        $reserva = $em->getRepository('LogicBundle:Reserva')->find($idReserva);
        $reserva->setEstado($estadoReserva);
        if ($reserva->getMotivoCancelacion()) {
            $reserva->setMotivoCancelacion(null);
        }
        $em->persist($reserva);
        $em->flush();
        return $this->json('La Reserva ha sido ' . $estadoReserva);
    }

    /**
     * @Route("/rechazarReserva", name="ajax_rechazar_reserva", options={"expose"=true})
     */
    public function rechazarReserva(Request $request) {

        $estadoReserva = $request->request->get('estadoReserva');
        $idReserva = $request->request->get('idReserva');
        $motivoCancelacion = $request->request->get('motivoCancelacion');

        $em = $this->getDoctrine()->getManager();
        $reserva = $em->getRepository('LogicBundle:Reserva')->find($idReserva);
        $motivo = $em->getRepository('LogicBundle:MotivoCancelacion')->find($motivoCancelacion);
        $reserva->setEstado($estadoReserva);
        $reserva->setMotivoCancelacion($motivo);
        $em->persist($reserva);
        $em->flush();
        //return $this->json(array('mensaje' => 'La Reserva ha sido '.$estadoReserva));
        return $this->json('La Reserva ha sido ' . $estadoReserva);
    }

    /**
     * @Route("/tipoReserva", name="ajax_tipo_reserva", options={"expose"=true})
     */
    public function tipoReserva(Request $request) {
        $idTipoReserva = $request->get('idTipoReserva');
        $em = $this->getDoctrine()->getManager();
        $tipoReserva = $em->getRepository('LogicBundle:TipoReserva')->find($idTipoReserva);
        if ($tipoReserva->getNombre() != null) {
            return $this->json(array('resultado' => $tipoReserva->getNombre(), 'existeTipoReserva' => true));
        } else {
            return $this->json(array('existeTipoReserva' => false));
        }
    }


    /**
     * @Route("/proyectosPorArea", name="ajax_proyectos_por_area", options={"expose"=true})
     */
    public function proyectosPorAreaAction(Request $request) {
        $area = $request->request->get('area_id');
        $area = $area != "" ? $area : 0;
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('LogicBundle:Proyecto')->findBy(array('area' => $area, 'activo' => true), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($proyectos as $proyecto) {
            array_push($respuesta, array('id' => $proyecto->getId(), 'nombre' => $proyecto->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/estrategiasPorProyecto", name="ajax_estrategias_por_proyecto", options={"expose"=true})
     */
    public function estrategiasPorProyectoAction(Request $request) {
        $proyecto = $request->request->get('proyecto_id');
        $proyecto = $proyecto != "" ? $proyecto : 0;
        $em = $this->getDoctrine()->getManager();
        $estrategias = $em->getRepository('LogicBundle:Estrategia')->findBy(array('proyecto' => $proyecto, 'activo' => true), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($estrategias as $estrategia) {
            array_push($respuesta, array('id' => $estrategia->getId(), 'nombre' => $estrategia->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/disciplinasTendencias", name="ajax_disciplinas_tendencias_por_estrategia", options={"expose"=true})
     */
    public function disciplinasTendenciasAction(Request $request) {
        $estrategia = $request->request->get('estrategia_id');
        $estrategia = $estrategia != "" ? $estrategia : 0;
        $em = $this->getDoctrine()->getManager();
        $disciplinaEstrategias = $em->getRepository('LogicBundle:DisciplinaEstrategia')->findBy(array('estrategia' => $estrategia));
        $tendenciaEstrategias = $em->getRepository('LogicBundle:TendenciaEstrategia')->findBy(array('estrategia' => $estrategia));
        $institucionalEstrategias = $em->getRepository('LogicBundle:InstitucionalEstrategia')->findBy(array('estrategia' => $estrategia));
        $respuesta = array();
        $disciplinaArray = array();
        $tendenciasArray = array();
        $categoriasArray = array();
        foreach ($disciplinaEstrategias as $disciplinaEstrategia) {
            array_push($disciplinaArray, array('id' => $disciplinaEstrategia->getId(), 'nombre' => $disciplinaEstrategia->getDisciplina()->getNombre()));
        }

        foreach ($tendenciaEstrategias as $tendenciaEstrategia) {
            array_push($tendenciasArray, array('id' => $tendenciaEstrategia->getId(), 'nombre' => $tendenciaEstrategia->getTendencia()->getNombre()));
        }

        foreach ($institucionalEstrategias as $institucionalEstrategia) {
            array_push($categoriasArray, array('id' => $institucionalEstrategia->getId(), 'nombre' => $institucionalEstrategia->getCategoriaInstitucional()->getNombre()));
        }
        $respuesta["disciplina"] = $disciplinaArray;
        $respuesta["tendencia"] = $tendenciasArray;
        $respuesta["institucional"] = $categoriasArray;
        return $this->json($respuesta);
    }

    /**
     * @Route("existe-correo", name="existe_correo")
     */
    public function existecorreoAction(Request $request) {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $correoElectronico = $request->request->get('correoelectronico');

        $existeUsuario = $em->getRepository('ApplicationSonataUserBundle:User')->findByEmail($correoElectronico);
        if ($existeUsuario) {
            return $this->json(array('resultado' => $translator->trans('formulario_registro.correo_registrado'), 'usuario_existe' => true));
        }
        return $this->json(array('usuario_existe' => false));
    }

    /**
     * @Route("existe-organismo", name="existe_organismo")
     */
    public function existeorganismoAction(Request $request) {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $existeOrganismo = $em->getRepository('LogicBundle:OrganizacionDeportiva')->findByNit($request->request->get('numeroidentificacion'));
        if ($existeOrganismo) {
            return $this->json(array('resultado' => $translator->trans('formulario_registro.nit_registrado'), 'usuario_existe' => true));
        }
        return $this->json(array('usuario_existe' => false));
    }

    /**
     * @Route("getusuario", name="getusuario")
     */
    public function obtenerUsuarioAction($id = null) {
        $author = $this->getDoctrine()->getRepository('ApplicationSonataUserBundle:User')->find($id);
        return new Response($author->getTipoPersona());
    }

    /**
     * @Route("/ajax_usuarios_rol/{rol}", name="ajax_usuarios_rol")
     */
    public function usuariosPorRol(Request $request, $rol) {
        $nombreOIdentificacion = $request->get('q');
        $usuarios = $this->getDoctrine()->getRepository('ApplicationSonataUserBundle:User')->buscarUsuariosPorRolEIdentificacionNombre($rol, $nombreOIdentificacion);
        $json = [];
        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                $json[] = array(
                    'id' => $usuario->getId(),
                    'text' => $usuario->getFullnameIdentificacion(),
                );
            }
        }
        $response = new Response();
        $response->setContent(json_encode($json));
        return $response;
    }

    /**
     * @Route("/subDiscapacidades", name="ajax_subdiscapacidades_por_discapacidad", options={"expose"=true})
     */
    public function subDiscapacidadesAction(Request $request) {
        $discapacidad = $request->request->get('discapacidad_id');
        $discapacidad = $discapacidad != "" ? $discapacidad : 0;
        $em = $this->getDoctrine()->getManager();
        $subDiscapacidades = $em->getRepository('LogicBundle:SubDiscapacidad')->findBy(array('discapacidad' => $discapacidad), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($subDiscapacidades as $subDiscapacidad) {
            array_push($respuesta, array('id' => $subDiscapacidad->getId(), 'nombre' => $subDiscapacidad->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/puntosAtencionPorBarrio", name="ajax_puntos_atencion_por_direccion_barrio", options={"expose"=true})
     */
    public function puntosAtencionPorBarrioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $barrio = $em->getRepository('LogicBundle:Barrio')->findOneById($request->request->get('barrio_id', null));
        $direccion = $request->request->get('direccion');
        $puntosAtencion = $em->getRepository(PuntoAtencion::class)->consultaDireccionBarrio($direccion, $barrio);
        $respuesta = [];
        foreach ($puntosAtencion as $pt) {
            array_push($respuesta, array('id' => $pt->getId(), 'nombre' => $pt->getDireccion()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/traerDiaProgramadoAsistencia", name="ajax_traer_dia_programado_asistencia", options={"expose"=true})
     */
    public function traerDiaProgramadoAsistenciaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id_programacion = $request->request->get('id_programacion');
        $programacion = $em->getRepository('LogicBundle:Programacion')->find($id_programacion);
        $oferta = $programacion->getOferta();
        $dia = $programacion->getDia()->getNumero();
        $inicio = $oferta->getFechaInicial();
        $fin = $oferta->getFechaFinal();
        $buscarfechas = new BuscarFechas();
        $fechas = $buscarfechas->todasLosDias($inicio, $fin);
        $respuesta = $buscarfechas->tenerDias($fechas, $dia);
        return $this->json($respuesta);
    }

    /**
     * @Route("/traerDiaProgramadoAsistenciaReserva", name="ajax_traer_dia_programado_asistencia_reserva", options={"expose"=true})
     */
    public function traerDiaProgramadoAsistenciaReservaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dia = $request->request->get('id_programacion');
        $reserva = $request->request->get('reserva_id');
        $programacion = $em->getRepository('LogicBundle:ProgramacionReserva')->buscarProgramacion($dia, $reserva);
        $reserva = $programacion->getReserva();
        $dia = $programacion->getDia()->getNumero();
        $inicio = $reserva->getFechaInicio();
        $fin = $reserva->getFechaFinal();
        $buscarfechas = new BuscarFechas();
        $fechas = $buscarfechas->todasLosDias($inicio, $fin);
        $respuesta = $buscarfechas->tenerDias($fechas, $dia);
        return $this->json($respuesta);
    }

    /**
     * @Route("/asignarValorTipoIdenfificacion", name="ajax_asignar_valor_tipo_identificacion", options={"expose"=true})
     */
    public function asignarValorTipoIdenfificacionAction(Request $request) {
        $session = $request->getSession();
        if ($request->get('tipo_usuario') == "formador") {
            $session->set('id_tipo_documento_formador', $request->get('tipoIdentificacion_id'));
        } else if ($request->get('tipo_usuario') == "gestor") {
            $session->set('id_tipo_documento_gestor', $request->get('tipoIdentificacion_id'));
        }
        exit;
//        return $session;
    }

    /**
     * @Route("/mostrarEncuesta", name="ajax_mostrar_encuesta", options={"expose"=true})
     */
    public function mostrarEncuestaAction(Request $request) {
        date_default_timezone_set('America/Bogota');
        //validador, sirver para comprobar si hay encuesta en la comuna o en las estrategio y oferta.
        $isQuery = false;
        $em = $this->getDoctrine()->getManager();
        //Obteniendo el usuario logueado
        $user = $this->getUser();
        if ($user == null) {
            return $this->json(array('show_encuesta' => false));
        }
        $encuestaRepository = $em->getRepository("LogicBundle:Encuesta");

        $query = $encuestaRepository
                ->createQueryBuilder('e');
        if ($user->getBarrio() != null) {
            if ($user->getBarrio()->getComuna() != null) {
                $comuna = $user->getBarrio()->getComuna();
                $query
                        ->setParameter('comuna', $comuna)
                        ->orWhere('e.comuna = :comuna')
                        ->getQuery();
                $isQuery = true;
            }
        }

        if ($user->getOfertaFormador() != null) {
            foreach ($user->getOfertaFormador() as $formadorOferta) {
                if ($formadorOferta->getEstrategia() != null) {
                    $query->setParameter('estrategiaQ', $formadorOferta->getEstrategia())
                            ->orWhere('e.estrategia = :estrategiaQ')
                            ->setParameter('ofertaQ', $formadorOferta)
                            ->orWhere('e.oferta = :ofertaQ')
                            ->getQuery();
                    $isQuery = true;
                }
            }
        }

        if ($user->getOfertaGestor() != null) {
            foreach ($user->getOfertaGestor() as $gestorOferta) {
                if ($gestorOferta->getEstrategia() != null) {
                    $query->setParameter('estrategiaQ', $gestorOferta->getEstrategia())
                            ->orWhere('e.estrategia = :estrategiaQ')
                            ->setParameter('ofertaQ', $gestorOferta)
                            ->orWhere('e.oferta = :ofertaQ')
                            ->getQuery();
                    $isQuery = true;
                }
            }
        }


        if (!$isQuery) {
            return $this->json(array('show_encuesta' => false));
        }

        //ordenar consulta por fecha
        $encuestasQuery = $query->andWhere('e.activo = true')
                ->orderBy('e.fechaInicio', 'DESC')
                ->getQuery()
                ->getResult();
        //fecha actual del sistema                
        $fechaActual = new \DateTime();
        //Fecha consultada
        $fechaConsulta = null;
        $encuestaCumple = array();
        //validaciones para mostrar encuesta
        if ($encuestasQuery != null) {
            foreach ($encuestasQuery as $encuestaInte) {
                $encuestaPregunta = $encuestaInte->getEncuestaPreguntas()[0];
                //Obtener la ultima respuesta de la encuesta que se esta interando
                $respuestaUser = $em->getRepository("LogicBundle:EncuestaRespuesta")
                        ->createQueryBuilder('er')
                        ->join('er.encuestaOpcion', 'eo')
                        ->where('eo.encuestaPregunta = :encuestaPreguntaCurrent')
                        ->setParameter('encuestaPreguntaCurrent', $encuestaPregunta)
                        ->andWhere('er.usuario = :user')
                        ->setParameter('user', $user)
                        ->orderBy('er.fechaRespuesta', 'DESC')
                        ->getQuery()
                        ->getResult();
                if ($respuestaUser != null) {
                    $fechaConsulta = current($respuestaUser)->getFechaRespuesta();
                } else {
                    $fechaConsulta = null;
                }
                $fechaInicioEncuesta = $encuestaInte->getFechaInicio();
                $duracionEncuesta = $encuestaInte->getDuracion();
                $validarFecha = true;
                $temFechaConPeriodoValidador = $fechaInicioEncuesta;
                while ($validarFecha) {
                    $temFechaConPeriodoValidador->modify('+' . ($encuestaInte->diasPeriodo() + $duracionEncuesta) . 'day');
                    if ($fechaActual < $temFechaConPeriodoValidador) {
                        $fechaInicio = clone $temFechaConPeriodoValidador;
                        $fechaInicio->modify('-' . ($encuestaInte->diasPeriodo() + $duracionEncuesta) . 'day');
                        $fechaFinEjecucionEncuesta = clone $fechaInicio;
                        $fechaFinEjecucionEncuesta->modify('+' . $duracionEncuesta . 'day');
                        if ($fechaInicio <= $fechaActual && $fechaActual <= $fechaFinEjecucionEncuesta) {
                            if ($fechaConsulta != null) {
                                if ($fechaInicio <= $fechaConsulta && $fechaConsulta <= $fechaFinEjecucionEncuesta) {
                                    //no se hace nada 
                                    $validarFecha = false;
                                } else {
                                    //no ha respondido la encuesta
                                    array_push($encuestaCumple, array(
                                        "encuesta" => $encuestaInte,
                                        "fechaSinPeriodo" => $fechaInicio,
                                        "fechaConPeriodo" => $fechaFinEjecucionEncuesta,
                                    ));
                                    // array_push($encuestaCumple,$encuestaInte);
                                    $validarFecha = false;
                                }
                            } else {
                                //no ha respondido la encuesta
                                //array_push($encuestaCumple,$encuestaInte);
                                array_push($encuestaCumple, array(
                                    "encuesta" => $encuestaInte,
                                    "fechaSinPeriodo" => $fechaInicio,
                                    "fechaConPeriodo" => $fechaFinEjecucionEncuesta,
                                ));
                                $validarFecha = false;
                            }
                        } else {
                            $validarFecha = false;
                        }
                    } else {
                        //se vuelve a iterar para sumar los dias de periodicidad con la duracion 
                        //para alcanzar a la fecha actual y comparar si se muestra la encuesta
                        $validarFecha = true;
                    }
                }
            }//foreach de encuesta encontradas
        }


        if (isset($encuestaCumple)) {
            if (count($encuestaCumple) > 0) {
                //Se construye el formulario apartir del current de las encuestar habilitadas
                $arrayEncuesta = current($encuestaCumple);
                $encuestaCuerrent = $arrayEncuesta['encuesta'];
                $fechaConPeriodo = $arrayEncuesta['fechaConPeriodo'];
                $fechaSinPeriodo = $arrayEncuesta['fechaSinPeriodo'];
                //Verificar si ya se cumplio con la muestra    
                $encuestaPregunta = $encuestaCuerrent->getEncuestaPreguntas()[0];
                $countRespuesta = $em->createQueryBuilder()
                        ->select('count(ep.id)')
                        ->from('LogicBundle:EncuestaRespuesta', 'ep')
                        ->innerJoin('ep.encuestaOpcion', 'eo')
                        ->where('eo.encuestaPregunta = :encuestaPreguntaCurrent')
                        ->setParameter('encuestaPreguntaCurrent', $encuestaPregunta)
                        ->andWhere('ep.fechaRespuesta BETWEEN :fechaMin AND :fechaMax')
                        ->setParameter('fechaMin', $fechaSinPeriodo)
                        ->setParameter('fechaMax', $fechaConPeriodo)
                        ->getQuery()
                        ->getSingleScalarResult();
                //Obtener el total de muestra
                $muestraTotal = 0;
                if ($encuestaCuerrent->getMuestraComuna()) {
                    $muestraTotal += $encuestaCuerrent->getMuestraComuna();
                }
                if ($encuestaCuerrent->getMuestraEstrategia()) {
                    $muestraTotal += $encuestaCuerrent->getMuestraEstrategia();
                }
                if ($encuestaCuerrent->getMuestraOferta()) {
                    $muestraTotal += $encuestaCuerrent->getMuestraOferta();
                }
                if ($countRespuesta >= $muestraTotal) {
                    return $this->json(array('show_encuesta' => false));
                }
                $noVacio = array(
                    new NotBlank(array(
                        'message' => 'formulario_escenario_deportivo.no_vacio',
                            ))
                );

                $formBuilder = $this->createFormBuilder($encuestaCuerrent);
                $preguntas = $encuestaCuerrent->getEncuestaPreguntas();
                foreach ($preguntas as $pregunta) {
                    $opcionesChoices = array();
                    $opciones = $pregunta->getEncuestaOpciones();
                    foreach ($opciones as $row2) {
                        $opcionesChoices[$row2->getNombre()] = $row2->getId();
                    }

                    $formBuilder->add($pregunta->getId(), ChoiceType::class, array(
                        'choices' => $opcionesChoices,
                        'label' => $pregunta->getNombre(),
                        'mapped' => false,
                        'required' => true,
                        "constraints" => $noVacio,
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => array('class' => 'EncuestaPregunta'),
                    ));
                }
                $formBuilder->add('save', SubmitType::class, array(
                    'label' => 'formulario_encuesta.labels.boton',
                ));
                $form = $formBuilder->getForm();
                return $this->render('AdminBundle:Encuesta:encuesta.html.twig', array(
                            'form' => $form->createView(),
                            'encuesta' => $encuestaCuerrent,
                            'preguntas' => $preguntas
                ));
            }
        }
        return $this->json(array('show_encuesta' => false));
    }

    /**
     * @Route("/escenariodeportivo/mostrarCamposInfraestructura", name="ajax_escenario_deportivo_mostrar_campos_infraestructura", options={"expose"=true})
     */
    public function mostrarCamposInfraestructuraAction(Request $request) {
        $subCategoriaId = $request->get('sub_categoria_id');
        $escenarioSubCategoria = $request->get('escenarioSubCategoria');
        $categoria = $request->get('escenarioCategoria');
        $escenarioSubCategoriaEntity = $request->get('escenarioSubcategoriaEntity');
        $campos = $request->get('campos', []);
        $em = $this->getDoctrine()->getManager();
        $subCategoria = $em->getRepository('LogicBundle:SubcategoriaInfraestructura')->find($subCategoriaId);
        $formBuilder = $this->createFormBuilder();
        $camposIfraestructura = $subCategoria->getCampoInfraestructuras();
        if (count($camposIfraestructura) > 0) {
            foreach ($camposIfraestructura as $campoIfraestructura) {
                $nombre = $categoria . "_" . $escenarioSubCategoria . "_" . $campoIfraestructura->getId();
                $data = null;
                if ($escenarioSubCategoriaEntity) {
                    $escenarioSubcatergoriaCampo = $this->em->getRepository("LogicBundle:EscenarioSubCategoriaInfraestructuraCampo")->buscarValorAlmacenado($escenarioSubCategoriaEntity, $campoIfraestructura->getId(), $subCategoria->getId());
                    if ($escenarioSubcatergoriaCampo) {
                        $data = $escenarioSubcatergoriaCampo->getValor();
                    }
                }
                if (is_array($campos)) {
                    if (key_exists($nombre, $campos)) {
                        $data = $campos[$nombre];
                    }
                }
                if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_TEXTO) {
                    $formBuilder->add($nombre, TextType::class, array(
                        'label' => $campoIfraestructura->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_AREA_TEXTO) {
                    $formBuilder->add($nombre, TextareaType::class, array(
                        'label' => $campoIfraestructura->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_FECHA) {
                    if ($data) {
                        $data = \DateTime::createFromFormat("d/m/Y", $data);
                    }

                    $formBuilder->add($nombre, DateMaskType::class, array(
                        'block_name' => 'asdasd',
                        'label' => $campoIfraestructura->getNombre(),
                        'attr' => array('class' => 'form-control mask'),
                        'mask-alias' => 'dd/mm/yyyy',
                        'placeholder' => 'dd/mm/yyyy',
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_NUMERO) {
                    $formBuilder->add($nombre, NumberType::class, array(
                        'label' => $campoIfraestructura->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_SELECCION) {
                    $opciones = array();
                    foreach ($campoIfraestructura->getOpcionCampoInfraestructuras() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoIfraestructura->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => false,
                        'multiple' => false,
                        'attr' => array('class' => 'form-control form-select'),
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_SELECCION_MULTIPLE) {
                    $opciones = array();
                    foreach ($campoIfraestructura->getOpcionCampoInfraestructuras() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoIfraestructura->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => false,
                        'multiple' => true,
                        'attr' => array('class' => 'form-control form-select'),
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_RADIO_BUTTON) {
                    $opciones = array();
                    foreach ($campoIfraestructura->getOpcionCampoInfraestructuras() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoIfraestructura->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'placeholder' => false,
                        'attr' => array('class' => 'form-control radio-button'),
                        'data' => $data
                    ));
                } else if ($campoIfraestructura->getTipoEntrada() == CampoInfraestructura::TIPO_CHECKBOX) {
                    $opciones = array();
                    foreach ($campoIfraestructura->getOpcionCampoInfraestructuras() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoIfraestructura->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'form-control'),
                        'data' => $data
                    ));
                }
            }
        }
        $form = $formBuilder->getForm();
        return $this->render('AdminBundle:EscenarioDeportivo:campo_dinamico.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/escenariodeportivo/mostrarCamposAmbiental", name="ajax_escenario_deportivo_mostrar_campos_ambiental", options={"expose"=true})
     */
    public function subCategoriaAmbientalAction(Request $request) {
        $subCategoriaId = $request->get('sub_categoria_id');
        $escenarioSubCategoria = $request->get('escenarioSubCategoria');
        $categoria = $request->get('escenarioCategoria');
        $escenarioSubCategoriaEntity = $request->get('escenarioSubcategoriaEntity');
        $campos = $request->get('campos', []);

        $em = $this->getDoctrine()->getManager();
        $subCategoria = $em->getRepository('LogicBundle:SubcategoriaAmbiental')->find($subCategoriaId);

        $formBuilder = $this->createFormBuilder();

        $camposAmbiental = $subCategoria->getCampoAmbientales();

        if (count($camposAmbiental) > 0) {
            foreach ($camposAmbiental as $campoAmbiental) {
                $nombre = $categoria . "_" . $escenarioSubCategoria . "_" . $campoAmbiental->getId();
                $data = null;

                if ($escenarioSubCategoriaEntity) {
                    $escenarioSubcatergoriaCampo = $this->em->getRepository("LogicBundle:EscenarioSubCategoriaAmbientalCampo")->buscarValorAlmacenado($escenarioSubCategoriaEntity, $campoAmbiental->getId(), $subCategoria->getId());
                    if ($escenarioSubcatergoriaCampo) {
                        $data = $escenarioSubcatergoriaCampo->getValor();
                    }
                }

                if (is_array($campos)) {
                    if (key_exists($nombre, $campos)) {
                        $data = $campos[$nombre];
                    }
                }

                if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_TEXTO) {
                    $formBuilder->add($nombre, TextType::class, array(
                        'label' => $campoAmbiental->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_AREA_TEXTO) {
                    $formBuilder->add($nombre, TextareaType::class, array(
                        'label' => $campoAmbiental->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_FECHA) {
                    if ($data) {
                        $data = \DateTime::createFromFormat("d/m/Y", $data);
                    }

                    $formBuilder->add($nombre, DateMaskType::class, array(
                        'block_name' => 'asdasd',
                        'label' => $campoAmbiental->getNombre(),
                        'attr' => array('class' => 'form-control mask'),
                        'mask-alias' => 'dd/mm/yyyy',
                        'placeholder' => 'dd/mm/yyyy',
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_NUMERO) {
                    $formBuilder->add($nombre, NumberType::class, array(
                        'label' => $campoAmbiental->getNombre(),
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_SELECCION) {
                    $opciones = array();
                    foreach ($campoAmbiental->getOpcionesCampo() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoAmbiental->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => false,
                        'multiple' => false,
                        'attr' => array('class' => 'form-control form-select'),
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_SELECCION_MULTIPLE) {
                    $opciones = array();
                    foreach ($campoAmbiental->getOpcionesCampo() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoIfraestructura->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => false,
                        'multiple' => true,
                        'attr' => array('class' => 'form-control form-select'),
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_RADIO_BUTTON) {
                    $opciones = array();
                    foreach ($campoAmbiental->getOpcionesCampo() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoAmbiental->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'placeholder' => false,
                        'attr' => array('class' => 'form-control radio-button'),
                        'data' => $data
                    ));
                } else if ($campoAmbiental->getTipoEntrada() == CampoAmbiental::TIPO_CHECKBOX) {
                    $opciones = array();
                    foreach ($campoAmbiental->getOpcionesCampo() as $opcion) {
                        $opciones[$opcion->getOpcion()] = $opcion->getId();
                    }

                    $formBuilder->add($nombre, ChoiceType::class, array(
                        'choices' => $opciones,
                        'label' => $campoAmbiental->getNombre(),
                        'mapped' => false,
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'form-control'),
                        'data' => $data
                    ));
                }
            }
        }

        $form = $formBuilder->getForm();
        return $this->render('AdminBundle:EscenarioDeportivo/Ambiental:campo_dinamico.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/estrategiasPorArea", name="ajax_estrategias_por_area", options={"expose"=true})
     */
    public function estrategiasPorAreaAction(Request $request) {
        $area = $request->request->get('area_id');
        $area = $area != "" ? $area : 0;
        $em = $this->getDoctrine()->getManager();
        $estrategias = $em->getRepository('LogicBundle:Estrategia')->findBy(array('area' => $area), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($estrategias as $estrategia) {
            array_push($respuesta, array('id' => $estrategia->getId(), 'nombre' => $estrategia->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/ofertasPorEstrategia2", name="ajax_ofertas_por_estrategia2", options={"expose"=true})
     */
    public function ofertasPorEstrategia2Action(Request $request) {
        $estrategia = $request->request->get('estrategia_id');
        $estrategia = $estrategia != "" ? $estrategia : 0;
        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $ofertas = $em->getRepository('LogicBundle:Oferta')->createQueryBuilder('o')
                        ->join('o.formador', 'formador')
                        ->where('o.estrategia = :estrategia')
                        ->andWhere('formador.id = :id_user')
                        ->orderBy("o.nombre", 'ASC')
                        ->setParameter('estrategia', $estrategia)
                        ->setParameter('id_user', $userId)
                        ->getQuery()->getResult();
        $respuesta = array();
        foreach ($ofertas as $oferta) {
            array_push($respuesta, array('id' => $oferta->getId(), 'nombre' => $oferta->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/modelosPorPlanAnualMetodologico", name="ajax_modelos_por_planAnualMetodologico", options={"expose"=true})
     */
    public function modelosPorPlanAnualMetodologicoAction(Request $request) {
        $planAnualMetodologico = $request->request->get('planAnualMetodologico_id');
        $planAnualMetodologico = $planAnualMetodologico != "" ? $planAnualMetodologico : 0;
        $em = $this->getDoctrine()->getManager();
        $modelos = $em->getRepository('LogicBundle:Modelo')->findBy(array('planAnualMetodologico' => $planAnualMetodologico), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($modelos as $modelo) {
            array_push($respuesta, array('id' => $modelo->getId(), 'nombre' => $modelo->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/componentesPorPlanAnualMetodologico", name="ajax_componentes_por_planAnualMetodologico", options={"expose"=true})
     */
    public function componentesPorPlanAnualMetodologicoAction(Request $request) {
        $planAnualMetodologico = $request->request->get('planAnualMetodologico_id');
        $planAnualMetodologico = $planAnualMetodologico != "" ? $planAnualMetodologico : 0;
        $em = $this->getDoctrine()->getManager();
        $componentes = $em->getRepository('LogicBundle:Componente')->findBy(array('planAnualMetodologico' => $planAnualMetodologico), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($componentes as $componente) {
            array_push($respuesta, array('id' => $componente->getId(), 'nombre' => $componente->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/contenidosPorComponente", name="ajax_contenidos_por_componente", options={"expose"=true})
     */
    public function contenidosPorComponenteAction(Request $request) {
        $componente = $request->request->get('componente_id');
        $componente = $componente != "" ? $componente : 0;
        $em = $this->getDoctrine()->getManager();
        $contenidos = $em->getRepository('LogicBundle:Contenido')->findBy(array('componente' => $componente), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($contenidos as $contenido) {
            array_push($respuesta, array('id' => $contenido->getId(), 'nombre' => $contenido->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/actividadesPorContenido", name="ajax_actividades_por_contenido", options={"expose"=true})
     */
    public function actividadesPorContenidoAction(Request $request) {
        $contenido = $request->request->get('contenido_id');
        $contenido = $contenido != "" ? $contenido : 0;
        $em = $this->getDoctrine()->getManager();
        $actividades = $em->getRepository('LogicBundle:Actividad')->findBy(array('contenido' => $contenido), array('nombre' => 'ASC'));
        $respuesta = array();
        foreach ($actividades as $actividad) {
            array_push($respuesta, array('id' => $actividad->getId(), 'nombre' => $actividad->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/ofertasPorEstrategia", name="ajax_ofertas_por_estrategia", options={"expose"=true})
     */
    public function ofertasPorEstrategiaAction(Request $request) {
        $estrategia = $request->request->get('estrategia_id');
        $estrategia = $estrategia != "" ? $estrategia : 0;
        $em = $this->getDoctrine()->getManager();
        $ofertas = $em->getRepository('LogicBundle:Oferta')
                ->findBy(
                array(
            'estrategia' => $estrategia,
            'planAnualMetodologico' => null
                ), array('nombre' => 'ASC')
        );
        $respuesta = array();
        foreach ($ofertas as $oferta) {
            array_push($respuesta, array('id' => $oferta->getId(), 'nombre' => $oferta->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/borrarSancionEvento", name="ajax_borrar_sancionEvento", options={"expose"=true})
     */
    public function borrarSancionEvento(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $idSancion = $request->request->get('id');
        $sancionBorrar = $em->getRepository('LogicBundle:SancionEvento')->findOneById($idSancion);
        if ($sancionBorrar != null) {
            $em->remove($sancionBorrar);
            $em->flush();
            return $this->json(array('resultado' => 1));
        } else {
            return $this->json(array('resultado' => 0));
        }
    }

    /**
     * @Route("/mostrarSancionEvento", name="ajax_mostrar_sancionEvento", options={"expose"=true})
     */
    public function mostrarSancionEvento(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $idSancion = $request->request->get('id');
        $sancionBorrar = $em->getRepository('LogicBundle:SancionEvento')->findOneById($idSancion);
        if ($sancionBorrar != null) {
            $puntaje = $sancionBorrar->getPuntajeJuegoLimpio();
            $tipoFalta = $sancionBorrar->getSancion()->getTipoFalta()->getNombre();
            $descripcion = $sancionBorrar->getSancion()->getDescripcion();
            $nombre = $sancionBorrar->getSancion()->getNombre();
            return $this->json(array('nombre' => $nombre, 'descripcion' => $descripcion, 'tipo' => $tipoFalta, 'puntaje' => $puntaje));
        } else {
            return $this->json(array('resultado' => 0));
        }
    }

    /**
     * @Route("/mostrarSancionEvento", name="ajax_editar_sancionEvento", options={"expose"=true})
     */
    public function editarSancionEvento(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $idSancion = $request->request->get('id');
        $sancionBorrar = $em->getRepository('LogicBundle:SancionEvento')->findOneById($idSancion);
        if ($sancionBorrar != null) {
            $puntaje = $sancionBorrar->getPuntajeJuegoLimpio();
            $tipoFalta = $sancionBorrar->getSancion()->getTipoFalta();
            $descripcion = $sancionBorrar->getSancion()->getDescripcion();
            $nombre = $sancionBorrar->getSancion()->getNombre();
            return $this->json(array('nombre' => $nombre, 'descripcion' => $descripcion, 'tipo' => $tipoFalta, 'puntaje' => $puntaje));
        }
        return $this->json(array('resultado' => 0));
    }

    function prepararTexto($cadena) {
        $no_permitidas = array(
            "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²",
            "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«"
            , "Ò", "Ã", "Ã„", "Ã‹", " ", ".", ",", "/", ")", "(", "°"
        );
        $permitidas = array(
            "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u",
            "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E",
            "_", "", "", "", "", "", ""
        );
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        $texto = strtolower($texto);
        return $texto;
    }

    /**
     * @Route("/subCategoriasPorCategoria", name="ajax_subcategorias_por_categorias", options={"expose"=true})
     */
    public function subCategoriasPorCategoriaAction(Request $request) {
        $categoria = $request->request->get('categoria_id');
        $categoria = $categoria != "" ? $categoria : 0;
        $em = $this->getDoctrine()->getManager();
        $subCategorias = $em->getRepository('LogicBundle:SubCategoriaEvento')->findBy(array('categoria' => $categoria));
        $respuesta = array();
        foreach ($subCategorias as $subCategoria) {
            array_push($respuesta, array(
                'id' => $subCategoria->getId(),
                'nombre' => $subCategoria->getNombre(),
            ));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/divisionesPorEscenario", name="ajax_divisiones_por_escenario", options={"expose"=true})
     */
    public function divisionesPorEscenarioAction(Request $request) {
        $escenario = $request->request->get('escenario_id');
        $escenario = $escenario != "" ? $escenario : 0;
        $em = $this->getDoctrine()->getManager();
        $divisiones = $em->getRepository('LogicBundle:Division')->findBy(array('escenarioDeportivo' => $escenario));
        $respuesta = array();
        foreach ($divisiones as $division) {
            array_push($respuesta, array(
                'id' => $division->getId(),
                'nombre' => $division->getNombre(),
            ));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/puntosPorFalta", name="ajax_puntos_por_falta", options={"expose"=true})
     */
    public function puntosPorFaltaAction(Request $request) {
        $falta = $request->request->get('tipoFalta_id');
        $falta = $falta != "" ? $falta : 0;
        $em = $this->getDoctrine()->getManager();
        $puntos = $em->getRepository('LogicBundle:TipoFalta')->find($falta);
        $respuesta = $puntos->getPuntosJuegolimpio();
        return $this->json($respuesta);
    }

    /**
     * @Route("/puntosPorSancion", name="ajax_puntos_por_sancion", options={"expose"=true})
     */
    public function puntosPorSancion(Request $request) {
        $sancion = $request->request->get('sancion_id');
        $sancion = $sancion != "" ? $sancion : 0;
        $em = $this->getDoctrine()->getManager();
        $sancion = $em->getRepository('LogicBundle:Sancion')->find($sancion);
        $respuesta = $sancion->getTipoFalta()->getPuntosJuegolimpio();
        return $this->json($respuesta);
    }

    /**
     * @Route("/disciplinasPorEscenario", name="ajax_disciplinas_por_escenario", options={"expose"=true})
     */
    public function disciplinasPorEscenario(Request $request) {
        $escenario = $request->request->get('escenario_id');
        $escenario = $escenario != "" ? $escenario : 0;
        $escenario = $request->request->get('escenario_id');
        $em = $this->getDoctrine()->getManager();
        $escenarioReal = $em->getRepository("LogicBundle:EscenarioDeportivo")->find($escenario);
        $disciplinas = $em->getRepository('LogicBundle:Disciplina')->createQueryBuilder('disciplina')
                        ->innerJoin('disciplina.disciplinas', 'disciplinas')
                        ->innerJoin('disciplinas.escenarioDeportivo', 'escenarioDeportivo')
                        ->where('escenarioDeportivo.id = :escenario_deportivo')
                        ->orderBy("disciplina.nombre", 'DESC')
                        ->setParameter('escenario_deportivo', $escenarioReal ?: 0)
                        ->getQuery()->getResult();
        $respuesta = array();
        foreach ($disciplinas as $disci) {
            array_push($respuesta, array('id' => $disci->getId(), 'nombre' => $disci->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/tendenciasPorEscenario", name="ajax_tendencias_por_escenario", options={"expose"=true})
     */
    public function tendenciasPorEscenario(Request $request) {
        $escenario = $request->request->get('escenario_id');
        $escenario = $escenario != "" ? $escenario : 0;
        $escenario = $request->request->get('escenario_id');
        $em = $this->getDoctrine()->getManager();
        $escenarioReal = $em->getRepository("LogicBundle:EscenarioDeportivo")->find($escenario);
        $tendencias = $em->getRepository("LogicBundle:Tendencia")
                ->createQueryBuilder('tendencia')
                ->innerJoin('LogicBundle:TendenciaEscenarioDeportivo', 'ten', 'WITH', 'ten.tendencia = tendencia.id')
                ->where('ten.escenarioDeportivo = :escenario_deportivo')
                ->setParameter('escenario_deportivo', $escenario ?: 0)
                ->getQuery()
                ->getResult();
        $respuesta = array();
        foreach ($tendencias as $tenden) {
            array_push($respuesta, array('id' => $tenden->getId(), 'nombre' => $tenden->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/agregarMotivoCancelacionReserva", name="ajax_agregar_motivo_cancelacion_reserva", options={"expose"=true})
     */
    public function agregarMotivoCancelacionReservaAction(Request $request) {
        $idReserva = $request->request->get('idReserva');
        $motivoCancelacion = $request->request->get('motivoCancelacion');
        $em = $this->getDoctrine()->getManager();
        $reserva = $em->getRepository('LogicBundle:Reserva')->find($idReserva);
        $motivo = $em->getRepository('LogicBundle:MotivoCancelacion')->find($motivoCancelacion);
        $reserva->setMotivoCancelacion($motivo);
        $em->persist($reserva);
        $em->flush();
        return $this->json('Exitoso');
    }

    /**
     * @Route("/barriosPorTipo", name="ajax_barrios_por_tipo", options={"expose"=true})
     */
    public function barriosPorTipoAction(Request $request) {
        $municipio = $request->request->get('municipio_id') != "" ? $request->request->get('municipio_id') : 0;
        $em = $this->getDoctrine()->getManager();
        $barrios = $em->getRepository('LogicBundle:Barrio')->createQueryBuilder('b')
                        ->join('b.municipio', 'm')
                        ->where('b.esVereda = :es_vereda')
                        ->andWhere('m.id = :municipio')
                        ->setParameter('es_vereda', true)
                        ->setParameter('municipio', $municipio)
                        ->getQuery()->getResult();
        $respuesta = array();
        foreach ($barrios as $barrio) {
            array_push($respuesta, array('id' => $barrio->getId(), 'nombre' => $barrio->getNombre()));
        }
        return $this->json($respuesta);
    }

    /**
     * @Route("/barriosPorTipo2", name="ajax_barrios_por_tipo2", options={"expose"=true})
     */
    public function barriosPorTipo2Action(Request $request) {
        $municipio = $request->request->get('municipio_id') != "" ? $request->request->get('municipio_id') : 0;
        $em = $this->getDoctrine()->getManager();
        $barrios = $em->getRepository('LogicBundle:Barrio')->createQueryBuilder('b')
                        ->join('b.municipio', 'm')
                        ->where('b.esVereda = :es_vereda')
                        ->andWhere('m.id = :municipio')
                        ->setParameter('es_vereda', false)
                        ->setParameter('municipio', $municipio)
                        ->getQuery()->getResult();

        $respuesta = array();
        foreach ($barrios as $barrio) {
            array_push($respuesta, array('id' => $barrio->getId(), 'nombre' => $barrio->getNombre()));
        }
        return $this->json($respuesta);
    }

}
