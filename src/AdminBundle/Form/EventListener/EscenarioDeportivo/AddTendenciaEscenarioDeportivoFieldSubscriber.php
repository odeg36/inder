<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTendenciaEscenarioDeportivoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addTendenciaEscenarioDeportivoForm($form,  $tendencias = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Tendencia',
            'placeholder' => '',
            'label' => 'formulario_escenario_deportivo.labels.paso_dos.tendencia',
            'empty_data' => null,
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );
        if ($tendencias) {
            $formOptions['data'] = $tendencias;
        }
        
        $form->add('tendencia', EntityType::class, $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $tendencias = array();
        foreach($data->getTendenciaEscenarioDeportivos() as $tendenciaEscenarioDeportivos){
            array_push($tendencias, $tendenciaEscenarioDeportivos->getTendencia());
        }

        $this->addTendenciaEscenarioDeportivoForm($form, $tendencias);
    }


}
