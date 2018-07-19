<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AdminBundle\Form\EventListener\PlanAnualMetodologico\AddComponenteFieldSubscriber;

class ContenidoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        $object = $builder->getData();

        $componente = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'required' => true,
            "constraints" => $noVacio,
            'empty_data' => null,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('c')
                                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                                ->where('planAnualMetodologico.id = :id_pam')
                                ->setParameter('id_pam', $options['planAnualMetodologicoId']);
            },
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        /* if($object->getComponentes()->getContenidos()){
          $Componente['data'] = $object->getComponentes();
          } */

        if ($options['enfoqueId'] == 1) {
            $builder
                    ->add('componente', EntityType::class, $componente)
                    ->add('nombre', TextType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nombre',
                        "constraints" => $noVacio,
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('objetivo', TextType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.objetivo',
                        "constraints" => $noVacio,
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('ponderacion', NumberType::class, [
                        'required' => true,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control ponderacion2Off', 'autocomplete' => 'off'
                        ]
            ]);
        }

        if ($options['enfoqueId'] == 2) {
            $builder
                    ->add('componente', EntityType::class, $componente)
                    ->add('nombre', TextType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_uno.nombre',
                        "constraints" => $noVacio,
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('tema', TextareaType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.tema',
                        'attr' => array(
                            'class' => 'form-control',
                            'rows' => 6,
                            'autocomplete' => 'off')
                    ))
                    ->add('objetivo', TextType::class, array(
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.objetivo',
                        "constraints" => $noVacio,
                        'attr' => array(
                            'class' => 'form-control',
                            'autocomplete' => 'off')
                    ))
                    ->add('actores', TextType::class, [
                        'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.actores',
                        'required' => true,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Contenido',
            'planAnualMetodologicoId' => null,
            'enfoqueId' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'contenido_type';
    }

}
