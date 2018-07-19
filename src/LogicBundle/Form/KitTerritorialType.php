<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;

class KitTerritorialType extends AbstractType {

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
                ->add('comuna', EntityType::class, array(
                    'class' => 'LogicBundle:Comuna',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'carne.comuna',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('componentes', CollectionType::class, array(
                    'entry_type' => KitTerritorialComponenteType::class,
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
                    ),
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.guardarContinuar',
                    'attr' => array(
                        'class' => 'btn btnVerde')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\KitTerritorial',
            'em' => null,
            'kitTerritorialId' => null
        ));
    }

}
