<?php

namespace AdminBundle\Form\EventListener\PlanAnualMetodologico;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddContenidoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addContenidoForm($form, $contenido = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Contenido',
            'placeholder' => '',
            'required' => true,
            'mapped' => false,
            "constraints" => $noVacio,
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.contenido',
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );
        if ($contenido) {
            $formOptions['data'] = $contenido;
        }

        $form->add('contenidos', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {

        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            $this->addContenidoForm($form, null);
            return;
        }
        $contenidos = [];
        foreach ($data->getContenidos() as $contenido) {
            $contenidos = $contenido->getId();
        }

        $this->addContenidoForm($form, $contenidos);
    }

}
