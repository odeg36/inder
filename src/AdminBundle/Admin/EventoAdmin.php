<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;


class EventoAdmin extends AbstractAdmin
{
   

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        $collection->remove("delete");
        $collection->add('configuracion', 'configuracion/'.$this->getRouterIdParameter());
        $collection->add('carne', 'carne/'.$this->getRouterIdParameter());
        $collection->add('inscripcion', 'inscripcion/'.$this->getRouterIdParameter());
        $collection->add('equiposParticipantes', 'equiposParticipantes/'.$this->getRouterIdParameter());
        $collection->add('sanciones', 'sanciones/'.$this->getRouterIdParameter() );
        $collection->add('clasificacionCalendario','clasificacionCalendario/'.$this->getRouterIdParameter()); 
        $collection->add('inscripcionUsuarioNatural', 'inscripcionUsuarioNatural');
        $collection->add('inscripcionEquipoUsuarioNatural', 'inscripcionEquipoUsuarioNatural');

        $collection->add('carnes','carnes/'.$this->getRouterIdParameter());
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre')
            ->add('disciplina')
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

        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $ROLE_PERSONANATURAL = $user->hasRole('ROLE_PERSONANATURAL');

        $ROLE_ADMIN = $user->hasRole('ROLE_SUPER_ADMIN');
        

        if($ROLE_ADMIN == true)
        {
            $listMapper
            ->add('nombre')
            ->add('disciplina', 'text')
            ->add('cupo')
            ->add('estado')
            ->add('fechaInicial', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('fechaFinal', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                ),
            ));
        }elseif($ROLE_PERSONANATURAL == true){

            $listMapper
            ->add('nombre')
            ->add('disciplina', 'text')
            ->add('cupo')
            ->add('estado')
            ->add('fechaInicial', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('fechaFinal', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'inscribir' => array('template' => 'AdminBundle:Evento:inscribir_usuario_action.html.twig'),
                ),
            ));

        }else{

            $listMapper
            ->add('nombre')
            ->add('disciplina', 'text')
            ->add('cupo')
            ->add('estado')
            ->add('fechaInicial', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('fechaFinal', null, array(
                'format' => 'd-m-Y ',
                'timezone' => 'America/Bogota'
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                ),
            ));

        }

        
    }
    

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('nombre')
            ->add('estado')
            ->add('fechaInicial')
            ->add('fechaFinal')
            ->add('fechaInicialInscripcion')
            ->add('fechaFinalInscripcion')
            ->add('cupo')
            ->add('numeroEquipos')
            ->add('participantesEquipoMinimo')
            ->add('participantesEquipoMaximo')
            ->add('numeroMujeres')
            ->add('numeroHombres')
            ->add('edadMayorQue')
            ->add('edadMenorQue')
            ->add('descripcion')
            ->add('terminosCondiciones')
            ->add('imagen')
            ->add('tieneInscripcionPublica')
            ->add('tienePreinscripcionPublica')
            ->add('tieneFormularioGanador')
            ->add('tieneFormularioRecambios')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('nombre')
            ->add('estado')
            ->add('fechaInicial')
            ->add('fechaFinal')
            ->add('fechaInicialInscripcion')
            ->add('fechaFinalInscripcion')
            ->add('escenarioDeportivo')
            ->add('division')
            ->add('puntoAtencion')
            ->add('cupo')
            ->add('numeroEquipos')
            ->add('participantesEquipoMinimo')
            ->add('participantesEquipoMaximo')
            ->add('numeroMujeres')
            ->add('numeroHombres')
            ->add('edadMayorQue')
            ->add('edadMenorQue')
            ->add('descripcion', null, array('safe' => true))
            ->add('terminosCondiciones')
            ->add('imagen')
            ->add('tieneInscripcionPublica')
            ->add('tienePreinscripcionPublica')
            ->add('tieneFormularioGanador')
            ->add('tieneFormularioRecambios')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Evento:base_list_evento.html.twig';
            case 'show':
                return 'AdminBundle:Evento:base_show.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }


    public function createQuery($context = 'list') {
        $request = $this->getRequest();


        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $ROLE_PERSONANATURAL = $user->hasRole('ROLE_PERSONANATURAL');

        $query = parent::createQuery($context);
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

                
        if($ROLE_PERSONANATURAL == true)
        {
            $repoActivity = $em->getRepository('LogicBundle:Evento');
            $query = new ProxyQuery(
                    $repoActivity->createQueryBuilder('evento')
                                ->where('evento.estado = :estado')
                                ->setParameter('estado', "Publicado"));
        }else{

            $repoActivity = $em->getRepository('LogicBundle:Evento');
            $query = new ProxyQuery(
                    $repoActivity->createQueryBuilder('evento'));
        }

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        } return $query;
    }
}
