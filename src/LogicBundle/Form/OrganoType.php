<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrganoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('tipoOrgano', EntityType::class, array(
                    'class' => 'LogicBundle:TipoOrganismo',
                    'placeholder' => '',
                    'empty_data' => false,
                    'label' => 'formulario_registro.pasodos.tipoorgano',
                    'empty_data' => null,
                    'disabled' => true,
                    'attr' => [
                        'class' => 'form-control select-tipo-organo',
                        'autocomplete' => 'off'
                    ],
                    'choice_attr' => function($val, $key, $index) {
                        return ['data-abreviatura' => $val->getAbreviatura()];
                    }
                ))
                ->add('perfilOrganismos', CollectionType::class, array(
                    'entry_type' => PerfilOrganismosType::class,
                    'label' => 'formulario_registro.pasodos.asigna_usuario',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    'attr' => [
                        'class' => 'perfiles', 'autocomplete' => 'off'
                    ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Organo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'organo_type';
    }

}
