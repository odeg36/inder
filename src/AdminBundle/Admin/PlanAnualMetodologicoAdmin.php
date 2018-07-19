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

class PlanAnualMetodologicoAdmin extends AbstractAdmin
{

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        
        $collection->remove("delete");

        $collection->add('addpaso1', 'paso1/'.$this->getRouterIdParameter());
        $collection->add('addpaso2', 'paso2/'.$this->getRouterIdParameter());
        $collection->add('addpaso3', 'paso3/'.$this->getRouterIdParameter());
        $collection->add('addpaso4', 'paso4/'.$this->getRouterIdParameter());
        $collection->add('planAnualMetodologicoTerminado', 'planAnualMetodologicoTerminado/'.$this->getRouterIdParameter());
        
    }

    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre')
            ->add('estado')
        ;
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }

    public function getDataSourceIterator()
    {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }
    
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    { 
        $request = $this->getRequest();
        $listMapper
            ->add('estrategias')
            ->add('nombre')
            ->add('estado', null, [
                'label' => 'formulario_plan_anual_metodologico.activo'
            ])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'actividades' => array(
                        'template' => 'AdminBundle:PlanAnualMetodologico:list__action_actividades.html.twig'
                    ),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nombre')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nombre')
            ->add('enfoque')
            ->add('estado', null, [
                'label' => 'formulario_plan_anual_metodologico.activo'
            ])
        ;
    }

}
