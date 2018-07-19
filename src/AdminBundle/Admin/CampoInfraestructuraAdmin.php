<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CampoInfraestructuraAdmin extends AbstractAdmin
{

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        
        //$collection->remove("edit");
        //$collection->remove("create");
        //$collection->remove("remove");

        $collection->add('addcampo','addcampo/'.$this->getRouterIdParameter());
        
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre')
            ->add('tipoEntrada')
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
            ->add('tipoEntrada')
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
                        'message' => 'formulario_campo.no_vacio',
                    ))
        );

        $formMapper
            ->with('Campo', array('class' => 'campo'))

                ->add('nombre')
                ->add('tipoEntrada', ChoiceType::class, array(
                    "constraints" => $noVacio,
                    'label' => 'formulario_campo.labels.tipoEntrada',
                    'required' => true,
                    'choices'   => array(
                        'Escoger Una Opción' => '',
                        'Area de texto' => 'text_area',
                        'Texto' => 'text',
                        'Fecha'=> 'date',
                        'Numero' => 'number',
                        'Selección'=> 'select',
                        'Selección Multiple'=> 'select_multiple',
                        'Radio Button'=> 'radio_button',
                        'Checkbox'=> 'checkbox'
                    ),
                    'required'  => true,
                    'attr' => array(
                        'class' => 'form-control tipo_entrada',
                        'onchange' => 'inder.campo.mostrarTipoEntrada(this);'
                    ),
                ))
            ->end()

            ->with('Opciones De Campo', array('class' => 'opcionesCampoInfraestructura'))
                 ->add('opcionesCampoInfraestructura', 'sonata_type_collection', [
                    'required'     => false,
                    'by_reference' => false,
                    'type_options' => [
                        'delete' => true
                    ],
                    'btn_add' => 'Agregar nuevo'
                ], [
                    'edit'   => 'inline',
                    'inline' => 'table'
                ])
                
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nombre')
            ->add('tipoEntrada')
            ->add('opcionCampoInfraestructuras')
        ;
    }
}
