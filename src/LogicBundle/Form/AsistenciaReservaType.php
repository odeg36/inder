<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AsistenciaReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('tipoidentificacion', EntityType::class, [
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'placeholder' => '',
                    'empty_data' => false,
                    'query_builder' => function(EntityRepository $repository) {
                        return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                    },
                    'label' => 'formulario.tipo.documento.acompanate',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ],
                    'mapped' => false
                ])
                ->add('usuario', AutocompleteType::class, [
                    'class' => 'Application\Sonata\UserBundle\Entity\User',
                    'label' => 'formulario.tipo.documento.acompanate',
                    "required" => true,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off',
                        'placeholder' => '',
                    ],
                    'constraints' => new NotBlank(['message' => 'formulario_registro.no_vacio'])
                ])
                ->add('nombreUsuario', HiddenType::class, [
                    'mapped' => false
                ])
                ->add('btnRemover', HiddenType::class, [
                    'mapped' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\AsistenciaReserva'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'asistencia_reserva_type';
    }

}
