<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CarneEventoAdmin extends AbstractAdmin {

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('mostrarNombre')
                ->add('mostrarEquipo')
                ->add('mostrarEvento')
                ->add('mostrarColegio')
                ->add('mostrarComuna')
                ->add('mostrarFechaNacimiento')
                ->add('mostrarDeporte')
                ->add('mostrarRama')
                ->add('mostrarRol')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('mostrarNombre')
                ->add('mostrarEquipo')
                ->add('mostrarEvento')
                ->add('mostrarColegio')
                ->add('mostrarComuna')
                ->add('mostrarFechaNacimiento')
                ->add('mostrarDeporte')
                ->add('mostrarRama')
                ->add('mostrarRol')
                ->add('_action', null, [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ],
                ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('mostrarNombre', null, ["label" =>"label.mostrar.nombre"])
                ->add('mostrarEquipo', null, ["label" =>"label.mostrar.equipo"])
                ->add('mostrarEvento', null, ["label" =>"label.mostrar.evento"])
                ->add('mostrarColegio', null, ["label" =>"label.mostrar.colegio"])
                ->add('mostrarComuna', null, ["label" =>"label.mostrar.comuna"])
                ->add('mostrarFechaNacimiento', null, ["label" =>"label.mostrar.fecha"])
                ->add('mostrarDeporte', null, ["label" =>"label.mostrar.deporte"])
                ->add('mostrarRama', null, ["label" =>"label.mostrar.rama"])
                ->add('mostrarRol', null, ["label" =>"label.mostrar.rol"])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('mostrarNombre')
                ->add('mostrarEquipo')
                ->add('mostrarEvento')
                ->add('mostrarColegio')
                ->add('mostrarComuna')
                ->add('mostrarFechaNacimiento')
                ->add('mostrarDeporte')
                ->add('mostrarRama')
                ->add('mostrarRol')
        ;
    }

}
