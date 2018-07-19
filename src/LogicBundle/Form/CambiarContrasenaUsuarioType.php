<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CambiarContrasenaUsuarioType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $container = $options['container'];
        $user = $container->get('security.token_storage')->getToken()->getUser();
        if (!$user->hasRole('ROLE_SUPER_ADMIN')) {
            $builder
                    ->add('contrasenaActual', PasswordType::class, [
                        'attr' => [
                            'class' => 'form-control',
                        ],
                        'label' => "formulario.usuario.contrasena.actual",
            ]);
        }
        $builder
                ->add('contrasenaNueva', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'options' => array('attr' => array('class' => 'form-control')),
                    'invalid_message' => 'formulario_registro.claves_deben_coincidir',
                    'first_options' => array('label' => 'formulario.usuario.contrasena.nueva'),
                    'second_options' => array('label' => 'formulario.usuario.contrasena.confirmar_nueva'),
                    'constraints' => [
                        new Regex([
                            'message' => 'error.clave.regex',
                            'pattern' => '/^(?=.*[A-Za-z])(?=.*\d).{6,}$/' // Valida numeros, letras y numero caracteres
                        ])
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'container' => ''
        ));
    }

}
