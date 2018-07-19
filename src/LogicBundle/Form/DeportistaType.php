<?php

namespace LogicBundle\Form;

use AdminBundle\Validator\Constraints\ConstraintsUsuarioUnico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class DeportistaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $builder
                ->add('tipoIdentificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'placeholder' => '',
                    'empty_data' => false,
                    'query_builder' => function(EntityRepository $repository) {
                        return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                    },
                    'label' => 'formulario_registro.pnatural_informacion.tipo_documento',
                    'empty_data' => null,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                ))
                ->add('numeroIdentificacion', null, array(
                    "label" => "formulario_registro.pnatural_informacion.numero_identificacion",
                    "required" => true,
                    'constraints' => [
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                )),
                        new Assert\Length(array(
                            'min' => 5,
                            'max' => 10,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                )),
                        new ConstraintsUsuarioUnico()
                    ],
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'No. de identificación')
                ))
                ->add('firstname', null, array(
                    "label" => "formulario.labels.nombres",
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'formulario.labels.nombres')
                ))
                ->add('lastname', null, array(
                    "label" => "formulario.labels.apellidos",
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'formulario.labels.apellidos')
                ))
                ->add('email', null, array(
                    "label" => "formulario_registro.contacto.correo_electronico",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                )),
                        new Assert\Email()
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => '',
                    ]
                ))
                ->add('phone', null, array(
                    "label" => "formulario_registro.contacto.telefono_movil",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                )),
                        new Assert\Length(array(
                            'min' => 7,
                            'max' => 10,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                ))
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => '',
                    ]
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'deportista_type';
    }

}
