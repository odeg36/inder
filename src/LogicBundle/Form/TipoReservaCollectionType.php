<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use AdminBundle\Form\EventListener\AddTipoReservaFieldSubscriber;

class TipoReservaCollectionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        $builder
                ->add('tipoReserva', EntityType::class, array(
                    'class' => 'LogicBundle:TipoReserva',
                    "required" => false,
                    "constraints" => $noVacio,
                    "mapped" => false,
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tipo_reserva',
                    'empty_data' => null,
                    'multiple' => false,
                    'query_builder' => function (EntityRepository $repository) use ($options) {
                        if ($options['escenarioId'] == null) {
                            $qb = $repository->createQueryBuilder('tipoReserva')
                                    ->innerJoin('tipoReserva.tipoReservaEscenarioDeportivos', 'tipoReservaEscenarioDeportivos')
                            ;
                        } else {
                            $qb = $repository->createQueryBuilder('tipoReserva')
                                    ->innerJoin('tipoReserva.tipoReservaEscenarioDeportivos', 'tipoReservaEscenarioDeportivos')
                                    ->where('tipoReservaEscenarioDeportivos.escenarioDeportivo = :escenario_deportivo')
                                    ->setParameter('escenario_deportivo', $options['escenarioId'])
                            ;
                        }
                        return $qb;
                    },
                    'attr' => [
                        'class' => 'form-control tipo_reserva', 'autocomplete' => 'off'
                    ]
                ))
                ->addEventSubscriber(new AddTipoReservaFieldSubscriber())
                ->add('bloqueTiempo', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.bloque_tiempo',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control campoBloque',
                        'autocomplete' => 'off',
                        'min' => 15,
                        'max' => 60,
                        'step' => 15)
                ])
                ->add('tiempo_minimo', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tiempo_minimo',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control campoMinimoTiempo',
                        'autocomplete' => 'off',
                        'min' => 1)
                ])
                ->add('tiempo_maximo', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tiempo_maximo',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control campoMaximoTiempo',
                        'autocomplete' => 'off',
                        'min' => 1)
                ])
                ->add('dias_previos_reserva', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.dias_previos_reserva',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control', 'autocomplete' => 'off', 'min' => 1)
                ])
                ->add('usuarios_minimos', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.usuarios_minimo',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control campoUsuariosMinimos', 'autocomplete' => 'off', 'min' => 1)
                ])
                ->add('usuarios_maximos', IntegerType::class, [
                    'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.usuarios_maximo',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control campoUsuariosMaximos', 'autocomplete' => 'off', 'min' => 1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision',
            'escenarioId' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'tiposReserva_type';
    }

}
