<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Route\RouteCollection;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Form\DireccionType;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class EncuentroSistemaTresAdmin extends AbstractAdmin {

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
                ->add('llave')
                ->add('ronda')
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
        $listMapper
                ->add('id')
                ->add('competidorUno', 'text')
                ->add('competidorDos', 'text')
                ->add('fecha')
                ->add('hora')
                ->add('llave')
                ->add('ronda')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array('template' => 'AdminBundle:SistemaJuegoTres:list_edit_action.html.twig', $this->data = $evento, $this->tipo = $tipoDeSistemaDeJuego,
                        ),
                        'delete' => array(),
                        'resultado' => array(
                            'template' => 'AdminBundle:SistemaJuegoTres:list__action_resultado.html.twig'
                        )
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
        $request = $this->getRequest();
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $evento = $em->getRepository("LogicBundle:Evento")->find($request->get('evento'));


        $cupoEvento = $evento->getCupo();


        $equipos = $em->getRepository('LogicBundle:EquipoEvento')
                ->createQueryBuilder('equipoEvento')
                ->Where('equipoEvento.evento = :evento')
                ->andWhere('equipoEvento.estado = :estado')
                ->setParameter('evento', $evento->getId())
                ->setParameter('estado', 1)
                ->getQuery()
                ->getResult();
        $cantidad = (count($equipos) % 2);
        $numeroLlaves = (count($equipos) / 2);
        $llaves = array();
        if ($cantidad == 0) {

            $cart = array();
            for ($i = 0; $i < $numeroLlaves; $i++) {
                $llaves[$i + 1] = $i + 1;
            }
        }
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

            $formMapper
                    ->add('tipo_juego', ChoiceType::class, array(
                        "constraints" => $noVacio,
                        'label' => 'formulario_campo.labels.tipoEntrada',
                        'required' => true,
                        'choices' => array(
                            'Escoger Una Opción' => '',
                            'EncuetroSistemaTres.tipoJuegoUno' => true,
                            'EncuetroSistemaTres.tipoJuegoDos' => false
                        ),
                        'required' => true,
                        'attr' => array(
                            'class' => 'form-control tipo_entrada',
                        ),
                    ))
                    ->add('llave', ChoiceType::class, array(
                        "constraints" => $noVacio,
                        'label' => 'EncuetroSistemaTres.llave',
                        'choices' => $llaves,
                        'required' => true,
                        'attr' => array(
                            'class' => 'form-control tipo_entrada',
                        )
                    ))
                    ->add('competidorUno', EntityType::class, array(
                        'class' => 'LogicBundle:EquipoEvento',
                        'attr' => [
                            'class' => 'col-md-6'
                        ],
                        "constraints" => $noVacio,
                        'data' => $competidorUno,
                        'mapped' => false,
                        'choices' => $equipos,
                            )
                    )
                    ->add('competidorDos', EntityType::class, array(
                        'class' => 'LogicBundle:EquipoEvento',
                        'attr' => array(
                            'class' => 'col-md-6',
                        ),
                        "constraints" => $noVacio,
                        'data' => $competidorDos,
                        'mapped' => false,
                        'choices' => $equipos
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
                ->add('tipoSistema')
                ->add('puntos_competidor_uno')
                ->add('puntos_competidor_dos')
                //->add('tipo_juego')
                ->add('llave')
                ->add('ronda')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'list':
                return 'AdminBundle:SistemaJuegoTres:base_list.html.twig';
            case 'edit':
                return 'AdminBundle:SistemaJuegoTres:base_edit.html.twig';
            case 'resultado':
                return 'AdminBundle:SistemaJuegoTres:tipos_faltas.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    //para evitar que muestre datos de otros eventos
    public function createQuery($context = 'list') {
        $request = $this->getRequest();
        $evento_id = $request->get('id');
        $tipo = $request->get('tipo');
        $query = parent::createQuery($context);
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $repoActivity = $em->getRepository('LogicBundle:EncuentroSistemaTres');
        $query = new ProxyQuery(
                $repoActivity->createQueryBuilder('encuentroSistemaTres')
                        ->where('encuentroSistemaTres.evento = :evento')
                        ->setParameter('evento', $evento_id)
        );
        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        } return $query;
    }
}
