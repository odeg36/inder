<?php

namespace AdminBundle\Form\EventListener\PlanAnualMetodologico;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAreaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addAreaForm($form, $area = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Area',
            'placeholder' => '',
            'label' => 'formulario_plan_anual_metodologico.area',
            'mapped' => false,
            'label_attr' => array('class' => 'label_area'),
            'required' => true,
            'attr' => array(
                'onchange' => 'inder.plan_anual_metodologico.actualizarEstrategias(this);',
                'class' => 'form-control ',
            )
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

        $area = "";
        if (method_exists($data, "getEstrategias")) {
            if (count($data->getEstrategias()) > 0) {
                $area = ($data->getEstrategias()) ? $data->getEstrategias()[0]->getArea() : null;
            }
        }

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
