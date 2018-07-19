<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CategoriaAmbientalAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        
        //$collection->remove("edit");
        //$collection->remove("create");
        $collection->remove("remove");

        $collection->add('addcampo', 'addcampo/'.$this->getRouterIdParameter());
        
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nombre')
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
            ->add('nombre')
            ->add('subcategoriaAmbientales')
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
        ->with('Agregar Categoria', array('class' => 'opcionesCampo'))
            ->add('nombre')
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('nombre')
            ->add('subcategoriaAmbientales')
        ;
    }
}
