<?php

namespace AdminBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Form\DireccionType;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class EncuentroSistemaCuatroAdmin extends AbstractAdmin
{
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection  $collection) {
        parent::configureRoutes($collection);
        $collection->add('resultadoEncuentroCuatro', 'resultadoEncuentroCuatro/');
        $collection->add('programarEncuentroCuatro', 'programarEncuentroCuatro/' );
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('competidorUno')
            ->add('competidorDos')
            ->add('grupo')
            ->add('puntoAtencion')
            ->add('escenarioDeportivo')
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
        ->add('id')
        ->add('competidorUno', 'text')
        ->add('competidorDos', 'text')
        ->add('grupo')
        ->add('fecha')
        ->add('hora')
        ->add('puntoAtencion')
        ->add('escenarioDeportivo', 'text')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    //'delete' => array(),
                    'resultado'=>  array(
                        'template' => 'AdminBundle:PlayOff:list__action_resultado.html.twig'
                    ),
                    'programar'=>  array(
                        'template' => 'AdminBundle:PlayOff:list__action_programar.html.twig'
                    ),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('hora')
            ->add('fecha')
            ->add('grupo')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('competidorUno')
            ->add('competidorDos')
            ->add('grupo')
            ->add('fecha')
            ->add('hora')
            ->add('puntoAtencion')
            ->add('escenarioDeportivo')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:PlayOff:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:PlayOff:base_edit.html.twig';
            case 'resultado':
                return 'AdminBundle:PlayOff:tipos_faltas.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }



    public function createQuery($context = 'list') 
    { 
        $request = $this->getRequest(); 
        

        $evento_id = $request->get('id');
        $tipo = $request->get('tipo');


        if( $tipo == 6 )
        {
            $tipoDeSistema = "PlayOff";
            
        }

        $query = parent::createQuery($context);

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        
        $repoActivity = $em->getRepository('LogicBundle:EncuentroSistemaCuatro'); 
        $query = new ProxyQuery(
            $repoActivity->createQueryBuilder('encuentroSistemaCuatro') 
            ->join('encuentroSistemaCuatro.sistemaJuegoCuatro', 'sistemaJuegoCuatro') 
            ->where('sistemaJuegoCuatro.evento = :evento')
            ->setParameter('evento',$evento_id)
        ); 

        foreach ($this->extensions as $extension) 
        { 
            $extension->configureQuery($this, $query, $context); 
        } return $query; 


    }


    public function configureBatchActions($actions) 
    { 
        $actions['delete'] = array( 'ask_confirmation' => false  ); 
        return $actions; 
    }
}
