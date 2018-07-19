<?php

namespace AdminBundle\Form\EventListener\Oferta;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddEscenarioFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addEscenarioForm($form, $escenario) {

        $formOptions = array(
            'required' => false,
            'placeholder' => '',
            'class' => 'LogicBundle:EscenarioDeportivo',
            'label_attr' => array('class' => 'label_escenario'),
            'attr' => array(
                'class' => 'form-control seleccion_escenario escenario_oferta',
                'onchange' => 'inder.oferta.obtenerDivisiones(this);'
            ),
        );

        if ($escenario) {
            $formOptions['data'] = $escenario;
        }
        $form->add("escenarioDeportivo", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $escenarioDeportivo = method_exists($data, "getEscenarioDeportivo") ? $data->getEscenarioDeportivo() : null;

        $barrio = '';
        if ($escenarioDeportivo) {
            $barrio = ($escenarioDeportivo) ? $escenarioDeportivo->getBarrio()->getId() : null;
        }
        $this->addEscenarioForm($form, $escenarioDeportivo);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $escenario = array_key_exists('escenarioDeportivo', $data) ? $data['escenarioDeportivo'] : null;
        $this->addEscenarioForm($form, $escenario);
    }

}
