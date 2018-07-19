<?php

namespace LogicBundle\Form;

use LogicBundle\Entity\EscenarioSubCategoriaAmbiental;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubCategoriaAmbientalEscenarioType extends AbstractType {

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

        ->add('subCategoriaAmbiental', EntityType::class, array(
                'class' => 'LogicBundle:SubcategoriaAmbiental',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control tipo_entrada','autocomplete' => 'off',
                    'onchange' => 'inder.ambiental.mostrarCamposAmbiental(this);'
                ),
            ))
        ;
                
    }

        /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'subcategoria_ambiental_escenario_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(            
            'data_class' => EscenarioSubCategoriaAmbiental::class
        ));
    }

}
