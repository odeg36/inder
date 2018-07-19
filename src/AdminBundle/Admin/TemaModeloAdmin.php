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

class TemaModeloAdmin extends AbstractAdmin {

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre')
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
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );


        $TemaModelo = $this->getSubject();
        $imagenWeb = "";
        $fileFieldOptionsWeb = [
            'data_class' => null,
            'label' => 'Imagen',
            'constraints' => [
                new Image([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ],
                    'minWidth' => 70,
                    'maxWidth' => 500,
                    'minHeight' => 70,
                    'maxHeight' => 600
                        ])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
                "data-msg-placeholder" => $this->trans("seleccionar.imagen")
            ]
        ];
        if ($TemaModelo->getId()) {
            $noVacioClave = [];
            $imagenWeb = $TemaModelo->getImagen();
            $fileFieldOptionsWeb = array_merge($fileFieldOptionsWeb, [
                'required' => false,
            ]);
            if ($imagenWeb) {
                // get the container so the full path to the image can be set
                $container = $this->getConfigurationPool()->getContainer();
                $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagenWeb;

                // add a 'help' option containing the preview's img tag
                $fileFieldOptionsWeb['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
            }
        }

        $formMapper
                ->add('modelo', EntityType::class, array(
                    'class' => 'LogicBundle:Modelo',
                    'placeholder' => '',
                    'required' => true,
                    "constraints" => $noVacio,
                    'label' => 'Modelo',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('nombre')
                ->add('imagen', FileType::class, $fileFieldOptionsWeb)

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
                ->add('imagen')
        ;
    }

}
