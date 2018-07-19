<?php

namespace LogicBundle\Form;

use AdminBundle\Validator\Constraints\ConstraintsDisciplinaOTendencia;
use AdminBundle\Validator\Constraints\ConstraintsPerfilesPermitidos;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Entity\OrganizacionDeportiva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class OrganizacionDeportivaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['paso'] == 1) {
            $disciplinas = [];
            foreach ($builder->getData()->getDisciplinaOrganizaciones() as $key => $disciplina) {
                array_push($disciplinas, $disciplina->getDisciplina());
            }
            if ($options['esEdicion']) {
                $builder
                    ->add('tipoEntidad', null, []);
            }
            $builder->add('disciplinas', EntityType::class, array(
                        'multiple' => true,
                        'class' => "LogicBundle:Disciplina",
                        'data' => $disciplinas,
                        'constraints' => [
                            new ConstraintsDisciplinaOTendencia()
                        ]
                    ))
                    ->add('tendencias', null, []);
        }
        if ($options['paso'] == 2) {
            $builder
                    ->add('organismosorganizacion', CollectionType::class, array(
                        'entry_type' => OrganoType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ' ',
                        'attr' => [
                            'class' => 'organos',
                            'autocomplete' => 'off'
                        ],
                        "constraints" => [
                            new ConstraintsPerfilesPermitidos()
                        ],
                        'prototype_name' => '__parent_name__'
            ));
        }
        if ($options['paso'] == 3) {
            $organizacionDeportiva = $options['organizacionDeportiva'];
            $builder
                    ->add('disciplinaOrganizaciones', CollectionType::class, array(
                        'entry_type' => DisciplinaOrganizacionType::class,
                        'entry_options' => array('label' => false, 'paso' => $options['paso'], 'organizacionDeportiva' => $organizacionDeportiva),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ' '
            ));
        }
        if ($options['paso'] == 4) {
            $builder
                    ->add('documentos', CollectionType::class, array(
                        'entry_type' => DocumentosOrganismoType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ' '
            ));
        }
        if ($options['paso'] == 5) {
            $builder
                    ->add('periodoestatutario', DateMaskType::class, array(
                        "label" => "formulario_registro.pnatural_informacion.fecha_nacimiento",
                        "required" => false,
                        'mask-alias' => 'dd/mm/yyyy',
                        'placeholder' => 'dd/mm/yyyy',
                        'constraints' => [
                            new LessThan("tomorrow"),
                            new GreaterThan("-200 years"),
                            new NotBlank(['message' => 'formulario_registro.no_vacio'])
                        ],
                        'attr' => array(
                            'class' => 'form-control',
                        )
            ));
        }
        if ($options['paso'] == 6) {
            $builder
                ->add('acciones', ChoiceType::class, array(
                    'mapped' => false,
                    'label' => ' ',
                    'placeholder' => ' ',
                    'choices' => [
                        OrganizacionDeportiva::APROBAR => OrganizacionDeportiva::APROBAR,
                        OrganizacionDeportiva::RECHAZAR => OrganizacionDeportiva::RECHAZAR
                    ],
                    'expanded' => true,
                    'constraints' => [
                        new NotNull()
                    ],
                    'choice_attr' => function($a, $b, $c){
                        return ['valor' => $a, 'class' => 'accion'];
                    }
                ))
                ->add('clasificacionOrganizacion', null, array(
                    'label' => 'formulario.clasificacion_organizacion',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ))
                ->add('vigencia_calculada', HiddenType::class, array(
                    'mapped' => false,
                ))
                ->add('observaciones', TextareaType::class, array(
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 6,
                        'autocomplete' => 'off')))
            ;
        }
        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_registro.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\OrganizacionDeportiva',
            'paso' => null,
            'esEdicion' => false,
            'organizacionDeportiva' => null
        ));
    }

}
