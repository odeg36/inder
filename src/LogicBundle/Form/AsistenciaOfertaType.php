<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\Oferta;
use LogicBundle\Utils\BuscarFechas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class AsistenciaOfertaType extends AbstractType {

    protected $oferta;
    protected $em;
    protected $configuracion;
    protected $noVacio;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->oferta = $builder->getData();
        $this->em = $options['em'];
        $container = $options['container'];
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];

        $diasArray = $this->getDias();
        $this->configuracion = $this->getOpciones();

        $builder
                ->add('oferta', HiddenType::class, [
                    'mapped' => false,
                    'data' => $this->oferta->getId()
                ])
                ->add('dias_semana', ChoiceType::class, [
                    'label' => 'Selecciona el horario',
                    'choices' => $diasArray,
                    'multiple' => false,
                    'expanded' => true,
                    'mapped' => false,
                    'choice_attr' => function() {
                        return [
                            'class' => 'dias_semana_programacion checkbox-asistencia',
                            'onclick' => 'inder.asistencia.checkSeleccionHorario(this)'
                        ];
                    },
                    'constraints' => $this->noVacio
                ])
                ->add('seleccionar_todo', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'formulario.asistio.todos',
                    'required' => false,
                    'attr' => [
                        'onclick' => 'inder.asistencia.selectIfChecked(this)',
                        'class' => 'asistio_todos checkbox-asistencia'
                    ]
                ])
                ->add('seleccion_dia_unico', ChoiceType::class, $this->configuracion['seleccion_dia_unico'])
                ->add('usuariosAsistentes', TextareaType::class, [
                    'mapped' => false,
                    'attr' => [
                        'class' => 'hidden usuariosAsistentes'
                    ],
                    'constraints' => [
                        new NotNull([
                            'message' => 'error.oferta.sin.usuarios'
                        ])
                    ]
                ]);
                    

        $formModifier = function ($form, $data) {
            $oferta = $form->getData();
            $dia = 0;
            $fecha = 0;
            if (key_exists('seleccion_dia_unico', $data)) {
                $fecha = $data['seleccion_dia_unico'];
            }
            
            if (key_exists('dias_semana', $data)) {
                $dia = $data['dias_semana'];
            }
            $tipoRegistro = '';
            if (key_exists('radioOpciones', $data)) {
                $tipoRegistro = $data['radioOpciones'];
            }
            $programacion = $this->em->getRepository('LogicBundle:Programacion')->find($dia);
            if (!$programacion) {
                return true;
            }
            $oferta = $programacion->getOferta();
            if ($oferta) {
                $dia = $programacion->getDia()->getNumero();
            }
            $buscarFechas = new BuscarFechas();
            $fechas = $buscarFechas->todasLosDias($oferta->getFechaInicial(), $oferta->getFechaFinal());
            $opciones = $buscarFechas->tenerDias($fechas, $dia);
            $fechas = [];
            foreach ($opciones as $key => $opcion) {
                $fechas[$opcion] = $opcion;
            }
            $this->configuracion['seleccion_dia_unico']['choices'] = $fechas;
            $this->configuracion['seleccion_dia_unico']['data'] = $fecha;
            $this->configuracion['asistencias']['entry_options'] = ['label' => false, 'oferta' => $oferta, 'fecha' => $fecha];

            $form
                    ->add('seleccion_dia_unico', ChoiceType::class, $this->configuracion['seleccion_dia_unico'])
                    ->add('asistencias', CollectionType::class, $this->configuracion['asistencias']);
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $usuario = $event->getData();

                $formModifier($event->getForm(), $usuario);
            }
        });
    }

    public function getDias() {
        $dias = $this->em->getRepository('LogicBundle:Programacion')->findByOferta($this->oferta);
        $diasArray = [];
        foreach ($dias as $dia) {
            if ($dia->getHoraInicial()) {
                $diasArray[$dia->getDiaHoras()] = $dia->getId();
            }
        }

        return $diasArray;
    }

    public function getOpciones() {
        return [
            'seleccion_dia_unico' => [
                'empty_data' => null,
                'mapped' => false,
                'attr' => [
                    'class' => 'seleccion_dia_unico',
                    'onchange' => 'inder.asistencia.cambioSeleccionDia(this, true)'
                ],
                'label_attr' => [
                    'class' => 'label_fecha_asistencia'
                ],
                'constraints' => $this->noVacio
            ],
            'asistencias' => [
                'entry_type' => AsistenciaType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
                'label' => ' ',
                'attr' => [
                    'class' => 'collecctionAsistencias',
                    'autocomplete' => 'off'
                ]
            ],
            'archivo_usuarios' => [
                'data_class' => null,
                'label' => 'formulario_registro.carga_archivo.excel_usuarios',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => array(
                            'application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel',
                            'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel',
                            'application/xls', 'application/x-xls',
                            'application/*',
                            'text/csv'
                        ),
                        'mimeTypesMessage' => 'formulario_registro.carga_archivo.archivo_valido',
                            ])
                ],
                'attr' => [
                    "class" => "file",
                    "data-show-upload" => "false",
                    "data-show-caption" => "true",
                    "data-msg-placeholder" => "Seleccione un archivo excel para subir"
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Oferta::class,
            'em' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'asistencia_oferta';
    }

}
