<?php

namespace LogicBundle\Form;

use AdminBundle\Form\EventListener\EscenarioDeportivo\AddDisciplinasEscenarioDeportivoFieldSubscriber;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddEscenarioDeportivoRestriccionesFieldSubscriber;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddTendenciaEscenarioDeportivoFieldSubscriber;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddTipoReservaEscenarioDeportivoFieldSubscriber;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddUsuarioEscenarioDeportivoPrincipalFieldSubscriber;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddUsuarioEscenarioDeportivoSecundarioFieldSubscriber;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Form\DireccionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotNull;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Form\GoogleMapType;
use AdminBundle\Form\EventListener\EscenarioDeportivo\AddComunaFieldSubscriber;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use LogicBundle\Form\ComunaEscenarioType;
use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;

class EscenarioDeportivoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    ))
        );
        $email = array(
            new Email(array(
                'message' => 'Ingrese un correo valido por favor.'
                    ))
        );


        $rutaMunicipio = "barrio";
        $latitudCoordenada = 6.244203;
        $longitudCoordenada = -75.58121189999997;
        if ($options['paso'] == 1) {
            $dataDireccionOComuna = null;
            $object = $builder->getData();
            if ($object->getId()) {
                if ($object->getBarrio()) {
                    $qb = $options['em']->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
                    $qb
                            ->join('m.barrios', 'b')
                            ->andWhere('b.esVereda = :es_vereda')
                            ->andWhere('m.id = :municipio')
                            ->setParameter('es_vereda', true)
                            ->setParameter('municipio', $object->getBarrio()->getMunicipio()->getId())
                    ;
                    $query = $qb->getQuery();
                    $municipioObject = $query->getOneOrNullResult();
                    if ($municipioObject && $object->getBarrio()->getEsVereda()) {
                        $dataDireccionOComuna = User::COMUNA;
                    } elseif ($municipioObject) {
                        $dataDireccionOComuna = User::DIRECCION;
                    }
                }
            }
            $builder
                    ->add('nombre', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_uno.nombre",
                        "constraints" => $noVacio,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    // type actualizado
                    ->add('tipoDireccion', EntityType::class, array(
                        'class' => 'LogicBundle:TipoDireccion',
                        'expanded' => true,
                        'multiple' => false,
                        'attr' => array('class' => 'choice-escenario-tipo-direccion')
                    ))
                    ->add('municipio', EntityType::class, array(
                        "required" => true,
                        'class' => 'LogicBundle:Municipio',
                        "constraints" => $noVacio,
                        'label' => 'formulario_registro.contacto.municipio',
                        'mapped' => false
                    ))
                    ->add('barrio', null, array('label' => 'formulario_registro.contacto.barrio'))
                    ->add('direccionOcomuna', ChoiceType::class, [
                        'data' => $dataDireccionOComuna,
                        'mapped' => false,
                        'required' => false,
                        'choices' => [
                            'formulario.vereda' => User::COMUNA,
                            'formulario.barrio' => User::DIRECCION
                        ],
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
                        'formularioPadre' => '.direccionResidencia',
                        'attr' => array(
                            'class' => 'campoDireccionOferta fondoDireccion col-md-12',
                        ),
                        'label' => 'formulario_registro.contacto.direccion_confirmar',
                    ))
                    ->add('direccion', null, array(
                        'data' => $object->getDireccion(),
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
                        "required" => false,
                        'label' => 'formulario_registro.contacto.direccion_confirmar',
                    ])
                    ->add('direccionComuna', TextType::class, [
                        'data' => $object->getDireccion(),
                        'label' => 'formulario_registro.contacto.direccion_confirmar',
                        'mapped' => false,
                        'attr' => array(
                            'readonly' => true,
                            'class' => 'form-control direccionComuna'
                        )
                    ])
                    ->addEventSubscriber(new AddMunicipioFieldSubscriber("barrio"))
                    ->addEventSubscriber(new AddBarrioFieldSubscriber(false, true, "", $options['request']))
                    ->add('unidad_deportiva', EntityType::class, array(
                        'class' => 'LogicBundle:UnidadDeportiva',
                        'placeholder' => '',
                        'required' => false,
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.unidad_deportiva',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('tipo_escenario', EntityType::class, array(
                        'class' => 'LogicBundle:TipoEscenario',
                        'placeholder' => '',
                        'required' => true,
                        "constraints" => $noVacio,
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.tipo_escenario',
                        'empty_data' => null,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ))
                    ->add('localizacion', GoogleMapType::class, array(
                        'label' => 'formulario.localizacion',
                        'type' => 'text', // the types to render the lat and lng fields as
                        'options' => array(), // the options for both the fields
                        'lat_options' => array('attr' => array('class' => 'form-control')), // the options for just the lat field
                        'lng_options' => array('attr' => array('class' => 'form-control')), // the options for just the lng field
                        'lat_name' => 'latitud', // the name of the lat field
                        'lng_name' => 'longitud', // the name of the lng field
                        'map_width' => '100%', // the width of the map
                        'map_height' => '300px', // the height of the map
                        'default_lat' => $latitudCoordenada, // the starting position on the map
                        'default_lng' => $longitudCoordenada, // the starting position on the map
                        'include_jquery' => false, // jquery needs to be included above the field (ie not at the bottom of the page)
                        'include_gmaps_js' => true, // is this the best place to include the google maps javascript?
                    ))
                    ->add('ubicacion', TextType::class, [
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.ubicacion',
                        'required' => true,
                        "mapped" => false,
                    ])
                    ->add('telefono', null, [
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.telefono',
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ])
                    ->add('email', null, [
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.email_escenario',
                        "required" => false,
                        "constraints" => $email,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ])
                    ->add('informacionReserva', CKEditorType::class, array(
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
                        'label' => 'formulario_escenario_deportivo.labels.paso_uno.informacionReserva',
                        'attr' => array(
                            'class' => 'form-control',
                            'rows' => 6,
                            'autocomplete' => 'off')));

            ;
        }
        if ($options['paso'] == 2) {
            $builder
                    ->addEventSubscriber(new AddEscenarioDeportivoRestriccionesFieldSubscriber())
                    ->addEventSubscriber(new AddTipoReservaEscenarioDeportivoFieldSubscriber())
                    ->addEventSubscriber(new AddDisciplinasEscenarioDeportivoFieldSubscriber())
                    ->addEventSubscriber(new AddTendenciaEscenarioDeportivoFieldSubscriber())
                    ->addEventSubscriber(new AddUsuarioEscenarioDeportivoPrincipalFieldSubscriber())
                    ->addEventSubscriber(new AddUsuarioEscenarioDeportivoSecundarioFieldSubscriber())
                    ->add('lunes', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Lunes' => null,
                        ),
                    ])
                    ->add('martes', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Martes' => null,
                        ),
                    ])
                    ->add('miercoles', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Miercoles' => null,
                        ),
                    ])
                    ->add('jueves', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Jueves' => null,
                        ),
                    ])
                    ->add('viernes', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Viernes' => null,
                        ),
                    ])
                    ->add('sabado', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Sabado' => null,
                        ),
                    ])
                    ->add('domingo', ChoiceType::class, [
                        'required' => false,
                        "mapped" => false,
                        'choices' => array(
                            'Domingo' => null,
                        ),
                    ])
                    ->add('hora_inicial_lunes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_lunes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_martes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_martes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_miercoles', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_miercoles', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_jueves', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_jueves', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_viernes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_viernes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_sabado', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_sabado', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial_domingo', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final_domingo', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_lunes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_lunes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_martes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_martes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_miercoles', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_miercoles', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_jueves', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_jueves', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_viernes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_viernes', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_sabado', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_sabado', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_inicial2_domingo', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('hora_final2_domingo', TextType::class, array(
                        "required" => false,
                        'label' => 'formulario_reserva.labels.paso_uno.hora_inicio',
                        'attr' => array('class' => 'hora_programacion_reserva form-control', 'placeholder' => 'formulario_reserva.labels.paso_uno.placeholder_hora'),
                    ))
                    ->add('horarioDivision', CheckboxType::class, array(
                        'label' => 'formulario_escenario_deportivo.labels.paso_dos.divisionHorario',
                        'required' => false,
                    ))
                    ->add('normaEscenario', CKEditorType::class, array(
                        'required' => true,
                        "constraints" => $noVacio,
                        'config' => array('toolbar' => 'full'),
                        'label' => 'formulario_escenario_deportivo.labels.paso_dos.normas',
                        'attr' => array(
                            'class' => 'form-control',
                            'rows' => 6,
                            'autocomplete' => 'off')));
        }
        if ($options['paso'] == 3) {
            $escenarioObject = $builder->getData();
            $opcion = [
                'data_class' => null,
                'label' => 'Selecciona una foto para subir',
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
            if ($escenarioObject->getId()) {
                $noVacioClave = [];
                $imagenWeb = $escenarioObject->getImagenEscenarioDividido();
                $opcion = array_merge($opcion, [
                    'required' => false,
                ]);
                if ($imagenWeb) {
                    // get the container so the full path to the image can be set
                    $container = $options['em'];

                    $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagenWeb;
                    // add a 'help' option containing the preview's img tag
                    $opcion['attr'] = [
                        "class" => "file",
                        "src" => $fullPath,
                        "data-show-upload" => "false",
                        "data-show-caption" => "true",
                        "data-show-caption" => "true",
                        "data-msg-placeholder" => "Selecciona una foto para subir"
                    ];
                }
            }
            if ($escenarioObject->getHorarioDivision() == true) {
                //horarios para la mañana
                $lunesInicio = $escenarioObject->getHoraInicialLunes();
                $lunesFinal = $escenarioObject->getHoraFinalLunes();
                $martesInicial = $escenarioObject->getHoraInicialMartes();
                $martesFinal = $escenarioObject->getHoraFinalMartes();
                $miercolesInicial = $escenarioObject->getHoraInicialMiercoles();
                $miercolesFinal = $escenarioObject->getHoraFinalMiercoles();
                $juevesInicial = $escenarioObject->getHoraInicialJueves();
                $juevesFinal = $escenarioObject->getHoraFinalJueves();
                $viernesInicial = $escenarioObject->getHoraInicialViernes();
                $viernesFinal = $escenarioObject->getHoraFinalViernes();
                $sabadoInicial = $escenarioObject->getHoraInicialSabado();
                $sabadoFinal = $escenarioObject->getHoraFinalSabado();
                $domingoInicial = $escenarioObject->getHoraInicialDomingo();
                $domingoFinal = $escenarioObject->getHoraFinalDomingo();

                //horarios para la tarde
                $lunesInicio2 = $escenarioObject->getHoraInicial2Lunes();
                $lunesFinal2 = $escenarioObject->getHoraFinal2Lunes();
                $martesInicial2 = $escenarioObject->getHoraInicial2Martes();
                $martesFinal2 = $escenarioObject->getHoraFinal2Martes();
                $miercolesInicial2 = $escenarioObject->getHoraInicial2Miercoles();
                $miercolesFinal2 = $escenarioObject->getHoraFinal2Miercoles();
                $juevesInicial2 = $escenarioObject->getHoraInicial2Jueves();
                $juevesFinal2 = $escenarioObject->getHoraFinal2Jueves();
                $viernesInicial2 = $escenarioObject->getHoraInicial2Viernes();
                $viernesFinal2 = $escenarioObject->getHoraFinal2Viernes();
                $sabadoInicial2 = $escenarioObject->getHoraInicial2Sabado();
                $sabadoFinal2 = $escenarioObject->getHoraFinal2Sabado();
                $domingoInicial2 = $escenarioObject->getHoraInicial2Domingo();
                $domingoFinal2 = $escenarioObject->getHoraFinal2Domingo();
            } else {

                //horarios para la mañana
                $lunesInicio = "";
                $lunesFinal = "";
                $martesInicial = "";
                $martesFinal = "";
                $miercolesInicial = "";
                $miercolesFinal = "";
                $juevesInicial = "";
                $juevesFinal = "";
                $viernesInicial = "";
                $viernesFinal = "";
                $sabadoInicial = "";
                $sabadoFinal = "";
                $domingoInicial = "";
                $domingoFinal = "";

                //horarios para la tarde
                $lunesInicio2 = "";
                $lunesFinal2 = "";
                $martesInicial2 = "";
                $martesFinal2 = "";
                $miercolesInicial2 = "";
                $miercolesFinal2 = "";
                $juevesInicial2 = "";
                $juevesFinal2 = "";
                $viernesInicial2 = "";
                $viernesFinal2 = "";
                $sabadoInicial2 = "";
                $sabadoFinal2 = "";
                $domingoInicial2 = "";
                $domingoFinal2 = "";
            }
            $builder
                    ->add('ancho', IntegerType::class, [
                        'required' => true,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ])
                    ->add('largo', IntegerType::class, [
                        'required' => true,
                        "constraints" => $noVacio,
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ])
                    ->add('profundidad', IntegerType::class, [
                        'attr' => [
                            'class' => 'form-control', 'autocomplete' => 'off'
                        ]
                    ])
                    ->add('imagenEscenarioDividido', FileType::class, $opcion)
                    ->add('divisiones', CollectionType::class, array(
                        'entry_type' => DivisionType::class,
                        'entry_options' => array('label' => false),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'required' => false,
                        'prototype' => true,
                        'attr' => array(
                            'class' => 'divisiones-collection',
                        ),
                        'prototype_name' => '__divisiones_name__',
                        'by_reference' => false,
                        'entry_options' => array(
                            'escenarioId' => $options['escenarioId'],
                            'tiposReservas' => $options['tiposReservas'],
                            'lunesInicio' => $lunesInicio,
                            'lunesFinal' => $lunesFinal,
                            'martesInicial' => $martesInicial,
                            'martesFinal' => $martesFinal,
                            'miercolesInicial' => $miercolesInicial,
                            'miercolesFinal' => $miercolesFinal,
                            'juevesInicial' => $juevesInicial,
                            'juevesFinal' => $juevesFinal,
                            'viernesInicial' => $viernesInicial,
                            'viernesFinal' => $viernesFinal,
                            'sabadoInicial' => $sabadoInicial,
                            'sabadoFinal' => $sabadoFinal,
                            'domingoInicial' => $domingoInicial,
                            'domingoFinal' => $domingoFinal,
                            'lunesInicio2' => $lunesInicio2,
                            'lunesFinal2' => $lunesFinal2,
                            'martesInicial2' => $martesInicial2,
                            'martesFinal2' => $martesFinal2,
                            'miercolesInicial2' => $miercolesInicial2,
                            'miercolesFinal2' => $miercolesFinal2,
                            'juevesInicial2' => $juevesInicial2,
                            'juevesFinal2' => $juevesFinal2,
                            'viernesInicial2' => $viernesInicial2,
                            'viernesFinal2' => $viernesFinal2,
                            'sabadoInicial2' => $sabadoInicial2,
                            'sabadoFinal2' => $sabadoFinal2,
                            'domingoInicial2' => $domingoInicial2,
                            'domingoFinal2' => $domingoFinal2,
                            'validar' => $escenarioObject->getHorarioDivision(),
                        ),
            ));
        }
        if ($options['paso'] == 4) {
            $builder
                    ->add('cobamaLote', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.cobamaLote",
                        "required" => true,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('eliminarPlanos', CheckBoxType::class, array(
                        'label' => 'formulario_escenario_deportivo.labels.paso_cuatro.eliminarPlanos',
                        'mapped' => false,
                        'required' => false,
                    ))
                    ->add('planos', FileType::class, [
                        'data_class' => null,
                        'label' => 'formulario_escenario_deportivo.labels.paso_cuatro.planos',
                        'required' => false,
                        "multiple" => true,
                        "mapped" => false,
                        'constraints' => array(
                            new Count(array('max' => 3), new All(array(
                                'constraints' => array(
                                    new File([
                                        'maxSize' => '2M',
                                        'mimeTypes' => [
                                            "image/jpeg", "image/jpg", "image/png"
                                        ]
                                            ])
                                )
                                    )))),
                        'attr' => [
                            "class" => "file",
                            "data-show-upload" => "false",
                            "data-show-caption" => "true",
                            "data-msg-placeholder" => "Selecciona una imagén para subir",
                            'multiple' => 'multiple'
                        ]
                    ])
                    ->add('eliminarImagenes', CheckBoxType::class, array(
                        'label' => 'formulario_escenario_deportivo.labels.paso_cuatro.eliminarImagenes',
                        'mapped' => false,
                        'required' => false,
                    ))
                    ->add('imagenes', FileType::class, [
                        'data_class' => null,
                        'label' => 'formulario_escenario_deportivo.labels.paso_cuatro.imagenes',
                        'required' => false,
                        "multiple" => true,
                        "mapped" => false,
                        'constraints' => array(
                            new Count(array('max' => 3), new All(array(
                                'constraints' => array(
                                    new File([
                                        'maxSize' => '2M',
                                        'mimeTypes' => [
                                            "image/jpeg", "image/jpg", "image/png"
                                        ]
                                            ])
                                )
                                    )))),
                        'attr' => [
                            "class" => "file",
                            "data-show-upload" => "false",
                            "data-show-caption" => "true",
                            "data-msg-placeholder" => "Selecciona una imagén para subir",
                            'multiple' => 'multiple'
                        ]
                    ])
                    ->add('tieneAcueducto', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.tieneAcueducto",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('cantidadContadoresAcueducto', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.cantidadContadoresAcueducto",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('codigoContadoresAcueducto', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.codigoContadoresAcueducto",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('observacionesAcueducto', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.observacionesAcueducto",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('tieneEnergia', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.tieneEnergia",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('cantidadContadoresEnergia', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.cantidadContadoresEnergia",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('codigoContadoresEnergia', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.codigoContadoresEnergia",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('observacionesEnergia', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.observacionesEnergia",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('tieneTelefono', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.tieneTelefono",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('observacionesTelefono', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.observacionesTelefono",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('tieneInternet', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.tieneInternet",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('tieneIluminacion', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.tieneIluminacion",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
                    ->add('fuente', null, array(
                        "label" => "formulario_escenario_deportivo.labels.paso_cuatro.fuente",
                        "required" => false,
                        'attr' => array('class' => 'form-control', 'autocomplete' => 'off')
                    ))
            ;
        }

        if ($options['paso'] == 41) {
            $builder
                    ->add('escenarioCategoriaInfraestructuras', CollectionType::class, array(
                        'entry_type' => CategoriaInfraestructuraEscenarioType::class,
                        'entry_options' => array('label' => false),
                        'label' => 'Categorias Infraestructura',
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'by_reference' => false,
                        'entry_options' => array(
                            'tipoEscenario' => $options['tipoEscenario'],
                        ),
                        'attr' => array(
                            'class' => 'escenarioCategoriaInfraestructuras-collection'
                        ),
                        'constraints' => [
                            new Count(array(
                                'min' => 1
                                    ))
                        ],
                        'prototype_name' => '__parent_name__'
            ));
        }

        if ($options['paso'] == 42) {
            $builder
                    ->add('escenarioCategoriaAmbientales', CollectionType::class, array(
                        'entry_type' => CategoriaAmbientalEscenarioType::class,
                        'entry_options' => array('label' => false),
                        'label' => 'formulario.categoria.ambiental',
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'required' => false,
                        'by_reference' => false,
                        'attr' => array(
                            'class' => 'escenarioCategoriaAmbientales-collection'
                        ),
                        'constraints' => [
                            new Count(array(
                                'min' => 1
                                    ))
                        ],
                        'prototype_name' => '__parent_name__'
            ));
        }

        $builder
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => EscenarioDeportivo::class,
            'paso' => null,
            'escenarioId' => null,
            'escenarioTipoDireccion' => null,
            'em' => null,
            'categorias' => null,
            'subcategorias' => null,
            'escenarioDeportivo' => null,
            'tiposReservas' => null,
            'tipoEscenario' => null,
            'divisionesEscenario' => null,
            'request' => null
        ));
    }

}
