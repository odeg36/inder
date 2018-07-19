<?php

namespace AdminBundle\Form\EventListener;

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
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Area',
            'placeholder' => '',
            'label' => 'formulario.oferta.area',
            'mapped' => false,
            'label_attr' => array('class' => 'label_area'),
            'attr' => array(
                'onchange' => 'inder.oferta.actualizarProyectos(this);',
                'class' => 'form-control ',
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
        $soloEstrategia = false;
        if (null === $data) {
            return;
        }

        $elementoCobertura = ($data->getDisciplinaEstrategia()) ? $data->getDisciplinaEstrategia() : null;
        if ($elementoCobertura == null) {
            $elementoCobertura = ($data->getTendenciaEstrategia()) ? $data->getTendenciaEstrategia() : null;
        }

        if ($elementoCobertura == null) {
            $soloEstrategia = true;
            $elementoCobertura = ($data->getEstrategia()) ? $data->getEstrategia() : null;
        }

        if ($soloEstrategia) {

            $area = ($elementoCobertura) ? $elementoCobertura->getProyecto()->getArea() : null;
        } else {
            $area = ($elementoCobertura) ? $elementoCobertura->getEstrategia()->getProyecto()->getArea() : null;
        }


        $this->addAreaForm($form, $area);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        $area = array_key_exists('area', $data) ? $data['area'] : null;
        $this->addAreaForm($form, $area);
    }

}
