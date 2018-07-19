<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubCategoriaEventoAdmin extends AbstractAdmin {

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre')
                ->add('categoria')
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
                ->add('categoria')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
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
                'message' => $trans->trans('formulario_plan_anual_metodologico.no_vacio')
                    ))
        );

        $formMapper
                ->add('categoria', EntityType::class, array(
                    'class' => 'LogicBundle:CategoriaEvento',
                    "constraints" => $noVacio,
                    'required' => true,
                    'placeholder' => '',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                        )
                )
                ->add("nombre", null, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
                ->add('categoria')
        ;
    }

}
