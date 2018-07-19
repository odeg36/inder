<?php

namespace Application\Sonata\UserBundle\Form;

use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddComunaFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use AdminBundle\Validator\Constraints\ConstraintsUsuarioUnico;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Entity\TipoIdentificacion;
use LogicBundle\Entity\TipoPersona;
use LogicBundle\Form\ComunaType;
use LogicBundle\Form\DireccionType;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    protected static $genero = [
        'gender_female' => UserInterface::GENDER_FEMALE,
        'gender_male' => UserInterface::GENDER_MALE
    ];
    protected static $tiposociedad = [
        "titulo.persona.natural" => TipoPersona::N,
        "titulo.persona.deprotivo" => TipoPersona::D
    ];
    protected $notBlank;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->notBlank = array(
            new NotBlank(['message' => 'formulario_registro.no_vacio'])
        );
        $this->configuracion = $this->configuracionCampos($options);

        $builder
                ->add('tipopersona', ChoiceType::class, $this->configuracion["tipopersona"])
                ->add('tipoidentificacion', EntityType::class, $this->configuracion["tipoidentificacion"])
                ->add('numeroidentificacion', null, $this->configuracion["numeroidentificacion"])
                ->add('firstname', null, $this->configuracion["firstname"])
                ->add('lastname', null, $this->configuracion["lastname"])
                ->add('gender', ChoiceType::class, $this->configuracion["gender"])
                ->add('dateOfBirth', DateMaskType::class, $this->configuracion["dateOfBirth"])
                ->add('password', RepeatedType::class, $this->configuracion["password"])
                ->addEventSubscriber(new AddMunicipioFieldSubscriber())
                ->addEventSubscriber(new AddBarrioFieldSubscriber())
                ->add('direccionOcomuna', ChoiceType::class, $this->configuracion["direccionOcomuna"])
                ->add('direccion_format', DireccionType::class, $this->configuracion["direccion_format"])
                ->add('comuna_format', ComunaType::class, $this->configuracion["comuna_format"])
                ->add('direccionresidencia', TextType::class, $this->configuracion["direccionresidencia"])
                ->add('direccionComuna', TextType::class, $this->configuracion["direccionComuna"])
                ->add('email', EmailType::class, $this->configuracion["email"])
                ->add('phone', null, $this->configuracion["phone"])
                ->add('tipoentidad', EntityType::class, $this->configuracion["tipoentidad"])
                ->add('organizaciondeportiva', TextType::class, $this->configuracion["organizaciondeportiva"])
                ->add('estrato', EntityType::class, $this->configuracion["estrato"])
                ->add('aceptahb', CheckboxType::class, $this->configuracion["aceptahb"])
                ->add('aceptatc', CheckboxType::class, $this->configuracion["aceptatc"])
        ;

        $formModifier = function ($form, $data) {
            $tipoPersona = $data["tipopersona"];
            $validaciones = $this->configuracion;

            $validaciones["tipoidentificacion"]["constraints"] = $this->notBlank;
            $validaciones["password"]["constraints"] = [
                new NotBlank(['message' => 'formulario_registro.no_vacio']),
                new Regex([
                    'message' => 'error.clave.regex',
                    'pattern' => '/^(?=.*[A-Za-z])(?=.*\d).{6,}$/' // Valida numeros, letras y numero caracteres
                        ])
            ];
            $validaciones["aceptatc"]["constraints"] = [
                new NotBlank(['message' => 'formulario_registro.error.acepta.tc']),
            ];
            $validaciones["estrato"]["constraints"] = $this->notBlank;
            if (key_exists("direccionOcomuna", $data)) {
                if (User::COMUNA == $data["direccionOcomuna"]) {
                    $validaciones["direccionComuna"]["constraints"] = $this->notBlank;
                    $validaciones["comuna_format"]["required"] = true;
                    $form
                            ->add('direccionComuna', TextType::class, $validaciones["direccionComuna"])
                            ->add('comuna_format', ComunaType::class, $validaciones["comuna_format"]);
                } else if (User::DIRECCION == $data["direccionOcomuna"]) {
                    $validaciones["direccionresidencia"]["constraints"] = $this->notBlank;
                    $validaciones["direccion_format"]["required"] = true;
                    $form
                            ->add('direccionresidencia', TextType::class, $validaciones["direccionresidencia"])
                            ->add('direccion_format', DireccionType::class, $validaciones["direccion_format"]);
                }
            }

            $form
                    ->add('tipoidentificacion', EntityType::class, $validaciones["tipoidentificacion"])
                    ->add('password', RepeatedType::class, $validaciones["password"])
                    ->add('aceptatc', CheckboxType::class, $validaciones["aceptatc"]);
            $tipoIdentificacionId = $data['tipoidentificacion'];
            if ($tipoIdentificacionId) {
                $tipoIdentificacion = $this->container->get('doctrine')->getManager()->getRepository('LogicBundle:TipoIdentificacion')->find($tipoIdentificacionId);
                if ($tipoIdentificacion->getAbreviatura() == TipoIdentificacion::CE) {
                    $validaciones["numeroidentificacion"]["constraints"] = [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Assert\Regex([
                            'pattern' => '/^[a-zA-Z]*[0-9][a-zA-Z0-9]*$/' //Valida que solo sean números o números y letas pero no solo letras
                                ])
                    ];
                    $form
                            ->add('numeroidentificacion', null, $validaciones["numeroidentificacion"]);
                }
            }
            if ($tipoPersona == TipoPersona::N) {
                $validaciones = $this->validacionNatural($validaciones);
                $form
                        ->add('firstname', null, $validaciones["firstname"])
                        ->add('lastname', null, $validaciones["lastname"])
                        ->add('gender', ChoiceType::class, $validaciones["gender"])
                        ->add('estrato', EntityType::class, $validaciones["estrato"])
                        ->add('email', EmailType::class, $validaciones["email"])
                        ->add('dateOfBirth', DateMaskType::class, $validaciones["dateOfBirth"]);
            } else if ($tipoPersona == TipoPersona::D) {
                $validaciones = $this->validacionJuridica($validaciones);
                $form
                        ->add('organizaciondeportiva', TextType::class, $validaciones["organizaciondeportiva"])
                        ->add('tipoentidad', EntityType::class, $validaciones["tipoentidad"])
                        ->add('email', EmailType::class, $validaciones["email"]);
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $usuario = $event->getData();

                $formModifier($event->getForm(), $usuario);
            }
        });
    }

    public function validacionNatural($validaciones) {
        $validaciones["firstname"]["constraints"] = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
            new Assert\Regex([
                'message' => 'error.no_numeros',
                'pattern' => '/^([^0-9]*)$/' //Valida que seolo se ingresen letas
                    ])
        ];
        $validaciones["lastname"]["constraints"] = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
            new Assert\Regex([
                'message' => 'error.no_numeros',
                'pattern' => '/^([^0-9]*)$/' //Valida que seolo se ingresen letas
                    ])
        ];

        $validaciones["dateOfBirth"]["constraints"] = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
            new Assert\LessThan("-1 years"),
            new Assert\GreaterThan("-130 years")
        ];
        $validaciones["gender"]["constraints"] = [
            new NotBlank(['message' => 'formulario_registro.no_vacio'])
        ];

        return $validaciones;
    }

    public function validacionJuridica($validaciones) {
        $validaciones["organizaciondeportiva"]["constraints"] = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
            new Assert\Regex([
                'pattern' => '/\D+/' // Valida que exista letas en la cadena.
                    ])
        ];
        $validaciones["tipoentidad"]["constraints"] = $this->notBlank;

        return $validaciones;
    }

    public function configuracionCampos($options) {
        return [
            "tipopersona" => [
                "label" => "formulario_registro.tipo_persona",
                "required" => true,
                "constraints" => $this->notBlank,
                'placeholder' => '',
                'empty_data' => false,
                'empty_data' => null,
                'choices' => self::$tiposociedad,
                'attr' => [
                    'onchange' => 'inder.formulario.cambiaTipoPersona(this)'
                ]
            ],
            "tipoidentificacion" => [
                "constraints" => $this->notBlank,
                'class' => 'LogicBundle:TipoIdentificacion',
                'empty_data' => false,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                },
                'placeholder' => '',
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
                        'min' => 7,
                        'max' => 10,
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
                    'oninput' => 'inder.formulario.buscarUsuario()'
                ]
            ],
            "firstname" => [
                "label" => "formulario_registro.pnatural_informacion.nombre",
                "required" => true,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off']
            ],
            "lastname" => [
                "label" => "formulario_registro.pnatural_informacion.apellido",
                "required" => true,
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off']
            ],
            "gender" => [
                "label" => "formulario_registro.pnatural_informacion.genero",
                "required" => true,
                'choices' => self::$genero,
                'placeholder' => '',
            ],
            "orientacionSexual" => [
                'label' => 'formulario_registro.pnatural_informacion.orientacion_sexual',
                'required' => false
            ],
            "dateOfBirth" => [
                "label" => "formulario_registro.pnatural_informacion.fecha_nacimiento",
                "required" => true,
                'mask-alias' => 'dd/mm/yyyy',
                'placeholder' => 'dd/mm/yyyy',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ]
            ],
            "password" => [
                "constraints" => $this->notBlank,
                'type' => 'password',
                'invalid_message' => 'formulario_registro.claves_deben_coincidir',
                'required' => true,
                'options' => ['label' => 'Confirmar clave', 'attr' => ['class' => 'form-control claveu', 'autocomplete' => 'off']],
                'first_options' => ['label' => 'Clave', 'attr' => ['class' => 'form-control claved', 'autocomplete' => 'off']]
            ],
            "direccionOcomuna" => [
                'mapped' => false,
                'required' => false,
                'placeholder' => false,
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
                'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
            ],
            "direccion_format" => [
                'mapped' => false,
                'object' => $options,
                'formularioPadre' => '#direccion',
                "required" => false,
                'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
            ],
            "direccionresidencia" => [
                'label' => 'formulario_registro.contacto.direccion_confirmar',
                'required' => true,
                'attr' => array('readonly' => true, 'class' => 'form-control'),
            ],
            "comuna_format" => [
                'label_attr' => [
                    'class' => 'required'
                ],
                'formularioPadre' => '#comuna',
                'mapped' => false,
                'object' => $options,
                "required" => false,
                'label' => 'formulario.corregimiento'
            ],
            "direccionComuna" => [
                'label' => 'formulario_registro.contacto.direccion_confirmar',
                'required' => true,
                'attr' => array('readonly' => true, 'class' => 'form-control'),
            ],
            "email" => [
                "label" => "formulario_registro.contacto.correo_electronico",
                "required" => true,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off', 'type' => 'email'),
                'constraints' => [
                    new NotBlank(['message' => 'formulario_registro.no_vacio']),
                    new Assert\Email()
                ]
            ],
            "phone" => [
                "label" => "formulario_registro.contacto.telefono_movil",
                "required" => false,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
            ],
            "tipoentidad" => [
                'label' => 'formulario_registro.pjuridica_informacion.tipo_entidad',
                'class' => 'LogicBundle:TipoEntidad',
                'mapped' => false,
                'empty_data' => false,
                'empty_data' => null,
                'placeholder' => '',
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off', "required" => true)
            ],
            "estrato" => [
                'placeholder' => ' ',
                'label' => 'formulario_registro.pnatural_informacion.estrato',
                'class' => 'LogicBundle:Estrato',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'required' => false
                ]
            ],
            "etnia" => [
                'placeholder' => ' ',
                'label' => 'formulario_registro.pnatural_informacion.etnia',
                'class' => 'LogicBundle:Etnia',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'required' => false
                ]
            ],
            "tipoClub" => [
                'label' => 'formulario_registro.pjuridica_informacion.tipo_club',
                'class' => 'LogicBundle:TipoClub',
                "mapped" => false,
                'empty_data' => false,
                'empty_data' => null,
                'placeholder' => '',
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off', "required" => true)
            ],
            "organizaciondeportiva" => [
                "label" => "formulario_registro.pjuridica_informacion.razon_social",
                "required" => true,
                "mapped" => false,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
            ],
            "aceptahb" => [
                'required' => false,
            ],
            "aceptatc" => [
                'required' => true,
                "mapped" => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
            'object' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'formulario_registro';
    }

}
