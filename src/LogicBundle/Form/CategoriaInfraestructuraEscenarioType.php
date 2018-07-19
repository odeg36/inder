<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\EscenarioCategoriaInfraestructura;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

class CategoriaInfraestructuraEscenarioType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
        

        $builder
            ->add('categoriaInfraestructura', EntityType::class, array(
                'class' => 'LogicBundle:CategoriaInfraestructura',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('categoria')
                                    ->innerJoin('LogicBundle:CategoriaInfraTipoEscenario', 'fej', 'WITH', 'categoria.id = fej.categoriaInfraestructura')
                                    ->where('fej.tipoEscenario = :tipo_escenario')
                                    ->setParameter('tipo_escenario', $options['tipoEscenario']);
                },
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off',
                    'onchange' => 'inder.escenarioDeportivo.changeCategoriaInfraestructura(this)'
                ],
                'choice_attr' => function($val, $key, $index) {
                    $importanciaRelativa = 0;
                    if($val->getImportanciaRelativa()){
                        $importanciaRelativa = $val->getImportanciaRelativa();
                    }
                    return ['importancia-relativa' => $importanciaRelativa];
                },
            ))
            ->add('importanciaRelativa', HiddenType::class, [
                'attr' => [
                    'class' => 'importancia-relativa'
                ]
            ])
            ->add('calificacionGeneral', HiddenType::class, [
                'attr' => [
                    'class' => 'calificacion-general'
                ]
            ])
            ->add('escenarioCategoriaSubCategoriaInfraestructuras', CollectionType::class, array(
                'entry_type' => SubCategoriaInfraestructuraEscenarioType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'attr' => array(
                    'class' => 'escenarioCategoriaSubCategoriaInfraestructuras-collection',
                ),
                'by_reference' => false,
                'entry_options'  => array(),
                'constraints' => [
                    new Count(array(
                        'min' => 1
                    ))
                ]
            ));
                
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'categoria_infraestructura_escenario_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(            
            'data_class' => EscenarioCategoriaInfraestructura::class,
            'categorias' => null,
            'subcategorias' => null,
            'tipoEscenario' => null,
        ));
    }
}
