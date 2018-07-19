<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;

class PlanClaseType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $object = $builder->getdata();

        if ($options['pamTecnico'] == null && $options['pamSocial'] == null) {
            $idTecnico = 0;
            $idSocial = 0;
        } else {
            $idTecnico = $options['pamTecnico']->getId();
            $idSocial = $options['pamSocial']->getId();
        }

        $roles = $options['roles'];
        if (in_array("ROLE_SUPER_ADMIN", $roles)) {
            $optionsEstrategia = array(
                'class' => 'LogicBundle:Estrategia',
                'placeholder' => '',
                'required' => true,
                "constraints" => $noVacio,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                                    ->Where('e.activo = true');
                },
                'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.estrategia',
                'empty_data' => null,
                'mapped' => false,
                'data' => $options['estrategia'],
                'attr' => [
                    'onchange' => 'inder.plan_clase.actualizarOfertas(this);',
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            );
        } else {
            $optionsEstrategia = array(
                'class' => 'LogicBundle:Estrategia',
                'placeholder' => '',
                'required' => true,
                "constraints" => $noVacio,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('e')
                                    ->join('e.ofertas', 'o')
                                    ->join('o.formador', 'formador')
                                    ->where('formador.id = :id_user')
                                    ->andWhere('e.activo = true')
                                    ->setParameter('id_user', $options['userId']);
                },
                'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.estrategia',
                'empty_data' => null,
                'mapped' => false,
                'data' => $options['estrategia'],
                'attr' => [
                    'onchange' => 'inder.plan_clase.actualizarOfertas(this);',
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            );
        }

        $optionsOferta = array(
            'class' => 'LogicBundle:Oferta',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'label' => 'formulario.oferta.title',
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsPlanMetodologico = array(
            'class' => 'LogicBundle:PlanAnualMetodologico',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('pam')
                                ->join('pam.ofertas', 'o')
                                ->join('o.formador', 'formador')
                                ->where('formador.id = :id_user')
                                ->andWhere('pam.enfoque = 1')
                                ->setParameter('id_user', $options['userId']);
            },
            'label' => 'titulo.guia_metodologica',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['pamTecnico'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarComponentes(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsComponente = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            /* 'query_builder' => function(EntityRepository $er) use ($idTecnico){
              return $er->createQueryBuilder('com')
              ->join('com.planAnualMetodologico', 'planAnualMetodologico')
              ->where('planAnualMetodologico.id = :id_pam_tecnico')
              ->setParameter('id_pam_tecnico', $idTecnico) ;
              }, */
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['componenteTecnico'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarContenidos(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsContenido = array(
            'class' => 'LogicBundle:Contenido',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            /* 'query_builder' => function(EntityRepository $er) use ($idTecnico){
              return $er->createQueryBuilder('con')
              ->join('con.componente', 'com')
              ->join('com.planAnualMetodologico', 'planAnualMetodologico')
              ->where('planAnualMetodologico.id = :id_pam_tecnico')
              ->setParameter('id_pam_tecnico', $idTecnico) ;
              }, */
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['contenidoTecnico'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarActividades(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsPlanMetodologico2 = array(
            'class' => 'LogicBundle:PlanAnualMetodologico',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('pam')
                                ->join('pam.ofertas', 'o')
                                ->join('o.formador', 'formador')
                                ->where('formador.id = :id_user')
                                ->andWhere('pam.enfoque = 2')
                                ->setParameter('id_user', $options['userId']);
            },
            'label' => 'titulo.guia_metodologica',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['pamSocial'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarComponentes2(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsModelo = array(
            'class' => 'LogicBundle:Modelo',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            /* 'query_builder' => function(EntityRepository $er) use ($idSocial){
              return $er->createQueryBuilder('m')
              ->join('m.componente', 'com')
              ->join('com.planAnualMetodologico', 'planAnualMetodologico')
              ->where('planAnualMetodologico.id = :id_pam_social')
              ->setParameter('id_pam_social', $idSocial) ;
              }, */
            'label' => 'formulario_plan_anual_metodologico.labels.paso_dos.modelo',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['modelo'],
            'attr' => [
                //'onchange' => 'inder.plan_clase.actualizarComponentes2(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsComponente2 = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            /* 'query_builder' => function(EntityRepository $er) use ($idSocial){
              return $er->createQueryBuilder('com')
              ->join('com.planAnualMetodologico', 'planAnualMetodologico')
              ->where('planAnualMetodologico.id = :id_pam_social')
              ->setParameter('id_pam_social', $idSocial) ;
              }, */
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['componenteSocial'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarContenidos2(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $optionsContenido2 = array(
            'class' => 'LogicBundle:Contenido',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            /* 'query_builder' => function(EntityRepository $er) use ($idSocial){
              return $er->createQueryBuilder('con')
              ->join('con.componente', 'com')
              ->join('com.planAnualMetodologico', 'planAnualMetodologico')
              ->where('planAnualMetodologico.id = :id_pam_social')
              ->setParameter('id_pam_social', $idSocial) ;
              }, */
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido',
            'empty_data' => null,
            'mapped' => false,
            'data' => $options['contenidoSocial'],
            'attr' => [
                'onchange' => 'inder.plan_clase.actualizarActividades2(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $builder
                ->add('estrategia', EntityType::class, $optionsEstrategia)
                ->add('oferta', EntityType::class, $optionsOferta)
                ->add('fechaInicio', DateType::class, array(
                    "label" => "formulario_plan_clases.labels.fecha_inicio",
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add('fechaFin', DateType::class, array(
                    "label" => "formulario_plan_clases.labels.fecha_fin",
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    "constraints" => $noVacio,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add('planAnualMetodologico', EntityType::class, $optionsPlanMetodologico)
                ->add('planAnualMetodologico2', EntityType::class, $optionsPlanMetodologico2)
                ->add('modelo', EntityType::class, $optionsModelo)
                ->add('componente', EntityType::class, $optionsComponente)
                ->add('componente2', EntityType::class, $optionsComponente2)
                ->add('contenido', EntityType::class, $optionsContenido)
                ->add('contenido2', EntityType::class, $optionsContenido2)
                ->add('actividades', CollectionType::class, array(
                    'entry_type' => ActividadPlanClaseType::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte1'],
                    'attr' => array(
                        'class' => 'actividades-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('actividades2', CollectionType::class, array(
                    'entry_type' => ActividadPlanClase2Type::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte2'],
                    'attr' => array(
                        'class' => 'actividades2-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('actividades3', CollectionType::class, array(
                    'entry_type' => ActividadPlanClase3Type::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte3'],
                    'attr' => array(
                        'class' => 'actividades3-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('actividades4', CollectionType::class, array(
                    'entry_type' => ActividadPlanClase4Type::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte4'],
                    'attr' => array(
                        'class' => 'actividades4-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('actividades5', CollectionType::class, array(
                    'entry_type' => ActividadPlanClase5Type::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte5'],
                    'attr' => array(
                        'class' => 'actividades5-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('actividades6', CollectionType::class, array(
                    'entry_type' => ActividadPlanClase6Type::class,
                    'entry_options' => array('label' => false),
                    'label' => 'formulario_plan_clases.labels.actividades',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'prototype' => true,
                    'mapped' => false,
                    'data' => $options['actividadesParte6'],
                    'attr' => array(
                        'class' => 'actividades6-collection',
                    ),
                    'by_reference' => false,
                    'entry_options' => array(
                    ),
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.guardarContinuar',
                    'attr' => array(
                        'class' => 'btn btnVerde')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\PlanClase',
            'em' => null,
            'planClaseId' => null,
            'userId' => null,
            'pamTecnico' => null,
            'pamSocial' => null,
            'componenteTecnico' => null,
            'componenteSocial' => null,
            'contenidoTecnico' => null,
            'contenidoSocial' => null,
            'modelo' => null,
            'roles' => null,
            'estrategia' => null,
            'actividadesParte1' => null,
            'actividadesParte2' => null,
            'actividadesParte3' => null,
            'actividadesParte4' => null,
            'actividadesParte5' => null,
            'actividadesParte6' => null
        ));
    }

}
