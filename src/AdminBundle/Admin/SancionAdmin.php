<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class SancionAdmin extends AbstractAdmin {

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre')
                ->add('tipoFalta')
                ->add('descripcion')
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
                ->add('tipoFalta', 'text')
                ->add('descripcion')
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
                'message' => $trans->trans('formulario_plan_anual_metodologico.no_vacio')
                    ))
        );

        $formMapper
                ->add("nombre", null, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ))
                ->add('tipo_falta', EntityType::class, array(
                    'class' => 'LogicBundle:TipoFalta',
                    "constraints" => $noVacio,
                    'required' => true,
                    "label" => "titulo.tipofalta",
                    'placeholder' => '',
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ))
                ->add('descripcion', CKEditorType::class, array(
                    'required' => true,
                    "constraints" => $noVacio,
                    'config' => array('toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'items' => array('NumberedList'),
                            ),
                        ),),
                    'label' => 'formulario_noticias.labels.descripcion',
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 6,
                        'autocomplete' => 'off'))
                )
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
                ->add('tipoFalta')
                ->add('descripcion')
        ;
    }

}
