<?php

namespace LogicBundle\Form;

use AdminBundle\Form\EventListener\Usuario\AddDiscapacidadFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddSubDiscapacidadFieldSubscriber;
use AdminBundle\Form\InformacionExtraUsuarioType;
use AdminBundle\Validator\Constraints\ConstraintsUsuarioUnico;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\PreinscripcionOferta;
use LogicBundle\Entity\TipoIdentificacion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CargaUsuarioNuevoPreinscripcionType extends AbstractType {

    protected $container;
    protected $em;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    protected $notBlank;
    protected $noVacio;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $oferta = $options['oferta'];
        $usuario = $options['usuario'];
        $em = $options['em'];
        $container = $options['container'];
        $this->configuracion = $this->configuracionCampos($options);
        $this->notBlank = array(
            new NotBlank(['message' => 'formulario_registro.no_vacio'])
        );
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];
        $tieneDiscapacidad = $builder->getData()->getSubDiscapacidad() ? true : false;
        $esDesplazado = $builder->getData()->getTipoDesplazado() ? true : false;

        $opciones = $this->getOpciones();

        $dataDireccionOComuna = null;

        $builder
                ->add('firstname', null, $this->configuracion["firstname"])
                ->add('lastname', null, $this->configuracion["lastname"])
                ->add('estrato', null)
                ->add('municipio', EntityType::class, array(
                    'class' => 'LogicBundle:Municipio',
                    'label' => 'formulario_registro.contacto.municipio',
                    'mapped' => false
                ))
                ->add('dateOfBirth', DateType::class, $this->configuracion["fechaNacimiento"])
                ->add('barrio', null, array('label' => 'formulario_registro.contacto.barrio'))
                ->add('direccionOcomuna', ChoiceType::class, [
                    'mapped' => false,
                    'required' => false,
                    'choices' => [
                        'formulario.vereda' => User::COMUNA,
                        'formulario.barrio' => User::DIRECCION
                    ],
                    'choice_attr' => function($val, $key, $index) {
                        return [
                            'class' => 'choice-direcion-type', 'choice-key' => $index
                        ];
                    },
                    'multiple' => false,
                    'expanded' => true,
                    'placeholder' => false,
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
                ])
                ->add('direccion_creado', DireccionType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'formularioPadre' => '.direccionResidencia',
                    'attr' => array(
                        'class' => 'campoDireccionOferta fondoDireccion col-md-12',
                    ),
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                ))
                ->add('direccionResidencia', null, array(
                    'label' => 'formulario_registro.contacto.direccion_residencia',
                    'attr' => [
                        'class' => 'direccionResidencia',
                        'readonly' => true
                    ]
                ))
                ->add('comuna_format', ComunaType::class, [
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    'formularioPadre' => '.direccionComuna',
                    'mapped' => false,
                    "required" => false,
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                ])
                ->add('direccionComuna', TextType::class, [
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                    'required' => true,
                    'attr' => array(
                        'readonly' => true,
                        'class' => 'form-control direccionComuna'
                    )
                ])
                ->add('tipoidentificacion', EntityType::class, $this->configuracion["tipoidentificacion"])
                ->add('numeroidentificacion', null, $this->configuracion["numeroidentificacion"])
                ->add('activo', CheckboxType::class, [
                    'label' => 'formulario.preinscripcion.inscrito',
                    "mapped" => false,
                    'required' => false,
                    'data' => true,
                    'attr' => array(
                        'class' => 'form-control iCheck-helper icheckbox_square-blue',
                    )
                ])->add('email', EmailType::class, array(
                    'label' => 'formulario_registro.contacto.correo_electronico',
                    "required" => false,
                    "constraints" => [
                        new Email()
                    ],
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
                ))
                ->add('phone', null, [
                    "label" => "formulario_registro.contacto.telefono_movil",
                    "required" => false,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
                        ]
                )
                ->add('orientacionSexual', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.orientacion_sexual',
                    "required" => false,
                    'placeholder' => '',
                ))
                ->add('eps', EntityType::class, array(
                    "class" => "LogicBundle:Eps",
                    "label" => "formulario.info_adicional.eps",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('gender', ChoiceType::class, array(
                    'label' => 'formulario_registro.pnatural_informacion.genero',
                    'required' => false,
                    'choices' => User::GENDERS,
                ))
                ->add('etnia', EntityType::class, array(
                    "class" => "LogicBundle:Etnia",
                    "label" => "titulo.etnia",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('nivelEscolaridad', EntityType::class, array(
                    "class" => "LogicBundle:NivelEscolaridad",
                    "label" => "formulario.info_adicional.nivel_escolaridad",
                    "required" => false,
                    'attr' => [
                        'onchange' => 'inder.preinscripcion.nivelEscolaridad(this)',
                        'class' => 'nivel-escolaridad'
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('tipoEstablecimientoEducativo', EntityType::class, $opciones["tipoEstablecimientoEducativo"])
                ->add('establecimientoEducativo', EntityType::class, $opciones["establecimientoEducativo"])
                ->add('ocupacion', EntityType::class, array(
                    "class" => "LogicBundle:Ocupacion",
                    "label" => "formulario.info_adicional.ocupacion",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('esDesplazado', CheckboxType::class, array(
                    "mapped" => false,
                    "required" => false,
                    "data" => $esDesplazado,
                    "label" => "formulario.info_adicional.desplazado"
                ))
                ->add('tipoDesplazado', EntityType::class, array(
                    "class" => "LogicBundle:TipoDesplazado",
                    "label" => "formulario.info_adicional.tipo_desplazado",
                    "required" => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('esDiscapacitado', CheckboxType::class, array(
                    "mapped" => false,
                    "required" => false,
                    "data" => $tieneDiscapacidad,
                    "label" => "formulario.info_adicional.discapacidad"
                ))
                ->addEventSubscriber(new AddDiscapacidadFieldSubscriber())
                ->addEventSubscriber(new AddSubDiscapacidadFieldSubscriber())
                ->add('esJefeCabezaHogar', CheckboxType::class, array(
                    "required" => false,
                    "label" => "formulario.info_adicional.es_jefe_cabeza_hogar",
                ))
                ->add('informacionExtraUsuario', InformacionExtraUsuarioType::class, [
                    'usuario' => $usuario,
                    'oferta' => $oferta,
                    'em' => $em,
                    'container' => $container
                ])
                ->addEventSubscriber(new AddMunicipioFieldSubscriber("barrio"))
                ->addEventSubscriber(new AddBarrioFieldSubscriber(false, true, "", $options['request']))
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    if ($form->get('esDesplazado')->getData() && !$form->get('tipoDesplazado')->getData()) {
                        $form->get('tipoDesplazado')->addError(new FormError('error.no_vacio'));
                    }
                    if ($form->get('esDiscapacitado')->getData() && !$form->get('subDiscapacidad')->getData()) {
                        $form->get('subDiscapacidad')->addError(new FormError('error.no_vacio'));
                    }
                });

        $formModifier = function ($form, $data) {
            $nivelEscolaridad = $data["nivelEscolaridad"];
            if ($nivelEscolaridad) {
                $nivelEscolaridad = $this->em->getRepository("LogicBundle:NivelEscolaridad")->findOneById($nivelEscolaridad);
                if ($nivelEscolaridad) {
                    if (strtoupper(PreinscripcionOferta::NO_DEFINIDO) == strtoupper($nivelEscolaridad->getNombre())) {
                        $opciones = $this->getOpciones();
                        $opciones["tipoEstablecimientoEducativo"]["required"] = false;
                        $opciones["establecimientoEducativo"]["required"] = false;
                        $opciones["tipoEstablecimientoEducativo"]["constraints"] = [];
                        $opciones["establecimientoEducativo"]["constraints"] = [];

                        $form
                                ->add('tipoEstablecimientoEducativo', EntityType::class, $opciones["tipoEstablecimientoEducativo"])
                                ->add('establecimientoEducativo', EntityType::class, $opciones["establecimientoEducativo"]);
                    }
                }
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $usuario = $event->getData();

                $formModifier($event->getForm(), $usuario);
            }
        });

        $formModifierIdentificacion = function ($form, $data) {
            $validaciones = $this->configuracion;
            $validaciones["tipoidentificacion"]["constraints"] = $this->notBlank;
            $form
                    ->add('tipoidentificacion', EntityType::class, $validaciones["tipoidentificacion"]);
            $tipoIdentificacionId = $data['tipoidentificacion'];
            if ($tipoIdentificacionId) {
                $tipoIdentificacion = $this->container->get('doctrine')->getManager()->getRepository('LogicBundle:TipoIdentificacion')->find($tipoIdentificacionId);
                if ($tipoIdentificacion->getAbreviatura() == TipoIdentificacion::CE) {
                    $validaciones["numeroidentificacion"]["constraints"] = [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Assert\Length(array(
                            'min' => 5,
                            'max' => 11,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                )),
                        new ConstraintsUsuarioUnico(),
                        new Assert\Regex([
                            'pattern' => '/^[a-zA-Z]*[0-9][a-zA-Z0-9]*$/' //Valida que solo sean números o números y letas pero no solo letras
                                ])
                    ];
                    $form
                            ->add('numeroidentificacion', null, $validaciones["numeroidentificacion"]);
                }
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifierIdentificacion) {
            if (null != $event->getData()) {
                $usuario = $event->getData();
                $formModifierIdentificacion($event->getForm(), $usuario);
            }
        });
    }

    public function configuracionCampos($options) {
        return [
            "tipoidentificacion" => [
                "constraints" => $this->notBlank,
                'class' => 'LogicBundle:TipoIdentificacion',
                'placeholder' => '',
                'empty_data' => false,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                },
                'choice_attr' => function($val, $key, $index) {
                    return ['tipo' => $val->getAbreviatura()];
                },
                'label' => 'formulario_registro.pnatural_informacion.tipo_documento',
                'empty_data' => null,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off']
            ],
            "numeroidentificacion" => [
                "label" => "formulario_registro.pnatural_informacion.numero_identificacion",
                "required" => true,
                "constraints" => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio']),
                    new Assert\Length(array(
                        'min' => 5,
                        'max' => 11,
                        'minMessage' => 'formulario.validar.limite.minimo',
                        'maxMessage' => 'formulario.validar.limite.maximo',
                            )),
                    new ConstraintsUsuarioUnico(),
                    new Assert\Regex([
                        'message' => 'error.solo_numeros',
                        'pattern' => '/^[0-9]+$/' //Valida que solo sean números
                            ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                ]
            ],
            "firstname" => [
                "label" => "formulario_registro.pnatural_informacion.nombre",
                "required" => true,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio']),
                    new Assert\Regex([
                        'message' => 'error.no_numeros',
                        'pattern' => '/^([^0-9]*)$/' //Valida que seolo se ingresen letas
                            ])
                ]
            ],
            "lastname" => [
                "label" => "formulario_registro.pnatural_informacion.apellido",
                "required" => true,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio']),
                    new Assert\Regex([
                        'message' => 'error.no_numeros',
                        'pattern' => '/^([^0-9]*)$/' //Valida que seolo se ingresen letas
                            ])
                ]
            ],
            "fechaNacimiento" => [
                "label" => "formulario_registro.pnatural_informacion.fecha_nacimiento",
                "required" => true,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio']),
                ],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array('class' => 'form-control'),
            ],
        ];
    }

    public function getOpciones() {
        return [
            "tipoEstablecimientoEducativo" => [
                "class" => "LogicBundle:TipoEstablecimientoEducativo",
                "label" => "formulario.info_adicional.tipo_establecimiento_educativo",
                "required" => false,
                'placeholder' => '',
                'attr' => [
                    'onchange' => 'inder.preinscripcion.tipoEstablecimientoEducativo(this)',
                    'class' => 'tipo-establecimiento-educativo'
                ],
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                    ->orderBy('u.nombre', 'ASC');
                },
            ],
            "establecimientoEducativo" => [
                "class" => "LogicBundle:EstablecimientoEducativo",
                "label" => "formulario.info_adicional.establecimiento_educativo",
                "required" => false,
                'placeholder' => '',
                'attr' => [
                    'class' => 'establecimiento-educativo'
                ],
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                    ->orderBy('u.nombre', 'ASC');
                },
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'usuario' => null,
            'oferta' => null,
            'em' => null,
            'container' => null,
            'request' => null
        ));
    }

}
