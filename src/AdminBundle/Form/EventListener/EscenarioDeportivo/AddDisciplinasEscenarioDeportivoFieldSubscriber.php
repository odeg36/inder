<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDisciplinasEscenarioDeportivoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addDisciplinasEscenarioDeportivoForm($form,  $disciplinas = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Disciplina',
            'placeholder' => '',
            'mapped' => false,
            'multiple' => true,
            'label' => 'formulario_escenario_deportivo.labels.paso_dos.disciplina',
            'empty_data' => null,
            'required' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );
        if ($disciplinas) {
            $formOptions['data'] = $disciplinas;
        }
        
        $form->add('disciplina', EntityType::class, $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $disciplinas = array();
        foreach($data->getDisciplinasEscenarioDeportivos() as $disciplinasEscenarioDeportivos){
            array_push($disciplinas, $disciplinasEscenarioDeportivos->getDisciplina());
        }

        $this->addDisciplinasEscenarioDeportivoForm($form, $disciplinas);
    }


}
