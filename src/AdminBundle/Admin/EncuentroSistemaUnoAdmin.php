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
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class EncuentroSistemaUnoAdmin extends AbstractAdmin {

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
                ->add('hora');
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    public function getDataSourceIterator() {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d-m-Y'); //change this to suit your needs
        return $iterator;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $object = $this->getRequest()->get('id');

        $request = $this->getRequest();

        $evento = $request->get('id');
        $tipoDeSistemaDeJuego = $request->get('tipo');

        

        if ($tipoDeSistemaDeJuego == 1) {
            $tipo = "Escalera";
            $this->classnameLabel = "Escalera";
        }

        if ($tipoDeSistemaDeJuego == 2) {
            $tipo = "Piramide";
            $this->classnameLabel = "Piramide";
        }

        if ($tipoDeSistemaDeJuego == 3) {
            $tipo = "Chimenea";
            $this->classnameLabel = "Chimenea";
        }

        if ($tipoDeSistemaDeJuego == 0) {
            $tipo = "Ninguno";
            $this->classnameLabel = "Ninguno";
        }


        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $query = $em->getRepository('LogicBundle:SistemaJuegoUno')
                ->createQueryBuilder('sistemaJuegoUno')
                ->Where('sistemaJuegoUno.evento = :evento')
                ->andWhere('sistemaJuegoUno.tipoSistema = :tipo')
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
                ->add('competidorUnoObject', 'text', [
                    'label' => 'competidorunosistemaJuego',
                ])
                ->add('competidorDosObject', 'text', [
                    'label' => 'competidordossistemaJuego',
                ])
                ->add('escenarioDeportivo', 'text')
                ->add('puntoAtencion', 'text')
                ->add('fecha', null, array('format' => 'd-m-Y ', 'timezone' => 'America/Bogota'))
                ->add('hora', 'text', [
                    'label' => 'horasistemaJuego',
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array('template' => 'AdminBundle:Escalera:list_edit_action.html.twig', $this->data = $sistemaId, $this->tipo = $tipoDeSistemaDeJuego,
                        ),
                        'delete' => array(),
                        'resultado' => array(
                            'template' => 'AdminBundle:Escalera:list__action_resultado.html.twig'
                        ),
                    ),
                ))
        ;
    }

    /**
     * @param ListMapper $listMapper
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


        $sistemaJuego_id = $request->get('sistemaJuegoUno');
        $tipoDeSistemaDeJuego = $request->get('tipoDeSistemaDeJuego');



        if ($tipoDeSistemaDeJuego == 1) {
            $tipo = "Escalera";
            $this->classnameLabel = "Escalera";
        }

        if ($tipoDeSistemaDeJuego == 2) {
            $tipo = "Piramide";
            $this->classnameLabel = "Piramide";
        }

        if ($tipoDeSistemaDeJuego == 3) {
            $tipo = "Chimenea";
            $this->classnameLabel = "Chimenea";
        }

        if ($tipoDeSistemaDeJuego == 0) {
            $tipo = "Ninguno";
            $this->classnameLabel = "Ninguno";
        }


        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $query = $em->getRepository('LogicBundle:SistemaJuegoUno')
                ->createQueryBuilder('sistemaJuegoUno')
                ->Where('sistemaJuegoUno.id = :sistemaId')
                ->andWhere('sistemaJuegoUno.tipoSistema = :tipo')
                ->setParameters([
                    'sistemaId' => $sistemaJuego_id,
                    'tipo' => $tipo,
                ])
                ->getQuery()
                ->getOneOrNullResult();


        $cupoEvento = $query->getEvento()->getCupo();
        $idEvento = $query->getEvento()->getId();

        if ($cupoEvento == "Individual") {

            $jugadores = $em->getRepository('LogicBundle:JugadorEvento')
                    ->createQueryBuilder('jugadorEvento')
                    ->Where('jugadorEvento.evento = :evento')
                    ->andWhere('jugadorEvento.estado = :estado')
                    ->setParameter('evento', $idEvento)
                    ->setParameter('estado', "Exitoso")
                    ->getQuery()
                    ->getResult();

            $competidorUno = null;
            $competidorDos = null;
            if ($object->getId() != null) {
                $competidorUno = $em->getRepository('LogicBundle:EncuentroSistemaUno')->getNombreCompetidorUno($object, 'object');
                $competidorDos = $em->getRepository('LogicBundle:EncuentroSistemaUno')->getNombreCompetidorDos($object, 'object');
            }

            if (count($object->getEscenarioDeportivo()) > 0) {
                $estatocheckEscenarioPuntoAtencion = "true";
            } else if (count($object->getPuntoAtencion()) > 0) {
                $estatocheckEscenarioPuntoAtencion = "false";
            } else {
                $estatocheckEscenarioPuntoAtencion = null;
            }


            $formMapper
                    ->add('competidorUno', EntityType::class, array(
                        'class' => 'LogicBundle:JugadorEvento',
                        'attr' => [
                            'class' => 'col-md-6'
                        ],
                        'data' => $competidorUno,
                        'choices' => $jugadores,
                        "constraints" => $noVacio,
                        'mapped' => false,
                            )
                    )
                    ->add('competidorDos', EntityType::class, array(
                        'class' => 'LogicBundle:JugadorEvento',
                        'attr' => array(
                            'class' => 'col-md-6',
                        ),
                        'data' => $competidorDos,
                        "constraints" => $noVacio,
                        'choices' => $jugadores,
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

        if ($cupoEvento == "Equipos") {

            $equipos = $em->getRepository('LogicBundle:EquipoEvento')
                    ->createQueryBuilder('equipoEvento')
                    ->Where('equipoEvento.evento = :evento')
                    ->andWhere('equipoEvento.estado = :estado')
                    ->setParameter('evento', $idEvento)
                    ->setParameter('estado', 1)
                    ->getQuery()
                    ->getResult();


            $competidorUno = null;
            $competidorDos = null;
            if ($object->getId() != null) {
                $competidorUno = $em->getRepository('LogicBundle:EncuentroSistemaUno')->getNombreCompetidorUno($object, 'object');
                $competidorDos = $em->getRepository('LogicBundle:EncuentroSistemaUno')->getNombreCompetidorDos($object, 'object');
            }

            if (!$object->getEscenarioDeportivo()) {
                $estatocheckEscenarioPuntoAtencion = "true";
            } else if (!$object->getPuntoAtencion()) {
                $estatocheckEscenarioPuntoAtencion = "false";
            } else {
                $estatocheckEscenarioPuntoAtencion = null;
            }


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
                ->add('fecha')
                ->add('hora')
                ->add('competidor_id')
                ->add('competidor_dos_id')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Escalera:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:Escalera:base_edit.html.twig';
            case 'resultado':
                return 'AdminBundle:Escalera:tipos_faltas.html.twig';
            //caso para piramide
            case 'listPiramide':
                return 'AdminBundle:Piramide:base_list.html.twig';
            case 'editPiramide':
                return 'AdminBundle:Piramide:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function createQuery($context = 'list') {
        $request = $this->getRequest();



        $evento_id = $request->get('id');
        $tipo = $request->get('tipo');

        if ($tipo == 1) {
            $tipoDeSistema = "Escalera";
        }

        if ($tipo == 2) {
            $tipoDeSistema = "Piramide";
        }

        if ($tipo == 3) {
            $tipoDeSistema = "Chimenea";
        }

        $query = parent::createQuery($context);

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $repoActivity = $em->getRepository('LogicBundle:EncuentroSistemaUno');
        $query = new ProxyQuery(
                $repoActivity->createQueryBuilder('encuentroSistemaUno')
                        ->join('encuentroSistemaUno.sistemaJuegoUno', 'sistemaJuegoUno')
                        ->where('sistemaJuegoUno.evento = :evento')
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
