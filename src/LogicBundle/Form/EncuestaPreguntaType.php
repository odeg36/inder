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

class EncuestaPreguntaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
        $object = $builder->getData();
        $builder
            ->add('nombre',null,array(
                "constraints" => $noVacio,
                'required' => true,
                'attr' => array(
                'class' => 'form-control',
                )
            ))
            //preguntas
            ->add('encuestaOpciones', CollectionType::class, array(
                'entry_type' => EncuestaOpcionType::class,
                "label" => "formulario_encuesta.labels.opciones",
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => true,
                "constraints" => $noVacio,
                'attr' => array(
                    'class' => 'encuestaOpciones-collection',
                ),
                'prototype_name' => '__children_name__',
                'by_reference' => false
            ));
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\EncuestaPregunta',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'encuesta_pregunta_type';
    }

}
