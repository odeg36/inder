<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEscenarioDeportivoRestriccionesFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addEscenarioDeportivoRestriccionesForm($form,  $hasRestricciones = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'label'     => 'formulario_escenario_deportivo.labels.paso_dos.restricciones',
            'required'  => false,
            'mapped'  => false ,
            'attr' => [
                'onchange' => 'inder.escenarioDeportivo.restricciones()'
            ] 
        );
        if ($hasRestricciones) {
            $formOptions['data'] = $hasRestricciones;
        }
        
        $form->add('tiene_restricciones', 'checkbox', $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        
        $hasRestricciones = false;
        if(
            count($data->getTendenciaEscenarioDeportivos() ) == 0  && 
            count($data->getDisciplinasEscenarioDeportivos()) == 0  && 
            count($data->getTipoReservaEscenarioDeportivos()) == 0 ){
            $hasRestricciones = true;
        }
        $this->addEscenarioDeportivoRestriccionesForm($form, $hasRestricciones);
    }


}
