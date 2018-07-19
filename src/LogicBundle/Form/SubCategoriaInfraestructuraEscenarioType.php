<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubCategoriaInfraestructuraEscenarioType extends AbstractType {

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
            ->add('subcategoriaInfraestructura', EntityType::class, array(
                'class' => 'LogicBundle:SubcategoriaInfraestructura',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control form-select tipo_entrada','autocomplete' => 'off'
                ),
                'label' => 'formulario_escenario_deportivo.labels.paso_cuatro_uno.subcategoria'
            ))
            ->add('importanciaRelativa', ChoiceType::class, array(
                "required" => false,
                'empty_data' => null,
                'multiple' => false,
                'choices' => [
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_DOS => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_DOS,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_TRES => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_TRES,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CUATRO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CUATRO,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CINCO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CINCO
                ],
                'data' => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO,
                "constraints" => [
                    new NotBlank()
                ],
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ),
                'label' => 'formulario.importancia.relativa'
            ))
            ->add('calificacionGeneral', ChoiceType::class, array(
                "required" => false,
                'empty_data' => null,
                'multiple' => false,
                'choices' => [
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_DOS => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_DOS,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_TRES => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_TRES,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CUATRO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CUATRO,
                    EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CINCO => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_CINCO
                ],
                'data' => EscenarioCategoriaSubCategoriaInfraestructura::VALOR_UNO,
                "constraints" => [
                    new NotBlank()
                ],
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ),
                'label' => 'formulario.calificacion.general'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'subcategoria_infraestructura_escenario_type';
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(            
            'data_class' => 'LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura'
        ));
    }

    

}
