<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DisciplinaOrganizacionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('disciplina', EntityType::class, array(
                    'class' => 'LogicBundle:Disciplina',
                    'placeholder' => '',
                    'empty_data' => false,
                    'label' => 'formulario_registro.pasouno.titulo_disciplinas',
                    'empty_data' => null,
                    'constraints' => [
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                ))
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'disabled' => ($options['paso'] == 3) ? true : false
                    ]
                ))
        ;
        if ($options['paso'] == 3) {
            $organizacionDeportiva = $options['organizacionDeportiva'];
            
            $builder
                ->add('deportistas', CollectionType::class, array(
                        'entry_type' => OrganismoDeportistaType::class,
                        'entry_options' => array('label' => false, 'organizacionDeportiva' => $organizacionDeportiva),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ' ',
                        'attr' => array('class' => 'deportistas', 'autocomplete' => 'off')
                ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\DisciplinaOrganizacion',
            'paso' => null,
            'organizacionDeportiva' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'disciplinaorganizacion';
    }

}
