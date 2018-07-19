<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use LogicBundle\Form\UsuarioType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use AdminBundle\Form\EventListener\Usuario\AddDiscapacidadFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddSubDiscapacidadFieldSubscriber;

use Symfony\Component\Validator\Constraints as Assert;

class InformacionExtraUsuarioReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );

        $noVacioNumber = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            )),
            new Assert\Range(array(
                'min'        => 0 ,
                'max'        => 2147483647,
                'maxMessage' => 'error.estrategia.valor_max',
            ))
        );

        $opcion = [
            'data_class' => null,            
            'constraints' => [
                new File([
                    'maxSize' => '1024K',
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ]
                ])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
            ],
            "required" => true,
            "constraints" => $noVacio,
        ];
        
        $object = $builder->getData();
        $em =  $options['em'];

        $tieneDiscapacidad = $builder->getData()->getSubDiscapacidad() ? true : false;
        $esDesplazado = $builder->getData()->getTipoDesplazado() ? true : false;
        if ($options['paso'] == 41) {
            
            $opcion = [
                'data_class' => null,            
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            "image/jpeg", "image/jpg", "image/png"
                        ]])
                ],
                'attr' => [
                    "class" => "file",
                    "data-show-upload" => "false",
                    "data-show-caption" => "true",
                    "data-msg-placeholder" => "Selecciona una foto para subir"
                ],
                "required" => true,
                "constraints" => $noVacio,
            ];

            $opciones = $this->getOpciones();
            $readonly = true;
            $usuario = $builder->getData();
            if(!$usuario->getEmail()){
                $readonly=false;
            }
            $builder
                ->add('email', EmailType::class, array(
                    'label' => 'formulario_registro.contacto.correo_electronico',
                    "required" => true,
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Email()
                    ],
                    'attr' => array('class' => 'form-control',"readonly" => $readonly, 'autocomplete' => 'off'),
                    'empty_data' => false,
                ))
                ->add('phone', null, [
                    "label" => "formulario_registro.contacto.telefono_movil",
                    "required" => true,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
                    "constraints" => $noVacio,
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
                    "constraints" => $noVacio,
                    'placeholder' => '',
                    'empty_data' => false,
                ))
                ->add('nivelEscolaridad', EntityType::class, array(
                    "class" => "LogicBundle:NivelEscolaridad",
                    "label" => "formulario.info_adicional.nivel_escolaridad",
                    "required" => true,
                    "constraints" => $noVacio,
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
                    "constraints" => $noVacio,
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
                ));      
        }


        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_registro.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;

        $formModifier = function ($form, $data) {

        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $reserva = $event->getData();

                $formModifier($event->getForm(), $reserva);
            }
        });
    }

    public function getOpciones() {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
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
                "constraints" => $noVacio,
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
                "constraints" => $noVacio,
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
            'paso' => null,
            'id' => null,
            'em' => null,
            'isSuperAdminOrganismoDeportivo' => null,
            'organizacionDeporId' => null,
            'isGestorEscenario' => null,
            'ROLE_ORGANISMO_DEPORTIVO' => null
        ));
    }

}
