<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;

class ActividadAdmin extends AbstractAdmin {

    protected $em;

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    protected function configureRoutes(RouteCollection $collection) {
        parent::configureRoutes($collection);

        $collection->add('crearActividad', 'crear/' . $this->getRouterIdParameter());
    }

    public function createQuery($context = 'list') {
        $request = $this->getRequest();
        $pam_id = $request->get('pam');
        $query = parent::createQuery($context);

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $con = $em
                ->getRepository('LogicBundle:Contenido')
                ->createQueryBuilder('con')
                ->join('con.componente', 'c')
                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                ->where('planAnualMetodologico.id = :id_pam')
                ->setParameter('id_pam', $pam_id)
                ->getQuery()
                ->getResult();

        $contenidos = [];
        foreach ($con as $cons) {
            if (!in_array($cons->getId(), $contenidos)) {
                array_push($contenidos, $cons->getId());
            }
        }

        $repoActivity = $em->getRepository('LogicBundle:Actividad');
        $query = new ProxyQuery($repoActivity->createQueryBuilder('a')
                        ->join('a.contenido', 'contenido')
                        ->add('where', $query->expr()->in('contenido.id', $contenidos)));

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        }
        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

        $request = $this->getRequest();
        $pam_id = $request->get('pam');

        $componentes = $this->modelManager->createQuery('LogicBundle\Entity\Componente', 'c')
                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                ->where('planAnualMetodologico.id = :id_pam')
                ->setParameter('id_pam', $pam_id)
                ->getQuery()
                ->getResult();
        $choicesComponentes = array();
        foreach ($componentes AS $componente) {
            $choicesComponentes[$componente->getNombre()] = $componente->getId();
        }

        $contenidos = $this->modelManager->createQuery('LogicBundle\Entity\Contenido', 'con')
                ->join('con.componente', 'c')
                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                ->where('planAnualMetodologico.id = :id_pam')
                ->setParameter('id_pam', $pam_id)
                ->getQuery()
                ->getResult();
        $choicesContenidos = array();
        foreach ($contenidos AS $contenido) {
            $choicesContenidos[$contenido->getNombre()] = $contenido->getId();
        }

        $datagridMapper
                ->add('contenido.componente', 'doctrine_orm_choice', array(
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente'
                        ), 'choice', array('choices' => $choicesComponentes)
                )
                ->add('contenido', 'doctrine_orm_choice', array(
                    'show_filter' => true,
                        ), 'choice', array('choices' => $choicesContenidos)
                )
                ->add('nombre', null, [
                    'show_filter' => true,
                ])

        ;
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $request = $this->getRequest();
        $pam_id = $request->get('pam');

        $listMapper
                ->add('contenido.componente', null, [
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente'
                ])
                ->add('contenido', null, [
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido'
                ])
                ->add('nombre', null, [
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.nombre'
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(
                            'template' => 'AdminBundle:Actividad:list_edit_action.html.twig', $this->data = $pam_id
                        ),
                    ),
        ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $request = $this->getRequest();
        $pam_id = $request->get('pam');

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );

        $object = $this->getSubject();

        $optionsComponente = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($pam_id) {
                return $er->createQueryBuilder('c')
                                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                                ->where('planAnualMetodologico.id = :id_pam')
                                ->setParameter('id_pam', $pam_id);
            },
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'empty_data' => null,
            'mapped' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        if ($object->getContenido()) {
            $optionsComponente['data'] = $object->getContenido()->getComponente();
        }


        $optionsContenido = array(
            'class' => 'LogicBundle:Contenido',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($pam_id) {
                return $er->createQueryBuilder('con')
                                ->join('con.componente', 'c')
                                ->join('c.planAnualMetodologico', 'planAnualMetodologico')
                                ->where('planAnualMetodologico.id = :id_pam')
                                ->setParameter('id_pam', $pam_id);
            },
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido',
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        $formMapper
                ->add('componente', EntityType::class, $optionsComponente)
                ->add('contenido', EntityType::class, $optionsContenido)
                ->add('nombre', TextType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.nombre',
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off')
                ))
                ->add('tipoTiempoEjecucion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoTiempoEjecucion',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.tiempo_ejecucion',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('duracion', IntegerType::class, [
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.duracion',
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ])
                ->add('indicador', TextareaType::class, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.indicador',
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 3,
                        'autocomplete' => 'off')
                ))
                ->add('metodoEvaluacion', TextType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.metodo_evaluacion',
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off')
        ));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
                ->add('contenido')
                ->add('duracion')
                ->add('indicador')
                ->add('metodoEvaluacion')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Actividad:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:Actividad:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function configureBatchActions($actions) {
        $actions['delete'] = array(
            'ask_confirmation' => false
        );
        return $actions;
    }

}
