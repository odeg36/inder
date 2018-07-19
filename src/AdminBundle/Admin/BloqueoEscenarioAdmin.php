<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\Bloqueo\AddDivisionFieldSubscriber;
use AdminBundle\Form\EventListener\Bloqueo\AddEscenarioFieldSubscriber;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class BloqueoEscenarioAdmin extends AbstractAdmin {

    public function createQuery($context = 'list') {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $tokenStorage = $this->getConfigurationPool()->getContainer()->get('security.token_storage');
        $user = $tokenStorage->getToken()->getUser();

        $repository = $this->modelManager->getEntityManager($this->getClass())->getRepository($this->getClass());
        if ($user->hasRole('ROLE_GESTOR_ESCENARIO')) {
            $query = new ProxyQuery($repository->createQueryBuilder('b')
                            ->join("b.escenarioDeportivo", "e")
                            ->join("e.usuarioEscenarioDeportivos", "ued")
                            ->where("ued.usuario = :usuario")
                            ->setParameter("usuario", $user));
            foreach ($this->extensions as $extension) {
                $extension->configureQuery($this, $query, $context);
            }
            return $query;
        }

        $query = parent::createQuery($context);
        return $query;
    }

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
        $collection->remove('edit');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('fechaInicial')
                ->add('fechaFinal')
                ->add('horaInicial')
                ->add('horaFinal')
                ->add('descripcion')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('fechaInicial')
                ->add('fechaFinal')
                ->add('horaInicial')
                ->add('horaFinal')
                 ->add("escenarioDeportivo")
                ->add('descripcion')
                ->add('_action', null, [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ],
                ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper) {
        $noVacio = array(new NotBlank());

        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $ROLE_GESTOR_ESCENARIO = $user->hasRole('ROLE_GESTOR_ESCENARIO');
        $formMapper
                ->add('tipoReserva', EntityType::class, [
                    'class' => 'LogicBundle:TipoReserva',
                    'label' => 'label.motivo',
                    'constraints' => $noVacio,
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.bloquea = :bloquea')
                                ->setParameter('bloquea', true);
                    },
                ])
                ->add('fechaInicial', DateType::class, array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'constraints' => $noVacio,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add('fechaFinal', DateType::class, array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'constraints' => $noVacio,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add('horaInicial', TextType::class, ['attr' => [
                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time'
                    ],
                    'constraints' => $noVacio,
                ])
                ->add('horaFinal', TextType::class, ['attr' => [
                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time'
                    ],
                    'constraints' => $noVacio,
                ])
                ->add("escenarioDeportivo")
                ->add("division", EntityType::class, [
                    'class' => 'LogicBundle:Division',
                        ]
                )
                ->add('descripcion', null, [
                    'constraints' => $noVacio,
                ])
                ->getFormBuilder()
                ->addEventSubscriber(new AddEscenarioFieldSubscriber($user, $this->getConfigurationPool()->getContainer()->get("doctrine")->getManager()))
                ->addEventSubscriber(new AddDivisionFieldSubscriber($this->getConfigurationPool()->getContainer()->get('doctrine')->getManager()))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('fechaInicial')
                ->add('fechaFinal')
                ->add('horaInicial')
                ->add('horaFinal')
                 ->add("escenarioDeportivo")
                ->add('descripcion')
                ->add('fechaCreacion')
                ->add('fechaActualizacion')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'create':
            case 'edit':
                return 'AdminBundle:BloqueoEscenario:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

}
