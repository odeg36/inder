<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AreaAdmin extends AbstractAdmin {

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre', null, array('label' => 'area.nombre'))
                ->add('codigo', null, array('label' => 'area.codigo'))
                ->add('activo', null, array('label' => 'area.activo'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('nombre', null, array('label' => 'area.nombre'))
                ->add('codigo', null, array('label' => 'area.codigo'))
                ->add('activo', null, array('label' => 'area.activo'))
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                        'creatProyecto' => array(
                            "template" => "AdminBundle:Area/btn:crear.proyecto.html.twig"
                        )
                    ),
                ))
        ;
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }
    
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('nombre', null, array('label' => 'area.nombre'))
                ->add('codigo', null, array('label' => 'area.codigo'))
                ->add('descripcion', null, array('label' => 'area.descripcion', 'required' => false))
                ->add('activo', null, array('label' => 'area.activo'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre', null, array('label' => 'area.nombre'))
                ->add('codigo', null, array('label' => 'area.codigo'))
                ->add('descripcion', null, array('label' => 'area.descripcion'))
                ->add('activo', null, array('label' => 'area.activo'))
        ;
    }

}
