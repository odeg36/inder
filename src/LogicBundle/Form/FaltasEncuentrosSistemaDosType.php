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
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FaltasEncuentrosSistemaDosType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            )),
        );       

        $options['sanciones'];

        if($options['disciplina'] == "Voleibol" || $options['disciplina'] == "VOLEIBOL")
        {
            $resultadoUno = $options['resultadoJugador1'];
            $resultadoDos = $options['resultadoJugador2'];

           if($resultadoUno == null || $resultadoDos == null || $resultadoDos == false || $resultadoUno == false)
           {
            $builder

            ->add("setUno", IntegerType::class,array(
                "label" => "formulario_liga.setUno",
                'required' => false,
                "constraints" => $noVacio,
                'mapped'=> false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]))

            ->add("setDos", IntegerType::class,array(
                    "label" => "formulario_liga.setDos",
                    'required' => false,
                    "constraints" => $noVacio,
                    'mapped'=> false,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 0,
                    ]))

            ->add("setTres", IntegerType::class,array(
                        "label" => "formulario_liga.setTres",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                        ]))

            ->add("setCuatro", IntegerType::class,array(
                        "label" => "formulario_liga.setCuatro",
                        'required' => false,
                        "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                'class' => 'form-control',
                                'min' => 0,
                        ]))
            
            ->add("setCinco", IntegerType::class,array(
                        "label" => "formulario_liga.setCinco",
                        'required' => false,
                        "constraints" => $noVacio,
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'min' => 0,
                        ]))

            ->add("setAfavor", IntegerType::class,array(
                        "label" => "formulario_liga.puntosAfavor",
                            'required' => false,
                            "constraints" => $noVacio,
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))
                
            ->add("setEnContra", IntegerType::class,array(
                        "label" => "formulario_liga.puntosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                                'class' => 'form-control',
                                'disabled'=>'disabled'
                        ]))

            ->add("tantosAfavor", IntegerType::class,array(
                            "label" => "formulario_liga.TantosAfavor",
                            'required' => false,
                            "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))
                    
            ->add("tantosEnContra", IntegerType::class,array(
                            "label" => "formulario_liga.TantosEnContra",
                            'required' => false,
                            "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))

            ->add("scoe", IntegerType::class,array(
                        "label" => "formulario_liga.scoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                                'class' => 'form-control',
                                'disabled'=>'disabled'
                        ]))

            ->add("tscoe", IntegerType::class,array(
                        "label" => "formulario_liga.tscoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))

                ->add("setUno2", IntegerType::class,array(
                                "label" => "formulario_liga.setUno",
                                'required' => false,
                                "constraints" => $noVacio,
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'min' => 0,
                                ]))
                
                ->add("setDos2", IntegerType::class,array(
                                    "label" => "formulario_liga.setDos",
                                    'required' => false,
                                    "constraints" => $noVacio,
                                    'mapped'=> false,
                                    'attr' => [
                                        'class' => 'form-control',
                                        'min' => 0,
                                    ]))
                
                ->add("setTres2", IntegerType::class,array(
                                        "label" => "formulario_liga.setTres",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                            'class' => 'form-control',
                                            'min' => 0,
                                        ]))
                
                ->add("setCuatro2", IntegerType::class,array(
                                        "label" => "formulario_liga.setCuatro",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                            'mapped'=> false,
                                            'attr' => [
                                                'class' => 'form-control',
                                                'min' => 0,
                                        ]))
                            
                ->add("setCinco2", IntegerType::class,array(
                                        "label" => "formulario_liga.setCinco",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                                'mapped'=> false,
                                                'attr' => [
                                                    'class' => 'form-control',
                                                    'min' => 0,
                                        ]))
                
                ->add("setAfavor2", IntegerType::class,array(
                                        "label" => "formulario_liga.puntosAfavor",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                                'mapped'=> false,
                                                'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                                
                ->add("setEnContra2", IntegerType::class,array(
                                        "label" => "formulario_liga.puntosEnContra",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                                'class' => 'form-control',
                                                'disabled'=>'disabled'
                                        ]))
                
                ->add("tantosAfavor2", IntegerType::class,array(
                                            "label" => "formulario_liga.TantosAfavor",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                            'mapped'=> false,
                                            'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                                    
                ->add("tantosEnContra2", IntegerType::class,array(
                                            "label" => "formulario_liga.TantosEnContra",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                            'mapped'=> false,
                                            'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                
                ->add("scoe2", IntegerType::class,array(
                                        "label" => "formulario_liga.scoe",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                                'class' => 'form-control',
                                                'disabled'=>'disabled'
                                        ]))
                
                ->add("tscoe2", IntegerType::class,array(
                                        "label" => "formulario_liga.tscoe",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))

            ->add('faltasEncuentroJugador1', CollectionType::class, array(
                    'entry_type' => SancionType::class,
                    'entry_options' => array('label' => false),
                    "label" => "formulario_escalera.labelFalta",
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'mapped'=> false,
                    'attr' => array(
                        'class' => 'form faltasEncuentroJugador1-collection',
                    ),
                    'data'=> $options['faltasJugador1'],
                    'by_reference' => false,
                    'entry_options'  => array(
                        'faltasJugador1'=>$options['faltasJugador1']
                    ),
                ))
            
                ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped'=> false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data'=> $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options'  => array(
                            'faltasJugador2'=>$options['faltasJugador2']
                        ),
                    ))
                
                  ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde'),
                        'disabled'=>$options['validarEdicion'],
                  ));
             
                ;

           }else
           {
            $builder

            ->add("setUno", IntegerType::class,array(
                "label" => "formulario_liga.setUno",
                'required' => false,
                "constraints" => $noVacio,
                'data'=> $resultadoUno->getSetUno(),
                'mapped'=> false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]))

            ->add("setDos", IntegerType::class,array(
                    "label" => "formulario_liga.setDos",
                    'required' => false,
                    "constraints" => $noVacio,
                    'data'=> $resultadoUno->getSetDos(),
                    'mapped'=> false,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 0,
                    ]))

            ->add("setTres", IntegerType::class,array(
                        "label" => "formulario_liga.setTres",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoUno->getSetTres(),
                        'mapped'=> false,
                        'attr' => [
                            'class' => 'form-control',
                            'min' => 0,
                        ]))

            ->add("setCuatro", IntegerType::class,array(
                        "label" => "formulario_liga.setCuatro",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoUno->getSetCuatro(),
                            'mapped'=> false,
                            'attr' => [
                                'class' => 'form-control',
                                'min' => 0,
                        ]))
            
            ->add("setCinco", IntegerType::class,array(
                        "label" => "formulario_liga.setCinco",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoUno->getSetCinco(),
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'min' => 0,
                        ]))

            ->add("setAfavor", IntegerType::class,array(
                        "label" => "formulario_liga.puntosAfavor",
                            'required' => false,
                            "constraints" => $noVacio,
                            'data'=> $resultadoUno->getSetsAfavor(),
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))
                
            ->add("setEnContra", IntegerType::class,array(
                        "label" => "formulario_liga.puntosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoUno->getSetsEnContra(),
                        'mapped'=> false,
                        'attr' => [
                                'class' => 'form-control',
                                'disabled'=>'disabled'
                        ]))

            ->add("tantosAfavor", IntegerType::class,array(
                            "label" => "formulario_liga.TantosAfavor",
                            'required' => false,
                            "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))
                    
            ->add("tantosEnContra", IntegerType::class,array(
                            "label" => "formulario_liga.TantosEnContra",
                            'required' => false,
                            "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))

            ->add("scoe", IntegerType::class,array(
                        "label" => "formulario_liga.scoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                                'class' => 'form-control',
                                'disabled'=>'disabled'
                        ]))

            ->add("tscoe", IntegerType::class,array(
                        "label" => "formulario_liga.tscoe",
                        'required' => false,
                        "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                            ]))
                

                ->add("setUno2", IntegerType::class,array(
                                "label" => "formulario_liga.setUno",
                                'required' => false,
                                "constraints" => $noVacio,
                                'data'=> $resultadoDos->getSetUno(),
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'min' => 0,
                                ]))
                
                ->add("setDos2", IntegerType::class,array(
                                    "label" => "formulario_liga.setDos",
                                    'required' => false,
                                    "constraints" => $noVacio,
                                    'data'=> $resultadoDos->getSetDos(),
                                    'mapped'=> false,
                                    'attr' => [
                                        'class' => 'form-control',
                                        'min' => 0,
                                    ]))
                
                ->add("setTres2", IntegerType::class,array(
                                        "label" => "formulario_liga.setTres",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'data'=> $resultadoDos->getSetTres(),
                                        'mapped'=> false,
                                        'attr' => [
                                            'class' => 'form-control',
                                            'min' => 0,
                                        ]))
                
                ->add("setCuatro2", IntegerType::class,array(
                                        "label" => "formulario_liga.setCuatro",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'data'=> $resultadoDos->getSetCuatro(),
                                            'mapped'=> false,
                                            'attr' => [
                                                'class' => 'form-control',
                                                'min' => 0,
                                        ]))
                            
                ->add("setCinco2", IntegerType::class,array(
                                        "label" => "formulario_liga.setCinco",
                                        'required' => false,
                                        'data'=> $resultadoDos->getSetCinco(),
                                        "constraints" => $noVacio,
                                                'mapped'=> false,
                                                'attr' => [
                                                    'class' => 'form-control',
                                                    'min' => 0,
                                        ]))
                
                ->add("setAfavor2", IntegerType::class,array(
                                        "label" => "formulario_liga.puntosAfavor",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                            'data'=> $resultadoDos->getSetsAfavor(),
                                                'mapped'=> false,
                                                'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                                
                ->add("setEnContra2", IntegerType::class,array(
                                        "label" => "formulario_liga.puntosEnContra",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'data'=> $resultadoDos->getSetsEnContra(),
                                        'mapped'=> false,
                                        'attr' => [
                                                'class' => 'form-control',
                                                'disabled'=>'disabled'
                                        ]))
                
                ->add("tantosAfavor2", IntegerType::class,array(
                                            "label" => "formulario_liga.TantosAfavor",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                            'mapped'=> false,
                                            'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                                    
                ->add("tantosEnContra2", IntegerType::class,array(
                                            "label" => "formulario_liga.TantosEnContra",
                                            'required' => false,
                                            "constraints" => $noVacio,
                                            'mapped'=> false,
                                            'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))
                
                ->add("scoe2", IntegerType::class,array(
                                        "label" => "formulario_liga.scoe",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                                'class' => 'form-control',
                                                'disabled'=>'disabled'
                                        ]))
                
                ->add("tscoe2", IntegerType::class,array(
                                        "label" => "formulario_liga.tscoe",
                                        'required' => false,
                                        "constraints" => $noVacio,
                                        'mapped'=> false,
                                        'attr' => [
                                                    'class' => 'form-control',
                                                    'disabled'=>'disabled'
                                            ]))

            ->add('faltasEncuentroJugador1', CollectionType::class, array(
                    'entry_type' => SancionType::class,
                    'entry_options' => array('label' => false),
                    "label" => "formulario_escalera.labelFalta",
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'mapped'=> false,
                    'attr' => array(
                        'class' => 'form faltasEncuentroJugador1-collection',
                    ),
                    'data'=> $options['faltasJugador1'],
                    'by_reference' => false,
                    'entry_options'  => array(
                        'faltasJugador1'=>$options['faltasJugador1']
                    ),
                ))
            
                ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped'=> false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data'=> $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options'  => array(
                            'faltasJugador2'=>$options['faltasJugador2']
                        ),
                    ))
                
                  ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde'),
                        'disabled'=>$options['validarEdicion'],
                  ));
             
                ;
           }

            

        }

        if($options['disciplina'] != "Voleibol"  && $options['disciplina'] != "VOLEIBOL" )
        {
            
            $resultadoUno = $options['resultadoJugador1'];
            $resultadoDos = $options['resultadoJugador2'];
            
           if($resultadoUno == null || $resultadoDos == null || $resultadoDos == false || $resultadoUno == false)
           {
                $builder
            ->add("puntosAfavor", IntegerType::class,array(
                "label" => "formulario_liga.TantosAfavor",
                'required' => false,
                "constraints" => $noVacio,
                    'mapped'=> false,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 0,
                ]))
    
            ->add("puntosEnContra", IntegerType::class,array(
                "label" => "formulario_liga.TantosEnContra",
                'required' => false,
                "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled'=>'disabled'
                ]))
            


            ->add("puntosAfavor2", IntegerType::class,array(
                        "label" => "formulario_liga.TantosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                            'mapped'=> false,
                            'attr' => [
                                'class' => 'form-control',
                                'min' => 0,
                        ]))
            
            ->add("puntosEnContra2", IntegerType::class,array(
                        "label" => "formulario_liga.TantosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                        ]))
                    

            
            ->add('faltasEncuentroJugador1', CollectionType::class, array(
                    'entry_type' => SancionType::class,
                    'entry_options' => array('label' => false),
                    "label" => "formulario_escalera.labelFalta",
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'mapped'=> false,
                    'attr' => array(
                        'class' => 'form faltasEncuentroJugador1-collection',
                    ),
                    'data'=> $options['faltasJugador1'],
                    'by_reference' => false,
                    'entry_options'  => array(
                        'faltasJugador1'=>$options['faltasJugador1']
                    ),
                ))
            
                ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped'=> false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data'=> $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options'  => array(
                            'faltasJugador2'=>$options['faltasJugador2']
                        ),
                    ))
                ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde')
                    ));
                ;
           }else{
               $builder
            ->add("puntosAfavor", IntegerType::class,array(
                "label" => "formulario_liga.TantosAfavor",
                'required' => false,
                "constraints" => $noVacio,
                'data'=> $resultadoUno->getPuntosAfavor(),
                    'mapped'=> false,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 0,
                ]))
    
            ->add("puntosEnContra", IntegerType::class,array(
                "label" => "formulario_liga.TantosEnContra",
                'required' => false,
                'data'=> $resultadoUno->getPuntosEnContra(),
                "constraints" => $noVacio,
                        'mapped'=> false,
                        'attr' => [
                            'class' => 'form-control',
                            'disabled'=>'disabled'
                ]))
            



            ->add("puntosAfavor2", IntegerType::class,array(
                        "label" => "formulario_liga.TantosAfavor",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoDos->getPuntosAfavor(),
                            'mapped'=> false,
                            'attr' => [
                                'class' => 'form-control',
                                'min' => 0,
                        ]))
            
            ->add("puntosEnContra2", IntegerType::class,array(
                        "label" => "formulario_liga.TantosEnContra",
                        'required' => false,
                        "constraints" => $noVacio,
                        'data'=> $resultadoDos->getPuntosEnContra(),
                                'mapped'=> false,
                                'attr' => [
                                    'class' => 'form-control',
                                    'disabled'=>'disabled'
                        ]))
            
            ->add('faltasEncuentroJugador1', CollectionType::class, array(
                    'entry_type' => SancionType::class,
                    'entry_options' => array('label' => false),
                    "label" => "formulario_escalera.labelFalta",
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'mapped'=> false,
                    'attr' => array(
                        'class' => 'form faltasEncuentroJugador1-collection',
                    ),
                    'data'=> $options['faltasJugador1'],
                    'by_reference' => false,
                    'entry_options'  => array(
                        'faltasJugador1'=>$options['faltasJugador1']
                    ),
                ))
            
                ->add('faltasEncuentroJugador2', CollectionType::class, array(
                        'entry_type' => SancionType::class,
                        'entry_options' => array('label' => false),
                        "label" => "formulario_escalera.labelFalta",
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'mapped'=> false,
                        'attr' => array(
                            'class' => 'form faltasEncuentroJugador2-collection',
                        ),
                        'data'=> $options['faltasJugador2'],
                        'by_reference' => false,
                        'entry_options'  => array(
                            'faltasJugador2'=>$options['faltasJugador2']
                        ),
                    ))
                ->add('save', SubmitType::class, array(
                        'label' => 'formulario_campo.labels.guardarcontinuar',
                        'attr' => array('class' => 'btn btnVerde'),
                        'disabled'=>$options['validarEdicion'],
                    ));
                ;
           }
            

        }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\EncuentroSistemaDos',
            'faltasJugador1' => null,
            'faltasJugador2' => null,
            'disciplina' => null,
            'resultadoJugador1'=> null,
            'resultadoJugador2'=> null,
            'validarEdicion' => null,
            'sanciones'=> null,
        ));
    }

}