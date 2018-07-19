<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\File;

class NoticiaAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titulo')
            ->add('descripcion')
            ->add('fecha')
        ;
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }

    public function getDataSourceIterator()
    {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }
    
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('titulo')
            ->add('descripcion', 'html', [
                'strip' => true
            ])
            ->add('fecha', null, array( 'format' => 'd-m-Y ', 'timezone' => 'America/Bogota'))
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
        $trans = $this->configurationPool->getContainer()->get('translator');
        $noVacio = array(
            new NotBlank(array(
                'message' => $trans->trans('formulario_plan_anual_metodologico.no_vacio')
            ))
        );

        $opcion = [
            'data_class' => null,            
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ]])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
                "data-msg-placeholder" => "Selecciona una foto para subir"
            ],
            'label' => 'formulario_noticias.labels.imagen',
            "required" => true,
            "constraints" => $noVacio,
        ];

        $formMapper
        ->add('titulo', TextType::class, array(
            'label' => 'formulario_noticias.labels.titulo',
            "constraints" => $noVacio,
            'attr' => array(
                'class' => 'form-control',
                'autocomplete' => 'off')
        ))
        ->add('descripcion', CKEditorType::class, array(
            'required' => true,
            "constraints" => $noVacio,
            'config' => array('toolbar' => array(
                array(
                    'name'  => 'basicstyles',
                    'items' => array('Bold'),
                ),
                array(
                    'name'  => 'paragraph',
                    'items' => array('NumberedList'),
                ),
            ),),
            'label' => 'formulario_noticias.labels.descripcion',
            'attr' => array(
                'class' => 'form-control',
                'rows' => 6,
                'autocomplete' => 'off'))
            )
        ->add('noticiaImagen', FileType::class, $opcion)
        ->add('fecha', DateType::class, array(
            "label" => "formulario_noticias.labels.fecha",              
            'widget' => 'single_text',                    
            'format' => 'yyyy-MM-dd',
            "constraints" => $noVacio,                   
            'attr' => array('class' => 'form-control'),
        ))
        ;
    }

    

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('titulo')
            ->add('descripcion', null, array('safe' => true))
            ->add('fecha')
        ;
    }
}
