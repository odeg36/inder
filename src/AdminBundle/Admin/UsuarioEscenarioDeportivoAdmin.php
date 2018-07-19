<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddEscenarioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UsuarioEscenarioDeportivoAdmin extends AbstractAdmin {
    
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                //->add('usuario')
                ->add('principal')
                ->add('escenarioDeportivo')
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
                ->add('usuario')
                ->add('principal')
                ->add('escenarioDeportivo')
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
        $object = $this->getSubject();
        $es_requerido = "verdadero";
        $mappeado = false;
        $onChange = "inder.oferta.actualizarEscenario(this)";
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        if ($object->getEscenarioDeportivo()) {
            $municipio = $object->getEscenarioDeportivo()->getBarrio()->getMunicipio();
        } else {
            $municipio = "";
        }
        $formMapper
                ->add('municipio', EntityType::class, [
                    'class' => 'LogicBundle:Municipio',
                    'mapped' => false,
                ])
                ->add('barrio', EntityType::class, [
                    'class' => 'LogicBundle:Barrio',
                    'attr' => [
                        'class' => 'seleccion_barrio'
                    ]
                ])
                ->add('escenarioDeportivo')
                ->add('principal')
                ->add('usuario', 'sonata_type_model_autocomplete', [
//                    'mapped' => false,
//                    'multiple' => true,
                    'property' => 'title',
                    'minimum_input_length' => 6,
                    'callback' => function ($admin, $property, $value) {
                        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
                        $rol = "ROLE_GESTOR_ESCENARIO";
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();
                        $query = $em->getRepository('LogicBundle:UsuarioEscenarioDeportivo')->filtroUsuarioPorRol($query, $rol, $value);
                        $datagrid->setValue($property, null, $value);
                    },
                    'attr' => [
                    ]
                ])
                ->getFormBuilder()
                ->addEventSubscriber(new AddMunicipioFieldSubscriber($es_requerido, $municipio))
                ->addEventSubscriber(new AddBarrioFieldSubscriber($es_requerido, $mappeado, $onChange))
                ->addEventSubscriber(new AddEscenarioFieldSubscriber($es_requerido))

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('principal')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:UsuarioEscenarioDeportivo:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }
    
    public function validate(ErrorElement $errorElement, $object) {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $gestoresCreados = $em->getRepository('LogicBundle:UsuarioEscenarioDeportivo')
                ->findBy(array('usuario' => $object->getUsuario(), 'escenarioDeportivo' => $object->getEscenarioDeportivo()));
        if(count($gestoresCreados) > 0){
             $errorElement
                    ->addViolation($this->trans('error.gestor_escenario_deportivo.usuario_creado'))
                    ->end();
            
        }
        
    }
    
    public function configureBatchActions($actions) {
        return $actions;
    }

}
