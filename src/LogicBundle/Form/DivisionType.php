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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DivisionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
        
    
        if($options['validar'] == false || $options['validar'] == null)
        {

            $builder
            //->addEventSubscriber(new AddDivisionTipoReservaFieldSubscriber())
            ->add('categoriaDivision', EntityType::class, array(
                'class' => 'LogicBundle:CategoriaDivision',
                "required" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.categoria_division',
                'empty_data' => null,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->add('nombre', null, [
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.division',
                "required" => true,
                "constraints" => $noVacio,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
            ])
            ->addEventSubscriber(new AddDivisionDisciplinaFieldSubscriber())
            ->add('disciplina', EntityType::class, array(
                'class' => 'LogicBundle:Disciplina',
                "required" => false,
                "mapped" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.disciplinas',
                'empty_data' => null,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    if($options['escenarioId'] == null){
                        $qb = $repository->createQueryBuilder('disciplina')
                            ->innerJoin('disciplina.disciplinas', 'disciplinas')
                        ;
                    }else{
                        $qb = $repository->createQueryBuilder('disciplina')
                            ->innerJoin('disciplina.disciplinas', 'disciplinas')
                            ->where('disciplinas.escenarioDeportivo = :escenario_deportivo')
                            ->setParameter('escenario_deportivo', $options['escenarioId'])
                        ;
                    }
                    
                    return $qb;
                },
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->addEventSubscriber(new AddDivisionTendenciaFieldSubscriber())
            ->add('tendencia', EntityType::class, array(
                'class' => 'LogicBundle:Tendencia',
                "required" => false,
                "mapped" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tendencias',
                'empty_data' => null,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    if($options['escenarioId'] == null){
                        $qb = $repository->createQueryBuilder('tendencia')
                            ->innerJoin('tendencia.tendencias', 'tendencias')
                        ;
                    }else{
                        $qb = $repository->createQueryBuilder('tendencia')
                        ->innerJoin('tendencia.tendencias', 'tendencias')
                        ->where('tendencias.escenarioDeportivo = :escenario_deportivo')
                        ->setParameter('escenario_deportivo', $options['escenarioId'])
                    ;
                    }
                    
                    return $qb;
                },
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->add('tipoReservaEscenarioDeportivoDivisiones', CollectionType::class, array(
                'entry_type' => TipoReservaCollectionType::class,
                'entry_options' => array('label' => false),
                //"mapped" => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'prototype' => true,
                'prototype_name' => '__tipoReservaEscenarioDeportivoDivisiones_prot__',
                'attr' => array(
                    'class' => 'tiposReserva-collection',
                ),
                'by_reference' => false,
                //'data' => $options['tiposReservas'],
                'entry_options'  => array(
                    'escenarioId' => $options['escenarioId']
                ),
            ))
            ->add('lunes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Lunes' => null,
                ),
            ])
            ->add('martes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Martes' => null,
                ),
            ])
            ->add('miercoles', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Miercoles' => null,
                ),
            ])
            ->add('jueves', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Jueves' => null,
                ),
            ])
            ->add('viernes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Viernes' => null,
                ),
            ])
            ->add('sabado', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Sabado' => null,
                ),
            ])
            ->add('domingo', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Domingo' => null,
                ),
            ])
            ->add('hora_inicial_lunes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_lunes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_martes',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_martes',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_inicial_miercoles',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_miercoles', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_jueves', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_jueves', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_viernes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_viernes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_sabado', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_sabado', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_domingo', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_domingo', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))


            ->add('hora_inicial2_lunes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_lunes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))


            ->add('hora_inicial2_martes',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_martes',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_inicial2_miercoles',TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_miercoles', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_jueves', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_jueves', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_viernes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_viernes', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_sabado', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_sabado', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_domingo', TextType::class, array(
                "required" => false,
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_domingo', TextType::class, array(
                "required" => false,
                'data' => $options['domingoFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('edad_minima', IntegerType::class, [
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.edad_minima',
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off','min' => 0)
            ])
            ->add('necesitaAprobacion', CheckboxType::class, array(
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.requiere_aprobacion',
                'required' => false,              
            ))
        ;


        }else{

            $builder
            //->addEventSubscriber(new AddDivisionTipoReservaFieldSubscriber())
            ->add('categoriaDivision', EntityType::class, array(
                'class' => 'LogicBundle:CategoriaDivision',
                "required" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.categoria_division',
                'empty_data' => null,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->add('nombre', null, [
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.division',
                "required" => true,
                "constraints" => $noVacio,
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
            ])
            ->addEventSubscriber(new AddDivisionDisciplinaFieldSubscriber())
            ->add('disciplina', EntityType::class, array(
                'class' => 'LogicBundle:Disciplina',
                "required" => false,
                "mapped" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.disciplinas',
                'empty_data' => null,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    if($options['escenarioId'] == null){
                        $qb = $repository->createQueryBuilder('disciplina')
                            ->innerJoin('disciplina.disciplinas', 'disciplinas')
                        ;
                    }else{
                        $qb = $repository->createQueryBuilder('disciplina')
                            ->innerJoin('disciplina.disciplinas', 'disciplinas')
                            ->where('disciplinas.escenarioDeportivo = :escenario_deportivo')
                            ->setParameter('escenario_deportivo', $options['escenarioId'])
                        ;
                    }
                    
                    return $qb;
                },
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->addEventSubscriber(new AddDivisionTendenciaFieldSubscriber())
            ->add('tendencia', EntityType::class, array(
                'class' => 'LogicBundle:Tendencia',
                "required" => false,
                "mapped" => false,
                "constraints" => $noVacio,
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tendencias',
                'empty_data' => null,
                'multiple' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    if($options['escenarioId'] == null){
                        $qb = $repository->createQueryBuilder('tendencia')
                            ->innerJoin('tendencia.tendencias', 'tendencias')
                        ;
                    }else{
                        $qb = $repository->createQueryBuilder('tendencia')
                        ->innerJoin('tendencia.tendencias', 'tendencias')
                        ->where('tendencias.escenarioDeportivo = :escenario_deportivo')
                        ->setParameter('escenario_deportivo', $options['escenarioId'])
                    ;
                    }
                    
                    return $qb;
                },
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))
            ->add('tipoReservaEscenarioDeportivoDivisiones', CollectionType::class, array(
                'entry_type' => TipoReservaCollectionType::class,
                'entry_options' => array('label' => false),
                //"mapped" => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'prototype' => true,
                'attr' => array(
                    'class' => 'tiposReserva-collection',
                ),
                'by_reference' => false,
                //'data' => $options['tiposReservas'],
                'entry_options'  => array(
                    'escenarioId' => $options['escenarioId']
                ),
            ))
            ->add('lunes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Lunes' => null,
                ),
            ])
            ->add('martes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Martes' => null,
                ),
            ])
            ->add('miercoles', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Miercoles' => null,
                ),
            ])
            ->add('jueves', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Jueves' => null,
                ),
            ])
            ->add('viernes', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Viernes' => null,
                ),
            ])
            ->add('sabado', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Sabado' => null,
                ),
            ])
            ->add('domingo', ChoiceType::class, [
                'required' => false,
                "mapped" => false,
                'choices'  => array(
                    'Domingo' => null,
                ),
            ])
            ->add('hora_inicial_lunes', TextType::class, array(
                "required" => false,
                'data' => $options['lunesInicio'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_lunes', TextType::class, array(
                "required" => false,
                'data' => $options['lunesFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_martes',TextType::class, array(
                "required" => false,
                'data' => $options['martesInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_martes',TextType::class, array(
                "required" => false,
                'data' => $options['martesFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_inicial_miercoles',TextType::class, array(
                "required" => false,
                'data' => $options['miercolesInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_miercoles', TextType::class, array(
                "required" => false,
                'data' => $options['miercolesFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_jueves', TextType::class, array(
                "required" => false,
                'data' => $options['juevesInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_jueves', TextType::class, array(
                "required" => false,
                'data' => $options['juevesFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_viernes', TextType::class, array(
                "required" => false,
                'data' => $options['viernesInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_viernes', TextType::class, array(
                "required" => false,
                'data' => $options['viernesFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_sabado', TextType::class, array(
                "required" => false,
                'data' => $options['sabadoInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_sabado', TextType::class, array(
                "required" => false,
                'data' => $options['sabadoFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial_domingo', TextType::class, array(
                "required" => false,
                'data' => $options['domingoInicial'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final_domingo', TextType::class, array(
                "required" => false,
                'data' => $options['domingoFinal'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))


            ->add('hora_inicial2_lunes', TextType::class, array(
                "required" => false,
                'data' => $options['lunesInicio2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_lunes', TextType::class, array(
                "required" => false,
                'data' => $options['lunesFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))


            ->add('hora_inicial2_martes',TextType::class, array(
                "required" => false,
                'data' => $options['martesInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_martes',TextType::class, array(
                "required" => false,
                'data' => $options['martesFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_inicial2_miercoles',TextType::class, array(
                "required" => false,
                'data' => $options['miercolesInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_miercoles', TextType::class, array(
                "required" => false,
                'data' => $options['miercolesFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_jueves', TextType::class, array(
                "required" => false,
                'data' => $options['juevesInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_jueves', TextType::class, array(
                "required" => false,
                'data' => $options['juevesFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_viernes', TextType::class, array(
                "required" => false,
                'data' => $options['viernesInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_viernes', TextType::class, array(
                "required" => false,
                'data' => $options['viernesFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_sabado', TextType::class, array(
                "required" => false,
                'data' => $options['sabadoInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_sabado', TextType::class, array(
                "required" => false,
                'data' => $options['sabadoFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))

            ->add('hora_inicial2_domingo', TextType::class, array(
                "required" => false,
                'data' => $options['domingoInicial2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('hora_final2_domingo', TextType::class, array(
                "required" => false,
                'data' => $options['domingoFinal2'],
                'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
            ))
            ->add('edad_minima', IntegerType::class, [
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.edad_minima',
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off','min' => 0)
            ])
            ->add('necesitaAprobacion', CheckboxType::class, array(
                'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.requiere_aprobacion',
                'required' => false,              
            ))
        ;

        } 
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Division',
            'escenarioId' => null,
            'lunesInicio' => null,
            'lunesFinal' =>  null,
            'martesInicial' => null,
            'martesFinal' =>   null,
            'miercolesInicial' => null,
            'miercolesFinal' =>  null,
            'juevesInicial' => null,
            'juevesFinal' => null,
            'viernesInicial' => null,
            'viernesFinal' => null,
            'sabadoInicial' => null,
            'sabadoFinal' => null,
            'domingoInicial' => null,
            'domingoFinal'  => null,
            'lunesInicio2' => null,
            'lunesFinal2' =>   null,
            'martesInicial2' =>   null,
            'martesFinal2' =>  null,
            'miercolesInicial2' => null,
            'miercolesFinal2' =>null,
            'juevesInicial2' =>  null,
            'juevesFinal2' =>   null,
            'viernesInicial2' =>null,
            'viernesFinal2' =>  null,
            'sabadoInicial2' =>  null,
            'sabadoFinal2' =>   null,
            'domingoInicial2' => null,
            'domingoFinal2' =>  null,
            'validar' => null,    
            'tiposReservas' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'division_type';
    }

}
