<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class VariableGlobalAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper            
            ->add('nombre')
            ->add('dato1')
            ->add('dato2')
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
            ->add('nombre')
            ->add('dato1')
            ->add('dato2')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
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
            ->add('nombre')
            ->add('dato1')
            ->add('dato2')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper            
            ->add('nombre')
            ->add('dato1')
            ->add('dato2')
        ;
    }

    public function getTemplate($name) {        
        switch ($name) {            
            case 'edit':
                return 'AdminBundle:VariableGlobal/variable:base_edit.html.twig';    
            default:
                return parent::getTemplate($name);
        }
    }
}
