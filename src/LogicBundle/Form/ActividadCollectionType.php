<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ActividadCollectionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        $builder
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Actividad',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'actividades_type';
    }

}
