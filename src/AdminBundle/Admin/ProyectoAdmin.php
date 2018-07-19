<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProyectoAdmin extends AbstractAdmin {

    protected $em;

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        $collection->remove('delete');
    }

    public function setConfigurationPool(\Sonata\AdminBundle\Admin\Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('area', null, [
                    'label' => 'formulario.proyecto.area'
                ])
                ->add('codigo', null, [
                    'label' => 'formulario.proyecto.codigo'
                ])
                ->add('nombre', null, [
                    'label' => 'formulario.proyecto.nombre'
                ])
                ->add('activo', null, [
                    'label' => 'formulario.proyecto.activo'
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
        $listMapper
                ->add('area', null, array(
                    'label' => 'formulario.proyecto.area'
                ))
                ->add('nombre', null, array(
                    'label' => 'formulario.proyecto.nombre'
                ))
                ->add('codigo', null, [
                    'label' => 'formulario.proyecto.codigo'
                ])
                ->add('activo', null, array(
                    'label' => 'formulario.proyecto.activo'
                ))
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                        'creatEstrategia' => array(
                            "template" => "AdminBundle:Proyecto/btn:crear.estrategia.html.twig"
                        )
                    )
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $request = $this->getRequest();
        $area = $request->get("area", null);
        $areaEntity = null;
        if ($area) {
            $areaEntity = $this->em->getRepository("LogicBundle:Area")->findOneById($area);
        }

        $formMapper
                ->add('area', null, [
                    'label' => 'formulario.proyecto.area',
                    'attr' => [
                        'class' => ''
                    ],
                    'placeholder' => '',
                ])
                ->add('codigo', null, [
                    'label' => 'formulario.proyecto.codigo'
                ])
                ->add('nombre', null, [
                    'label' => 'formulario.proyecto.nombre'
                ])
                ->add('descripcion', null, [
                    'label' => 'formulario.proyecto.descripcion'
                ])
                ->add('activo', null, [
                    'label' => 'formulario.proyecto.activo'
                ])
        ;

        if ($areaEntity) {
            $formMapper
                    ->add('area', null, [
                        'label' => 'formulario.proyecto.area',
                        'data' => $areaEntity
            ]);
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('area', null, [
                    'label' => 'formulario.proyecto.area'
                ])
                ->add('codigo', null, [
                    'label' => 'formulario.proyecto.codigo'
                ])
                ->add('nombre', null, [
                    'label' => 'formulario.proyecto.nombre'
                ])
                ->add('descripcion', null, [
                    'label' => 'formulario.proyecto.descripcion'
                ])
                ->add('activo', null, [
                    'label' => 'formulario.proyecto.activo'
                ])
        ;
    }

}
