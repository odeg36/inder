<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ClientAdmin extends AbstractAdmin {

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);

        $collection->remove("edit");
        $collection->remove("create");
        $collection->remove("remove");
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('name', null, [
                    'label' => 'formulario.labels.nombre'
                ])
        ;
    }
    
    public function getExportFormats(){
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('id', null, [
                    'label' => 'formulario.labels.id'
                ])
                ->add('clientId', null, [
                    'label' => 'formulario.labels.clienteId'
                ])
                ->add('secret', null, [
                    'label' => 'formulario.labels.llave'
                ])
                ->add('name', null, [
                    'label' => 'formulario.labels.nombre'
                ])
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
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('clientId', null, [
                    'label' => 'formulario.labels.clienteId'
                ])
                ->add('redirectUris', null, [
                    'label' => 'formulario.labels.redirect.urls'
                ])
                ->add('secret', null, [
                    'label' => 'formulario.labels.llave'
                ])
                ->add('allowedGrantTypes', null, [
                    'label' => 'formulario.labels.permisos.tipos'
                ])
                ->add('id', null, [
                    'label' => 'formulario.labels.id'
                ])
                ->add('name', null, [
                    'label' => 'formulario.labels.nombre'
                ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id', null, [
                    'label' => 'formulario.labels.id'
                ])
                ->add('clientId', null, [
                    'label' => 'formulario.labels.clienteId'
                ])
                ->add('secret', null, [
                    'label' => 'formulario.labels.llave'
                ])
                ->add('name', null, [
                    'label' => 'formulario.labels.nombre'
                ])
                ->add('redirectUris', null, [
                    'label' => 'formulario.labels.redirect.urls'
                ])
                ->add('allowedGrantTypes', null, [
                    'label' => 'formulario.labels.tipo.permisos'
                ])
        ;
    }

}
