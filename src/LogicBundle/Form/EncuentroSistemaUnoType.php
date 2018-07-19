<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class EncuentroSistemaUnoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            )),
        );
        $eventoObject = $builder->getData();
    

        $builder
        ->add("puntos_derrota", IntegerType::class,array(
            "label" => "formulario_escalera.labelDerrota",
            'required' => true,
            "constraints" => $noVacio,
            'attr' => [
                'class' => 'form-control',
                'min' => 0,
            ]))

        ->add("puntos_empate", IntegerType::class,array(
                "label" => "formulario_escalera.labelEmpate",
                'required' => true,
                "constraints" => $noVacio,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
            ]))

        ->add("puntos_victoria", IntegerType::class,array(
                "label" => "formulario_escalera.labelVictoria",
                'required' => true,
                "constraints" => $noVacio,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
            ]))

            ->add("puntos_victoria", IntegerType::class,array(
                "label" => "formulario_escalera.labelVictoria",
                'required' => true,
                "constraints" => $noVacio,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
            ]))

        ->add("puntos_para_ganar", IntegerType::class,array(
                "label" => "formulario_escalera.puntosParaGanar",
                'required' => true,
                "constraints" => $noVacio,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
            ]))


        ->add('agregar', SubmitType::class, array(
            'label' => 'formulario_escalera.guardar',
            'attr' => array('class' => 'btn btnVerde')));
        ;
    
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\SistemaJuegoUno',
            'em' => null,
            'evento' => null,         
        ));
    }

}
