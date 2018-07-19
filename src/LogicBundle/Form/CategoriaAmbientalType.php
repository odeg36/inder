<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Doctrine\Common\Collections\ArrayCollection;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;

class CategoriaAmbientalType extends AbstractType {

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
                "required" => true,
                "constraints" => $noVacio,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))

            ->add('subcategoriaAmbientales', CollectionType::class, array(
                'entry_type' => SubCategoriaAmbientalType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'subcategoriaAmbientales-collection',
                ),
                'by_reference' => false,
                'entry_options'  => array(
             //       'escenarioId' => $options['escenarioId'],
                ),
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'formulario_campo.labels.guardarcontinuar',
                'attr' => array('class' => 'btn btnVerde')))
            ;
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\CategoriaAmbiental',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'categoria_ambiental_type';
    }

}
