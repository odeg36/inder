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
use LogicBundle\Form\OpcionCampoAmbientalType;

class CampoAmbientalType extends AbstractType {

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
            ->add('nombre',null,array(
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('tipoEntrada', ChoiceType::class, array(
                "constraints" => $noVacio,
                'label' => 'formulario_campo.labels.tipoEntrada',
                'required' => true,
                'choices'   => array(
                    'Escoger Una Opción' => '',
                    'Area de texto' => 'Area de texto',
                    'Texto' => 'Texto',
                    'Fecha'=> 'Fecha',
                    'Numero' => 'Numero',
                    'Selección'=> 'Selección',
                    'Selección Multiple'=> 'Selección Multiple',
                    'Radio Button'=> 'Radio Button',
                    'Checkbox'=> 'Checkbox'
                ),
                'required'  => true,
                'attr' => array(
                    'class' => 'form-control tipo_entrada',
                    'onchange' => 'inder.campo.mostrarTipoEntrada(this);'
                ),
            ))
        //opcionesCampo
            ->add('opcionesCampo', CollectionType::class, array(
                'entry_type' => OpcionCampoAmbientalType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'opcionesCampo-collection',
                ),
                'by_reference' => false,
                'entry_options'  => array(
             //       'escenarioId' => $options['escenarioId'],
                ),
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'formulario_campo.labels.guardarcontinuar',
                'attr' => array('class' => 'btn btnVerde')));
                
    }

}
