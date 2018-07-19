<?php

namespace AdminBundle\Form\EventListener\Estrategia;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAreaEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addAreaForm($form, $area = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Area',
            'placeholder' => '',
            'label' => 'formulario.oferta.area',
            'mapped' => false,
            'attr' => array(
                'onchange' => 'inder.oferta.actualizarProyectos(this);',
            ),
            'constraints' => $noVacio,
        );

        if ($area) {
            $formOptions['data'] = $area;
        }

        $form->add('area', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $proyecto = ($data->getProyecto()) ? $data->getProyecto() : null;
        $area = ($proyecto) ? $proyecto->getArea() : null;

        $this->addAreaForm($form, $area);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();

        $data = $event->getData();
        if (null === $data) {
            return;
        }

        $area = array_key_exists('area', $data) ? $data['area'] : null;
        $this->addAreaForm($form, $area);
    }

}
