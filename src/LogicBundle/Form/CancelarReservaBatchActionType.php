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

class CancelarReservaBatchActionType extends AbstractType {

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
                ->add('motivoCancelacion', EntityType::class, array(
                    'class' => 'LogicBundle:MotivoCancelacion',
                    'placeholder' => '',
                    'required' => false,
                    'label' => 'Motivos Cancelacion',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_registro.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Reserva',
            'em' => null,
        ));
    }

}
