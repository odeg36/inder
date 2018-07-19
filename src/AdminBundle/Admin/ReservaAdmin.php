<?php

namespace AdminBundle\Admin;

use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
//necesario para utilizar las rutas
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use LogicBundle\Entity\Reserva;
use Whyte624\SonataAdminExtraExportBundle\Admin\AdminExtraExportTrait;

class ReservaAdmin extends AbstractAdmin {

    use AdminExtraExportTrait;

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');

        $collection->add('paso1', 'paso1/' . $this->getRouterIdParameter());
        $collection->add('list', 'list', array(), array(), array('expose' => true));
        $collection->add('paso2', 'paso2/' . $this->getRouterIdParameter());
        $collection->add('paso3', 'paso3/' . $this->getRouterIdParameter());
        $collection->add('paso3-1', 'paso3-1/' . $this->getRouterIdParameter());
        $collection->add('paso4', 'paso4/' . $this->getRouterIdParameter());
        $collection->add('paso4Extra', 'paso4Extra/' . $this->getRouterIdParameter());
        $collection->add('paso5', 'paso5/' . $this->getRouterIdParameter());
        $collection->add('pasoFinal', 'pasoFinal/' . $this->getRouterIdParameter());
        $collection->add('asistencia', $this->getRouterIdParameter() . '/asistencia');
        $collection->add('cancelarReserva', $this->getRouterIdParameter() . '/cancelarReserva');
        $collection->add('motivoCancelacion', $this->getRouterIdParameter() . '/motivoCancelacion');
        $collection->add('motivoCancelacionBatch', 'motivoCancelacionBatch');
        $collection->add('reservasPorEscenarios', 'reservasPorEscenarios', array(), array(), array('expose' => true));
        $collection->add('obtenerInformacionEscenario', 'obtenerInformacionEscenario', array(), array(), array('expose' => true));
    }

    public function createQuery($context = 'list') {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $organismoDeportivo = $user->getUsuariosEscenariosDeportivos();

        if ($ROLE_SUPER_ADMIN == true) {
            $query = parent::createQuery($context);
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $reservaUsuario = $em->getRepository('LogicBundle:Reserva');
            $query = new ProxyQuery($reservaUsuario->createQueryBuilder('reserva'));
        } else if ($ROLE_GESTOR_ESCENARIO == true) {
            $query = parent::createQuery($context);
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $ed = array();

            $escenarioDeportivos = $em->getRepository('LogicBundle:EscenarioDeportivo')
                            ->createQueryBuilder('escenarioDeportivo')
                            ->select('escenarioDeportivo.id')
                            ->join('escenarioDeportivo.usuarioEscenarioDeportivos', 'usuarioEscenarioDeportivos')
                            ->where('usuarioEscenarioDeportivos.usuario = :id')
                            ->setParameter('id', $id)
                            ->getQuery()->getArrayResult();
            foreach ($escenarioDeportivos as $escenarioDeportivo) {
                array_push($ed, $escenarioDeportivo["id"]);
            }

            $query = new ProxyQuery($em->getRepository('LogicBundle:Reserva')
                            ->createQueryBuilder('reserva')
                            ->innerJoin('reserva.escenarioDeportivo', 'escenarioDeportivo')
                            ->where('escenarioDeportivo.id in (:idEscenarioDeportivo)')
                            ->andWhere('reserva.estado != :estadoReserva')
                            ->setParameter('idEscenarioDeportivo', $ed)
                            ->setParameter('estadoReserva', 'Pre-Reserva'));
        } else if ($ROLE_ORGANISMO_DEPORTIVO == true) {
            $query = parent::createQuery($context);
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $reservaUsuario = $em->getRepository('LogicBundle:Reserva');
            $query = new ProxyQuery($reservaUsuario->createQueryBuilder('reserva')
                            ->join("reserva.usuario", "u")
                            ->where("u.organizacionDeportiva = :organismo")
                            ->setParameter("organismo", $user->getOrganizacionDeportiva()->getId())
            );
        } else {
            $query = parent::createQuery($context);
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $reservaUsuario = $em->getRepository('LogicBundle:Reserva');
            $query = new ProxyQuery($reservaUsuario->createQueryBuilder('reserva')
                            ->where('reserva.usuario = :idUsuario')
                            ->setParameter('idUsuario', $id));
        }
        $query->andWhere("reserva.completada = :completada")
                ->setParameter("completada", true);
        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        }
        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('usuario.firstname', null, array('label' => 'Nombre'))
                ->add('usuario.numeroIdentificacion', null, array('label' => 'Número de identificación'))
                ->add('escenarioDeportivo', null, array('label' => 'Escenario'))
                ->add('tipoReserva', null, array('label' => 'Tipo de reserva'))
                ->add('fechaInicio', 'doctrine_orm_date', [
                    'field_type' => 'sonata_type_date_picker',
                    'field_options' => [
                        'format' => 'yyyy-MM-dd'
                    ]
                ])
                ->add('fechaFinal', 'doctrine_orm_date', [
                    'field_type' => 'sonata_type_date_picker',
                    'field_options' => [
                        'format' => 'yyyy-MM-dd'
                    ]
                ])
                ->add('estado')
        ;
    }

    public function getExportFormats() {
        return ['csv', 'xls', 'pdf'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('usuario', null, array('label' => 'Nombre'))
                ->add('usuario.numeroIdentificacion', null, array('label' => 'Número de identificación'))
                ->add('escenarioDeportivo', 'text', array('label' => 'Escenario'))
                ->add('tipoReserva', 'text', array('label' => 'Tipo de reserva'))
                ->add('fechaInicio', null, array('format' => 'd-m-Y ', 'label' => 'Fecha inicial'))
                ->add('fechaFinal', null, array('format' => 'd-m-Y ', 'label' => 'Fecha final'))
                ->add('estado')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'asistencia' => array(
                            'template' => 'AdminBundle:Reservas\btn:crear.asistencia.html.twig'
                        ),
                        'cancelar' => array(
                            'template' => 'AdminBundle:Reservas\btn:cancelarReserva.html.twig'
                        ),
                    ),
                ))
        ;
    }

    public function getExportFields() {
        return array(
            $this->trans('formulario_reserva.lista.numero_reserva') => 'id',
            $this->trans('formulario_reserva.lista.usuario') => 'usuario.firstname',
            $this->trans('formulario_reserva.lista.escenarioDeportivo') => 'escenarioDeportivo',
            $this->trans('formulario_reserva.lista.tipoReserva') => 'tipoReserva',
            $this->trans('formulario_reserva.lista.fechaInicio') => 'fechaInicio',
            $this->trans('formulario_reserva.lista.fechaFinal') => 'fechaFinal',
            $this->trans('formulario_reserva.lista.estado') => 'estado'
        );
    }

    public function getDataSourceIterator() {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('id')
                ->add('fechaInicio')
                ->add('fechaFinal')
                ->add('horaInicial')
                ->add('horaFinal')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('escenarioDeportivo')
                ->add('division')
                ->add('barrio')
                ->add('disciplina')
                ->add('tipoReserva')
                ->add('fechaInicio')
                ->add('fechaFinal')
                ->add('diaReserva')
                ->add('horaInicial')
                ->add('horaFinal')
                ->add('usuarios')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Reservas:base_list.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function configureBatchActions($actions) {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $ROLE_SUPER_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        $ROLE_ORGANISMO_DEPORTIVO = $user->hasRole('ROLE_ORGANISMO_DEPORTIVO');
        $ROLE_USER = $user->hasRole('ROLE_USER');
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');

        $rolAdministrador = false;
        if ($ROLE_SUPER_ADMIN == true || $ROLE_GESTOR_ESCENARIO == true) {
            $rolAdministrador = true;
        }
        if ($rolAdministrador == true) {
            $actions['aprobar'] = array(
                'ask_confirmation' => true
            );
            $actions['rechazar'] = array(
                'ask_confirmation' => true
            );
        }
        return $actions;
    }

}
