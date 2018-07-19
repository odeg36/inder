<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use LogicBundle\Form\CategoriaEventoType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use LogicBundle\Form\CarneType;

class EventoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    )),
        );

        if ($options['paso'] == 1) {
            $eventoObject = $builder->getData();

            $opcion = [
                'data_class' => null,
                'label' => 'formulario_evento.labels.configuracion.agregar_imagen',
                'constraints' => [
                    new Image([
                        'mimeTypes' => [
                            "image/jpeg", "image/jpg", "image/png"
                        ],
                        'minWidth' => 800,
                        'minHeight' => 200
                            ])
                ],
                'attr' => [
                    "class" => "file",
                    "data-show-upload" => "false",
                    "data-show-caption" => "true",
                    "data-msg-placeholder" => "Seleccionar una imagen"
                ]
            ];
            if ($eventoObject->getId()) {
                $noVacioClave = [];
                $imagen = $eventoObject->getImagen();
                $opcion = array_merge($opcion, [
                    'required' => false,
                ]);
                if ($imagen) {
                    $container = $options['em'];

                    $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagen;
                }
            }

            $estatocheckEscenarioPuntoAtencion = "true";

            $builder
                    ->add('estado', ChoiceType::class, array(
                        'choices' => array(
                            'No Publicado' => 'No Publicado',
                            'Publicado' => 'Publicado',
                        ),
                        'required' => true,
                        "label" => "formulario_evento.labels.configuracion.estado",
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => array('class' => 'alinear'),
                    ))
                    ->add('nombre', null, array(
                        "label" => "formulario_evento.labels.configuracion.nombre",
                        'required' => true,
                        "constraints" => $noVacio,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('estrategia', EntityType::class, array(
                        'class' => 'LogicBundle:Estrategia',
                        'placeholder' => '',
                        'required' => false,
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('e')
                                    ->Where('e.activo = true');
                        },
                        "label" => "formulario_evento.labels.configuracion.estrategia",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('disciplina', EntityType::class, array(
                        'class' => 'LogicBundle:Disciplina',
                        'placeholder' => '',
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.disciplina",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('rama', EntityType::class, array(
                        'class' => 'LogicBundle:Rama',
                        'placeholder' => '',
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.rama",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('tipoEnfoque', EntityType::class, array(
                        'class' => 'LogicBundle:TipoEnfoque',
                        'placeholder' => '',
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.tipo_evento",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('fuenteFinanciacion', EntityType::class, array(
                        'class' => 'LogicBundle:fuenteFinanciacion',
                        'placeholder' => '',
                        'required' => true,
                        "constraints" => $noVacio,
                        "label" => "formulario_evento.labels.configuracion.fuente",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('categoriaSubcategorias', CollectionType::class, array(
                        'label' => false,
                        'entry_type' => CategoriaEventoType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'required' => false,
                        'prototype' => true,
                        'attr' => array(
                            'class' => 'subcategorias-collection',
                        ),
                        'by_reference' => false,
                        'entry_options' => array(
                            'subcategorias' => $options['subcategorias']
                        ),
                    ))
                    ->add('fechaInicial', DateType::class, array(
                        "label" => "formulario_evento.labels.configuracion.inicioevento",
                        "constraints" => $noVacio,
                        'required' => true,
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr' => array('class' => 'form-control'),
                    ))
                    ->add('fechaFinal', DateType::class, array(
                        "label" => "formulario_evento.labels.configuracion.finevento",
                        "constraints" => $noVacio,
                        'required' => true,
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr' => array('class' => 'form-control'),
                    ))
                    ->add('fechaInicialInscripcion', DateType::class, array(
                        "label" => "formulario_evento.labels.configuracion.inicioinscripciones",
                        "constraints" => $noVacio,
                        'required' => true,
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr' => array('class' => 'form-control'),
                    ))
                    ->add('fechaFinalInscripcion', DateType::class, array(
                        "label" => "formulario_evento.labels.configuracion.fininscripciones",
                        "constraints" => $noVacio,
                        'required' => true,
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr' => array('class' => 'form-control'),
                    ))
                    ->add('horaInicial', TextType::class, array(
                        'label' => 'formulario_evento.labels.configuracion.formato_hora',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('horaFinal', TextType::class, array(
                        'label' => 'formulario_evento.labels.configuracion.formato_hora',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('cupo', ChoiceType::class, array(
                        'choices' => array(
                            'Individual' => 'Individual',
                            'Equipos' => 'Equipos',
                        ),
                        "constraints" => $noVacio,
                        'required' => true,
                        "label" => "formulario_evento.labels.configuracion.cupos",
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => array(
                            'class' => 'alinear',
                        ),
                    ))
                    ->add('numeroCupos', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.numerocupos",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('numeroEquipos', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.numeroequipos",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('participantesEquipoMinimo', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.minimo",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('participantesEquipoMaximo', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.maximo",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('habilitarRecambios', 'checkbox', [
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.habilitar_recambios",
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                    ->add('habilitarListaLarga', 'checkbox', [
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.habilitar_lista_larga",
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                    ->add('personaporsexo', 'checkbox', [
                        'required' => false,
                        'data' => $options['personaporsexo'],
                        'mapped' => false,
                        "label" => "formulario_evento.labels.configuracion.personaporsexo",
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                    ->add('numeroMujeres', IntegerType::class, [
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.mujeres",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0
                        ]
                    ])
                    ->add('numeroHombres', IntegerType::class, [
                        'required' => false,
                        "label" => "formulario_evento.labels.configuracion.hombres",
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0
                        ]
                    ])
                    ->add('limitanteporedad', 'checkbox', [
                        'required' => false,
                        'mapped' => false,
                        'data' => $options['limitanteporedad'],
                        "label" => "formulario_evento.labels.configuracion.limitanteporedad",
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                    ->add('edadMayorQue', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.mayor",
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('edadMenorQue', IntegerType::class, [
                        "label" => "formulario_evento.labels.configuracion.menor",
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 1
                        ]
                    ])
                    ->add('descripcion', CKEditorType::class, array(
                        'label' => 'formulario_evento.labels.configuracion.descripcion',
                        "constraints" => $noVacio,
                        'config' => array('toolbar' => array(
                                array(
                                    'name' => 'basicstyles',
                                    'items' => array('Bold'),
                                ),
                                array(
                                    'name' => 'paragraph',
                                    'items' => array('NumberedList'),
                                ),
                            ),),
                        'attr' => array(
                            'class' => 'form-control',
                            'rows' => 6,
                            'autocomplete' => 'off')))
                    ->add('terminosCondiciones', TextareaType::class, array(
                        'label' => 'formulario_evento.labels.configuracion.terminos',
                        "constraints" => $noVacio,
                        'attr' => array(
                            'class' => 'form-control',
                            'rows' => 6,
                            'autocomplete' => 'off')))
                    ->add('imagen', FileType::class, $opcion)
                    ->add('tieneReserva', CheckboxType::class, [
                        "mapped" => false,
                        "required" => false,
                        "label" => "formulario_evento.labels.configuracion.unicoescenario",
                    ])
                    //-----------------  punto de atencion -----------------
                    ->add('tienePuntoAtencion', ChoiceType::class, [
                        'data' => $estatocheckEscenarioPuntoAtencion,
                        'choices' => array(
                            'formulario.oferta.escenario_seleccion.seleccion' => 'true',
                            'formulario.oferta.otro.seleccion' => 'false',
                        ),
                        'choice_attr' => function($val, $key, $index) {
                            return ['class' => 'seleccion-lugar-oferta'];
                        },
                        'label' => 'formulario.oferta.puntoAtencion.title',
                        'expanded' => true,
                        'multiple' => false,
                        'mapped' => false
                    ])
                    ->add('escenarioDeportivo', EntityType::class, array(
                        "class" => "LogicBundle:EscenarioDeportivo",
                        'placeholder' => '',
                        'required' => false,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off',
                            'onchange' => 'inder.evento.divisiones(this)',
                        ]
                    ))
                    ->add('division', EntityType::class, array(
                        'class' => 'LogicBundle:Division',
                        'placeholder' => '',
                        'required' => false,
                        'mapped' => false,
                        "label" => "formulario_evento.labels.configuracion.division",
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('municipio', EntityType::class, array(
                        'required' => false,
                        'class' => 'LogicBundle:Municipio',
                        'mapped' => false,
                        'label' => 'formulario.oferta.puntoAtencion.municipio',
                    ))
                    ->add("barrio", EntityType::class, array(
                        'required' => false,
                        'class' => 'LogicBundle:Barrio',
                        'mapped' => false,
                        'data' => 'null',
                        'empty_data' => '',
                        'label' => 'formulario.oferta.puntoAtencion.Barrio',
                        'attr' => [
                            'class' => 'seleccion_barrio'
                        ],
                    ))
                    ->add('direccion_creado', DireccionType::class, array(
                        'required' => false,
                        'mapped' => false,
                        //'object' => ['data' => $this->getSubject()],
                        'formularioPadre' => '.autocompletePuntoAtencion>div>.select2-input',
                        'attr' => array(
                            'clases' => 'campoDireccionOferta direccionCreadaEvento mostrarDirCreada',
                        ),
                        'label' => "formulario.oferta.direccion.creada",
                    ))
                    ->add('puntoAtencion', EntityType::class, array(
                        'class' => 'LogicBundle:PuntoAtencion',
                        'label_attr' => array('class' => 'label_puntoAtencion'),
                        'placeholder' => '',
                        'attr' => array(
                            'class' => 'mostrarDirCreada seleccion-puntoAtencion',
                        ),
                        'required' => false,
                        'mapped' => false
                    ))
                    ->add('formulario_evento_puntoatencion_check', CheckboxType::class, [
                        "mapped" => false,
                        "required" => false,
                        "label" => "formulario.oferta.puntoAtencion.agregar",
                        'attr' => array(
                            'class' => 'checkNuevoPuntoAtencion',
                        )
                    ])
                    ->add('direccion_format', DireccionType::class, array(
                        'required' => false,
                        'mapped' => false,
                        //'object' => ['data' => $this->getSubject()],
                        'formularioPadre' => '.nuevaDireccion',
                        'attr' => array(
                            'class' => 'campoDireccionOferta mostrarDirNueva'
                        ),
                        'label' => false,
                    ))
                    ->add('direccion', TextType::class, array(
                        'required' => false,
                        'label' => 'formulario.oferta.puntoAtencion.nueva.direccion',
                        'mapped' => false,
                        'attr' => array(
                            'class' => ' form-control nuevaDireccion campoEscribirDireccion  col-md-12  mostrarDirNueva',
                            'readonly' => 'true')
                    ))
                    ->add("latitud", TextType::class, array(
                        'required' => false,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'mostrarDirNueva form-control'
                        ]
                    ))
                    ->add("longitud", TextType::class, array(
                        'required' => false,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'mostrarDirNueva form-control'
                        ])
            );
//
            $formOptions = array(
                'entry_type' => UsuarioEventoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'label' => false,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                "mapped" => false,
                'attr' => array(
                    'class' => 'usuarios-collection',
                ),
                'by_reference' => false
            );

            $usuariosData = array();
            if ($eventoObject->getEventoRoles()) {
                foreach ($eventoObject->getEventoRoles() as $rol) {
                    //$rol->getUsuario()->setEventoRol("Rol dos");
                    $usuariosData[] = $rol->getUsuario();
                }
                $formOptions['data'] = $usuariosData;
            }
            $builder
                    ->add('usuarios', CollectionType::class, $formOptions)

            // ->add('eventoRoles')
            ;
        }
        if ($options['paso'] == 2) {

            $eventoObject = $builder->getData();


            $camposSelecionadoInscripcionPublica = array();
            $camposSelecionadoPreInscripcionPublica = array();
            $camposSelecionadoFormularioGanador = array();
            $camposSelecionadoFormularioRecambio = array();
            foreach ($eventoObject->getCampoFormularioEventos() as $campoFormulario) {
                if ($campoFormulario->getPertenece() == "Inscripcion Publica") {
                    array_push($camposSelecionadoInscripcionPublica, $campoFormulario->getCampoEvento());
                } else if ($campoFormulario->getPertenece() == "Pre-Inscripcion Publica") {
                    array_push($camposSelecionadoPreInscripcionPublica, $campoFormulario->getCampoEvento());
                } else if ($campoFormulario->getPertenece() == "Formulario Ganador") {
                    array_push($camposSelecionadoFormularioGanador, $campoFormulario->getCampoEvento());
                } else if ($campoFormulario->getPertenece() == "Formulario Recambio") {
                    array_push($camposSelecionadoFormularioRecambio, $campoFormulario->getCampoEvento());
                }
            }

            $builder
                    ->add('tieneInscripcionPublica', RadioType::class, array(
                        "required" => false,
                        "label" => "formulario_evento.labels.inscripcion.activarinscripcionpublica",
                        'attr' => array('class' => 'inscripcionPublica')
                    ))
                    ->add('tienePreinscripcionPublica', RadioType::class, array(
                        "required" => false,
                        "label" => "formulario_evento.labels.inscripcion.activarpreinscripcionpublica",
                        'attr' => array('class' => 'preinscripcionPublica')
                    ))
                    ->add('tieneFormularioGanador', CheckboxType::class, array(
                        "required" => false,
                        "label" => "formulario_evento.labels.inscripcion.activarformularioganador"
                    ))
                    ->add('tieneFormularioRecambios', CheckboxType::class, array(
                        "required" => false,
                        "label" => "formulario_evento.labels.inscripcion.activarformulariopararecambios"
                    ))
                    ->add('checkTieneInscripcionPublica', EntityType::class, array(
                        'class' => 'LogicBundle:CampoEvento',
                        'mapped' => false,
                        'data' => $camposSelecionadoInscripcionPublica,
                        'required' => false,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'classFormulario')
                    ))
                    ->add('checkTienePreinscripcionPublica', EntityType::class, array(
                        'class' => 'LogicBundle:CampoEvento',
                        'mapped' => false,
                        'data' => $camposSelecionadoPreInscripcionPublica,
                        'required' => false,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'classFormulario')
                    ))
                    ->add('checkTieneFormularioGanador', EntityType::class, array(
                        'class' => 'LogicBundle:CampoEvento',
                        'mapped' => false,
                        'data' => $camposSelecionadoFormularioGanador,
                        'required' => false,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'classFormulario')
                    ))
                    ->add('checkTieneFormularioRecambios', EntityType::class, array(
                        'class' => 'LogicBundle:CampoEvento',
                        'mapped' => false,
                        'data' => $camposSelecionadoFormularioRecambio,
                        'required' => false,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => true,
                        'attr' => array('class' => 'classFormulario')
                    ))
                    ->add('guardar', SubmitType::class, array(
                        'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde')))
            ;
        }
        if ($options['paso'] == 3) {
            $builder
                    ->add('equipoEventos')
                    ->add('jugadorEventos')
            ;
        }

        if ($options['paso'] == 4) {

            $builder

            ;
        }
        //en realidad es el paso 2 pero se pone al final por que no estaba presente al momento de desarrollar la logica
        if ($options['paso'] == 5) {
            $builder->add('carne', CollectionType::class, [
                'label' => 'label.carne',
                'entry_type' => CarneType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'required' => true,
                'prototype' => true,
                'attr' => array(
                    'class' => 'carne-collection',
                ),
                'by_reference' => false,
                'entry_options' => array(
                    'evento' => $builder->getData(),
                )]
            );
        }
        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Evento',
            'paso' => null,
            'personaporsexo' => null,
            'limitanteporedad' => null,
            'em' => null,
            'eventoroles' => null,
            'subcategorias' => null
        ));
    }

}
