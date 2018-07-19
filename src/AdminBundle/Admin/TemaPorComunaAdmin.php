<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class TemaPorComunaAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        

        $collection->remove("delete");

        $collection->add('addform', 'form/'.$this->getRouterIdParameter());
        
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nivel')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('nivel')
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

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
            ))
        );


        $formMapper
        
            ->add('comuna',EntityType::class,array(
                'class'=>'LogicBundle:Comuna',
                'placeholder' =>'',
                'required' => true,
                "constraints" => $noVacio,
                'label' => 'Comuna',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control','autocomplete' => 'off'
                ]
            ))

            ->add('temaModelo', EntityType::class, array(
                'class' => 'LogicBundle:TemaModelo',
                'placeholder' => '',
                'required' => true,
                'multiple' => true,
                "constraints" => $noVacio,
                'label' => 'Tema',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ))

            ->add('nivel',null, array('attr' => [
                'class' => 'col-md-3'
                ]))
            
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nivel')
        ;
    }
}
