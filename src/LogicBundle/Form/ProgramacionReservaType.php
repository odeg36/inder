<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\ProgramacionReserva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProgramacionReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $formOptions = array(
            'mapped' => true,
            'class' => 'LogicBundle:Dia',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'disabled' => 'disabled',
                'class' => "coleccionDias dias-disabled"
            ]
        );
        $builder
                ->add('dia', EntityType::class, $formOptions)
                ->add('inicioManana', TextType::class, ['attr' => [
                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time manana'
                    ]
                ])
                ->add('finManana', TextType::class, ['attr' => [
//                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time manana']
                ])
                ->add('inicioTarde', TextType::class, ['attr' => [
//                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time tarde'
                    ]
                ])
                ->add('finTarde', TextType::class, ['attr' => [
//                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time tarde'
                    ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ProgramacionReserva::class,
            'reserva' => null,
            'em' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuarios_division_reserva_type';
    }

}
