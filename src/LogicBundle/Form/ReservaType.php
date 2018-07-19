<?php

namespace LogicBundle\Form;

use AdminBundle\Form\EventListener\Reserva\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\Reserva\AddEscenarioFieldSubscriber;
use Doctrine\ORM\EntityRepository;
use LogicBundle\Form\ProgramacionReservaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    ))
        );
        $object = $builder->getData();

        $em = $options['em'];


        if ($options['paso'] == 1) {
            $isSuperAdminOrganismoDeportivo = $options['isSuperAdminOrganismoDeportivo'];


            if ($options['valida'] == 1) {
                $palabra = $em->getRepository('LogicBundle:TipoReserva')->findOneBy(array('nombre' => "Organismo Deportivo"));

                $optionsTipoReserva = array(
                    'class' => 'LogicBundle:TipoReserva',
                    'required' => true,
                    'placeholder' => false,
                    'expanded' => true,
                    'multiple' => false,
                    'data' => $palabra,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('tipoReserva')
                                        ->where('tipoReserva.nombre = :nombreTipoReservaEvento')
                                        ->andWhere('tipoReserva.bloquea = :bloquea')
                                        ->setParameter('nombreTipoReservaEvento', 'Organismo Deportivo')
                                        ->setParameter('bloquea', false);
                    }
                );
            } else {

                if ($isSuperAdminOrganismoDeportivo == false) {
                    $practica = $em->getRepository('LogicBundle:TipoReserva')->findOneBy(array('nombre' => "Practica Libre"));
                    $optionsTipoReserva = array(
                        'class' => 'LogicBundle:TipoReserva',
                        'required' => true,
                        'placeholder' => false,
                        'constraints' => $noVacio,
                        'expanded' => true,
                        'multiple' => false,
                        'data' => $practica,
                        'query_builder' => function(EntityRepository $er) use ($options) {
                            return $er->createQueryBuilder('tipoReserva')
                                            ->where('tipoReserva.nombre = :nombreTipoReserva')
                                            ->andWhere('tipoReserva.bloquea = :bloquea')
                                            ->setParameter('nombreTipoReserva', 'Practica Libre')
                                            ->setParameter('bloquea', false);
                        }
                    );
                } else {
                    $optionsTipoReserva = array(
                        'class' => 'LogicBundle:TipoReserva',
                        'constraints' => $noVacio,
                        'required' => true,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'query_builder' => function(EntityRepository $er) use ($options) {
                            return $er->createQueryBuilder('tipoReserva')
                                            ->Where('tipoReserva.bloquea = :bloquea')
                                            ->setParameter('bloquea', false);
                        }
                    );
                }
            }
            $reservaDisponible = 'false';
            if ($object->getId() != null) {
                $reservaDisponible = 'true';
            }
            $dias = [];
            foreach ($object->getDiaReserva() as $diaReserva) {
                array_push($dias, $diaReserva->getDia());
            }
            if ($options['validaPeersonaNatural']) {
                $barrio = [];
                $builder
                        ->addEventSubscriber(new AddBarrioFieldSubscriber())
                        ->addEventSubscriber(new AddEscenarioFieldSubscriber())
                        ->add('seleccion', ChoiceType::class, array(
                            'expanded' => true,
                            'multiple' => false,
                            'mapped' => false,
                            'data' => $options['tendenciaDisciplina'],
                            'attr' => array('class' => 'seleccion_tendencia_disciplina'),
                            'choices' => array(
                                'Disciplina' => 'Disciplina',
                                'Tendencia' => 'Tendencia',
                            )
                        ))
                        ->add('disciplina', EntityType::class, array(
                            "class" => "LogicBundle:Disciplina",
                            'empty_data' => null,
                            'required' => false))
                        ->add('tendenciaEscenarioDeportivo', EntityType::class, array(
                            "class" => "LogicBundle:TendenciaEscenarioDeportivo",
                            'empty_data' => null,
                            'required' => false))
                        ->add('tipoReserva', EntityType::class, $optionsTipoReserva)
                        ->add('fechaInicio', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'constraints' => $noVacio,
                            'attr' => array('class' => 'form-control fechaInicio', 'solo' => 1),
                        ))
                        ->add('fechaFinal', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr' => array('class' => 'form-control fechaFin'),
                        ))
                        ->add('reservaDisponible', HiddenType::class, array(
                            'mapped' => false,
                            'data' => $reservaDisponible
                        ))
                        ->add('tipoIdentificacion', EntityType::class, array(
                            'class' => 'LogicBundle:TipoIdentificacion',
                            'mapped' => false,
                            'attr' => array(
                                'class' => 'form-control',
                                'onchange' => 'inder.reserva.asignarNoDocumentoReservaPaso1(this)',
                                'placeholder' => 'formulario_reserva.labels.paso_tres.tipo_documento',
                            ),
                            'query_builder' => function(EntityRepository $er) use ($options) {
                                return $er->createQueryBuilder('tipoIdentificacion')
                                        ->where('tipoIdentificacion.nombre != :nombreTipoIdentificacionTarjeta')
                                        ->andWhere('tipoIdentificacion.nombre != :nombreTipoIdentificacionRegistro')
                                        ->setParameter('nombreTipoIdentificacionTarjeta', 'Tarjeta de Identidad')
                                        ->setParameter('nombreTipoIdentificacionRegistro', 'Registro Civil');
                            }
                        ))
                        ->add('numeroIdentificacion', TextType::class, array(
                            'mapped' => false,
                            'attr' => array(
                                'class' => 'form-control',
                                'placeholder' => 'formulario_reserva.labels.paso_tres.no_documento'
                            )
                        ))
                        ->add('jornada', ChoiceType::class, [
                            'expanded' => false,
                            'multiple' => false,
                            'mapped' => false,
                            'data' => $options['jornada'],
                            'attr' => array(
                                'class' => 'jornada',
                                'onchange' => 'inder.reserva.cambioJornada(this)',),
                            'choices' => array(
                                'Seleccione una opción' => "",
                                'Mañana' => 1,
                                'Tarde' => 2,
                            )]
                        )
                        ->add('programaciones', CollectionType::class, [
                            'entry_type' => ProgramacionReservaType::class,
                            'entry_options' => array(
                                'em' => $em,
                                'reserva' => $options['reserva']
                            ),
                            'allow_add' => true,
                            'allow_delete' => true,
                            'prototype' => true,
                            'by_reference' => false,
                            'required' => false,
                            'label' => ' ',
                            'attr' => [
                                'class' => 'coleccionDivisiones',
                                'autocomplete' => 'off'
                            ],
                            'prototype_name' => '__parent_name__'
                ]);
            } else {
                $barrio = [];
                $builder
                        ->addEventSubscriber(new AddBarrioFieldSubscriber())
                        ->addEventSubscriber(new AddEscenarioFieldSubscriber())
                        ->add('seleccion', ChoiceType::class, array(
                            'expanded' => true,
                            'multiple' => false,
                            'mapped' => false,
                            'data' => $options['tendenciaDisciplina'],
                            'attr' => array('class' => 'seleccion_tendencia_disciplina'),
                            'choices' => array(
                                'Disciplina' => 'Disciplina',
                                'Tendencia' => 'Tendencia',
                    )))
                        ->add('disciplina', EntityType::class, array(
                            "class" => "LogicBundle:Disciplina",
                            'empty_data' => null,
                            'required' => false))
                        ->add('tendenciaEscenarioDeportivo', EntityType::class, array(
                            "class" => "LogicBundle:TendenciaEscenarioDeportivo",
                            'empty_data' => null,
                            'required' => false))
                        ->add('tipoReserva', EntityType::class, $optionsTipoReserva)
                        ->add('fechaInicio', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr' => array('class' => 'form-control fechaInicio'),
                        ))
                        ->add('fechaFinal', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr' => array('class' => 'form-control fechaFin'),
                        ))
                        ->add('reservaDisponible', HiddenType::class, array(
                            'mapped' => false,
                            'data' => $reservaDisponible
                        ))
                        ->add('tipoIdentificacion', EntityType::class, array(
                            'class' => 'LogicBundle:TipoIdentificacion',
                            'mapped' => false,
                            'attr' => array(
                                'class' => 'form-control',
                                'onchange' => 'inder.reserva.asignarNoDocumentoReservaPaso1(this)',
                                'placeholder' => 'formulario_reserva.labels.paso_tres.tipo_documento',
                            ),
                            'query_builder' => function(EntityRepository $er) use ($options) {
                                return $er->createQueryBuilder('tipoIdentificacion')
                                        ->where('tipoIdentificacion.nombre != :nombreTipoIdentificacionTarjeta')
                                        ->andWhere('tipoIdentificacion.nombre != :nombreTipoIdentificacionRegistro')
                                        ->setParameter('nombreTipoIdentificacionTarjeta', 'Tarjeta de Identidad')
                                        ->setParameter('nombreTipoIdentificacionRegistro', 'Registro Civil');
                            }
                        ))
                        ->add('numeroIdentificacion', TextType::class, array(
                            'mapped' => false,
                            'attr' => array(
                                'class' => 'form-control',
                                'placeholder' => 'formulario_reserva.labels.paso_tres.no_documento'
                            )
                        ))->add('programaciones', CollectionType::class, [
                    'entry_type' => ProgramacionReservaType::class,
                    'entry_options' => array(
                        'em' => $em,
                        'reserva' => $options['reserva']
                    ),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    'required' => false,
                    'label' => ' ',
                    'attr' => [
                        'class' => 'coleccionDivisiones',
                        'autocomplete' => 'off'
                    ],
                    'prototype_name' => '__parent_name__'
                ]);
            }
        }
        if ($options['paso'] == 2) {

            $object = $builder->getData();
            $idReserva = $object->getId();
            $idEscenario = $object->getEscenarioDeportivo()->getId();
            //obtener y formatear divisiones de un escenario
            $dql = "SELECt dr From LogicBundle:DivisionReserva dr 
                    INNER JOIN LogicBundle:Reserva r WITH dr.reserva = r.id 
                    WHERE r.escenarioDeportivo = :escenario
                    AND r.fechaInicio <= :fechaInicial AND r.fechaFinal >= :fechaFinal
                    AND r.id != :id
                    AND r.estado != :rechazado
                    AND r.completada = :completada";

            $parameters = array();
            $parameters["fechaInicial"] = $object->getFechaInicio();
            $parameters["fechaFinal"] = $object->getFechaFinal();
            $parameters["escenario"] = $idEscenario;
            $parameters["rechazado"] = "Rechazado";
            $parameters["completada"] = true;
            $parameters["id"] = $idReserva;
            $query = $em->createQuery($dql);
            foreach ($parameters as $name => $value) {
                $query->setParameter($name, $value);
            }
            $divisionesReserva = $query->getResult();
            $divisionesOcupadas = [];
            $divisionesOcupadas[] = 0;
            foreach ($divisionesReserva as $divisionReserva) {
                foreach ($divisionReserva->getReserva()->getProgramaciones() as $prog) {
                    if ($prog->getInicioManana() || $prog->getInicioTarde()) {
                        foreach ($object->getProgramaciones() as $key => $programacion) {
                            if ($programacion->getInicioManana()) {
                                if ($prog->getInicioManana() <= $programacion->getInicioManana() && $prog->getFinManana() >= $programacion->getFinManana()) {
                                    if (!in_array($divisionReserva->getDivision()->getId(), $divisionesOcupadas)) {
                                        $divisionesOcupadas[] = $divisionReserva->getDivision()->getId();
                                    }
                                }
                            }
                            if ($programacion->getInicioTarde()) {
                                if ($prog->getInicioTarde() <= $programacion->getInicioTarde() && $prog->getFinTarde() >= $programacion->getFinTarde()) {
                                    if (!in_array($divisionReserva->getDivision()->getId(), $divisionesOcupadas)) {
                                        $divisionesOcupadas[] = $divisionReserva->getDivision()->getId();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $divisionesOferta = $em->getRepository("LogicBundle:Oferta")->obtenerDivisionesOcupadas($object);
            $divisionesBloqueadas = $em->getRepository("LogicBundle:BloqueoEscenario")->buscarDivisionesBloqueadasReserva($object);
            foreach ($divisionesBloqueadas as $bloqueoEscenario) {
                foreach ($bloqueoEscenario->getDivisionesBLoqueo() as $divisionBloqueo) {
                    if (!in_array($divisionBloqueo->getDivision()->getId(), $divisionesOcupadas)) {
                    $divisionesOcupadas[] = $divisionBloqueo->getDivision()->getId(); 
                }
                }
            }
            foreach ($divisionesOferta as $division) {
                if (!in_array($division->getId(), $divisionesOcupadas)) {
                    $divisionesOcupadas[] = $division->getId(); 
                }
            }
            $divisiones = $em->getRepository("LogicBundle:Division")
                    ->createQueryBuilder('division')
                    ->where("division.id NOT IN (:divisiones)")
                    ->andWhere("division.escenarioDeportivo = :escenario")
                    ->setParameter("divisiones", $divisionesOcupadas)
                    ->setParameter("escenario", $idEscenario)
                    ->getQuery()->getResult();
            $disponibles = [];
            foreach($divisiones as $division){
                $disponibles[$division->getNombre()] = $division->getId();
            }
            $divisioneSeleccionadas = $em->getRepository("LogicBundle:Division")
                    ->createQueryBuilder('d')
                    ->join('d.reservas', 'dr')
                    ->where('dr.reserva = :reserva')
                    ->setParameter('reserva', $object->getId())
                    ->getQuery()
                    ->getResult();
            $seleccion = [];
            foreach ($divisioneSeleccionadas as $division) {
                $seleccion[$division->getId()] = $division->getId();
            }
            $builder
                    ->add('divisiones', ChoiceType::class, array(
                        'required' => true,
                        'placeholder' => false,
                        'choices' => $disponibles,
                        'label' => 'formulario_reserva.labels.paso_dos.divisiones',
                        'mapped' => false,
                        'expanded' => true,
                        'data' => $seleccion,
                        'multiple' => true,
                        'attr' => array('class' => 'lista-escenario col-lg-10 col-md-10 col-lg-10 col-md-10 '),
                    ))
                    ->add('divisionesOcupadas', ChoiceType::class, array(
                        'choices' => null,
                        'mapped' => false,
                        'required' => false,
                        'expanded' => true,
                        'disabled' => true,
                        'placeholder' => false,
                        'multiple' => false,
                        'choice_attr' => function($key, $val, $index) {
                            $disabled = false;
                            return $disabled ? ['disabled' => 'disabled'] : [];
                        },
                        'attr' => array('class' => 'inactivo-texto disabled col-lg-10 col-md-10 col-lg-10 col-md-10 '),
            ));
        }
        if ($options['paso'] == 31) {

            $builder
                    ->add('divisiones', CollectionType::class, [
                        'entry_type' => DivisionesReservaType::class,
                        'entry_options' => array(
                            'em' => $em,
                            'organizacionDeporId' => $options['organizacionDeporId'],
                            'organismoDeportistas' => $options['organismoDeportistas'],
                            'reserva' => $options['reserva']
                        ),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ' ',
                        'attr' => [
                            'class' => 'coleccionDivisiones',
                            'autocomplete' => 'off'
                        ],
                        'prototype_name' => '__parent_name__'
            ]);
        }
        if ($options['paso'] == 4) {
            $object = $builder->getData();

            $builder
                    ->add('terminos', ChoiceType::class, array(
                        'choices' => array(
                            'Aceptar Terminos y Condiciones ' => 1,
                        ),
                        'mapped' => false,
                        'required' => true,
                        'expanded' => true,
                        'multiple' => true,
                    ))
            ;
        }
        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_registro.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            if (null != $event->getData()) {
                $reserva = $event->getData();
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Reserva',
            'paso' => null,
            'id' => null,
            'em' => null,
            'valida' => null,
            'isSuperAdminOrganismoDeportivo' => null,
            'organizacionDeporId' => null,
            'isGestorEscenario' => null,
            'ROLE_ORGANISMO_DEPORTIVO' => null,
            'validaPeersonaNatural' => null,
            'organismoDeportistas' => null,
            'tendenciaDisciplina' => null,
            'usuario' => null,
            'reserva' => null,
            'jornada' => null
        ));
    }

}
