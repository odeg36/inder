<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\AddAreaFieldSubscriber;
use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddDisciplinaEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\AddEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\AddInstitucionalEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use AdminBundle\Form\EventListener\AddProyectoFieldSubscriber;
use AdminBundle\Form\EventListener\AddTendenciaEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\Oferta\AddDivisionFieldSubscriber;
use AdminBundle\Form\EventListener\Oferta\AddEscenarioFieldSubscriber;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Form\ComunaType;
use LogicBundle\Form\DireccionType;
use LogicBundle\Form\GoogleMapType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use function mb_substr;
use LogicBundle\Utils\Validaciones;
use Symfony\Component\Validator\Constraints\Time;
use LogicBundle\Entity\OfertaDivision;

class OfertaAdmin extends AbstractAdmin {

    protected $em;
    protected $container;
    private $imagen;

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
        $collection->add('fechaReporteOferta', 'fechaReporteOferta');
        $collection->add('generarReporteOfertaExcel', 'generarReporteOfertaExcel');
    }

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
        $this->container = $configurationPool->getContainer();
    }

    public function createQuery($context = 'list') {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $tokenStorage = $this->getConfigurationPool()->getContainer()->get('security.token_storage');
        $user = $tokenStorage->getToken()->getUser();

        $repository = $this->modelManager->getEntityManager($this->getClass())->getRepository($this->getClass());

        $query = parent::createQuery($context);
        if ($context === 'list') {
            if (!$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
                if ($securityContext->isGranted('ROLE_FORMADOR')) {
                    $query->andWhere("o.formador = :formador")
                            ->andWhere("o.activo = :activo")
                            ->setParameter('formador', $user->getId())
                            ->setParameter('activo', true);
                } else if ($securityContext->isGranted('ROLE_GESTOR_TERRITORIAL')) {
                    $query->andWhere("o.gestor = :gestor")
                            ->andWhere("o.activo = :activo")
                            ->setParameter('gestor', $user->getId())
                            ->setParameter('activo', true);
                } else {
                    $query->andWhere("o.activo = :activo")
                            ->setParameter('activo', true);
                }
            }
        }
        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($user->hasRole('ROLE_SUPER_ADMIN') || $user->hasRole('ROLE_FORMADOR') || $user->hasRole('ROLE_GESTOR_TERRITORIAL')) {
            $datagridMapper
                    ->add('nombre', null, [
                        'show_filter' => true,
                    ])
                    ->add('formador.numeroIdentificacion', null, [
                        'show_filter' => true,
                        'label' => 'formulario.oferta.formador.numeroIdentificacion'
            ]);
        }
        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            $datagridMapper
                    ->add('activo', null, [
                        'show_filter' => true,
            ]);
        }
        $datagridMapper
                ->add('disciplinaEstrategia.disciplina', null, [
                    'label' => 'formulario.oferta.disciplina',
                    'show_filter' => true,
                ])
                ->add('tendenciaEstrategia.tendencia', null, [
                    'label' => 'formulario.oferta.tendencia',
                    'show_filter' => true,
                ])
                ->add('estrategia', null, [
                    'show_filter' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                                ->where('e.activo = :estado')
                                ->setParameter('estado', true);
                    }
                ])
                ->add('estrategia.segmentacion', null, [
                    'label' => 'formulario.estrategia.segmentacion',
                    'show_filter' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                                ->where('s.activo = :estado')
                                ->setParameter('estado', true);
                    }
        ]);
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $editable = false;
        $mostrar = false;
        if ($user->hasRole('ROLE_SUPER_ADMIN') || $user->hasRole('ROLE_FORMADOR') || $user->hasRole('ROLE_GESTOR_TERRITORIAL')) {
            $editable = true;
            $mostrar = true;
        }
        if ($mostrar) {
            $listMapper
                    ->add('nombre', null, [
                        'label' => 'formulario.oferta.nombre',
                        'template' => 'AdminBundle:Oferta:nombre.html.twig'
            ]);
        }
        $listMapper
                ->add('estrategia', null, [
                    'label' => 'formulario.oferta.estrategia',
                ])
                ->add('barrio', null, [
                    'template' => 'AdminBundle:Oferta:list_item_barrio.html.twig'
                ])
                ->add('puntoAtencion', null, [
                    'label' => 'formulario.oferta.puntoAtencion.title',
                    'template' => 'AdminBundle:Oferta:list_item_puntoAtencion.html.twig'
                ])
                ->add('estrategia.segmentacion', null, [
                    'label' => 'formulario.estrategia.segmentacion',
                ])
                ->add('formador.fullname', null, [
                    'label' => 'formulario.formador',
                ])
                ->add('fechas', null, [
                    'template' => 'AdminBundle:Oferta:list_fechas.html.twig',
                ])
        ;

        if ($mostrar) {
            $listMapper
                    ->add('activo', null, [
                        'editable' => $editable
            ]);
        }
        $listMapper->add('_action', null, array(
            'actions' => array(
                'preinscritos' => array(
                    'template' => 'AdminBundle:Preinscripcion:list__action_preinscritos.html.twig'
                ),
//                'show' => array(),
                'edit' => array(),
//                        'delete' => array(),
            ),
        ))
        ;
    }

    public function getExportFields() {
        return [
            $this->trans('formulario.oferta.nombre') => 'nombre',
            $this->trans('formulario.oferta.estrategia') => 'estrategia',
            "Barrio punto de atención" => 'puntoAtencion.barrio',
            "Barrio escenario deportivo" => 'escenarioDeportivo.barrio',
            $this->trans('formulario.oferta.puntoAtencion.title') => 'puntoAtencion',
            "Escenario deportivo" => 'escenarioDeportivo',
            "Fuente de financiación" => 'fuenteFinanciacion',
            $this->trans('formulario.estrategia.segmentacion') => 'estrategia.segmentacion',
            $this->trans('formulario.formador') => 'formador.fullname',
            $this->trans('formulario.gestor') => 'gestor.fullname',
            $this->trans('oferta.fecha_inicial') => 'fecha_inicial',
            $this->trans('oferta.fecha_final') => 'fecha_final',
        ];
    }

    public function getDataSourceIterator() {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d/m/Y'); //change this to suit your needs
        return $iterator;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $request = $this->getRequest();
        $object = $this->getSubject();

        $usuario_autenticado = $this->container->get('security.token_storage')->getToken()->getUser();
        $campo_gestor_deshabilitado = false;
        if ($usuario_autenticado->hasRole('ROLE_GESTOR_TERRITORIAL')) {
            if ($object && !$object->getId()) {
                $object->setGestor($usuario_autenticado);
            }
            if (!$usuario_autenticado->hasRole('ROLE_SUPER_ADMIN') && $usuario_autenticado->hasRole('ROLE_GESTOR_TERRITORIAL')) {
                $campo_gestor_deshabilitado = true;
            }
        }
        $estatocheckDisciplinaTendencia = "0";
        $estatocheckEscenarioPuntoAtencion = "false";
        $dataDireccionOComuna = null;
        $imagen = "";
        $fileFieldOptions = [
            'required' => false,
            'data_class' => null,
            'label' => 'formulario.oferta.imagen',
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ]])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
                "data-msg-placeholder" => "Selecciona una foto para subir"
            ]
        ];
        if ($object) {
            $this->imagen = $imagen = $object->getImagen();
            if ($imagen) {
                // get the container so the full path to the image can be set
                $container = $this->getConfigurationPool()->getContainer();
                $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagen;

                // add a 'help' option containing the preview's img tag
                $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
            }
            if ($object->getDisciplinaEstrategia()) {
                $estatocheckDisciplinaTendencia = "1";
            } else if ($object->getTendenciaEstrategia()) {
                $estatocheckDisciplinaTendencia = "2";
            } else if ($object->getInstitucionalEstrategia()) {
                $estatocheckDisciplinaTendencia = "3";
            } else {
                $estatocheckDisciplinaTendencia = "0";
            }

            if ($object->getEscenarioDeportivo()) {
                $estatocheckEscenarioPuntoAtencion = "true";
            } else if ($object->getPuntoAtencion()) {
                $estatocheckEscenarioPuntoAtencion = "false";
            } else {
                $estatocheckEscenarioPuntoAtencion = null;
            }
            if ($object->getPuntoAtencion() && $object->getPuntoAtencion()->getBarrio()) {
                $qb = $this->em->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
                $qb
                        ->join('m.barrios', 'b')
                        ->andWhere('b.esVereda = :es_vereda')
                        ->andWhere('m.id = :municipio')
                        ->setParameter('es_vereda', true)
                        ->setParameter('municipio', $object->getPuntoAtencion()->getBarrio()->getMunicipio()->getId())
                ;
                $query = $qb->getQuery();
                $municipioObject = $query->getOneOrNullResult();
                if ($municipioObject && $object->getPuntoAtencion()->getBarrio()->getEsVereda()) {
                    $dataDireccionOComuna = User::COMUNA;
                } elseif ($municipioObject) {
                    $dataDireccionOComuna = User::DIRECCION;
                }
            }
        }

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $es_requerido = "falso";
        $mappeado = false;
        $latitudCoordenada = 6.244203;
        $longitudCoordenada = -75.58121189999997;
        $formMapper
                ->with("formulario.oferta.title")
                ->add('area', EntityType::class, array(
                    'class' => 'LogicBundle:Area',
                        )
                )
                ->add('proyecto', EntityType::class, array(
                    'class' => 'LogicBundle:Proyecto',
                        )
                )
                ->add('estrategia', EntityType::class, array(
                    'class' => 'LogicBundle:Estrategia',
                        )
                )
                ->add('imagen', FileType::class, $fileFieldOptions)
                ->add('descripcion', CKEditorType::class, array(
                    'required' => false,
                    'config' => array('toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'items' => array('NumberedList'),
                            ),
                        ),),
                    'label' => 'formulario.oferta.descripcion',
                    'attr' => array(
                        'class' => 'form-control',
                        'rows' => 6,
                        'autocomplete' => 'off'
                    )
                ))
                ->add('formulario.oferta.estrategia.opcion_cobertura', ChoiceType::class, [
                    'data' => $estatocheckDisciplinaTendencia,
                    'choices' => array(
                        'formulario.oferta.seleccion.nada' => '0',
                        'formulario.oferta.seleccion.disciplina' => '1',
                        'formulario.oferta.seleccion.tendencia' => '2',
                        'formulario.oferta.seleccion.categoria_institucional' => '3',
                    ),
                    'choice_attr' => function($val, $key, $index) {
                        return ['class' => 'seleccionEstrategiaCobertura col-md-3'];
                    },
                    'label' => 'formulario.oferta.seleccion.disciplinaTendenciaInstitucional',
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false
                ])
                ->add('disciplinaEstrategia', EntityType::class, array(
                    'class' => 'LogicBundle:DisciplinaEstrategia',
                    'required' => false,
                        )
                )
                ->add('tendenciaEstrategia', EntityType::class, array(
                    'class' => 'LogicBundle:TendenciaEstrategia',
                    'attr' => array('class' => 'tendenciaEstrategia')
                        )
                )
                ->add('institucionalEstrategia', EntityType::class, array(
                    'class' => 'LogicBundle:InstitucionalEstrategia',
                    'attr' => array('class' => 'institucionalEstrategia')
                        )
                )
                ->add("fuenteFinanciacion", null, array(
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                                ->orderBy('f.nombre');
                    },
                ))
                ->add('formulario.oferta.seleccion.puntoAtencion', ChoiceType::class, [
                    'data' => $estatocheckEscenarioPuntoAtencion,
                    'choices' => array(
                        'formulario.oferta.escenario_seleccion.seleccion' => 'true',
                        'formulario.oferta.otro.seleccion' => 'false',
                    ),
                    'choice_attr' => function($val, $key, $index) {
                        return ['class' => 'seleccion-lugar-oferta'];
                    },
                    'label' => 'formulario.oferta.puntoAtencion.title',
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false
                ])
                ->end()
                ->with('formulario.oferta.escenario', array('class' => 'col-md-12 seleccion-escenario ocultar'))
                ->add('escenarioDeportivo', EntityType::class, array(
                    "class" => "LogicBundle:EscenarioDeportivo",
                    'required' => false,
                ))->add('division', EntityType::class, array(
                    "class" => "LogicBundle:Division",
                    'required' => false,
                ))
                ->end()
                ->with('formulario.oferta.puntoAtencion.title', array('class' => 'col-md-12 seleccion-puntoAtencion ocultar'))
                ->add('municipio', EntityType::class, array(
                    'class' => 'LogicBundle:Municipio',
                    'label' => 'formulario_registro.contacto.municipio',
                    'mapped' => false
                ))
                ->add("barrio", EntityType::class, array(
                    'required' => false,
                    'class' => 'LogicBundle:Barrio',
                    'mapped' => false,
                    'label' => 'formulario.oferta.puntoAtencion.barrio',
                    'attr' => [
                        'class' => 'seleccion_barrio'
                    ]
                ))
                ->add('direccionOcomuna', ChoiceType::class, [
                    'mapped' => false,
                    'required' => false,
                    'choices' => [
                        'formulario.vereda' => User::COMUNA,
                        'formulario.barrio' => User::DIRECCION
                    ],
                    'data' => $dataDireccionOComuna,
                    'choice_attr' => function($val, $key, $index) {
                        return [
                            'class' => 'choice-direcion-type', 'choice-key' => $index
                        ];
                    },
                    'multiple' => false,
                    'expanded' => true,
                    'placeholder' => false,
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
                ])
                ->add('direccion_creado', DireccionType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'object' => ['data' => $this->getSubject()],
                    'formularioPadre' => '.autocompletePuntoAtencion>div>.select2-input',
                    'attr' => array(
                        'clases' => 'campoDireccionOferta direccionCreada mostrarDirCreada',
                    ),
                    'label' => "formulario.oferta.direccion.creada",
                ))
                ->add('direccion_comuna_creado', ComunaType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'object' => ['data' => $this->getSubject()],
                    'formularioPadre' => '.autocompletePuntoAtencion>div>.select2-input',
                    'attr' => array(
                        'clases' => 'campoDireccionOferta direccionCreada mostrarDirCreada',
                    ),
                    'label' => "formulario.oferta.direccion.creada",
                ))
                ->add('puntoAtencion', EntityType::class, array(
                    'label' => 'formulario.oferta.select_puntoAtencion',
                    'class' => 'LogicBundle:PuntoAtencion',
                    'label_attr' => array('class' => 'label_puntoAtencion'),
                    'placeholder' => '',
                    'attr' => array(
                        'class' => 'mostrarDirCreada seleccion-puntoAtencion',
                    ),
                    'query_builder' => function(EntityRepository $er) use ($object) {
                        if ($object && !$object->getPuntoAtencion()) {
                            return $er->createQueryBuilder('pa')
                                    ->where('pa.barrio = -1');
                        } elseif ($object && $object->getPuntoAtencion()) {
                            return $er->createQueryBuilder('pa')
                                    ->where('pa.barrio = :barrio')
                                    ->setParameter('barrio', $object->getPuntoAtencion()->getBarrio()->getId());
                        }
                    },
                    'required' => false,
                ))
                ->add('formulario.oferta.puntoatencion.check', CheckboxType::class, [
                    "mapped" => false,
                    "required" => false,
                    "label" => "formulario.oferta.puntoAtencion.agregar",
                    'attr' => array(
                        'class' => 'checkNuevoPuntoAtencion',
                    )
                ])
                ->add('direccion_format', DireccionType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'object' => ['data' => $this->getSubject()],
                    'formularioPadre' => '.nuevaDireccion',
                    'attr' => array(
                        'class' => 'campoDireccionOferta',
                        'clases' => 'mostrarDirNueva'
                    ),
                    'label' => false,
                ))
                ->add('direccion', TextType::class, [
                    'required' => false,
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion',
                    'mapped' => false,
                    'attr' => [
                        'class' => ' form-control col-md-12  nuevaDireccion mostrarDirNueva',
                        'readonly' => 'true'
                    ]
                ])
                ->add('comuna_format', ComunaType::class, [
                    'label_attr' => [
                        'class' => 'required'
                    ],
                    'formularioPadre' => '.direccionComuna',
                    'mapped' => false,
                    'object' => ['data' => $this->getSubject()],
                    "required" => false,
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                ])
                ->add('direccionComuna', TextType::class, [
                    'required' => false,
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion',
                    'mapped' => false,
                    'attr' => [
                        'class' => ' form-control col-md-12 direccionComuna mostrarDirNueva',
                        'readonly' => 'true'
                    ]
                ])
                ->add('localizacion', GoogleMapType::class, array(
                    'mapped' => false,
                    'type' => 'text', // the types to render the lat and lng fields as
                    'options' => array(), // the options for both the fields
                    'lat_options' => array(), // the options for just the lat field
                    'lng_options' => array(), // the options for just the lng field
                    'lat_name' => 'latitud', // the name of the lat field
                    'lng_name' => 'longitud', // the name of the lng field
                    'map_width' => '100%', // the width of the map
                    'map_height' => '300px', // the height of the map
                    'default_lat' => $latitudCoordenada, // the starting position on the map
                    'default_lng' => $longitudCoordenada, // the starting position on the map
                    'include_jquery' => false, // jquery needs to be included above the field (ie not at the bottom of the page)
                    'include_gmaps_js' => true, // is this the best place to include the google maps javascript?
                    'attr' => array(
                        'class' => 'mostrarDirNueva',
                    )
                ))
                ->end()
                ->add('tipo_documento_formador', EntityType::class, [
                    'class' => 'LogicBundle:tipoIdentificacion',
                    'mapped' => false,
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'col-md-2 tipoDocumentoFormador',
                        'data-tipousuario' => 'formador',
                        'onclick' => 'inder.oferta.asignarTipoUsuario(this)'
                    ]
                ])
                ->add('formador', 'sonata_type_model_autocomplete', [
                    'property' => 'title',
                    'minimum_input_length' => 6,
                    'to_string_callback' => function($entity, $property) {
                        return $entity->getUsername() . " - " . $entity->getFirstname() . ' ' . $entity->getLastname();
                    },
                    'callback' => function ($admin, $property, $value) {
                        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
                        $rol = "ROLE_FORMADOR";
                        $request = $this->getRequest();
                        $session = $request->getSession();
                        $tipoIdentificacion = $session->get('id_tipo_documento_formador') ?: 0;
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();
                        $query = $em->getRepository('LogicBundle:Oferta')->buscarUsuariosRol($query, $tipoIdentificacion, $rol, $value);
                        $datagrid->setValue($property, null, $value);
                    },
                    'attr' => [
                        'class' => 'form-control autocomplete-formador'
                    ]
                ])
                ->add('tipo_documento_gestor', EntityType::class, [
                    'disabled' => $campo_gestor_deshabilitado,
                    'class' => 'LogicBundle:tipoIdentificacion',
                    'mapped' => false,
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'col-md-2 tipoDocumentoGestor',
                        'data-tipousuario' => 'gestor',
                        'onclick' => 'inder.oferta.asignarTipoUsuario(this)'
                    ]
                ])
                ->add('gestor', 'sonata_type_model_autocomplete', [
                    'disabled' => $campo_gestor_deshabilitado,
                    'property' => 'title',
                    'minimum_input_length' => 6,
                    'to_string_callback' => function($entity, $property) {
                        return $entity->getUsername() . " - " . $entity->getFirstname() . ' ' . $entity->getLastname();
                    },
                    'callback' => function ($admin, $property, $value) {
                        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
                        $rol = "ROLE_GESTOR_TERRITORIAL";
                        $request = $this->getRequest();
                        $session = $request->getSession();
                        $tipoIdentificacion = $session->get('id_tipo_documento_gestor') ?: 0;
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();
                        $query = $em->getRepository('LogicBundle:Oferta')->buscarUsuariosRol($query, $tipoIdentificacion, $rol, $value);
                        $datagrid->setValue($property, null, $value);
                    },
                ])
                ->add('fecha_inicial', DateMaskType::class, array(
                    "label" => "oferta.fecha_inicial",
                    "required" => true,
                    'mask-alias' => 'dd/mm/yyyy',
                    'placeholder' => 'dd/mm/yyyy',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                    "constraints" => $noVacio
                ))
                ->add('fecha_final', DateMaskType::class, array(
                    "label" => "oferta.fecha_final",
                    "required" => true,
                    'mask-alias' => 'dd/mm/yyyy',
                    'placeholder' => 'dd/mm/yyyy',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                    "constraints" => $noVacio
                ))
                ->add('fecha_inicio_preinscripcion', DateMaskType::class, array(
                    "label" => "oferta.fecha_inicio_preinscripcion",
                    "required" => false,
                    'mask-alias' => 'dd/mm/yyyy',
                    'placeholder' => 'dd/mm/yyyy',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add('fecha_final_preinscripcion', DateMaskType::class, array(
                    "label" => "oferta.fecha_final_preinscripcion",
                    "required" => false,
                    'mask-alias' => 'dd/mm/yyyy',
                    'placeholder' => 'dd/mm/yyyy',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                ))
                ->add("programacion", CollectionType::class, array(
                    'label' => 'formulario.programacion',
                    'type_options' => array('delete' => false),
                    'by_reference' => false,
                    'btn_add' => false,
                        ), array(
                    'edit' => 'inline',
                    'delete' => false,
                    'inline' => 'table',
                    'sortable' => 'id',
                ))
                ->getFormBuilder()
                ->addEventSubscriber(new AddEscenarioFieldSubscriber())
                ->addEventSubscriber(new AddDivisionFieldSubscriber($this->em))
                ->addEventSubscriber(new AddBarrioFieldSubscriber($es_requerido, $mappeado, "", $this->getRequest()))
                ->addEventSubscriber(new AddMunicipioFieldSubscriber($es_requerido))
                ->addEventSubscriber(new AddBarrioFieldSubscriber($es_requerido, $mappeado, "", $this->getRequest()))
                ->addEventSubscriber(new AddAreaFieldSubscriber())
                ->addEventSubscriber(new AddProyectoFieldSubscriber())
                ->addEventSubscriber(new AddEstrategiaFieldSubscriber(true))
                ->addEventSubscriber(new AddDisciplinaEstrategiaFieldSubscriber())
                ->addEventSubscriber(new AddTendenciaEstrategiaFieldSubscriber())
                ->addEventSubscriber(new AddInstitucionalEstrategiaFieldSubscriber())
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($object) {
                    $trans = $this->configurationPool->getContainer()->get('translator');
                    $form = $event->getForm();
                    $FormPuntoAtencion = $form->get('formulario__oferta__seleccion__puntoAtencion')->getData();
                    $FormPuntoAtencionCheck = $form->get('formulario__oferta__puntoatencion__check')->getData();
                    if ($form->get('formulario__oferta__estrategia__opcion_cobertura')->getData() == "true" && !$form->get('disciplinaEstrategia')->getData()) {
                        $form->get('disciplinaEstrategia')->addError(new FormError($trans->trans('error.no_vacio')));
                    } else if ($form->get('formulario__oferta__estrategia__opcion_cobertura')->getData() == "false" && !$form->get('tendenciaEstrategia')->getData()) {
                        $form->get('tendenciaEstrategia')->addError(new FormError($trans->trans('error.no_vacio')));
                    }

                    if ($FormPuntoAtencion == "true" && !$form->get('escenarioDeportivo')->getData()) {
                        $form->get('escenarioDeportivo')->addError(new FormError($trans->trans('error.no_vacio')));
                    } else if ($FormPuntoAtencion == "false" && !$form->get('puntoAtencion')->getData() && !$FormPuntoAtencionCheck) {
                        $form->get('puntoAtencion')->addError(new FormError($trans->trans('error.no_vacio')));
                    }
                    if ($FormPuntoAtencion == "false" && $FormPuntoAtencionCheck && !$this->getForm()->get('localizacion')->get('latitud')->getData()) {
                        $form->get('localizacion')->addError(new FormError($trans->trans('error.no_vacio')));
                    }

                    if ($FormPuntoAtencion == "false" && $FormPuntoAtencionCheck && !$this->getForm()->get('localizacion')->get('longitud')->getData()) {
                        $form->get('localizacion')->addError(new FormError($trans->trans('error.no_vacio')));
                    }
                })
        ;
        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            if (null != $event->getData()) {
                $form = $event->getForm();
                $form->add('puntoAtencion', EntityType::class, array(
                    'label' => 'formulario.oferta.select_puntoAtencion',
                    'class' => 'LogicBundle:PuntoAtencion',
                    'label_attr' => array('class' => 'label_puntoAtencion'),
                    'placeholder' => '',
                    'attr' => array(
                        'class' => 'mostrarDirCreada seleccion-puntoAtencion',
                    ),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('pa')
                        ;
                    },
                    'required' => false,
                ));
            }
        });

        if ($object) {
            if (!$object->getId() && $usuario_autenticado->hasRole('ROLE_GESTOR_TERRITORIAL')) {
                $formMapper->get('tipo_documento_gestor')->setData($usuario_autenticado->getTipoIdentificacion());
            }
            if ($object->getGestor()) {
                $formMapper->get('tipo_documento_gestor')->setData($object->getGestor()->getTipoIdentificacion());
            }
            if ($object->getFormador()) {
                $formMapper->get('tipo_documento_formador')->setData($object->getFormador()->getTipoIdentificacion());
            }
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $object = $this->getSubject();
        $barrio = 'barrio';
        $lugar = 'puntoAtencion';
        if ($object->getPuntoAtencion()) {
            $barrio = 'puntoAtencion.barrio';
            $lugar = 'puntoAtencion';
        } elseif ($object->getEscenarioDeportivo()) {
            $barrio = 'escenarioDeportivo.barrio';
            $lugar = 'escenarioDeportivo';
        }
        $showMapper
                ->add('nombre', null, [
                    'label' => 'formulario.oferta.nombre',
                ])
                ->add('disciplinaEstrategia', null, [
                    'label' => 'formulario.oferta.disciplina',
                ])
                ->add('tendenciaEstrategia', null, [
                    'label' => 'formulario.oferta.tendencia',
                ])
                ->add('estrategia', null, [
                ])
                ->add($barrio, null, [
                ])
                ->add($lugar, null, [
                    'label' => 'formulario.oferta.puntoAtencion.title',
                ])
                ->add('estrategia.segmentacion', null, [
                    'label' => 'formulario.estrategia.segmentacion',
                ])
                ->add('formador.fullname', null, [
                    'label' => 'formulario.formador',
                ])
                ->add('fecha_inicial', null, [
                    "label" => "oferta.fecha_inicial",
                ])
                ->add('fecha_final', null, [
                    "label" => "oferta.fecha_final",
                ])
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:Oferta:base_edit.html.twig';
            case 'list':
                return 'AdminBundle:Oferta:base_list.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function validate(ErrorElement $errorElement, $object) {
        $opcion_cobertura = $this->getForm()->get('formulario__oferta__estrategia__opcion_cobertura')->getData();
        $opcion_puntoAtencion = $this->getForm()->get('formulario__oferta__seleccion__puntoAtencion')->getData();
        $check_nuevo_puntoAtencion = $this->getForm()->get('formulario__oferta__puntoatencion__check')->getData();
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        if ($opcion_cobertura != null) {
            if ($opcion_cobertura == "true" && !$object->getDisciplinaEstrategia()) {
                $errorElement
                        ->addViolation($this->trans('error.disciplina.no_seleccionado'))
                        ->end();
            } else if ($opcion_cobertura == "false" && !$object->getTendenciaEstrategia()) {
                $errorElement
                        ->addViolation($this->trans('error.tendencia.no_seleccionado'))
                        ->end();
            }
        } else {
            $errorElement
                    ->addViolation($this->trans('error.check_cobertura.no_existe'))
                    ->end();
        }

        if ($opcion_puntoAtencion != null) {
            if ($opcion_puntoAtencion == "true" && !$object->getEscenarioDeportivo()) {
                $errorElement
                        ->addViolation($this->trans('error.escenario.no_seleccionado'))
                        ->end();
            } else if ($opcion_puntoAtencion == "false") {
                if (!$this->getForm()->get('municipio')->getData()) {
                    $errorElement
                            ->addViolation($this->trans('error.municipio.no_seleccionado'))
                            ->end();
                }

                if (!$this->getForm()->get('barrio')->getData()) {
                    $errorElement
                            ->addViolation($this->trans('error.barrio.no_seleccionado'))
                            ->end();
                }

                if ($check_nuevo_puntoAtencion == true) {
                    $direccionOComuna = $this->getForm()->get('direccionOcomuna')->getData();
                    if ($direccionOComuna != null) {
                        if ($direccionOComuna == User::DIRECCION && !$this->getForm()->get('direccion')->getData()) {
                            $errorElement
                                    ->addViolation($this->trans('error.puntoAtencion.direccion.vacio'))
                                    ->end();
                        } elseif ($direccionOComuna == User::COMUNA && !$this->getForm()->get('direccionComuna')->getData()) {
                            $errorElement
                                    ->addViolation($this->trans('error.puntoAtencion.direccion.vacio'))
                                    ->end();
                        }
                    } elseif (!$this->getForm()->get('direccion')->getData()) {
                        $errorElement
                                ->addViolation($this->trans('error.puntoAtencion.direccion.vacio'))
                                ->end();
                    }

                    if (!$this->getForm()->get('localizacion')->get('latitud')->getData()) {
                        $errorElement
                                ->addViolation($this->trans('error.puntoAtencion.latitud.vacio'))
                                ->end();
                    }
                    if (!$this->getForm()->get('localizacion')->get('longitud')->getData()) {
                        $errorElement
                                ->addViolation($this->trans('error.puntoAtencion.longitud.vacio'))
                                ->end();
                    }
                } else if (!$object->getPuntoAtencion()) {
                    $errorElement
                            ->addViolation($this->trans('error.puntoAtencion.vacio'))
                            ->end();
                }
            }
        } else {
            $errorElement
                    ->addViolation($this->trans('error.puntoAtencion.no_existe'))
                    ->end();
        }
        if ($object->getFechaInicioPreinscripcion() > $object->getFechaFinalPreinscripcion()) {
            $errorElement
                    ->with('fecha_inicio_preinscripcion')
                    ->addViolation($this->trans('error.fecha_final.mayor_fecha_Inicial'))
                    ->end();
        }
        $request = $this->getRequest();
        if ($object->getEscenarioDeportivo()) {
            $reservas = $this->em->getRepository("LogicBundle:Reserva")->buscarCruceOfertas($object, $this->getForm());
            if ($reservas) {
                $this->getForm()->addError(new FormError($this->trans('error.cruce.reserva')));
            }
            $bloqueos = $this->em->getRepository("LogicBundle:BloqueoEscenario")->buscarDivisionesBloqueadasOferta($object);
            if ($bloqueos) {
                $this->getForm()->addError(new FormError($this->trans('error.division.bloqueo', [
                            "%causa%" => $bloqueos[0]->getDescripcion(),
                            '%inicio%' => $bloqueos[0]->getFechaInicial()->format("d-m-Y"),
                            '%fin%' => $bloqueos[0]->getFechaFinal()->format("d-m-Y"),
                            '%hinicio%' => $bloqueos[0]->getHoraInicial()->format("H:i"),
                            '%hfin%' => $bloqueos[0]->getHoraFinal()->format("H:i"),
                ])));
            }
        }
        if ($object->getEscenarioDeportivo() && count($this->getForm()->get("division")->getData()) == 0) {
            if ($opcion_puntoAtencion == 'true') {
                $errorElement
                        ->with('division')
                        ->addViolation($this->trans('formulario_registro.no_vacio'))
                        ->end();
            }
        }
        if ($object->getFechaInicial() > $object->getFechaFinal()) {
            $errorElement
                    ->with('fecha_inicial')
                    ->addViolation($this->trans('error.fecha_final.mayor_fecha_Inicial'))
                    ->end();
        } else if ($object->getFechaInicial() && $object->getFechaFinal()) {
            $idObject = $object->getId() ? $object->getId() : 0;
            $validaciones = new Validaciones();
            $arregloDias = $validaciones->busquedaDisponibilidad($object);
            $errores = false;
            foreach ($arregloDias as $fecha => $datos) {
                $cruce = $em->getRepository('LogicBundle:Oferta')->OfertaPorFormador($object, $datos);
                if ($cruce && $cruce[1] == "cruce_formador") {
                    $errorElement
                            ->addViolation($this->trans('error.oferta.formador.cruce', [
                                        '%formador%' => $object->getFormador()->getFirstName() . " " . $object->getFormador()->getLastName(),
                                        '%oferta%' => $cruce[0]->getNombre(),
                                        '%dia%' => $datos['dia']->getNombre(),
                                        '%horario%' => $datos['horaInicio'] . ' a ' . $datos['horaFin']
                            ]))
                            ->end();
                    break;
                } elseif ($cruce && $cruce[1] == "error") {
                    $errorElement
                            ->addViolation($this->trans('error.general'))
                            ->end();
                    break;
                }
                if ($object->getEscenarioDeportivo()) {
                    if ($object->getDivisiones()) {
                        foreach ($object->getDivisiones() as $ofertaDivision) {
                            $cruce = $em->getRepository('LogicBundle:Oferta')->OfertaPorFormadorDivision($object, $datos, $ofertaDivision);
                            if ($cruce && $cruce[1] == "cruce") {
                                if ($object->getDivisiones() && !$errores) {
                                    $errorElement
                                            ->addViolation($this->trans('error.oferta.formador.division', [
                                                        '%formador%' => $object->getFormador()->getFirstName() . " " . $object->getFormador()->getLastName(),
                                                        '%oferta%' => $cruce[0]->getNombre(),
                                                        '%dia%' => $datos['dia']->getNombre(),
                                                        '%horario%' => $datos['horaInicio'] . ' a ' . $datos['horaFin'],
                                                        '%division%' => $ofertaDivision->getDivision()->getNombre()
                                            ]))
                                            ->end();
                                } else {
                                    if (!$errores) {
                                        $errorElement
                                                ->addViolation($this->trans('error.oferta.formador.escenario', [
                                                            '%formador%' => $object->getFormador()->getFirstName() . " " . $object->getFormador()->getLastName(),
                                                            '%oferta%' => $cruce[0]->getNombre(),
                                                            '%dia%' => $datos['dia']->getNombre(),
                                                            '%horario%' => $datos['horaInicio'] . ' a ' . $datos['horaFin'],
                                                            '%escenario%' => $object->getEscenarioDeportivo()->getNombre()
                                                ]))
                                                ->end();
                                    }
                                }
                                $errores = true;
                            } elseif ($cruce && $cruce[1] == "error" && !$errores) {
                                $errorElement
                                        ->addViolation($this->trans('error.general'))
                                        ->end();
                                $errores = true;
                            }
                        }
                    }
                }
            }
            $ofertasCreadas = $em->getRepository('LogicBundle:Oferta')->ofertaCreadaOcupada($idObject, $object);
        }

        $cantidadHorasProgramadas = 0;
        try {
            foreach ($object->getProgramacion() as $programa) {
                if ($programa->getHoraInicial()) {
                    $date = new \DateTime($programa->getHoraInicial());
                    $date->format('Y-m-d  H:i:s');
                    $programa->setHoraInicial($date);
                    $cantidadHorasProgramadas ++;
                    $date = new \DateTime($programa->getHoraFinal());
                    $date->format('Y-m-d  H:i:s');
                    $programa->setHoraFinal($date);
                }
                if ($programa->getHoraInicial() > $programa->getHoraFinal()) {

                    $mensaje = $this->trans(
                            'error.programacion.fecha_final.hora_inicial_mayor', array('%dia%' => $programa->getDia()->getNombre())
                    );
                    $errorElement
                            ->addViolation($mensaje)
                            ->end();
                }
            }
        } catch (\Exception $e) {
            $errorElement
                    ->addViolation($this->trans('error.general'))
                    ->end();
        }
        if ($cantidadHorasProgramadas == 0) {
            $errorElement
                    ->addViolation($this->trans('error.programacion.vacio'))
                    ->end();
        }
    }

    public function prePersist($object) {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $this->preUpdate($object);
        $nombreOferta = $this->crearNombreOferta($object, false);
        $object->setNombre($nombreOferta, true);
        $em->persist($object);
    }

    public function preUpdate($object) {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $direccion = $this->getForm()->get('direccion')->getData();
        if (!$direccion) {
            $direccion = $this->getForm()->get('direccionComuna')->getData();
        }
        if ($direccion !== null) {
            $puntoAtencion = new PuntoAtencion();
            $puntoAtencion->setDireccion($direccion);
            $puntoAtencion->setBarrio($this->getForm()->get('barrio')->getData());
            $puntoAtencion->setLatitud($this->getForm()->get('localizacion')->get('latitud')->getData());
            $puntoAtencion->setLongitud($this->getForm()->get('localizacion')->get('longitud')->getData());
            $object->setPuntoAtencion($puntoAtencion);
            $em->persist($puntoAtencion);
        }

        $form = $this->getForm();
        $diviones = $form->get("division")->getData();
        if ($diviones) {
            foreach ($diviones as $division) {
                $ofertaDivision = $em->getRepository('LogicBundle:OfertaDivision')->findOneBy(["division" => $division, "oferta" => $object]);
                if (!$ofertaDivision) {
                    $ofertaDivision = new OfertaDivision($division, $object);
                    $object->addDivisione($ofertaDivision);
                }
            }
        }
        if ($object->getImagen() == "carousel1.jpg" || !$object->getImagen()) {
            $object->setImagen($this->imagen);
        }
        $nombreOferta = $this->crearNombreOferta($object, true);
        $object->setNombre($nombreOferta, true);
        $em->persist($object);
    }

    public function crearNombreOferta(Oferta $oferta, $edicion = false) {
        $nombreOferta = "";

        // ACRONIMO DEL NOMBRE DE LA ESTRATEGIA
        $acronimoEstrategia = $result = mb_substr($oferta->getEstrategia()->getNombre(), 0, 3);
        $nombreOferta .= $acronimoEstrategia;

        // SEGMENTACION
        $segmentacion = $oferta->getEstrategia()->getSegmentacion();
        $nombreOferta .= " - " . ($segmentacion ? $segmentacion->getId() : '');

        // COMUNA
        $comuna = "";
        if ($oferta->getPuntoAtencion() && $oferta->getPuntoAtencion()->getBarrio() && $oferta->getPuntoAtencion()->getBarrio()->getComuna()) {
            $comuna = $oferta->getPuntoAtencion()->getBarrio()->getComuna()->getId();
        } elseif ($oferta->getEscenarioDeportivo() && $oferta->getEscenarioDeportivo()->getBarrio() && $oferta->getEscenarioDeportivo()->getBarrio()->getComuna()) {
            $comuna = $oferta->getEscenarioDeportivo()->getBarrio()->getComuna()->getId();
        }
        $nombreOferta .= " - " . $comuna;

        // Disciplina, tendencia o categoria institucional
        $enfoque = "";
        if ($oferta->getTendenciaEstrategia()) {
            $enfoque = $oferta->getTendenciaEstrategia()->getTendencia()->getNombre();
        }
        if ($oferta->getDisciplinaEstrategia()) {
            $enfoque = $oferta->getDisciplinaEstrategia()->getDisciplina()->getNombre();
        }
        if ($oferta->getInstitucionalEstrategia()) {
            $enfoque = $oferta->getInstitucionalEstrategia()->getCategoriaInstitucional()->getNombre();
        }
        $words = explode(" ", trim($enfoque));
        $acronimoOferta = "";
        foreach ($words as $w) {
            for ($index = 0; $index < strlen($w); $index++) {
                if ($index < 3) {
                    $acronimoOferta .= $w[$index];
                }
            }
        }
        $nombreOferta .= " - " . $acronimoOferta;
        // CONSECUTIVO
        if ($edicion) {
            $partesNombre = explode("-", trim($oferta->getNombre()));
            if (array_key_exists(4, $partesNombre)) {
                $nombreOferta .= " -" . $partesNombre[4];
            }
        } else {
            $em = $this->em;
            $consecutivo = $em->getRepository('LogicBundle:Oferta')->obtenerConsecutivo($oferta);
            $nombreOferta .= " - " . $consecutivo;
        }
        return $nombreOferta;
    }

    public function configureBatchActions($actions) {
        $container = $this->getConfigurationPool()->getContainer();
        $usuario = $container->get('security.token_storage')->getToken()->getUser();
        if ($usuario->hasRole('ROLE_ADMIN_ADMIN_OFERTA_EXPORT')) {
            $actions['generarReporte'] = array(
                'ask_confirmation' => true
            );
        }

        return $actions;
    }

}
