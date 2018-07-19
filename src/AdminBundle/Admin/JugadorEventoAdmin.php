<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use LogicBundle\Form\UsuarioEventoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Entity\TipoIdentificacion;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use LogicBundle\Entity\Evento;
use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Route\RouteCollection;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Whyte624\SonataAdminExtraExportBundle\Admin\AdminExtraExportTrait;
use Doctrine\ORM\EntityRepository;

class JugadorEventoAdmin extends AbstractAdmin {

    use AdminExtraExportTrait;

    public $cupo = '';
    public $idEvento = '';
    protected $em = '';

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->remove("delete");
        $collection->add('listaCarnes', 'listaCarnes/' . $this->getRouterIdParameter() . '/');
    }

    public function createQuery($context = 'list') {
        $request = $this->getRequest();
        $idEvento = $request->get('id');
        if ($idEvento) {
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $repoJugadorEvento = $em->getRepository('LogicBundle:JugadorEvento');
            $query = new ProxyQuery($repoJugadorEvento->createQueryBuilder('jugador')
                            ->where('jugador.evento = :evento')
                            ->setParameter('evento', $idEvento));

            foreach ($this->extensions as $extension) {
                $extension->configureQuery($this, $query, $context);
            }
            return $query;
        }
        
        $query = parent::createQuery($context);
        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $request = $this->getRequest();
        $idEvento = $request->get('id');
        $idEvento = (int) $idEvento;
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $jugadoresEvento = $em->getRepository("LogicBundle:JugadorEvento")
                        ->createQueryBuilder('jugadorEvento')
                        ->getQuery()->getResult();

        $usuarios = array();
        $equipos = array();
        $noDocumentos = array();

        foreach ($jugadoresEvento as $jugadorEvento) {

            if ($jugadorEvento->getUsuarioJugadorEvento() != null || $jugadorEvento->getUsuarioJugadorEvento() != "") {
                $usuario = $jugadorEvento->getUsuarioJugadorEvento()->getId();
                $equipo = $jugadorEvento->getEquipoEvento();
                $documentoUsuario = $jugadorEvento->getUsuarioJugadorEvento()->getNumeroIdentificacion();
                if ($equipo != null) {
                    $equipoId = $equipo->getId();
                    array_push($equipos, $equipoId);
                }
                array_push($usuarios, $usuario);
                array_push($noDocumentos, $documentoUsuario);
            }
        }
        $choicesDocumentos = $this->modelManager->createQuery('Application\Sonata\UserBundle\Entity\User', 'u')
                        ->join('u.jugadorEventos', 'j')
                        ->where('u.id IN (:idUsuario)')
                        ->andWhere('j.evento = :idEvento')
                        ->setParameters(array('idUsuario' => $usuarios, 'idEvento' => $idEvento))
                        ->getQuery()->getResult();

        $noDocumentosChoices = array();
        foreach ($choicesDocumentos as $choiceDocumento) {
            $documento = $choiceDocumento->getNumeroIdentificacion();
            $noDocumentosChoices[$documento] = $documento;
        }

        $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);

        if ($evento != null || $evento != '') {
            $cupo = $evento->getCupo();

            if ($cupo == "Individual") {
                $datagridMapper
                        ->add('estado', 'doctrine_orm_choice', array(
                            'label' => 'Estado'), 'choice', array(
                            'choices' => array(
                                'Exitoso' => 'Exitoso', // The key (value1) will contain the actual value that you want to filter on
                                'Recambio' => 'Recambio', // The 'Name Two' is the "display" name in the filter
                                'Pendiente' => 'Pendiente',
                            ),
                            'expanded' => true,
                            'multiple' => true))
                        ->add('usuarioJugadorEvento', null, array('label' => 'formulario.participante'), 'entity', array(
                            'class' => 'LogicBundle:JugadorEvento',
                            'query_builder' => function(EntityRepository $er) use ($usuarios, $idEvento) {
                                return $er->createQueryBuilder('j')
                                        ->join('j.usuarioJugadorEvento', 'u')
                                        ->where('u.id IN (:idUsuario)')
                                        ->andWhere('j.evento = :idEvento')
                                        ->setParameters(array('idUsuario' => $usuarios,
                                            'idEvento' => $idEvento));
                            }
                ));
            } else {

                $datagridMapper
                        ->add('estado', 'doctrine_orm_choice', array(
                            'label' => 'Estado'), 'choice', array(
                            'choices' => array(
                                'Exitoso' => 'Exitoso', // The key (value1) will contain the actual value that you want to filter on
                                'Recambio' => 'Recambio', // The 'Name Two' is the "display" name in the filter
                                'Pendiente' => 'Pendiente',
                            ),
                            'expanded' => true,
                            'multiple' => true))
                        ->add('usuarioJugadorEvento', null, array('label' => 'formulario.participante'), 'entity', array(
                            'class' => 'LogicBundle:JugadorEvento',
                            'query_builder' => function(EntityRepository $er) use ($usuarios, $idEvento) {
                                return $er->createQueryBuilder('j')
                                        ->join('j.usuarioJugadorEvento', 'u')
                                        ->where('u.id IN (:idUsuario)')
                                        ->andWhere('j.evento = :idEvento')
                                        ->setParameters(array('idUsuario' => $usuarios,
                                            'idEvento' => $idEvento));
                            }
                        ))
                        ->add('equipoEvento', null, array('label' => 'EquipoEvento'), 'entity', array(
                            'class' => 'LogicBundle:EquipoEvento',
                            'query_builder' => function(EntityRepository $er) use ($equipos, $idEvento) {
                                return $er->createQueryBuilder('e')
                                        ->join('e.jugadorEventos', 'j')
                                        ->where('e.id IN (:idEquipos)')
                                        ->andWhere('j.evento = :idEvento')
                                        ->setParameters(array('idEquipos' => $equipos,
                                            'idEvento' => $idEvento));
                            }
                ));
            }
        }
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $request = $this->getRequest();
        $idEvento = $this->idEvento;

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $evento;
        if ($idEvento == null) {
            $evento = new Evento();
        } else {
            $evento = $em->getRepository("LogicBundle:Evento")->find($idEvento);
        }



        $cupo = $evento->getCupo();

        if ($request->get('documentacion') == 1 && $cupo == "Individual") {
            $listMapper
                    ->add('noDocumento', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.label_no_documento'
                    ])
                    ->add('nombreUsuario', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.label_participante'
                    ])
                    ->add('equipoEvento', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.label_equipo'
            ]);
            $listMapper->add('_action', null, array(
                'actions' => array(
                    'mostrar' => array(
                        'template' => 'AdminBundle:Evento:mostrarCarne_action.html.twig'
                    ),
                    'resultado' => array(
                        'template' => 'AdminBundle:Evento:carne_action.html.twig'
                    ),
                    'carnes' => array(
                        'template' => 'AdminBundle:JugadorEvento\btn:carnes.jugador.html.twig'
                    )
                ),
            ));
        } else {

            $listMapper
                    ->add('estado', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.estado'
                    ])
                    ->add('noDocumento', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.label_no_documento'
                    ])
                    ->add('nombreUsuario', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.label_participante'
                    ])
                    ->add('observacion', null, [
                        'label' => 'formulario_evento.labels.jugador_evento.observacion',
                        'collapse' => true
            ]);
            if ($cupo == "Equipos") {
                $listMapper->add('nombreEquipo', null, [
                    'label' => 'formulario_evento.labels.jugador_evento.label_equipo'
                ]);
            }
            $listMapper->add('_action', null, array(
                'actions' => array(
                    'documentacion' => array(
                        'template' => 'AdminBundle:Evento\Jugadores:list__action_documentacion.html.twig',
                        'id' => $idEvento
                    ),
                    'carnes' => array(
                        'template' => 'AdminBundle:JugadorEvento\btn:carnes.jugador.html.twig'
                    )
                )
            ));
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $request = $this->getRequest();
        $idEvento = $request->get('id');

        $trans = $this->configurationPool->getContainer()->get('translator');
        $noVacio = array(
            new NotBlank(array(
                'message' => $trans->trans('error.no_vacio')
                    ))
        );

        $formMapper
                ->with("formulario.oferta.title_jugador_evento")
                ->add('tipoIdentificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'mapped' => false,
                    'empty_data' => null,
                    'multiple' => false,
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control tipoDocumentoJugadorEvento',
                        'placeholder' => '',
                    )
                ))
                ->add('usuarioJugadorEvento', TextType::class, array(
                    "required" => true,
                    'mapped' => false,
                    "constraints" => $noVacio,
                    'label' => 'formulario_evento.labels.configuracion.seleccione_usuario',
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control numeroIdentificacionJugadorEvento',
                        'placeholder' => '',
                    ),
        ));

        if ($this->cupo == "Equipos") {
            $formMapper->add('equipoEvento', EntityType::class, array(
                'class' => 'LogicBundle:EquipoEvento',
                //"required" => true,
                //"constraints" => $noVacio,                
                //'label' => 'formulario_evento.labels.configuracion.seleccione_usuario',
                'empty_data' => null,
                'attr' => array(
                    'class' => 'form-control inputEquipoEvento',
                    'placeholder' => '',
                ),
                'query_builder' => function(EntityRepository $er) use ($idEvento) {
                    return $er->createQueryBuilder('equipoEvento')
                                    ->where('equipoEvento.evento = :idEvento')
                                    ->setParameter('idEvento', $idEvento);
                }
            ));
        }
        // Esto se elimino segun el requerimiento 3735 - No adjuntar documentos en la parte de eventos y de organismos deportivos
        /* $formMapper
          ->add('documentoImagen', FileType::class, [
          'data_class' => null,
          'label' => 'formulario_evento.labels.jugador_evento.imagen_documento',
          'constraints' => [
          new File([
          'maxSize' => '1024K',
          'mimeTypes' => [
          "image/jpeg", "image/jpg", "image/png"
          ]
          ])
          ],
          'attr' => [
          "class" => "file",
          "data-show-upload" => "false",
          "data-show-caption" => "true",
          "data-msg-placeholder" => "Selecciona una imagén para subir",
          ],
          "required" => true,
          "constraints" => $noVacio,
          ])
          ->add('epsImagen', FileType::class, [
          'data_class' => null,
          'label' => 'formulario_evento.labels.jugador_evento.imagen_eps',
          'constraints' => [
          new File([
          'maxSize' => '1024K',
          'mimeTypes' => [
          "image/jpeg", "image/jpg", "image/png"
          ]
          ])
          ],
          'attr' => [
          "class" => "file",
          "data-show-upload" => "false",
          "data-show-caption" => "true",
          "data-msg-placeholder" => "Selecciona una imagén para subir",
          ],
          "required" => true,
          "constraints" => $noVacio,
          ]) */
        $formMapper
                ->add('observacion', null, array(
                    "required" => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('documentoImagen')
                ->add('epsImagen')
                ->add('observacion')
        ;
    }

    public function getExportFields() {
        return array(
            $this->trans('formulario_evento.labels.jugador_evento.estado') => 'estado',
            $this->trans('formulario_evento.labels.jugador_evento.observacion') => 'observacion',
            $this->trans('formulario_evento.labels.jugador_evento.label_no_documento') => 'noDocumento',
            $this->trans('formulario_evento.labels.jugador_evento.label_participante') => 'nombreUsuario',
        );
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:Evento/Jugadores:base_list.html.twig';
            case 'listCarnes':
                return 'AdminBundle:Evento/Jugadores:base_list_carnes.html.twig';
            case 'edit':
                return 'AdminBundle:Evento/Jugadores:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function setCupo($cupo) {
        $this->cupo = $cupo;
    }

    public function setEm($em) {
        $this->em = $em;
    }

    public function setIdEvento($idEvento) {
        $this->idEvento = $idEvento;
    }

    public function configureBatchActions($actions) {
        $idEvento = $this->idEvento;

        $request = $this->getRequest();
        $documentacion = $request->get('documentacion');

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

            $actions['Recambio'] = array('ask_confirmation' => false);

            $actions['Exitoso'] = array('ask_confirmation' => false);

            $actions['Pendiente'] = array('ask_confirmation' => false);
        }
        if ($this->hasRoute('list') && $this->hasAccess('list')) {
            if ($documentacion == 1 || $request->get('_route') == 'admin_logic_jugadorevento_batch') {
                $actions['carnes'] = array(
                    'ask_confirmation' => false,
                    'label' => 'btn.crear.carnes',
                    'id' => $idEvento
                );
            }
        }

        return $actions;
    }

}
