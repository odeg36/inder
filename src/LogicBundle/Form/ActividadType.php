<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ActividadType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        $object = $builder->getdata();

        $optionsComponente = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('c')
                                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                                ->where('planAnualMetodologico.id = :id_pam')
                                ->setParameter('id_pam', $options['idpam']);
            },
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'empty_data' => null,
            'mapped' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        if ($object->getContenido()) {
            $optionsComponente['data'] = $object->getContenido()->getComponente();
        }


        $optionsContenido = array(
            'class' => 'LogicBundle:Contenido',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('con')
                                ->join('con.componente', 'c')
                                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                                ->where('planAnualMetodologico.id = :id_pam')
                                ->setParameter('id_pam', $options['idpam']);
            },
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido',
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $builder
                ->add('componente', EntityType::class, $optionsComponente)
                ->add('contenido', EntityType::class, $optionsContenido)
                ->add('nombre', TextType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.nombre',
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off')
                ))
                ->add('tipoTiempoEjecucion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoTiempoEjecucion',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.tiempo_ejecucion',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('duracion', IntegerType::class, [
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.duracion',
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ])
                ->add('indicador', TextareaType::class, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.indicador',
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 3,
                        'autocomplete' => 'off')
                ))
                ->add('metodoEvaluacion', TextType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.metodo_evaluacion',
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off')
                ))
                /* ->add('actividades', CollectionType::class, array(
                  'entry_type' => ActividadCollectionType::class,
                  'entry_options' => array('label' => false),
                  'allow_add' => true,
                  'allow_delete' => true,
                  'required' => true,
                  'prototype' => true,
                  'mapped' => false,
                  'attr' => array(
                  'class' => 'actividades-collection',
                  ),
                  'by_reference' => false
                  )) */
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
            'data_class' => 'LogicBundle\Entity\Actividad',
            'actividadId' => null,
            'idpam' => null,
            'em' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'actividad_type';
    }

}
