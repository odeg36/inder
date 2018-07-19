<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class EscenarioDeportivoAdmin extends AbstractAdmin {

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        
        //$collection->remove("edit");
        //$collection->remove("create");
        $collection->remove("remove");

        $collection->add('addpaso1', 'paso1/'.$this->getRouterIdParameter());
        $collection->add('addpaso2', 'paso2/'.$this->getRouterIdParameter());
        $collection->add('addpaso3', 'paso3/'.$this->getRouterIdParameter());
        $collection->add('addpaso4', 'paso4/'.$this->getRouterIdParameter());
        $collection->add('addpaso41', 'paso4-1/'.$this->getRouterIdParameter());
        $collection->add('addpaso42', 'paso4-2/'.$this->getRouterIdParameter());
        $collection->add('escenarioTerminado', 'escenarioTerminado/'.$this->getRouterIdParameter());
        
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
            ->add('nombre')
            ->add('barrio')
            ->add('barrio.municipio', null, array('label' => 'Municipio'))
            ->add('barrio.comuna', null, array('label' => 'Corregimientoinder'))
        ;
    }
    
    public function getExportFormats(){
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $ROLE_USER = $user->hasRole('ROLE_USER');

        $actions = array(
            'show' => array()
        );
        if($ROLE_GESTOR_ESCENARIO || $ROLE_SUPER_ADMIN){
            $actions['delete'] = array();
            $actions['edit'] = array();
        }
        
        $listMapper
            ->add('nombre')            
            ->add('_action', null, array(
                'actions' => $actions,
        ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {        
        $formMapper
            ->add('nombre')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper                
            ->add('nombre')
            ->add('barrio')
            ->add('direccion')
            ->add('telefono')
            ->add('email')
            ->add('unidadDeportiva')
            ->add('horaInicial')
            ->add('horaFinal')
            ->add('normaEscenario')
            ->add('tipoEscenario')
            ->add('divisiones')
            ->add('tipoReservaEscenarioDeportivos')
            ->add('tendenciaEscenarioDeportivos')
            ->add('disciplinasEscenarioDeportivos')
            ->add('usuarioEscenarioDeportivos')
            ->add('escenarioCategoriaInfraestructuras')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {            
            case 'list':
                return 'AdminBundle:EscenarioDeportivo:base_list.html.twig';
            case 'show':
                return 'AdminBundle:EscenarioDeportivo:base_show.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }    
}