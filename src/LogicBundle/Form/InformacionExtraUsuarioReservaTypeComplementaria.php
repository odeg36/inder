<?php

namespace Application\Sonata\UserBundle\Form;

use AdminBundle\Form\EventListener\Usuario\AddDiscapacidadFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddSubDiscapacidadFieldSubscriber;
use AdminBundle\Form\InformacionExtraUsuarioType;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Entity\PreinscripcionOferta;
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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformacionExtraUsuarioReservaTypeComplementaria extends AbstractType {

    protected $container;
    protected $em;
    protected $noVacio;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $oferta = $options['oferta'];
        $em = $options['em'];
        $container = $options['container'];
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];
        $tieneDiscapacidad = $builder->getData()->getSubDiscapacidad() ? true : false;
        $esDesplazado = $builder->getData()->getTipoDesplazado() ? true : false;

        $opciones = $this->getOpciones();

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
                    "required" => false
                ))
                ->add('eps', EntityType::class, array(
                    "class" => "LogicBundle:Eps",
                    "label" => "formulario.info_adicional.eps",
                    "required" => true,
                    "constraints" => $this->noVacio,
                    'placeholder' => '',
                    'empty_data' => false,
                ))
                ->add('nivelEscolaridad', EntityType::class, array(
                    "class" => "LogicBundle:NivelEscolaridad",
                    "label" => "formulario.info_adicional.nivel_escolaridad",
                    "required" => true,
                    "constraints" => $this->noVacio,
                    'empty_data' => 0,
                    'attr' => [
                        'onchange' => 'inder.preinscripcion.nivelEscolaridad(this)',
                        'class' => 'nivel-escolaridad'
                    ]
                ))
                ->add('tipoEstablecimientoEducativo', EntityType::class, $opciones["tipoEstablecimientoEducativo"])
                ->add('establecimientoEducativo', EntityType::class, $opciones["establecimientoEducativo"])
                ->add('ocupacion', EntityType::class, array(
                    "class" => "LogicBundle:Ocupacion",
                    "label" => "formulario.info_adicional.ocupacion",
                    "required" => true,
                    "constraints" => $this->noVacio,
                    'placeholder' => '',
                    'empty_data' => false,
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
    }

    public function getOpciones() {
        return [
            "tipoEstablecimientoEducativo" => [
                "class" => "LogicBundle:TipoEstablecimientoEducativo",
                "label" => "formulario.info_adicional.tipo_establecimiento_educativo",
                "required" => true,
                'placeholder' => '',
                'empty_data' => true,
                'attr' => [
                    'onchange' => 'inder.preinscripcion.tipoEstablecimientoEducativo(this)',
                    'class' => 'tipo-establecimiento-educativo'
                ],
                "constraints" => $this->noVacio,
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                }
            ],
            "establecimientoEducativo" => [
                "class" => "LogicBundle:EstablecimientoEducativo",
                "label" => "formulario.info_adicional.establecimiento_educativo",
                "required" => true,
                'placeholder' => '',
                'empty_data' => true,
                'attr' => [
                    'class' => 'establecimiento-educativo'
                ],
                "constraints" => $this->noVacio,
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'oferta' => null,
            'em' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'info_complementaria';
    }

}
