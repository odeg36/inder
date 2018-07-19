<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PlanClaseAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);

        $collection->add('crearPlanClase', 'crear/'.$this->getRouterIdParameter());
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('oferta.estrategia', null, [
                'label' => 'titulo.estrategia'
            ])
            ->add('oferta')
            ->add('fechaInicio')
            ->add('fechaFin')
        ;
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }
    
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('oferta.estrategia', 'text', [
                'label' => 'titulo.estrategia'
            ])
            ->add('oferta', 'text')
            ->add('fechaInicio', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota'))
            ->add('fechaFin', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
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
            ->add('oferta.estrategia', null, [
                'label' => 'titulo.estrategia'
            ])
            ->add('oferta')
            ->add('fechaInicio')
            ->add('fechaFin')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('oferta.estrategia', 'text', [
                'label' => 'titulo.estrategia'
            ])
            ->add('oferta', 'text')
            ->add('fechaInicio')
            ->add('fechaFin')
        ;
    }
}
