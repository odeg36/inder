<?php

namespace AdminBundle\Admin;

use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class PreinscripcionOfertaAdmin extends AbstractAdmin {

    protected $accessMapping = array(
        'formador' => ['ROLE_FORMADOR']
    );

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('create');
        $collection->remove('delete');
        $collection->remove('export');
        $collection->add('preinscripcion', $this->getRouterIdParameter() . '/formulario');
        $collection->add('preinscripcionExitosa', $this->getRouterIdParameter() . '/confirmacion');
        $collection->add('preinscripcionAcompanantes', $this->getRouterIdParameter() . '/acompanantes');
        $collection->add('preinscripcionAcompanante', $this->getRouterIdParameter() . '/acompanante', array(), array(), array('expose' => true));
        $collection->add('asistencia', $this->getRouterIdParameter() . '/asistencia', array(), array(), array('expose' => true));
        $collection->add('cargaUsuarioNuevoPreinscripcion', $this->getRouterIdParameter() . '/cargaUsuarioNuevoPreinscripcion', array(), array(), array('expose' => true));
        $collection->add('registarUsuarioOferta', $this->getRouterIdParameter() . '/registrar-usuario-oferta');
        $collection->add('rechazarPreInscripcion', 'rechazarPreInscripcion', array(), array(), array('expose' => true));
        $collection->add('modificarDatos', $this->getRouterIdParameter() . '/modificar-datos');
    }

    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);
        if ($context === 'list') {
            $query->join('o.usuario', 'u')
                    ->addOrderBy('u.lastname', 'ASC')
            ;
        }
        return $query;
    }



    public function getExportFields() {
        return array(
            $this->trans('formulario.usuario.firstname') => 'usuario.firstname',
            $this->trans('formulario.usuario.lastname') => 'usuario.lastname',
            $this->trans('formulario.usuario.numeroIdentificacion') => 'usuario.numeroIdentificacion',
            $this->trans('formulario.usuario.tipoIdentificacion') => 'usuario.tipoIdentificacion',
            $this->trans('activo') => 'activo',
            $this->trans('formulario.preinscripcion.fecha_inscripcion') => 'fechaCreacion',
        );
    }

    public function getDataSourceIterator() {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('oferta')
                ->add('usuario.firstname', null, [
                    'show_filter' => true,
                    'label' => 'formulario.usuario.firstname'
                ])
                ->add('usuario.numeroIdentificacion', null, [
                    'show_filter' => true,
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
                ->add('activo', null, [
                    'show_filter' => true,
                    'label' => 'formulario.preinscripcion.activo',
                ])
        ;
    }

    public function configureBatchActions($actions) {
        $request = $this->getRequest();
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine')->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->find($request->query->get('filter')['oferta']['value']);
        if (!$oferta->getEstrategia()->getDiagnostico()) {
            $actions['aprobar'] = [];
        }
        $actions['exportar'] = [
            'ask_confirmation' => false
        ];
        
        $actions['rechazar'] = [];

        return $actions;
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('usuario.firstname', null, [
                    'label' => 'formulario.usuario.firstname'
                ])
                ->add('usuario.lastname', null, [
                    'label' => 'formulario.usuario.lastname'
                ])
                ->add('usuario.numeroIdentificacion', null, [
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
                ->add('usuario.tipoIdentificacion', StringType::STRING, [
                    'label' => 'formulario.usuario.tipoIdentificacion',
                    'placeholder' => '',
                ])
                ->add('activo', null, [
                    'label' => 'formulario.preinscripcion.activo',
                    'template' => 'AdminBundle:Preinscripcion:columna_estado.html.twig'
                ])
                ->add('acompanantes', null, [
                    'label' => 'formulario.preinscripcion.acompanantes'
                ])
                ->add('fechaCreacion', null, [
                    'label' => 'formulario.preinscripcion.fecha_inscripcion',
                ])
                ->add('_action', null, array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'edit' => [
                            'template' => 'AdminBundle:Preinscripcion:edit_link.html.twig'
                        ],
                        'datosUsuarios' => [
                            'template' => 'AdminBundle:Preinscripcion:modificar_edit_link.html.twig'
                        ],
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $object = $this->getSubject();
        if ($object->getOferta() && $object->getOferta()->getEstrategia()->getDiagnostico()) {
            $formMapper
                ->add('diagnostico', 'text', [
                    'attr' => [
                        'maxlength' => 60
                    ],
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
            ;
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('usuario.firstname', null, [
                    'label' => 'formulario.usuario.firstname'
                ])
                ->add('usuario.numeroIdentificacion', null, [
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Preinscripcion:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:Preinscripcion:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

}
