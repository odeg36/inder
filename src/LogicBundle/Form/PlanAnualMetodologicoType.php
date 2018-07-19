<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;

class PlanAnualMetodologicoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        if ($options['paso'] == 1) {
            $planMetodologico = $builder->getData();

            $opcionesEnfoque = array('class' => 'LogicBundle:Enfoque',
                'placeholder' => '',
                'required' => true,
                "constraints" => $noVacio,
                'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.enfoque',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control enf', 'autocomplete' => 'off',
                    'onchange' => 'inder.plan_anual_metodologico.datosBasicosPlanAnual(this);'
            ]);

            if ($planMetodologico->getId() != 0 && $planMetodologico->getId() != null) {
                $opcionesEnfoque['disabled'] = true;
            }

            $builder
                    ->add('enfoque', EntityType::class, $opcionesEnfoque)
                    ->add('clasificacion', EntityType::class, array(
                        'class' => 'LogicBundle:Clasificacion',
                        'placeholder' => '',
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.clasificacion',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off',
                            'onchange' => 'inder.plan_anual_metodologico.disciplinaPlanAnual()'
                        ]
                    ))
                    ->add('disciplina', EntityType::class, array(
                        'class' => 'LogicBundle:Disciplina',
                        'placeholder' => '',
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.disciplina',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control disci', 'autocomplete' => 'off',
                            'onchange' => 'inder.plan_anual_metodologico.asignarNombre()'
                        ]
                    ))
                    ->add('niveles', EntityType::class, array(
                        'class' => 'LogicBundle:Nivel',
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nivel',
                        'empty_data' => null,
                        'multiple' => true,
                        'attr' => [
                            'class' => 'form-control niv', 'autocomplete' => 'off',
                            'onchange' => 'inder.plan_anual_metodologico.asignarNombre()'
                        ]
                    ))
                    ->add('nombre', HiddenType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nombre',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control nomb', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('estado', HiddenType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.estado',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
            ));
        }

        if ($options['paso'] == 2) {
            if ($options['enfoqueId'] == 1) {
                $builder
                        ->add('ponderacionComponentes', CheckboxType::class, array(
                            'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.ponderaciones',
                            'required' => false,
                            'attr' => [
                                'class' => 'autoponderacion',
                            ]
                ));
            }
            $builder
                    ->add('componentes', CollectionType::class, array(
                        'entry_type' => ComponenteType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'required' => true,
                        'prototype' => true,
                        'attr' => array(
                            'class' => 'componentes-collection',
                        ),
                        'by_reference' => false,
                        'entry_options' => array(
                            'planAnualMetodologicoId' => $options['planAnualMetodologicoId'],
                            'enfoqueId' => $options['enfoqueId']
                        ),
            ));
        }

        if ($options['paso'] == 3) {
            if ($options['enfoqueId'] == 1) {
                $builder
                        ->add('ponderacionContenidos', CheckboxType::class, array(
                            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.ponderaciones',
                            'required' => false,
                            'attr' => [
                                'class' => 'autoponderacion',
                            ]
                ));
            }
            $builder
                    ->add('contenidos', CollectionType::class, array(
                        'entry_type' => ContenidoType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'required' => true,
                        'prototype' => true,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'contenidos-collection',
                        ),
                        'data' => $options['contenidos'],
                        'by_reference' => false,
                        'entry_options' => array(
                            'planAnualMetodologicoId' => $options['planAnualMetodologicoId'],
                            'enfoqueId' => $options['enfoqueId']
                        ),
            ));
        }

        if ($options['paso'] == 4) {
            $builder
                    ->add('estrategias', CollectionType::class, array(
                        'entry_type' => EstrategiaType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'required' => true,
                        'prototype' => true,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'estrategias-collection',
                        ),
                        'by_reference' => false,
                        'entry_options' => array(
                            'label' => false,
                            'planAnualMetodologicoId' => $options['planAnualMetodologicoId'],
                            'enfoqueId' => $options['enfoqueId'],
                            'ofertas' => $options['ofertas'],
                            'estrategias' => $options['estrategias'],
                        ),
            ));
        }
        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.guardarContinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\PlanAnualMetodologico',
            'paso' => null,
            'contenidos' => null,
            'ofertas' => null,
            'estrategias' => null,
            'planAnualMetodologicoId' => null,
            'enfoqueId' => null,
            'em' => null
        ));
    }

}
