<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Validator\Constraints\Count;

class OrganizacionDeportivaAdmin extends AbstractAdmin {

    protected $em;

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    public function createQuery($context = 'list') {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $tokenStorage = $this->getConfigurationPool()->getContainer()->get('security.token_storage');
        $user = $tokenStorage->getToken()->getUser();

        $repository = $this->modelManager->getEntityManager($this->getClass())->getRepository($this->getClass());
        if (!$securityContext->isGranted('ROLE_SUPER_ADMIN') && !$securityContext->isGranted('ROLE_ADMINISTRAR_ORGANISMOS_DEPORTIVOS')) {
            $query = new ProxyQuery($repository->buscarOrganizacionesDeportivas($user));

            foreach ($this->extensions as $extension) {
                $extension->configureQuery($this, $query, $context);
            }

            return $query;
        }

        $query = parent::createQuery($context);
        return $query;
    }

    protected $baseRoutePattern = "organizacion/deportiva";

    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('solicitudRegistro', $this->getRouterIdParameter() . '/solicitud');
        $collection->remove('delete');
        $collection->remove('create');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('razonSocial', null, array(
                    "label" => "formulario_registro.pjuridica_informacion.razon_social",
                ))
                ->add('nit')
                ->add('tipoEntidad', null, array(
                    'label' => 'formulario_registro.pjuridica_informacion.tipo_entidad',
                ))
                ->add('clasificacionOrganizacion', null, array('label' => 'formulario.clasificacion_organizacion'))
                ->add('periodoestatutario', null, array('label' => 'admin.organizaciondeportiva.periodoestatutario'))
                ->add('terminoregistro', null, array('label' => 'admin.organizaciondeportiva.terminoregistro'))
                ->add('aprobado')
                ->add('fecharegistro', 'doctrine_orm_datetime', array(
                    'widget' => 'single_text',
                    'label' => 'admin.organizaciondeportiva.fecharegistro',
                    'field_type'=>'sonata_type_datetime_picker',
                ), null, array(
                    'format' => 'yyyy-MM-dd',
                    'required' => false,  'attr' => array('class' => 'datepicker'),
                    'label' => 'admin.organizaciondeportiva.fecharegistro'
                ))
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
                ->add('nit')
                ->add('razonSocial', null, array(
                    "label" => "formulario_registro.pjuridica_informacion.razon_social",
                ))
                ->add('tipoEntidad', null, array(
                    'label' => 'formulario_registro.pjuridica_informacion.tipo_entidad',
                ))
                ->add('periodoestatutario', null, array('label' => 'admin.organizaciondeportiva.periodoestatutario'))
                ->add('clasificacionOrganizacion', null, array('label' => 'formulario.clasificacion_organizacion'))
                ->add('terminoregistro', null, array('label' => 'admin.organizaciondeportiva.terminoregistro'))
                ->add('aprobado')
                ->add('fecharegistro', null, array('label' => 'admin.organizaciondeportiva.fecharegistro'))
                ->add('_action', null, array(
                    'label' => 'admin.tituloacciones',
                    'actions' => array(
                        'solicitud' => array('template' => 'AdminBundle:OrganizacionDeportiva:list__action_solicitud.html.twig'),
                        'editar' => array('template' => 'AdminBundle:OrganizacionDeportiva:list__action_editarOrganizacion.html.twig'),
                        'show' => array(),
                        'edit' => array('template' => 'AdminBundle:OrganizacionDeportiva:list__action_edit.html.twig'),
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('razonSocial', null, [
                    'label' => 'formulario_registro.pjuridica_informacion.razon_social'
                ])
                ->add('usuarios', 'sonata_type_model_autocomplete', array(
                    'property' => 'full_text',
                    'multiple' => true,
                    'minimum_input_length' => 6,
                    'label' => 'formulario.administradores',
                    'to_string_callback' => function($entity, $property) {
                        return $entity->getUsername() . " - " . $entity->getFirstname() . ' ' . $entity->getLastname();
                    },
                    'callback' => function ($admin, $property, $value) {
                        $datagrid = $admin->getDatagrid();
                        $queryBuilder = $datagrid->getQuery();
                        $queryBuilder = $this->em->getRepository("ApplicationSonataUserBundle:User")->buscarUsernameQuery($queryBuilder, $value);
                        $datagrid->setValue($property, null, $value);
                    },
                    'constraints' => [
                        new Count(array(
                            'min' => 1
                                ))
                    ]
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nit')
                ->add('razonSocial', null, array(
                    "label" => "formulario_registro.pjuridica_informacion.razon_social",
                ))
                ->add('tipoEntidad', null, array(
                    'label' => 'formulario_registro.pjuridica_informacion.tipo_entidad',
                ))
                ->add('clasificacionOrganizacion', null, array('label' => 'formulario.clasificacion_organizacion'))
                ->add('periodoestatutario', null, array('label' => 'admin.organizaciondeportiva.periodoestatutario'))
                ->add('terminoregistro', null, array('label' => 'admin.organizaciondeportiva.terminoregistro'))
                ->add('aprobado')
                ->add('fecharegistro', null, array('label' => 'admin.organizaciondeportiva.fecharegistro'))
        ;
    }

    public function getTemplate($name) {

        switch ($name) {
            case 'create':
            case 'show':
                return 'AdminBundle:OrganizacionDeportiva:solicitudDetalle.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function preUpdate($object) {
        parent::preUpdate($object);

        $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName("Registrado (Organismos deportivos)");

        foreach ($object->getUsuarios() as $key => $usuario) {
            $usuario->setOrganizacionDeportiva($object);

            if ($grupo) {
                if (!$usuario->isSuperAdmin()) {
                    $usuario->addGroup($grupo);
                }
            }
        }
    }

}
