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

use LogicBundle\Form\SubCategoriaEventoType;
use AdminBundle\Form\EventListener\AddCategoriaEventoFieldSubscriber;

class CategoriaEventoType extends AbstractType {

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
            ->add('categoria', EntityType::class, array(
                'class' => 'LogicBundle:CategoriaEvento',
                'placeholder' => '',
                'required'=> false, 
                "label" => "formulario_evento.labels.configuracion.categoria",
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off',
                    'onchange' => 'inder.evento.subcategorias(this)',
                ]
            ))
            
            ->add('subcategorias', EntityType::class, array(
                'class' => 'LogicBundle:SubCategoriaEvento',
                'placeholder' => '',
                'required'=> false,
                'multiple' => true,
                "label" => "formulario_evento.labels.configuracion.subcategoria",
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ;
             
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => "LogicBundle\Entity\CategoriaSubcategoria",
            'categorias' => null,
            'subcategorias' => null
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'categorias_evento_type';
    }

}
