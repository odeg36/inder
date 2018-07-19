<?php

namespace AdminBundle\Form\EventListener\EncuentroSistemaUno;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddSancionesFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addFaltaForm($form, $falta = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        
        $formOptions = array(
                'class' => 'LogicBundle:SancionEvento',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,
                'mapped'=> false,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ],
            )
        ;

       

        if ($falta) {
            $formOptions['data'] = $falta;

        }

        $form->add('faltasEncuentroJugador', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        
        if (null === $data) {
            $this->addFaltaForm($form, null); 
            return;
        }
        
        $falta = "";
        $falta= $data;
        /*if (method_exists($data, "getFaltasEncuentroJugador")) {
            if(count($data->getFaltasEncuentroJugador()) > 0){
                $falta = ($data->getFaltasEncuentroJugador()) ? $data->getFaltasEncuentroJugador()[0]->getTipoFalta() : null;
            }
        } */

        $this->addFaltaForm($form, $falta);
    }

}
