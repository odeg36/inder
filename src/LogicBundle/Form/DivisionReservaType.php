<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\DivisionReserva;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DivisionReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    ))
        );
        $object = $builder->getData();
        $builder
                ->add('divisionReservas', CollectionType::class, [
                    'entry_type' => UsuarioDivisionReservaType::class,
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    'required' => true,
                    'label' => ' ',
                    'attr' => [
                        'class' => 'coleccionUsuarios',
                        'autocomplete' => 'off'
                    ],
                    'prototype_name' => '__parent_name__'
                ]);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => DivisionReserva::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuarios_division_reserva_type';
    }

}
