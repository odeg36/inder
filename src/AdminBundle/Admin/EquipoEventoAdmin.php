<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use LogicBundle\Entity\Evento;

class EquipoEventoAdmin extends AbstractAdmin {

    public function createQuery($context = 'list') {
        $request = $this->getRequest();
        $idEvento = $request->get('id');

        $query = parent::createQuery($context);
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $repoJugadorEvento = $em->getRepository('LogicBundle:EquipoEvento');
        $query = new ProxyQuery($repoJugadorEvento->createQueryBuilder('equipo')
                        ->where('equipo.evento = :evento')
                        ->setParameter('evento', $idEvento));

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
                ->add('nombre', null, [
                    'label' => 'formulario.equipo'
                ])
                ->add('estado', 'doctrine_orm_choice', array(
                    'label' => 'Estado'), 'choice', array(
                    'choices' => array(
                        'Aprobado' => '1', // The key (value1) will contain the actual value that you want to filter on
                        'Rechazado' => '2', // The 'Name Two' is the "display" name in the filter
                        'Pendiente' => '0',
                    ),
                    'expanded' => true,
                    'multiple' => true));
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $request = $this->getRequest();
        $idEvento = $request->get('id');

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        if ($idEvento == null) {
            $evento = new Evento();
        } else {
            $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
        }

        $cupo = $evento->getCupo();
        $listMapper
                ->add('nombreEstado', null, [
                    'label' => 'formulario_evento.labels.configuracion.estado'
                ])
                ->add('numeroJugadores', null, [
                    'label' => 'formulario_evento.labels.jugador_evento.numero_jugadores'
                ])
                ->add('nombre', null, [
                    'label' => 'formulario_evento.labels.jugador_evento.nombre_equipo'
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array()
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

        $formMapper
                //->with("formulario.oferta.title_jugador_evento")            
                ->add('nombre', null, array(
                    "required" => true,
                    "constraints" => $noVacio,
                    'label' => 'formulario_evento.labels.jugador_evento.nombre',
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => '',
                    ),
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('nombre')
                ->add('estado')
                ->add('jugadorEventos', null, [
                    'identifier' => false,
                    'label' => 'Jugadores'
                ])
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Evento/Equipos:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:Evento/Equipos:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function configureBatchActions($actions) {
        $actions['Aprobar'] = array('ask_confirmation' => false);
        $actions['Rechazar'] = array('ask_confirmation' => false);
        return $actions;
    }

}
