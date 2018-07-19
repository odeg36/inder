<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\PuntoAtencion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddMunicipioFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;
    private $municipio;
    private $comuna;
    private $mapeado;

    public function __construct($es_requerido = "verdadero", $municipio = "", $comuna = false, $mapeado = false) {
        $this->es_requerido = $es_requerido;
        $this->comuna = $comuna;
        $this->mapeado = $mapeado;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addMunicipioForm($form, $municipio) {

        if ($this->es_requerido == "verdadero") {
            $noVacio = array(
                new NotBlank(array(
                    'message' => 'formulario_registro.no_vacio',
                        ))
            );
        } else {
            $noVacio = array();
        }
        $onChange = "inder.oferta.actualizarBarrios(this);";
        if ($this->comuna) {
            $onChange = "inder.actualizarComunas(this);";
        }

        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:Municipio',
            'mapped' => $this->mapeado,
            'label_attr' => array('class' => 'label_municipio'),
            'placeholder' => '',
            'empty_data' => '',
            'constraints' => $noVacio,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('m')
                                ->orderBy('m.nombre', 'ASC');
            },
            'attr' => array(
                'class' => 'form-control',
                'onchange' => $onChange
            ),
        );

        if ($municipio) {
            $formOptions['data'] = $municipio;
        }

        $form->add('municipio', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        if (method_exists($data, "getBarrio")) {
            $municipio = ($data->getBarrio()) ? $data->getBarrio()->getMunicipio() : null;
        } else if (method_exists($data, "getPuntoAtencion") && $data->getPuntoAtencion() && $data->getPuntoAtencion() instanceof PuntoAtencion) {
            $municipio = $data->getPuntoAtencion()->getBarrio()->getMunicipio();
        } else if ($this->municipio != "") {
            $municipio = $this->municipio;
        } else if (method_exists($data, "getMunicipio")) {
            $municipio = $data->getMunicipio();
        } else {
            $municipio = "";
        }
        $this->addMunicipioForm($form, $municipio);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $municipio = array_key_exists('municipio', $data) ? $data['municipio'] : null;
        $this->addMunicipioForm($form, $municipio);
    }

}
