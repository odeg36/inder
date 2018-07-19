<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use LogicBundle\Form\EncuestaType;
use LogicBundle\Form\EncuestaPeriodoType;
use LogicBundle\Form\EncuestaPreguntaType;
use LogicBundle\Form\EncuestaOpcionType;

class EncuestaType extends AbstractType {

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
                ->add('nombre', null, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ))
                ->add('fechaInicio', DateType::class, array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'required' => true,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add('duracion', IntegerType::class, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => 1
                    )
                ))
                ->add('tipoPeriodicidad', ChoiceType::class, array(
                    'placeholder' => '',
                    'choices' => array(
                        'formulario_encuesta.periodicida.semanal' => 'Semanal',
                        'formulario_encuesta.periodicida.mensual' => 'Mensual',
                        'formulario_encuesta.periodicida.trimestral' => 'Trimestral',
                        'formulario_encuesta.periodicida.semestral' => 'Semestral',
                    ),
                    "constraints" => $noVacio,
                    'required' => false,
                    "label" => "formulario_encuesta.labels.periodicida",
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('comuna', EntityType::class, array(
                    'class' => 'LogicBundle:Comuna',
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('muestraComuna', IntegerType::class, array(
                    'required' => true,
                    'label' => 'formulario_encuesta.labels.muestra',
                    //"constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => 1
                    )
                ))
                ->add('estrategia', EntityType::class, array(
                    'class' => 'LogicBundle:Estrategia',
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('muestraEstrategia', IntegerType::class, array(
                    'label' => 'formulario_encuesta.labels.muestra',
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => 1
                    )
                ))
                ->add('oferta', EntityType::class, array(
                    'class' => 'LogicBundle:Oferta',
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('muestraOferta', IntegerType::class, array(
                    'label' => 'formulario_encuesta.labels.muestra',
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => 1
                    )
                ))
                //periodos de Encuesta
                ->add('encuestaPreguntas', CollectionType::class, array(
                    'entry_type' => EncuestaPreguntaType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'encuestaPreguntas-collection',
                    ),
                    'prototype_name' => '__parent_name__',
                    'entry_options' => array(
                    //       'escenarioId' => $options['escenarioId'],
                    ),))
                ->add('saveTwo', SubmitType::class, array(
                    'label' => 'formulario_encuesta.labels.guardarcrear',
                    'attr' => array('class' => 'btn btnMorado')))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_encuesta.labels.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
    }

}
