<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class RamaAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'nombre',
    ];

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->remove("delete");
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre')
        ;
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('nombre')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array()
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $noVacio = [
            new NotBlank([
                'message' => 'formulario_registro.no_vacio',
                    ])
        ];

        $formMapper
                ->add('nombre', 'text', array(
                    'required' => true,
                    'constraints' => $noVacio));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
        ;
    }

}