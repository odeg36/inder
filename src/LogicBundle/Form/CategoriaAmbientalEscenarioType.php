<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\EscenarioCategoriaAmbiental;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriaAmbientalEscenarioType extends AbstractType {

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
            ->add('categoriaAmbiental', EntityType::class, array(
                'class' => 'LogicBundle:CategoriaAmbiental',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off',
                    'onchange' => 'inder.escenarioDeportivo.changeCategoriaAmbiental(this)'
                ],
            ))
            ->add('escenarioSubCategoriaAmbientales', CollectionType::class, array(
                'entry_type' => SubCategoriaAmbientalEscenarioType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'attr' => array(
                    'class' => 'subCategoriaAmbiental-collection',
                ),
                'by_reference' => false,
                'entry_options'  => array(),
                'constraints' => [
                    new Count(array(
                        'min' => 1
                    ))
                ]
            ))          

            ;
                
    }
        /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'categoria_ambiental_escenario_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(            
            'data_class' => EscenarioCategoriaAmbiental::class,
            'categorias' => null,
            'subcategorias' => null
        ));
    }

}
