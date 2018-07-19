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

class EncuestaOpcionType extends AbstractType {

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
            "label" => "formulario_encuesta.labels.nombre_opcion",
            "constraints" => $noVacio,
            'required'=> true,
            'attr' => array(
                'class' => 'form-control',
                "placeholder" => "formulario_encuesta.labels.nombre_opcion",
            )
        ));
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\EncuestaOpcion',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'opcion_respuesta_type';
    }

}
