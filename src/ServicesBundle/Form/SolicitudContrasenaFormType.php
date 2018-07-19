<?php

namespace ServicesBundle\Form;

use AdminBundle\Validator\Constraints\ConstraintsUsuarioConEmail;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolicitudContrasenaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('tipo_identificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'choice_label' => 'Nombre', 'label' => 'formulario.login.tipo_documento',
                    'required' => false,
                    'placeholder' => '',
                    'constraints' => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                    ]
                ))
                ->add('identificacion', TextType::class, array(
                    'label' => 'formulario.reestablecer contrasena.campo.numeroDocumento',
                    'constraints' => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Length(array(
                            'min' => 5,
                            'max' => 11,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                )),
                        new ConstraintsUsuarioConEmail()
                    ]
                ))
                ->add('send', SubmitType::class, array('label' => 'admin.botones.enviar'));
    }

}
