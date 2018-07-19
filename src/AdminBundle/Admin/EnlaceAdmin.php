<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EnlaceAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'titulo',
    ];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                //->add('id')
                ->add('titulo')
                ->add('url')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                //->add('id')
                ->add('titulo')
                ->add('url')
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
                //->add('id')
                ->add('titulo', TextType::class, array(
                    "constraints" => $noVacio,
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('url', TextType::class, array(
                    "constraints" => $noVacio,
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('categoriaEnlace', EntityType::class, array(
                    'class' => 'LogicBundle:CategoriaEnlace',
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('titulo')
                ->add('url')
        ;
    }

}
