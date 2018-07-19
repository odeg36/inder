<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoriaEnlaceAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'nombre',
    ];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                //->add('id')
                ->add('nombre')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                //->add('id')
                ->add('nombre')
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
        $trans = $this->configurationPool->getContainer()->get('translator');
        $noVacio = array(
            new NotBlank(array(
                'message' => $trans->trans('error.no_vacio')
                    ))
        );

        $formMapper
                ->add('nombre', TextType::class, array(
                    "constraints" => $noVacio,
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('nombre')
        ;
    }

}