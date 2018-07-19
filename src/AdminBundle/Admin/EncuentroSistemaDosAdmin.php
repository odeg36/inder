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

class EncuentroSistemaDosAdmin extends AbstractAdmin {

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->add('resultadoEncuentro', 'resultadoEncuentro/');
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('competidorUno')
                ->add('competidorDos')
                ->add('puntoAtencion')
                ->add('escenarioDeportivo')
                ->add('tipoDeEncuentro')
        ;
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {

        $object = $this->getRequest()->get('id');

        $request = $this->getRequest();

        $evento = $request->get('id');
        $tipoDeSistemaDeJuego = $request->get('tipo');


        if ($tipoDeSistemaDeJuego == 4) {
            $tipo = "Liga";
        }


        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $query = $em->getRepository('LogicBundle:SistemaJuegoDos')
                ->createQueryBuilder('sistemaJuegoDos')
                ->Where('sistemaJuegoDos.evento = :evento')
                ->andWhere('sistemaJuegoDos.tipoSistema = :tipo')
                ->setParameters([
                    'evento' => $evento,
                    'tipo' => $tipo,
                ])
                ->getQuery()
                ->getOneOrNullResult();

        if ($query != null) {
            $sistemaId = $query->getId();
        } else {

            $sistemaId = 0;
        }

        $listMapper
                ->add('id')
                ->add('competidorUno', 'text')
                ->add('competidorDos', 'text')
                ->add('fecha')
                ->add('hora', 'text')
                ->add('puntoAtencion', 'text')
                ->add('escenarioDeportivo', 'text')
                ->add('tipoDeEncuentro')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array('template' => 'AdminBundle:Liga:list_edit_action.html.twig', $this->data = $sistemaId, $this->tipo = $tipoDeSistemaDeJuego,
                        ),
                        'delete' => array(),
                        'resultado' => array(
                            'template' => 'AdminBundle:Liga:list__action_resultado.html.twig'
                        ),
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $object = $this->getSubject();

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $es_requerido = "falso";
        $mappeado = false;

        $request = $this->getRequest();

        $sistemaJuego_id = $request->get('sistemaJuegoDos');
        $tipoDeSistemaDeJuego = $request->get('tipoDeSistemaDeJuego');

        if ($tipoDeSistemaDeJuego == 4) {
            $tipo = "Liga";
        }

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $query = $em->getRepository('LogicBundle:SistemaJuegoDos')
                ->createQueryBuilder('sistemaJuegoDos')
                ->Where('sistemaJuegoDos.id = :sistemaId')
                ->andWhere('sistemaJuegoDos.tipoSistema = :tipo')
                ->setParameters([
                    'sistemaId' => $sistemaJuego_id,
                    'tipo' => $tipo,
                ])
                ->getQuery()
                ->getOneOrNullResult();

        $cupoEvento = $query->getEvento()->getCupo();
        $idEvento = $query->getEvento()->getId();
        $diciplinaEvento = $query->getEvento()->getDisciplina()->getNombre();

        if ($cupoEvento == "Equipos") {
            $competidorUno = null;
            $competidorDos = null;
            if ($object->getId() != null) {

                $competidorUno = $object->getCompetidorUno();
                $competidorDos = $object->getCompetidorDos();
            }

            if (count($object->getEscenarioDeportivo()) > 0) {
                $estatocheckEscenarioPuntoAtencion = "true";
            } else if (count($object->getPuntoAtencion()) > 0) {
                $estatocheckEscenarioPuntoAtencion = "false";
            } else {
                $estatocheckEscenarioPuntoAtencion = null;
            }

            $equipos = $em->getRepository('LogicBundle:EquipoEvento')
                    ->createQueryBuilder('equipoEvento')
                    ->Where('equipoEvento.evento = :evento')
                    ->andWhere('equipoEvento.estado = :estado')
                    ->setParameter('evento', $idEvento)
                    ->setParameter('estado', 1)
                    ->getQuery()
                    ->getResult();

            $formMapper
                    ->add('competidorUno', EntityType::class, array(
                        'class' => 'LogicBundle:EquipoEvento',
                        'attr' => [
                            'class' => 'col-md-6'
                        ],
                        'choices' => $equipos,
                        'data' => $competidorUno,
                        "constraints" => $noVacio,
                        'mapped' => false,
                            )
                    )
                    ->add('competidorDos', EntityType::class, array(
                        'class' => 'LogicBundle:EquipoEvento',
                        'attr' => array(
                            'class' => 'col-md-6',
                        ),
                        'choices' => $equipos,
                        'data' => $competidorDos,
                        "constraints" => $noVacio,
                        'mapped' => false,
                            )
                    )
                    //------- punto o escenario ------ //
                    ->add('formulario.oferta.seleccion.puntoAtencion', ChoiceType::class, [
                        'data' => $estatocheckEscenarioPuntoAtencion,
                        'choices' => array(
                            'formulario.oferta.escenario_seleccion.seleccion' => 'true',
                            'formulario.oferta.otro.seleccion' => 'false',
                        ),
                        'choice_attr' => function($val, $key, $index) {
                            return ['class' => 'seleccion-lugar-oferta'];
                        },
                        'label' => 'formulario_escalera.seleccion',
                        'expanded' => true,
                        'multiple' => false,
                        'mapped' => false,
                        "constraints" => $noVacio
                    ])
                    ->end()
                    ->with('formulario.oferta.escenario', array('class' => 'col-md-12 seleccion-escenario ocultar'))
                    ->add('escenarioDeportivo', EntityType::class, array(
                        "class" => "LogicBundle:EscenarioDeportivo",
                        'attr' => [
                            'class' => 'seleccion-escenarioDeportivo',
                            'onchange' => 'inder.evento.divisiones(this)',
                        ],
                        'required' => false,
                    ))
                    ->end()
                    ->with('formulario.oferta.escenario', array('class' => 'col-md-12 seleccion-escenario ocultar'))
                    ->add('division', EntityType::class, array(
                        "class" => "LogicBundle:Division",
                        'attr' => array(
                            'class' => 'seleccion-escenarioDeportivo'
                        ),
                        'required' => false,
                    ))
                    ->end()
                    ->add('puntoAtencion', EntityType::class, array(
                        'class' => 'LogicBundle:PuntoAtencion',
                        'label_attr' => array('class' => 'label_puntoAtencion'),
                        'placeholder' => '',
                        'attr' => array(
                            'class' => 'mostrarDirCreada seleccion-puntoAtencion',
                        ),
                        'required' => false,
                    ))
                    //-------fin punto o escenario ------ //
                    ->add('tipoDeEncuentro', HiddenType::class, array(
                        'data' => $diciplinaEvento,
                        'attr' => [
                            'class' => 'form-control nomb', 'autocomplete' => 'off'
                ]))
                    ->add('fecha', DateMaskType::class, array(
                        "label" => "formulario_escalera.fecha",
                        "required" => true,
                        'mask-alias' => 'dd/mm/yyyy',
                        'placeholder' => 'dd/mm/yyyy',
                        'attr' => array('class' => 'form-control col-lg-6 col-md-6 '),
                        "constraints" => $noVacio
                    ))
                    ->add('hora', TextType::class, array(
                        'label' => 'formulario_escalera.hora',
                        "required" => true,
                        'attr' => array('class' => 'hora_programacion form-control'),
                        "constraints" => $noVacio
                    ))
            ;
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('competidorUno')
                ->add('competidorDos')
                ->add('fecha')
                ->add('hora')
                ->add('puntoAtencion')
                ->add('escenarioDeportivo')
                ->add('tipoDeEncuentro')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Liga:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:Liga:base_edit.html.twig';
            case 'resultado':
                return 'AdminBundle:Liga:tipos_faltas.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function createQuery($context = 'list') {
        $request = $this->getRequest();


        $evento_id = $request->get('id');
        $tipo = $request->get('tipo');
        if ($tipo == 4) {
            $tipoDeSistema = "Liga";
        }

        $query = parent::createQuery($context);

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $em->getRepository('LogicBundle:EncuentroSistemaDos');
        $query = new ProxyQuery(
                $repoActivity->createQueryBuilder('encuentroSistemaDos')
                        ->join('encuentroSistemaDos.sistemaJuegoDos', 'sistemaJuegoDos')
                        ->where('sistemaJuegoDos.evento = :evento')
                        ->setParameter('evento', $evento_id)
        );

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        } return $query;
    }

    public function configureBatchActions($actions) {
        $actions['delete'] = array('ask_confirmation' => false);
        return $actions;
    }

}
