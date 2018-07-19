<?php

namespace Application\Sonata\UserBundle\Form;

use AdminBundle\Form\EventListener\Usuario\AddDiscapacidadFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddSubDiscapacidadFieldSubscriber;
use AdminBundle\Form\InformacionExtraUsuarioType;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\PreinscripcionOferta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use LogicBundle\Form\DireccionType;
use LogicBundle\Form\ComunaType;

class InfoComplementariaUserType extends AbstractType {

    protected $container;
    protected $em;
    protected $noVacio;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $oferta = $options['oferta'];
        $usuario = $options['usuario'];
        $em = $options['em'];
        $container = $options['container'];
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];
        $tieneDiscapacidad = $builder->getData()->getSubDiscapacidad() ? true : false;
        $esDesplazado = $builder->getData()->getTipoDesplazado() ? true : false;

        $opciones = $this->getOpciones();

        $dataDireccionOComuna = null;
        $usuarioObject = $builder->getData();
        if ($usuarioObject->getId()) {
            if ($usuarioObject->getBarrio()) {
                $qb = $em->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
                $qb
                        ->join('m.barrios', 'b')
                        ->andWhere('b.esVereda = :es_vereda')
                        ->andWhere('m.id = :municipio')
                        ->setParameter('es_vereda', true)
                        ->setParameter('municipio', $usuarioObject->getBarrio()->getMunicipio()->getId())
                ;
                $query = $qb->getQuery();
                $municipioObject = $query->getOneOrNullResult();
                if ($municipioObject && $usuarioObject->getBarrio()->getEsVereda()) {
                    $dataDireccionOComuna = User::COMUNA;
                } elseif ($municipioObject) {
                    $dataDireccionOComuna = User::DIRECCION;
                }
            }
        }
        $builder
                ->add('email', EmailType::class, array(
                    'label' => 'formulario_registro.contacto.correo_electronico',
                    "required" => true,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Email()
                    ],
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
                    'empty_data' => false,
                ))
                ->add('phone', null, [
                    "label" => "formulario_registro.contacto.telefono_movil",
                    "required" => true,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
                    "constraints" => $this->noVacio,
                        ]
                )
                ->add('orientacionSexual', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.orientacion_sexual',
                    "required" => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('estrato', EntityType::class, array(
                    'class' => 'LogicBundle:Estrato',
                    "constraints" => $this->noVacio,
                    'label' => 'formulario_registro.pnatural_informacion.estrato',
                    'mapped' => true
                ))
                ->add('dateOfBirth', DateType::class, $opciones["fechaNacimiento"])
                ->add('municipio', EntityType::class, array(
                    "required" => true,
                    'class' => 'LogicBundle:Municipio',
                    "constraints" => $this->noVacio,
                    'label' => 'formulario_registro.contacto.municipio',
                    'mapped' => false
                ))
                ->add('barrio', null, array('label' => 'formulario_registro.contacto.barrio'))
                ->add('direccionOcomuna', ChoiceType::class, [
                    'data' => $dataDireccionOComuna,
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
                ->addEventSubscriber(new AddMunicipioFieldSubscriber("barrio"))
                ->addEventSubscriber(new AddBarrioFieldSubscriber(false, true, "", $options['request']))
                ->add('eps', EntityType::class, array(
                    "class" => "LogicBundle:Eps",
                    "label" => "formulario.info_adicional.eps",
                    "required" => true,
                    "constraints" => $this->noVacio,
                    'placeholder' => '',
                    'empty_data' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                    "required" => true,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
                ))
                ->add('gender', ChoiceType::class, array(
                    'label' => 'formulario_registro.pnatural_informacion.genero',
                    'required' => true,
                    'choices' => User::GENDERS,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
                ))
                ->add('etnia', EntityType::class, array(
                    "class" => "LogicBundle:Etnia",
                    "label" => "titulo.etnia",
                    "required" => true,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
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
                    "required" => true,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
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
                    "required" => true,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
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
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    if ($form->get('esDesplazado')->getData() && !$form->get('tipoDesplazado')->getData()) {
                        $form->get('tipoDesplazado')->addError(new FormError('error.no_vacio'));
                    }
                    if ($form->get('esDiscapacitado')->getData() && !$form->get('subDiscapacidad')->getData()) {
                        $form->get('subDiscapacidad')->addError(new FormError('error.no_vacio'));
                    }
                });

        $formModifier = function ($form, $data) use ($em) {
            $nivelEscolaridad = $data["nivelEscolaridad"];
            if ($nivelEscolaridad) {
                $nivelEscolaridad = $em->getRepository("LogicBundle:NivelEscolaridad")->find($nivelEscolaridad);
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
    }

    public function getOpciones() {
        return [
            "tipoEstablecimientoEducativo" => [
                "class" => "LogicBundle:TipoEstablecimientoEducativo",
                "label" => "formulario.info_adicional.tipo_establecimiento_educativo",
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
                "required" => true,
                "constraints" => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio'])
                ],
            ],
            "establecimientoEducativo" => [
                "class" => "LogicBundle:EstablecimientoEducativo",
                "label" => "formulario.info_adicional.establecimiento_educativo",
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
                "required" => true,
                "constraints" => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio'])
                ],
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'oferta' => null,
            'usuario' => null,
            'em' => null,
            'container' => null,
            'request' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'info_complementaria';
    }

}
