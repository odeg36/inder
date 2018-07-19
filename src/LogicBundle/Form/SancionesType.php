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

class SancionesType extends AbstractType {

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


        $formOptionsSeleccion = array(
            'choices' => array(
                'Existente' => 'Existente',
                'Nuevo' => 'Nuevo',
            ),
            "constraints" => $noVacio,
            'required' => false,
            "label" => "Sancion",
            'placeholder' => '',
            'expanded' => true,
            'multiple' => false,
            'mapped' => false,
            'attr' => array(
                'class' => 'alinear',
            ),
        );

        if ($options['existente']) {
            $formOptionsSeleccion['data'] = $options['existente'];
        }

        $formOptionsSancion = array(
            'required' => false,
            'class' => 'LogicBundle:Sancion',
            'mapped' => false,
            'label' => 'Sancion',
            'attr' => [
                'class' => 'form-control',
                'onchange' => 'inder.evento.puntosPorSancion(this)',
            ],
        );

        if ($options['sancionEventoEditar']) {
            $formOptionsSancion['data'] = $options['sancionEventoEditar'];
        }


        $formOptionsPuntos = array(
            'required' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'form-control',
                'min' => 1,
            ]
        );

        if ($options['puntos']) {
            $formOptionsPuntos['data'] = $options['puntos'];
        }


        $builder
                ->add('seleccion', ChoiceType::class, $formOptionsSeleccion)
                ->add("sancion", EntityType::class, $formOptionsSancion)
                ->add("nombre", TextType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ))
                ->add('tipo_falta', EntityType::class, array(
                    'class' => 'LogicBundle:TipoFalta',
                    "constraints" => $noVacio,
                    'required' => false,
                    "label" => "Tipo Falta",
                    'placeholder' => '',
                    'expanded' => false,
                    'multiple' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'onchange' => 'inder.evento.puntos(this)',
                    ],
                ))
                ->add('descripcion', TextareaType::class, array(
                    'label' => 'Descripcion',
                    'required' => false,
                    'mapped' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 6,
                        'autocomplete' => 'off')
                ))
                ->add("puntaje_juego_limpio", IntegerType::class, $formOptionsPuntos)
                ->add('agregar', SubmitType::class, array(
                    'label' => 'Agregar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\SancionEvento',
            'em' => null,
            'sancionEventoEditar' => null,
            'existente' => null,
            'puntos' => null,
        ));
    }

}
