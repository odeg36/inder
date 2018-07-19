<?php

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioPersonaNaturalType extends AbstractType {

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
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off',
                        'placeholder' => '',
                    ]
        ]);
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
        return 'usuario_persona_natural';
    }

}
