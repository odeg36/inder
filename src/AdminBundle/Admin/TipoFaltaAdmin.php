<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class TipoFaltaAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        
        $collection->remove("delete");
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre', null, [
                'label' => 'formulario.labels.nombre'
            ])
            ->add('descripcion', null, [
                'label' => 'formulario.labels.descripcion'
            ])
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
            ->add('nombre', null, [
                'label' => 'formulario.labels.nombre'
            ])
            ->add('descripcion', null, [
                'label' => 'formulario.labels.descripcion'
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
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nombre', null, [
                'label' => 'formulario.labels.nombre',
                'constraints' => [
                    new NotBlank(array(
                        'message' => 'formulario_registro.no_vacio',
                    ))
                ]
            ])
            ->add('descripcion', null, [
                'label' => 'formulario.labels.descripcion',
                'constraints' => [
                    new NotBlank(array(
                        'message' => 'formulario_registro.no_vacio',
                    ))
                ]
            ])
            ->add('puntosJuegolimpio', null, [
                'label' => 'formulario.labels.puntos',
                'constraints' => [
                    new NotBlank(array(
                        'message' => 'formulario_registro.no_vacio',
                    ))
                ]
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nombre', null, [
                'label' => 'formulario.labels.nombre'
            ])
            ->add('descripcion', null, [
                'label' => 'formulario.labels.descripcion'
            ])
        ;
    }
}
