<?php

namespace LogicBundle\Form;

use AdminBundle\Validator\Constraints\ConstraintsAsistenteUnico;
use LogicBundle\Entity\Reserva;
use LogicBundle\Utils\BuscarFechas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class AsistenciasReservaType extends AbstractType {

    protected $reserva;
    protected $em;
    protected $configuracion;
    protected $noVacio;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->reserva = $builder->getData();
        $this->em = $options['em'];
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];

        $diasArray = $this->getDias();
        $this->configuracion = $this->getOpciones();

        $builder
                ->add('reserva', HiddenType::class, [
                    'mapped' => false,
                    'data' => $this->reserva->getId()
                ])
                ->add('seleccionar_todo', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'formulario.asistio.todos',
                    'attr' => [
                        'class' => 'asistio_todos_reserva'
                    ]
                ])
                ->add('dias_semana', ChoiceType::class, [
                    'label' => 'formulario.seleccionar.horario',
                    'choices' => $diasArray,
                    'multiple' => false,
                    'expanded' => true,
                    'mapped' => false,
                    'choice_attr' => function() {
                        return [
                            'class' => 'dias_semana_programacion_reserva'
                        ];
                    },
                    'constraints' => $this->noVacio
                ])
                ->add('seleccion_dia_unico', ChoiceType::class, $this->configuracion['seleccion_dia_unico']);

        $formModifier = function ($form, $data) {
            $oferta = $form->getData();
            $dia = 0;
            if (key_exists('dias_semana', $data)) {
                $dia = $data['dias_semana'];
            }

            $programacion = $this->em->getRepository('LogicBundle:ProgramacionReserva')->buscarProgramacion($dia, $data["reserva"]);
            if (!$programacion) {
                return true;
            }

            $reserva = $programacion->getReserva();
            if ($oferta) {
                $dia = $programacion->getDia()->getNumero();
            }

            $buscarFechas = new BuscarFechas();
            $fechas = $buscarFechas->todasLosDias($reserva->getFechaInicio(), $reserva->getFechaFinal());

            $opciones = $buscarFechas->tenerDias($fechas, $dia);
            $fechas = [];
            foreach ($opciones as $key => $opcion) {
                $fechas[$opcion] = $opcion;
            }

            $this->configuracion['seleccion_dia_unico']['choices'] = $fechas;

            $form->add('seleccion_dia_unico', ChoiceType::class, $this->configuracion['seleccion_dia_unico']);
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $usuario = $event->getData();

                $formModifier($event->getForm(), $usuario);
            }
        });
    }

    public function getDias() {
        $programaciones = $this->em->getRepository('LogicBundle:ProgramacionReserva')->findByReserva($this->reserva);
        $diasArray = [];
        foreach ($programaciones as $programacion) {
            if ($programacion->getInicioManana() || $programacion->getInicioTarde()) {
                $diasArray[$programacion->getHorarioDia()] = $programacion->getDia()->getId();
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
                    'class' => 'seleccion_dia_unico_reserva',
                    'onchange' => 'inder.asistencia.reserva.cambioSeleccionDia(this, true)'
                ],
                'label_attr' => [
                    'class' => 'label_fecha_asistencia_reserva'
                ],
                'constraints' => $this->noVacio
            ],
            'asistenciaReservas' => [
                'entry_type' => AsistenciaReservaType::class,
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
                ],
                'constraints' => [
                    new ConstraintsAsistenteUnico(),
                    new Assert\Count(array(
                        'min' => 1
                            ))
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Reserva::class,
            'em' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'asistencias_reserva';
    }

}
