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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RecomendadoAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'titulo',
    ];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                //->add('id')
                ->add('url')
                ->add('titulo')
                ->add('imagenUrl')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                //->add('id')
                ->add('url')
                ->add('titulo')
                ->add('imagenUrl')
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

        $opcion = [
            'data_class' => null,
            'constraints' => [
                new File([
                    'maxSize' => '2048k',
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ]
                        ])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
            ],
            "required" => true,
            "constraints" => $noVacio,
        ];

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
                ->add('imagenUrl', FileType::class, $opcion)
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('url')
                ->add('titulo')
                ->add('imagenUrl')
        ;
    }

}
