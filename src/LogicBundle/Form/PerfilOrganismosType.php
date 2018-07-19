<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class PerfilOrganismosType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('documento', null, [
                    'label' => 'formulario_registro.pnatural_informacion.numero_identificacion',
                    'attr' => [
                        'placeholder' => 'formulario_registro.pnatural_informacion.numero_identificacion'
                    ],
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Assert\Length(array(
                            'min' => 5,
                            'max' => 10,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                        ))
                    ],
                ])
                ->add('nombre', null, [
                    'label' => 'formulario_registro.pnatural_informacion.nombre',
                    'attr' => [
                        'placeholder' => 'formulario_registro.pnatural_informacion.nombre',
                    ],
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ]
                ])
                ->add('perfil', EntityType::class, array(
                    'class' => 'LogicBundle:Perfil',
                    'placeholder' => '',
                    'empty_data' => false,
                    'label' => 'formulario_registro.pasodos.perfil',
                    'empty_data' => null,
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio'])
                    ],
                    'attr' => ['class' => 'form-control', 'autocomplete' => 'off']))
                ->add('telefono', null, [
                    'label' => 'formulario_registro.contacto.telefono_movil',
                    'attr' => [
                        'placeholder' => 'formulario_registro.contacto.telefono_movil'
                    ],
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    "constraints" => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Assert\Length(array(
                            'min' => 7,
                            'max' => 10,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                )),
                        new Assert\Regex([
                            'pattern' => '/\d+/'
                                ])
                    ]
                ])
                ->add('correo', null, array(
                    "label" => "formulario_registro.contacto.correo_electronico",
                    "constraints" => [
                        new Assert\Email()
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'formulario_registro.contacto.correo_electronico'
                    ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\PerfilOrganismo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'perfil_organismos_type';
    }

}
