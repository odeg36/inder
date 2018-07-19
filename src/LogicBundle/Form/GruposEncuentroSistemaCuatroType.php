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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;

class GruposEncuentroSistemaCuatroType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    )),
        );
        $builder
                ->add('equipoEvento', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                                ->where('e.evento = :id_evento')
                                ->andWhere('e.estado = :estado')
                                ->setParameter('id_evento', $options['eventoId'])
                                ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_1',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento2', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_2',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento3', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_3',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento4', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_4',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento5', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_1',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento6', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_2',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento7', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_3',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento8', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_4',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento9', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_1',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento10', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_2',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento11', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_3',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento12', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_4',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento13', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_1',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento14', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_2',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento15', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_3',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('equipoEvento16', EntityType::class, array(
                    'class' => 'LogicBundle:EquipoEvento',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('e')
                        ->where('e.evento = :id_evento')
                        ->andWhere('e.estado = :estado')
                        ->setParameter('id_evento', $options['eventoId'])
                        ->setParameter('estado', 1);
                    },
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo_4',
                    'empty_data' => null,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('agregar', SubmitType::class, array(
                    'label' => 'formulario_escalera.guardar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\GruposEncuentroSistemaCuatro',
            'em' => null,
            'eventoId' => null,
        ));
    }

}
