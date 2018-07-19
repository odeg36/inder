<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SubCategoriaInfraestructuraType extends AbstractType {

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
                    'class' => 'form-control', 'autocomplete' => 'off'
                )
            ))
            ->add('campoInfraestructuras', EntityType::class, array(
                'class' => 'LogicBundle:CampoInfraestructura',
                "required" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_categoria_infraestructura.labels.tipoCategoria',
                'empty_data' => null,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))        
                       
            
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\SubcategoriaInfraestructura',
            'escenarioId' => null            
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'subcategoria_infraestructura_type';
    }

}
