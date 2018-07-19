<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ComponenteType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        if ($options['enfoqueId'] == 1) {
            $builder
                    ->add('nombre', TextType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nombre',
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('objetivo', TextType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.objetivo',
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('ponderacion', NumberType::class, [
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.ponderacion',
                        'attr' => [
                            'class' => 'form-control ponderacionOff', 'autocomplete' => 'off',
                        ]
            ]);
        }

        if ($options['enfoqueId'] == 2) {
            $builder
                    ->add('modelo', EntityType::class, array(
                        'class' => 'LogicBundle:Modelo',
                        'placeholder' => '',
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.modelo',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('nombre', TextType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nombre',
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('objetivo', TextType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.objetivo',
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Componente',
            'planAnualMetodologicoId' => null,
            'enfoqueId' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'componente_type';
    }

}
