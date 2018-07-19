<?php

namespace AdminBundle\Admin;

use DateTime;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class BannerAdmin extends AbstractAdmin {

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    public function configure() {
        $this->setTemplate('edit', 'AdminBundle:Banner:edit.html.twig');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('fechaInicio')
                ->add('fechaFin')
                ->add('vecesVisto')
        ;
    }
    
    public function getExportFormats(){
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('nombre', null, [
                    'label' => 'formulario_banner.label.nombre',
                ])
                ->add('fechaInicio', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota', 'label' => 'formulario_banner.label.fecha_inicio' ))
                ->add('fechaFin', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota', 'label' => 'formulario_banner.label.fecha_fin' ))
                ->add('vecesVisto', null, [
                    'label' => 'formulario_banner.label.vecesVisto',
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
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
    protected function configureFormFields(FormMapper $formMapper) {
        $trans = $this->configurationPool->getContainer()->get('translator');
        $noVacio = array(
            new NotBlank(array(
                'message' => $trans->trans('formulario_plan_anual_metodologico.no_vacio')
            ))
        );


        $fechas = array(
            new NotBlank(array(
                'message' => $trans->trans('formulario_plan_anual_metodologico.no_vacio')
            ))
        );

        $bannerObject = $this->getSubject();
        $imagenWeb = "";
        $fileFieldOptionsWeb = [
            'data_class' => null,
            'data' => $bannerObject->getImagenWeb(),
            'label' => 'formulario_banner.label.imagen_web',
            'constraints' => [
                new Image([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ],
                    'minWidth' => 181,
                    'maxWidth' => 500,
                    'minHeight' => 600,
                    'maxHeight' => 600
                ])
            ],
            'attr' => [
                "class" => "file divImagenWebBanner",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
                "data-msg-placeholder" => $this->trans("seleccionar.imagen")
            ]
        ];
        if ($bannerObject->getId()) {
            $noVacioClave = [];
            $imagenWeb = $bannerObject->getImagenWeb();

            $fileFieldOptionsWeb = array_merge($fileFieldOptionsWeb, [
                'required' => false,
            ]);
            if ($imagenWeb) {
                // get the container so the full path to the image can be set
                $container = $this->getConfigurationPool()->getContainer();                
                $fullPath = $imagenWeb;

                // add a 'help' option containing the preview's img tag
                $fileFieldOptionsWeb['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';                
            }
        }

        $imagenMobile = "";
        $fileFieldOptionsMobile = [
            'data_class' => null,
            'label' => 'formulario_banner.label.imagen_mobile',
            'constraints' => [
                new Image([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ],
                    'minWidth' => 271,
                    'maxWidth' => 271,
                    'minHeight' => 326,
                    'maxHeight' => 326
                ])
            ],
            'attr' => [
                "class" => "file divImagenMobileBanner",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
                "data-msg-placeholder" => $this->trans("seleccionar.foto")
            ]
        ];
        if ($bannerObject->getId()) {
            $noVacioClave = [];
            $imagenMobile = $bannerObject->getImagenMobil();
            $fileFieldOptionsMobile = array_merge($fileFieldOptionsMobile, [
                'required' => false,
            ]);
            if ($imagenMobile) {
                // get the container so the full path to the image can be set
                $container = $this->getConfigurationPool()->getContainer();
                $fullPath = $imagenWeb;
                // add a 'help' option containing the preview's img tag
                $fileFieldOptionsMobile['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
            }
        }

        $now = new DateTime();
        $formMapper
        ->add("nombre", null, array(
            'required' => true,
            "constraints" => $noVacio,
            'attr' => [
                'class' => 'form-control'
            ]
        ))
        ->add('fechaInicio', DateMaskType::class, array(
            'required' => true,
            "constraints" => $noVacio,
            'label' => 'formulario_banner.label.fecha_inicio',
            'mask-alias' => 'dd/mm/yyyy',
            'placeholder' => 'dd/mm/yyyy',
            'attr' => ["class" => "form-control fechaInicio"]
        ))
        ->add('fechaFin', DateMaskType::class, array(
            'required' => true,
            "constraints" => $noVacio,
            'label' => 'formulario_banner.label.fecha_fin',
            'mask-alias' => 'dd/mm/yyyy',
            'placeholder' => 'dd/mm/yyyy',
            'attr' => ["class" => "form-control fechaFin"]
        ))
        ->add('vecesVisto', IntegerType::class, [
            'required' => true,
            "constraints" => $noVacio,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off',
                'min' => 1,
            ]
            
        ])
        ->add('comunas', EntityType::class, array(
            'class' => 'LogicBundle:Comuna',
            'required' => true,
            'constraints' => array(
                new Count(array(
                    'min' => 1,
                    'minMessage' => $trans->trans('error.no_vacio')
                ))
             ),
            'multiple'  => true,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        ))
        ->add('imagenWeb', FileType::class, $fileFieldOptionsWeb)
        ->add('imagenMobil', FileType::class, $fileFieldOptionsMobile);        
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
        ->add('nombre', null, [
            'label' => 'formulario_banner.label.nombre',
        ])
        ->add('comunas', null, [
            'label' => 'formulario_banner.label.comunas',
        ])
        ->add('fechaInicio', null, [
            'label' => 'formulario_banner.label.fecha_inicio',
        ])
        ->add('fechaFin', null, [
            'label' => 'formulario_banner.label.fecha_fin',
        ])
        ->add('vecesVisto', null, [
            'label' => 'formulario_banner.label.vecesVisto',
        ]);
    }   

    /*public function getTemplate($name) {
        switch ($name) {
            case 'edit':        
                return 'AdminBundle:Banner:edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }*/

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:Banner:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }
}