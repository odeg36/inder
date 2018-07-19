<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class CategoriaInfraestructuraType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
        $builder
            ->add('nombre',null,array(
                'required' => true,
                "constraints" => $noVacio,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('importanciaRelativa', TextType::class, [
                'label' => 'formulario.importancia.relativa',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 100
                    ])
                ]
            ])
            ->add('subInfraestructuras', CollectionType::class, array(
                'entry_type' => SubCategoriaInfraestructuraType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'subInfraestructuras-collection',
                ),
                'prototype_name' => '__children_name__',
                'by_reference' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'formulario_campo.labels.guardarcontinuar',
                'attr' => array('class' => 'btn btnVerde')
            )); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\CategoriaInfraestructura',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'categoria_infraestructura_type';
    }

}
