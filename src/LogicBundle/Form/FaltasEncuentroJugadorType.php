<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FaltasEncuentroJugadorType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    )),
        );


        if ($options['disciplina'] == "FÚTBOL" || $options['disciplina'] == "Futbol" || $options['disciplina'] == "Futbol 7" || $options['disciplina'] == "Futsal" || $options['disciplina'] == "FÚTBOL DE SALÓN") {

            $label = "formulario_escalera.marcador";
        } elseif ($options['disciplina'] == "BALONCESTO" || $options['disciplina'] == "Baloncesto") {

            $label = "formulario_escalera.canastas";
        } else {

            $label = "formulario_escalera.label";
        }



        if ($options['disciplina'] == "Voleibol" || $options['disciplina'] == "VOLEIBOL") {
            $builder
                    ->add("setUno", IntegerType::class, array(
                        "label" => "formulario_escalera.setUno",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setDos", IntegerType::class, array(
                        "label" => "formulario_escalera.setDos",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setTres", IntegerType::class, array(
                        "label" => "formulario_escalera.setTres",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setCuatro", IntegerType::class, array(
                        "label" => "formulario_escalera.setCuatro",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setCinco", IntegerType::class, array(
                        "label" => "formulario_escalera.setCinco",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setsAfavor", IntegerType::class, array(
                        "label" => "formulario_escalera.puntosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                            'disabled' => 'disabled'
                ]))
                    ->add("setsEnContra", IntegerType::class, array(
                        "label" => "formulario_escalera.puntosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                            'disabled' => 'disabled'
                ]))
                    ->add("tantosAfavor", IntegerType::class, array(
                        "label" => "formulario_liga.TantosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("tantosEnContra", IntegerType::class, array(
                        "label" => "formulario_liga.TantosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("scoe", IntegerType::class, array(
                        "label" => "formulario_liga.scoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("tscoe", IntegerType::class, array(
                        "label" => "formulario_liga.tscoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add('faltasEncuentroJugador1', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador1-collection',
                        ),
                        'data' => $options['faltasJugador1'],
                        'by_reference' => false,
                        'entry_options' => array(
                            'faltasJugador1' => $options['faltasJugador1']
                        ),
                    ))
                    ->add("setUno2", IntegerType::class, array(
                        "label" => "formulario_escalera.setUno",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setDos2", IntegerType::class, array(
                        "label" => "formulario_escalera.setDos",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setTres2", IntegerType::class, array(
                        "label" => "formulario_escalera.setTres",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setCuatro2", IntegerType::class, array(
                        "label" => "formulario_escalera.setCuatro",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setCinco2", IntegerType::class, array(
                        "label" => "formulario_escalera.setCinco",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add("setsAfavor2", IntegerType::class, array(
                        "label" => "formulario_escalera.puntosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                            'disabled' => 'disabled'
                ]))
                    ->add("setsEnContra2", IntegerType::class, array(
                        "label" => "formulario_escalera.puntosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                            'disabled' => 'disabled'
                ]))
                    ->add("tantosAfavor2", IntegerType::class, array(
                        "label" => "formulario_liga.TantosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("tantosEnContra2", IntegerType::class, array(
                        "label" => "formulario_liga.TantosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("scoe2", IntegerType::class, array(
                        "label" => "formulario_liga.scoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add("tscoe2", IntegerType::class, array(
                        "label" => "formulario_liga.tscoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled' => 'disabled'
                ]))
                    ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data' => $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options' => array(
                            'faltasJugador2' => $options['faltasJugador2']
                        ),
                    ))
                    ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde')));
            ;
        } else {

            $builder
                    ->add("puntosCompetidorUno", IntegerType::class, array(
                        "label" => $label,
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add('faltasEncuentroJugador1', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador1-collection',
                        ),
                        'data' => $options['faltasJugador1'],
                        'by_reference' => false,
                        'entry_options' => array(
                            'faltasJugador1' => $options['faltasJugador1']
                        ),
                    ))
                    ->add("puntosCompetidorDos", IntegerType::class, array(
                        "label" => $label,
                        'required' => false,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                ]))
                    ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data' => $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options' => array(
                            'faltasJugador2' => $options['faltasJugador2']
                        ),
                    ))
                    ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde')));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\EncuentroSistemaUno',
            'faltasJugador1' => null,
            'faltasJugador2' => null,
            'disciplina' => null,
        ));
    }

}
