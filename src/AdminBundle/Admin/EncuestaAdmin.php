<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EncuestaAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
       
        $collection->add('addencuesta', 'addencuesta/'.$this->getRouterIdParameter());
        $collection->add('addrespuestas', 'addrespuestas/'.$this->getRouterIdParameter());
        $collection->add('respuesta', 'respuesta/'.$this->getRouterIdParameter());
        
    }
   
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre')
            ->add('activo')
            ->add('fechaInicio')
            ->add('tipoPeriodicidad')
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
            ->add('nombre',null, array('label' => 'unidad_deportiva.nombre','editable' => true))
            ->add('fechaInicio', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota'))
            ->add('activo', null, array('editable' => true))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'respuesta' => array(
                        'template' => 'AdminBundle:Encuesta:list_action_respuestas.html.twig'
                    ),
                ),
            ))
        ;
    }


    public function getDataSourceIterator()
    {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
       /* $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_campo.no_vacio',
            )));*/
            
        $formMapper
            ->add('nombre')
          
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nombre')
            ->add('fechaInicio')
            ->add('tipoPeriodicidad')
        ;
    }
}
