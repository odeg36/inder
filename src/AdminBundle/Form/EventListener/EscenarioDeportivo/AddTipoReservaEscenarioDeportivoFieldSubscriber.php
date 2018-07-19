<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTipoReservaEscenarioDeportivoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addTipoReservaEscenarioDeportivoForm($form,  $tipoReservas = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:TipoReserva',
            'placeholder' => '',
            'label' => 'formulario_escenario_deportivo.labels.paso_dos.tipo_reserva',
            'empty_data' => null,
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );
        if ($tipoReservas) {
            $formOptions['data'] = $tipoReservas;
        }
        
        $form->add('tipo_reserva', EntityType::class, $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $tipoReservas = array();
        foreach($data->getTipoReservaEscenarioDeportivos() as $getTipoReservaEscenarioDeportivos){
            array_push($tipoReservas, $getTipoReservaEscenarioDeportivos->getTipoReserva());
        }

        $this->addTipoReservaEscenarioDeportivoForm($form, $tipoReservas);
    }


}
