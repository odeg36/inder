<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use Application\Sonata\UserBundle\Entity\User;
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
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PuntoAtencionAdmin extends AbstractAdmin {

    protected $em;

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);
        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('direccion', null, [
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
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
                ->add('direccion', null, [
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
                ])
                ->add('barrio', null, [
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $object = $this->getSubject();
        $latitudCoordenada = 6.244203;
        $longitudCoordenada = -75.58121189999997;
        $dataDireccionOComuna = null;
        if ($object->getId()) {
            if ($object->getBarrio()) {
                $qb = $this->em->getRepository('LogicBundle:Municipio')->createQueryBuilder('m');
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
        $es_requerido = "verdadero";
        $formMapper
                ->add('municipio', EntityType::class, array(
                    'class' => 'LogicBundle:Municipio',
                    'label' => 'formulario_registro.contacto.municipio',
                    'mapped' => false
                ))
                ->add('barrio', null, array('label' => 'formulario_registro.contacto.barrio'))
                ->add('direccionOcomuna', ChoiceType::class, [
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
                    'data' => $dataDireccionOComuna,
                    'multiple' => false,
                    'expanded' => true,
                    'placeholder' => false,
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
                ])
                ->add('direccion_creado', DireccionType::class, array(
                    'required' => true,
                    'mapped' => false,
                    'object' => ['data' => $this->getSubject()],
                    'formularioPadre' => '.direccionResidencia',
                    'attr' => array(
                        'class' => 'campoDireccionOferta fondoDireccion col-md-12',
                    ),
                    'label' => 'formulario_registro.contacto.direccion_confirmar',
                ))
                ->add('direccion', null, array(
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
                ->add('localizacion', GoogleMapType::class, array(
                    'label' => 'formulario.localizacion',
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
                ))
                ->getFormBuilder()
                ->addEventSubscriber(new AddMunicipioFieldSubscriber($es_requerido))
                ->addEventSubscriber(new AddBarrioFieldSubscriber($es_requerido, true, "", $this->getRequest()))
        ;
    }

    public function prePersist($object) {
        $object->setBarrio($this->getForm()->get('barrio')->getData());
    }

    public function preUpdate($object) {
        $object->setBarrio($this->getForm()->get('barrio')->getData());
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('direccion', null, [
                    'label' => 'formulario.oferta.puntoAtencion.nueva.direccion'
                ])
                ->add('barrio', null, [
                ])
                ->add('latitud', null, [
                ])
                ->add('longitud', null, [
                ])


        ;
    }

    public function validate(ErrorElement $errorElement, $object) {
        if (!is_numeric($this->getForm()->get('localizacion')->get('latitud')->getData())) {
            $errorElement
                    ->addViolation($this->trans('error.puntoAtencion.latitud.no_numerico'))
                    ->end();
        }
        if (!is_numeric($this->getForm()->get('localizacion')->get('longitud')->getData())) {
            $errorElement
                    ->addViolation($this->trans('error.puntoAtencion.longitud.no_numerico'))
                    ->end();
        }
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:PuntoAtencion:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

}
