<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AdminBundle\Form\EventListener\AddDivisionTipoReservaFieldSubscriber;
use AdminBundle\Form\EventListener\AddDivisionDisciplinaFieldSubscriber;
use AdminBundle\Form\EventListener\AddDivisionTendenciaFieldSubscriber;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TemaModeloType extends AbstractType {

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
            ->add('temaModelo', EntityType::class, array(
                'class' => 'LogicBundle:TemaModelo',
                "required" => false,
                "mapped" => true,
                "constraints" => $noVacio,
                'label' => 'Tema',
                'empty_data' => null,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
                
            ))
            ->add('nivel', null, [
                'label' => 'Nivel',
                "required" => true,
                "constraints" => $noVacio,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\TemaPorComuna',
            'escenarioId' => null            
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'tema_modelo_type';
    }

}
