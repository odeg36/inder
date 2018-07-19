<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\AddComunaFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class BarrioAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'nombre',
    ];

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('municipio')
                ->add('nombre')
                ->add('habilitado')
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
                ->add('municipio')
                ->add('nombre', null)
                ->add('habilitado', null, array('editable' => true))
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
                ->add('municipio')
                ->add('comuna')
                ->add('nombre', null, [
                    'constraints' => [
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                ))
                    ]
                ])
                ->add('esVereda')
                ->add('habilitado')
                ->getFormBuilder()
                ->addEventSubscriber(new AddMunicipioFieldSubscriber("verdadero", "", true, true))
                ->addEventSubscriber(new AddComunaFieldSubscriber("falso", true))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('municipio')
                ->add('nombre')
                ->add('habilitado')
        ;
    }

}
