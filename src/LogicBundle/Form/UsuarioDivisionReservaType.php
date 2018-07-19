<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\UsuarioDivisionReserva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioDivisionReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $object = $builder->getData();
        $builder
                ->add('tipoIdentificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'placeholder' => 'Seleccione una opción',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'formulario_reserva.labels.paso_tres.tipo_documento',
                    )
                ))
                ->add('numeroIdentificacion', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'formulario_reserva.labels.paso_tres.no_documento',
                    ),
                ))
                ->add('firstname', TextType::class, array(
                    'mapped' => false,
                    'attr' => array('class' => 'nombreUsuario form-control'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => UsuarioDivisionReserva::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuario_reserva_type';
    }

}
