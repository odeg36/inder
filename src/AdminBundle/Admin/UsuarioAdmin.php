<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddDiscapacidadFieldSubscriber;
use AdminBundle\Form\EventListener\Usuario\AddSubDiscapacidadFieldSubscriber;
use AdminBundle\Validator\Constraints\ConstraintsUsuarioUnico;
use Application\Sonata\UserBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Entity\PreinscripcionOferta;
use LogicBundle\Form\ComunaType;
use LogicBundle\Form\DireccionType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UsuarioAdmin extends AbstractAdmin {

    private $imagenPerfil;
    protected $noVacio;
    protected $em;

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('cargarMultiplesUsuarios', 'cargarMultiplesUsuarios');
        $collection->add('cambiarContrasenaUsuario', $this->getRouterIdParameter() . '/cambiarContrasenaUsuario');
        $collection->add('dashboard', 'dashboardAdmin');
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('firstname', null, [
                    'label' => 'formulario.labels.nombres'
                ])
                ->add('lastname', null, [
                    'label' => 'formulario.labels.apellidos'
                ])
                ->add('numeroIdentificacion', null, [
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
                ->add('tipoIdentificacion', null, [
                    'label' => 'formulario.usuario.tipoIdentificacion'
                ])
                ->add('email', null, [
                    'label' => 'formulario_registro.contacto.correo_electronico'
                ])
                ->add('enabled', null, [
                    'label' => 'formulario.habilitado'
                ])
                ->add('groups', null, [
                    'label' => 'titulo.roles'
                ])
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
                ->add('firstname', null, [
                    'label' => 'formulario.labels.nombres'
                ])
                ->add('lastname', null, [
                    'label' => 'formulario.labels.apellidos'
                ])
                ->add('numeroIdentificacion', null, [
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
                ->add('tipoIdentificacion', null, [
                    'label' => 'formulario.usuario.tipoIdentificacion'
                ])
                ->add('email', null, [
                    'label' => 'formulario_registro.contacto.correo_electronico'
                ])
        ;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper
                    ->add('enabled', null, [
                        'editable' => true,
                        'label' => 'formulario.habilitado'
                    ])
                    ->add('groups', null, [
                        'label' => 'titulo.roles'
                    ])
            ;
        }
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                    ->add('impersonating', 'string', [
                        'label' => 'titulo.impersonating',
                        'template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'
                    ])
            ;
        }
        $listMapper
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
        ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $formMapper
                ->with('formulario.registro_usuario.bloque.informacion_general', array('class' => 'col-md-6 p-right10'))->end()
                ->with('formulario.registro_usuario.bloque.datos_contacto', array('class' => 'col-md-6'))->end()
                ->end()
        ;

        if ($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                    ->with('formulario.registro_usuario.bloque.rol', array('class' => 'col-md-6'))->end()
                    ->end();
        }

        $noVacio = [
            new NotBlank([
                'message' => 'formulario_registro.no_vacio',
                    ])
        ];
        $noVacioClave = [
            new NotBlank([
                'message' => 'formulario_registro.no_vacio',
                    ]),
            new Length([
                'min' => 8,
                'minMessage' => 'formulario_registro.tamano_contrasena',
                    ])
        ];
        $usuarioObject = $this->getSubject();
        $imagen = "";
        $fileFieldOptions = [
            'required' => false,
            'data_class' => null,
            'label' => 'formulario_registro.pnatural_informacion.imagen_perfil',
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
        $dataDireccionOComuna = null;
        if ($usuarioObject->getId()) {
            $noVacioClave = [];
            $this->imagenPerfil = $imagen = $usuarioObject->getImagenPerfil();
            if ($imagen) {
                // get the container so the full path to the image can be set
                $container = $this->getConfigurationPool()->getContainer();
                $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagen;

                // add a 'help' option containing the preview's img tag
                $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
            }
            if ($usuarioObject->getBarrio()) {
                $qb = $this->em->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
                $qb
                        ->join('m.barrios', 'b')
                        ->andWhere('b.esVereda = :es_vereda')
                        ->andWhere('m.id = :municipio')
                        ->setParameter('es_vereda', true)
                        ->setParameter('municipio', $usuarioObject->getBarrio()->getMunicipio()->getId())
                ;
                $query = $qb->getQuery();
                $municipioObject = $query->getOneOrNullResult();
                if ($municipioObject && $usuarioObject->getBarrio()->getEsVereda()) {
                    $dataDireccionOComuna = User::COMUNA;
                } elseif ($municipioObject) {
                    $dataDireccionOComuna = User::DIRECCION;
                }
            }
        }
        $rutaMunicipio = "barrio";
        $now = new DateTime();

        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $modelType = 'Sonata\AdminBundle\Form\Type\ModelType';
        } else {
            $modelType = 'sonata_type_model';
        }
        $this->noVacio = [
            new NotBlank(['message' => 'formulario_registro.no_vacio']),
        ];
        $formModifier = function ($form, $data) {
            $nivelEscolaridad = $data["nivelEscolaridad"];
            $opciones = $this->getOpciones();
            if ($nivelEscolaridad) {
                $nivelEscolaridad = $this->em->getRepository("LogicBundle:NivelEscolaridad")->findOneById($nivelEscolaridad);
                if ($nivelEscolaridad) {
                    if (strtoupper(PreinscripcionOferta::NO_DEFINIDO) == strtoupper($nivelEscolaridad->getNombre())) {
                        $opciones["tipoEstablecimientoEducativo"]["required"] = false;
                        $opciones["establecimientoEducativo"]["required"] = false;
                        $opciones["tipoEstablecimientoEducativo"]["constraints"] = [];
                        $opciones["establecimientoEducativo"]["constraints"] = [];
                        $form
                                ->add('tipoEstablecimientoEducativo', EntityType::class, $opciones["tipoEstablecimientoEducativo"])
                                ->add('establecimientoEducativo', EntityType::class, $opciones["establecimientoEducativo"]);
                    }
                }
            }
        };
        $tieneDiscapacidad = $usuarioObject->getSubDiscapacidad() ? true : false;
        $esDesplazado = $usuarioObject->getTipoDesplazado() ? true : false;

        $opciones = $this->getOpciones();
        $formMapper
                ->with('formulario.registro_usuario.bloque.informacion_general')
                ->add('tipoIdentificacion', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.tipo_documento',
                    'required' => true,
                    'constraints' => $noVacio,
                    'placeholder' => '',
                    'query_builder' => function(EntityRepository $repository) {
                        return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                    },
                    'disabled' => !$securityContext->isGranted('ROLE_SUPER_ADMIN') ? true : false
                ))
                ->add('numeroIdentificacion', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.numero_identificacion',
                    'required' => true,
                    'constraints' => $noVacio,
                    'disabled' => !$securityContext->isGranted('ROLE_SUPER_ADMIN') ? true : false,
                    'constraints' => [
                        new NotBlank(['message' => 'formulario_registro.no_vacio']),
                        new Length(array(
                            'min' => 1,
                            'max' => 11,
                            'minMessage' => 'formulario.validar.limite.minimo',
                            'maxMessage' => 'formulario.validar.limite.maximo',
                                )),
                        new ConstraintsUsuarioUnico(),
                        new Regex([
                            'pattern' => '/^[0-9]+$/' //Valida que solo sean números
                                ])
                    ]
                ))
                ->add('firstname', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.nombre',
                    'required' => true,
                    'constraints' => $noVacio
                ))
                ->add('lastname', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.apellido',
                ))
                ->add('gender', ChoiceType::class, array(
                    'label' => 'formulario_registro.pnatural_informacion.genero',
                    'required' => false,
                    'choices' => User::GENDERS,
                ))
                ->add('orientacionSexual', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.orientacion_sexual',
                    'required' => false,
                ))
                ->add('dateOfBirth', DateMaskType::class, array(
                    'label' => 'formulario_registro.pnatural_informacion.fecha_nacimiento',
                    'mask-alias' => 'dd/mm/yyyy',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'dd/mm/yyyy',
                    ],
                    'required' => false,
                    'constraints' => [
                        new LessThan("-1 years"),
                        new GreaterThan("-130 years")
                    ]
                ))
                ->add('imagen_perfil', FileType::class, $fileFieldOptions)
                ->end()
                ->with('formulario.registro_usuario.bloque.datos_contacto')
                ->add('municipio', EntityType::class, array(
                    'class' => 'LogicBundle:Municipio',
                    'label' => 'formulario_registro.contacto.municipio',
                    'mapped' => false
                ))
                ->add('barrio', null, array('label' => 'formulario_registro.contacto.barrio'))
                ->add('email', null, array(
                    'label' => 'formulario_registro.contacto.correo_electronico',
                    'required' => false,
                ))
                ->add('phone', null, array('label' => 'formulario_registro.contacto.telefono_movil'))
                ->end()
                ->with('formulario.usuario.puntoAtencion')
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
                    'formularioPadre' => '.direccionResidencia',
                    'attr' => array(
                        'class' => 'campoDireccionOferta fondoDireccion col-md-12',
                    ),
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                ))
                ->add('direccionResidencia', null, array(
                    'label' => 'formulario_registro.contacto.direccion_residencia',
                    'attr' => [
                        'class' => 'direccionResidencia',
                        'readonly' => true
                    ]
                ))
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
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                    'required' => true,
                    'attr' => array(
                        'readonly' => true,
                        'class' => 'form-control direccionComuna'
                    )
                ])
                ->end()
                ->with('formulario.usuario.caracterizacion')
                ->add('eps', EntityType::class, array(
                    "class" => "LogicBundle:Eps",
                    "label" => "formulario.info_adicional.eps",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('nivelEscolaridad', EntityType::class, array(
                    "class" => "LogicBundle:NivelEscolaridad",
                    "label" => "formulario.info_adicional.nivel_escolaridad",
                    "required" => false,
                    'attr' => [
                        'onchange' => 'inder.preinscripcion.nivelEscolaridad(this)',
                        'class' => 'nivel-escolaridad'
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('tipoEstablecimientoEducativo', EntityType::class, $opciones["tipoEstablecimientoEducativo"])
                ->add('establecimientoEducativo', EntityType::class, $opciones["establecimientoEducativo"])
                ->add('ocupacion', EntityType::class, array(
                    "class" => "LogicBundle:Ocupacion",
                    "label" => "formulario.info_adicional.ocupacion",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('etnia', EntityType::class, array(
                    "class" => "LogicBundle:Etnia",
                    "label" => "titulo.etnia",
                    "required" => false,
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('esDesplazado', CheckboxType::class, array(
                    "mapped" => false,
                    "required" => false,
                    "data" => $esDesplazado,
                    "label" => "formulario.info_adicional.desplazado"
                ))
                ->add('tipoDesplazado', EntityType::class, array(
                    "class" => "LogicBundle:TipoDesplazado",
                    "label" => "formulario.info_adicional.tipo_desplazado",
                    "required" => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('esDiscapacitado', CheckboxType::class, array(
                    "mapped" => false,
                    "required" => false,
                    "data" => $tieneDiscapacidad,
                    "label" => "formulario.info_adicional.discapacidad"
                ))
                ->add('discapacidad', EntityType::class, array(
                    "class" => "LogicBundle:Discapacidad",
                    "label" => "formulario.info_adicional.tipo_discapacidad",
                    'label_attr' => array('class' => 'label_subDiscapacidades'),
                    "required" => false,
                    'mapped' => false,
                    'attr' => [
                        'onchange' => 'inder.preinscripcion.actualizarSubDiscapacidades(this);',
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
                    },
                ))
                ->add('subDiscapacidad', EntityType::class, array(
                    "class" => "LogicBundle:SubDiscapacidad",
                    "label" => "formulario.info_adicional.sub_discapacidad",
                    "required" => false,
                    'label_attr' => [
                        'class' => 'label_subDiscapacidades',
                    ]
                ))
                ->add('esJefeCabezaHogar', CheckboxType::class, array(
                    "required" => false,
                    "label" => "formulario.info_adicional.es_jefe_cabeza_hogar",
                ))
                ->end()
                ->getFormBuilder()
                ->addEventSubscriber(new AddMunicipioFieldSubscriber($rutaMunicipio))
                ->addEventSubscriber(new AddBarrioFieldSubscriber(false, true, "", $this->getRequest()))
                ->addEventSubscriber(new AddDiscapacidadFieldSubscriber())
                ->addEventSubscriber(new AddSubDiscapacidadFieldSubscriber())
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $form = $event->getForm();
                    if ($form->get('esDesplazado')->getData() && !$form->get('tipoDesplazado')->getData()) {
                        $form->get('tipoDesplazado')->addError(new FormError('error.no_vacio'));
                    }
                    if ($form->get('esDiscapacitado')->getData() && !$form->get('subDiscapacidad')->getData()) {
                        $form->get('subDiscapacidad')->addError(new FormError('error.no_vacio'));
                    }
                })
                ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
                    if (null != $event->getData()) {
                        $usuario = $event->getData();

                        $formModifier($event->getForm(), $usuario);
                    }
                });

        if ($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                    ->with('formulario.registro_usuario.bloque.rol')
                    ->add('groups', $modelType, array(
                        'label' => 'formulario.usuario.grupos',
                        'required' => true,
                        'multiple' => true,
                        'btn_add' => false,
                        'constraints' => [
                            new Count(array(
                                'min' => 1
                                    ))
                        ]
                    ))
                    ->add('enabled', null, array(
                        'label' => 'formulario.habilitado'
                    ))
                    ->add('fechaExpiracion', DateMaskType::class, array(
                        'label' => 'formulario_registro.expira',
                        'required' => false,
                        'mask-alias' => 'dd/mm/yyyy',
                        'attr' => [
                            'class' => 'form-control',
                            'placeholder' => 'dd/mm/yyyy',
                        ],
                        'constraints' => [
                            new GreaterThan("today")
                        ]
                    ))
                    ->end();
        }
    }

    public function getExistingRoles() {
        $roleHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }
        return $theRoles;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:Usuario:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function getOpciones() {
        return [
            "tipoEstablecimientoEducativo" => [
                "class" => "LogicBundle:TipoEstablecimientoEducativo",
                "label" => "formulario.info_adicional.tipo_establecimiento_educativo",
                "required" => false,
                'placeholder' => '',
                'attr' => [
                    'onchange' => 'inder.preinscripcion.tipoEstablecimientoEducativo(this)',
                    'class' => 'tipo-establecimiento-educativo'
                ],
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                    ->orderBy('u.nombre', 'ASC');
                },
            ],
            "establecimientoEducativo" => [
                "class" => "LogicBundle:EstablecimientoEducativo",
                "label" => "formulario.info_adicional.establecimiento_educativo",
                "required" => false,
                'placeholder' => '',
                'attr' => [
                    'class' => 'establecimiento-educativo'
                ],
                'choice_attr' => function($val, $key, $index) {
                    return ['data' => strtolower($val->getNombre())];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                    ->orderBy('u.nombre', 'ASC');
                },
            ]
        ];
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('firstname', null, [
                    'label' => 'formulario.labels.nombres'
                ])
                ->add('lastname', null, [
                    'label' => 'formulario.labels.apellidos'
                ])
                ->add('numeroIdentificacion', null, [
                    'label' => 'formulario.usuario.numeroIdentificacion'
                ])
                ->add('tipoIdentificacion', null, [
                    'label' => 'formulario.usuario.tipoIdentificacion'
                ])
                ->add('email', null, [
                    'label' => 'formulario_registro.contacto.correo_electronico'
                ])
                ->add('phone', null, array(
                    'label' => 'formulario_registro.contacto.telefono_movil'
                ))
                ->add('enabled', null, [
                    'label' => 'formulario.habilitado'
                ])
                ->add('fechaExpiracion', null, array(
                    'label' => 'formulario_registro.expira'
                ))
                ->add('groups', null, [
                    'label' => 'titulo.roles'
                ])
                ->add('sexo', null, [
                    'label' => 'formulario_registro.pnatural_informacion.genero'
                ])
                ->add('orientacionSexual', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.orientacion_sexual'
                ))
                ->add('dateOfBirth', null, array(
                    'label' => 'formulario_registro.pnatural_informacion.fecha_nacimiento'
                ))
                ->add('municipio', null, array(
                    'label' => 'formulario_registro.contacto.municipio'
                ))
                ->add('barrio', null, array(
                    'label' => 'formulario_registro.contacto.barrio'
                ))
        ;
    }

    public function prePersist($object) {
        $this->preUpdate($object);
    }

    public function preUpdate($object) {
        if ($object->getImagenPerfil() == "img-perfil.png" || !$object->getImagenPerfil()) {
            $object->setImagenPerfil($this->imagenPerfil);
        }
    }

}
