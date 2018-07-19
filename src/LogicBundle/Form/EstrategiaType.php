<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AdminBundle\Form\EventListener\PlanAnualMetodologico\AddEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\PlanAnualMetodologico\AddOfertaFieldSubscriber;

class EstrategiaType extends AbstractType {

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

        $estrategia = array(
            'class' => 'LogicBundle:Estrategia',
            'placeholder' => '',
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.estrategia',
            'required' => true,
            'empty_data' => null,
            'mapped' => false,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('e')
                                ->Where('e.activo = true');
            },
            'data' => $options['estrategias'],
            'attr' => [
                'onchange' => 'inder.plan_anual_metodologico.actualizarOfertas(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        /* $oferta = array(
          'class' => 'LogicBundle:Oferta',
          'label' => 'formulario.oferta.title',
          'required' => true,
          'empty_data' => null,
          'query_builder' => function(EntityRepository $er) use ($options){
          return $er->createQueryBuilder('o')
          ->join('o.planAnualMetodologico', 'planAnualMetodologico')
          ->where('planAnualMetodologico.id = :id_pam')
          ->setParameter('id_pam', $options['planAnualMetodologicoId']) ;
          },
          'data' => $options['ofertas'],
          'attr' => [
          'class' => 'form-control', 'autocomplete' => 'off'
          ]
          ); */

        $builder
                ->add('estrategia', EntityType::class, $estrategia)
                ->addEventSubscriber(new AddOfertaFieldSubscriber());
        /* ->add('estrategia', EntityType::class, array(
          'class' => 'LogicBundle:Estrategia',
          'placeholder' => 'formulario.oferta.select_estrategia',
          'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.estrategia',
          'mapped' => false,
          'label_attr' => array('class' => 'label_estrategia'),
          'required' => true,
          'attr' => array(
          'onchange' => 'inder.plan_anual_metodologico.actualizarOfertas(this);',
          'class' => 'form-control ',
          )
          'class' => 'LogicBundle:Estrategia',
          'placeholder' => 'formulario.oferta.select_estrategia',
          'label' => 'formulario.oferta.estrategia',
          'mapped' => false,
          'empty_data' => null,
          'attr' => [
          'class' => 'form-control', 'autocomplete' => 'off'
          ]
          )) */
        /* ->add('oferta', EntityType::class, array(
          'required' => true,
          "constraints" => $noVacio,
          'class' => 'LogicBundle:Oferta',
          'multiple' => true,
          'label_attr' => array('class' => 'label_oferta'),
          'label' => 'formulario.oferta.title',
          'data' => $options['ofertas'],
          'attr' => array(
          'class' => 'form-control', 'autocomplete' => 'off'
          ),
          'query_builder' => function (EntityRepository $repository) use ($estrategia) {
          $qb = $repository->createQueryBuilder('oferta')
          ->innerJoin('oferta.estrategia', 'estrategia')
          ->where('estrategia.id = :estrategia')
          ->orderBy("oferta.nombre", 'DESC')
          ->setParameter('estrategia', $estrategia ?: 0)
          ;
          return $qb;
          }
          )); */
        /* (
          'class' => 'LogicBundle:Oferta',
          'empty_data' => null,
          'multiple' => true,
          'attr' => [
          'class' => 'form-control', 'autocomplete' => 'off'
          ]
          )); */
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Estrategia',
            'planAnualMetodologicoId' => null,
            'enfoqueId' => null,
            'ofertas' => null,
            'estrategias' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'estrategia_type';
    }

}
