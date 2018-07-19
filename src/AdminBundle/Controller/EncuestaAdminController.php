<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Form\EncuestaType;
use LogicBundle\Entity\Encuesta;
use LogicBundle\Entity\EncuestaPeriodo;
use LogicBundle\Entity\EncuestaOpcion;
use LogicBundle\Entity\EncuestaRespuesta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use DateTime;

class EncuestaAdminController extends CRUDController {

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
        return $this->redirectToRoute('admin_logic_encuesta_addencuesta', array('id' => 0));
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
        return $this->redirectToRoute('admin_logic_encuesta_addencuesta', array('id' => $id));
    }

    function addencuestaAction(Request $request, $id) {

        if ($id == 0) {
            $encuesta = new Encuesta();
        } else {
            $encuesta = $this->em->getRepository("LogicBundle:Encuesta")->find($id);
        }

        if ($id == 0) {
            $edicion = 0;
        } else {

            $edicion = 1;
        }



        $form = $this->createForm(EncuestaType::class, $encuesta, array());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $fechaCurrent = null;


            $nextAction = $form->get('saveTwo')->isClicked();

            if ($nextAction == true) {
                foreach ($form->get('encuestaPreguntas') as $formencuestapPreguntas) {
                    $encuestaPreguntasObject = $formencuestapPreguntas->getData();

                    if (count($encuestaPreguntasObject->getEncuestaOpciones()) <= 1) {
                        $formencuestapPreguntas->get("nombre")->addError(
                                new FormError($this->trans->trans('formulario_encuesta.error.menos_una_opcion'))
                        );
                    }
                }
                $isChoice = false;

                if ($form->get('comuna')->getData() != "") {
                    $isChoice = true;
                    if ($form->get('muestraComuna')->getData() == "" || $form->get('muestraComuna')->getData() == null) {
                        $form->get("muestraComuna")->addError(
                                new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                        );
                    }
                }

                if ($form->get('estrategia')->getData() != "") {
                    $isChoice = true;
                    if ($form->get('muestraEstrategia')->getData() == "" || $form->get('muestraEstrategia')->getData() == null) {
                        $form->get("muestraEstrategia")->addError(
                                new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                        );
                    }
                }

                if ($form->get('oferta')->getData() != "") {
                    $isChoice = true;
                    if ($form->get('muestraOferta')->getData() == "" || $form->get('muestraOferta')->getData() == null) {
                        $form->get("muestraOferta")->addError(
                                new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                        );
                    }
                }


                if (!$isChoice) {
                    $form->get("comuna")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                    );
                    $form->get("estrategia")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                    );
                    $form->get("oferta")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                    );
                }

                date_default_timezone_set('America/Bogota');
                $fechaActual = new \DateTime(date('Y-m-d H:i:s'));


                $fechaActual->modify('-2 day');

                if ($fechaActual > $encuesta->getFechaInicio()) {
                    $form->get("fechaInicio")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.fecha_inicio'))
                    );
                }

                if ($form->isValid()) {
                    //Obteniendo el usuario
                    $user = $this->getUser();
                    $encuesta->setUsuario($user);
                    $dateObject = new DateTime();
                    $encuesta->setFechaCreacion($dateObject);
                    $encuesta->setActivo(true);

                    //Set encuesta
                    for ($i = 0; $i < count($encuesta->getEncuestaPreguntas()); $i++) {
                        $encuesta->getEncuestaPreguntas()[$i]->setEncuesta($encuesta);
                    }

                    $this->em->persist($encuesta);
                    $this->em->flush();

                    $this->addFlash('sonata_flash_success', $this->trans('encuesta_add'));

                    return $this->redirectToRoute('admin_logic_encuesta_addencuesta', array('id' => 0));
                }
            }

            foreach ($form->get('encuestaPreguntas') as $formencuestapPreguntas) {
                
                $encuestaPreguntasObject = $formencuestapPreguntas->getData();

                if (count($encuestaPreguntasObject->getEncuestaOpciones()) <= 1) {
                    $formencuestapPreguntas->get("nombre")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.menos_una_opcion'))
                    );
                }
            }
            
            if (count($form->get('encuestaPreguntas')) == 0) {                
                $form->get("encuestaPreguntas")->addError(
                    new FormError($this->trans->trans('formulario_encuesta.error.encuesta_pregunta_vacio'))
                );
            }
            $isChoice = false;

            if ($form->get('comuna')->getData() != "") {                
                $isChoice = true;

                if ($form->get('muestraComuna')->getData() == "" || $form->get('muestraComuna')->getData() == null) {                                        
                    $form->get("muestraComuna")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                    );
                }                
            }

            if ($form->get('estrategia')->getData() != "") {
                $isChoice = true;
                if ($form->get('muestraEstrategia')->getData() == "" || $form->get('muestraEstrategia')->getData() == null) {
                    $form->get("muestraEstrategia")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                    );
                }
            }

            if ($form->get('oferta')->getData() != "") {
                $isChoice = true;
                if ($form->get('muestraOferta')->getData() == "" || $form->get('muestraOferta')->getData() == null) {
                    $form->get("muestraOferta")->addError(
                            new FormError($this->trans->trans('formulario_encuesta.error.muestra_vacio'))
                    );
                }
            }
            
            if (!$isChoice) {
                $form->get("comuna")->addError(
                        new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                );
                $form->get("estrategia")->addError(
                        new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                );
                $form->get("oferta")->addError(
                        new FormError($this->trans->trans('formulario_encuesta.error.error_no_selection'))
                );
            }
            date_default_timezone_set('America/Bogota');
            $fechaActual = new \DateTime(date('Y-m-d H:i:s'));


            $fechaActual->modify('-2 day');

            if ($fechaActual > $encuesta->getFechaInicio()) {
                $form->get("fechaInicio")->addError(
                        new FormError($this->trans->trans('formulario_encuesta.error.fecha_inicio'))
                );
            }

            if ($form->isValid()) {
                //Obteniendo el usuario
                $user = $this->getUser();
                $encuesta->setUsuario($user);
                $dateObject = new DateTime();
                $encuesta->setFechaCreacion($dateObject);
                $encuesta->setActivo(true);

                //Set encuesta
                for ($i = 0; $i < count($encuesta->getEncuestaPreguntas()); $i++) {
                    $encuesta->getEncuestaPreguntas()[$i]->setEncuesta($encuesta);
                }

                $this->em->persist($encuesta);
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_encuesta_list', array('id' => 0));
            }
        }

        return $this->render('AdminBundle:Encuesta:create.html.twig', array(
                    'form' => $form->createView(),
                    'edicion' => $edicion
        ));
    }

    public function addrespuestasAction(Request $request) {

        //Obteniendo datos de la petición
        $id = $request->get('id');
        $form = $request->request->get('form');

        $encuesta = $this->em->getRepository("LogicBundle:Encuesta")->find($id);

        //Obteniendo el usuario
        $user = $this->getUser();
        $fechaActual = new DateTime();

        $preguntas = $encuesta->getEncuestaPreguntas();

        foreach ($preguntas as $pregunta) {
            $idOpcion = $form[$pregunta->getId()];
            $encuestaOpcion = $this->em->getRepository("LogicBundle:EncuestaOpcion")->find($idOpcion);

            $respuesta = new EncuestaRespuesta();
            $respuesta->setEncuestaOpcion($encuestaOpcion);
            $respuesta->setFechaRespuesta($fechaActual);
            $respuesta->setUsuario($user);

            $this->em->persist($respuesta);
        }
        $this->em->flush();

        return $this->redirectToRoute('homepage', array('id' => 0));
    }

    public function respuestaAction(Request $request, $id) {
        //validar la petición

        $periodo = 1;

        $form = $request->request->get('form');

        if ($request->request->get('form')) {

            $form = $request->request->get('form');
            if ($form['periodo']) {
                $periodo = $form['periodo'];
            }
        }

        $encuesta = $this->em->getRepository("LogicBundle:Encuesta")->find($id);

        //fecha actual del sistema                
        $fechaActual = new \DateTime();
        //rango de fecha
        $fechaFinEjecucionEncuesta = null;
        $fechaInicio = null;

        //variables de encuesta
        $fechaInicioEncuesta = $encuesta->getFechaInicio();
        $duracionEncuesta = $encuesta->getDuracion();
        $validarFecha = true;
        $tempFechaConPeriodoValidador = $fechaInicioEncuesta;

        $periodos = array();
        $contador = 1;


        while ($validarFecha) {

            $tempFechaConPeriodoValidador->modify('+' . ($encuesta->diasPeriodo() + $duracionEncuesta) . 'day');

            if ($fechaActual > $tempFechaConPeriodoValidador) {

                //add periodos cumplidos
                $periodos['Periodo ' . $contador] = $contador;

                if ($periodo == $contador) {

                    $fechaInicio = clone $tempFechaConPeriodoValidador;


                    $fechaInicio->modify('-' . ($encuesta->diasPeriodo() + $duracionEncuesta) . 'day');

                    $fechaFinEjecucionEncuesta = clone $fechaInicio;

                    $fechaFinEjecucionEncuesta->modify('+' . $duracionEncuesta . 'day');
                }
            } else {
                $validarFecha = false;
            }
            $contador ++;
        }

        //Consultamos las preguntas con base al periodo prensente
        /* $preguntasCurrent = $this->em->createQueryBuilder()
          ->select('pr')
          ->from('LogicBundle:EncuestaPregunta', 'pr')
          ->leftJoin('pr.encuestaOpciones','op')
          ->leftJoin('op.encuestaRespuestas','re')
          ->where('pr.encuesta = :encuestaCurrent')
          ->setParameter('encuestaCurrent', $encuesta)
          ->andWhere('re.fechaRespuesta BETWEEN :fechaMin AND :fechaMax')
          ->setParameter('fechaMin', $fechaInicio)
          ->setParameter('fechaMax', $fechaFinEjecucionEncuesta)
          ->getQuery()
          ->getResult(); */


        $preguntasCurrent = $this->em->getRepository('LogicBundle:EncuestaPregunta')
                ->createQueryBuilder('pr')
                ->leftJoin('pr.encuestaOpciones', 'op')
                ->leftJoin('op.encuestaRespuestas', 're')
                ->where('pr.encuesta = :encuestaCurrent')
                ->andWhere('re.fechaRespuesta <= :fechaMax')
                // ->andWhere('re.fechaRespuesta >= :fechaMax')
                ->andWhere('re.fechaRespuesta >= :fechaMin')
                ->setParameter('encuestaCurrent', $encuesta)
                ->setParameter('fechaMin', $fechaInicio)
                ->setParameter('fechaMax', $fechaFinEjecucionEncuesta)
                ->getQuery()
                ->getResult();


        /* $preguntasCurrent = $this->em
          ->createQuery('
          SELECT pr FROM LogicBundle:EncuestaPregunta pr
          INNER JOIN pr.encuestaOpciones op
          LEFT JOIN op.encuestaRespuestas re
          WHERE re.fechaRespuesta BETWEEN :fechaMin  AND :fechaMax
          AND pr.encuesta = :encuestaCurrent
          ')
          ->setParameter('encuestaCurrent', $encuesta)
          ->setParameter('fechaMin', $fechaInicio)
          ->setParameter('fechaMax', $fechaFinEjecucionEncuesta)
          ->getResult(); */


        //Verificar si hay respuestas
        $cantidadEncuestados = 0;
        $isData = false;

        foreach ($preguntasCurrent as $preguntaInte) {

            foreach ($preguntaInte->getEncuestaOpciones() as $opcion) {

                if ($opcion->getEncuestaRespuestas()) {

                    foreach ($opcion->getEncuestaRespuestas() as $respuesta) {

                        if ($fechaInicio <= $respuesta->getFechaRespuesta() &&
                                $respuesta->getFechaRespuesta() <= $fechaFinEjecucionEncuesta) {
                            
                        } else {
                            $opcion->removeEncuestaRespuesta($respuesta);
                        }
                    }
                }
            }
        }

        foreach ($preguntasCurrent as $preguntaInte) {

            if ($isData) {
                break;
            }

            foreach ($preguntaInte->getEncuestaOpciones() as $opcion) {
                if ($opcion->getEncuestaRespuestas()) {
                    $cantidadEncuestados += count($opcion->getEncuestaRespuestas());
                    $isData = true;
                    //break;
                }
            }
        }


        $muestraComuna = 0;
        $muestraEstrategia = 0;
        $muestraOferta = 0;

        if ($encuesta->getMuestraComuna()) {
            $muestraComuna = $encuesta->getMuestraComuna();
        }

        if ($encuesta->getMuestraEstrategia()) {
            $muestraEstrategia = $encuesta->getMuestraEstrategia();
        }

        if ($encuesta->getMuestraOferta()) {
            $muestraOferta = $encuesta->getMuestraOferta();
        }


        //Format fecha
        $fechaIncial = '';
        $fechaFinal = '';

        if ($fechaInicio != null) {
            $fechaIncial = $fechaInicio->format('Y-m-d');
        }

        if ($fechaFinEjecucionEncuesta != null) {
            $fechaFinal = $fechaFinEjecucionEncuesta->format('Y-m-d');
        }

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    ))
        );

        $formBuilder = $this->createFormBuilder();

        //Construir el formulario
        $formBuilder
                ->add('periodo', ChoiceType::class, array(
                    'placeholder' => '',
                    'choices' => $periodos,
                    "constraints" => $noVacio,
                    'required' => false,
                    'mapped' => false,
                    "label" => "formulario_encuesta.labels.periodo",
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('consultar', SubmitType::class, array(
                    'label' => 'formulario_encuesta.labels.boton_consultar',
                    'attr' => array('class' => 'btn btnVerde')));

        $form = $formBuilder->getForm();

        return $this->render('AdminBundle:Encuesta:lista_respuesta.html.twig', array(
                    'form' => $form->createView(),
                    'encuesta' => $encuesta,
                    'nombreEncuesta' => $encuesta->getNombre(),
                    'preguntas' => $preguntasCurrent,
                    'isData' => $isData,
                    'cantidadEncuestado' => $cantidadEncuestados,
                    'muestraComuna' => $muestraComuna,
                    'muestraEstrategia' => $muestraEstrategia,
                    'muestraOferta' => $muestraOferta,
                    'fechaIncial' => $fechaIncial,
                    'fechaFinal' => $fechaFinal,
                    'perido' => $periodo
        ));
    }

}
