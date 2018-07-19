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

use LogicBundle\Form\CategoriaEventoType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class InscripcionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            )),
        );
                
        if ($options['paso'] == 1) {
            $eventoObject = $builder->getData();
            
            $builder
                ->add('tieneInscripcionPublica', EntityType::class, array(
                    'class' => 'LogicBundle:CampoEvento',
                    'mapped' => false,
                    'required'=> false,
                    'placeholder' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'classFormulario')
                ))
                ->add('tienePreinscripcionPublica', EntityType::class, array(
                    'class' => 'LogicBundle:CampoEvento',
                    'mapped' => false,
                    'required'=> false,
                    'placeholder' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'classFormulario')
                ))
                ->add('tieneFormularioGanador', EntityType::class, array(
                    'class' => 'LogicBundle:CampoEvento',
                    'mapped' => false,
                    'required'=> false,
                    'placeholder' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'classFormulario')
                ))
                ->add('tieneFormularioRecambios', EntityType::class, array(
                    'class' => 'LogicBundle:CampoEvento',
                    'mapped' => false,
                    'required'=> false,
                    'placeholder' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => array('class' => 'classFormulario')
                ))
                ->add('guardar', SubmitType::class, array(
                    'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
                ;

        }
        if ($options['paso'] == 2) {
            $builder
                ->add('tieneInscripcionPublica')
                ->add('tienePreinscripcionPublica')
                ->add('tieneFormularioGandador')
                ->add('tieneFormularioRecambios')
            ;

        }
        if ($options['paso'] == 3) {
            $builder
                ->add('equipoEventos')
                ->add('jugadorEventos')
                ;

        }

        if ($options['paso'] == 4) {
      
            $builder
            
            ;
            
        }
        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Evento',
            'paso' => null,
            'personaporsexo'  => null,
            'limitanteporedad'  => null,
            'em' => null
            
        ));
    }

}
