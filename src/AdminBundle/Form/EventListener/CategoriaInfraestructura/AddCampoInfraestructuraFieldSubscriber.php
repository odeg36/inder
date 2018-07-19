<?php

namespace AdminBundle\Form\EventListener\Estrategia;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddCampoInfraestructuraFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            //FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addCampoForm($form, $campo = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:SubcategoriaInfraestructura',
            'label' => '',
            "required" => false,
            "constraints" => $noVacio,
            'empty_data' => null,
            'multiple' => false,
            'attr' => array(
                'class' => 'form-control tipo_entrada','autocomplete' => 'off',
                    'onchange' => 'inder.infraestructura.mostrarSubcategoria(this);'
            ),
        );
       
        

        if ($campo) {
            $formOptions['data'] = $campo;
        }

        $form->add('campo', EntityType::class, $formOptions);
    }


    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();

        $data = $event->getData();
        if (null === $data) {
            return;
        }

        $campo = array_key_exists('campo', $data) ? $data['campo'] : null;
        $this->addCampoForm($form, $campo);
    }

}
