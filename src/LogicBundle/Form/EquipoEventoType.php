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

class EquipoEventoType extends AbstractType {

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
        ->add('nombre', null, array(
            "required" => true,
            "constraints" => $noVacio,
            'label' => 'formulario_evento.labels.jugador_evento.nombre',
            'empty_data' => null,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => '',
            ),
        ))
        ->add('jugadorEventos', CollectionType::class, array(
            'entry_type' => JugadorEquipoEventoType::class,
            'label' => 'formulario_evento.labels.jugador_evento.participantes',
            'mapped' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => true,
            'prototype' => true,
            'attr' => array(
                'class' => 'jugadorEquipoEvento-collection',
            ),
            'by_reference' => false,
            'entry_options' => array(
                'usuario' => $options['usuario'],
                'evento' => $options['evento'],
                'em' => $options['em']
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
            'data_class' => 'LogicBundle\Entity\EquipoEvento',
            'em' => null,
            'usuario' => null,
            'evento' => null
        ));
    }

}
