<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDivisionDisciplinaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addDivisionDisciplinaForm($form,  $escenarioDeportivo = null, $tipoReserva = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Disciplina',
            "required" => false,
            "mapped" => false,
            "constraints" => $noVacio,
            'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.disciplinas',
            'empty_data' => null,
            'multiple' => true,
            'query_builder' => function (EntityRepository $repository) use ($escenarioDeportivo) {
                if($escenarioDeportivo == null){
                    $qb = $repository->createQueryBuilder('disciplina')
                        ->innerJoin('disciplina.disciplinas', 'disciplinas')
                    ;
                }else{
                    $qb = $repository->createQueryBuilder('disciplina')
                        ->innerJoin('disciplina.disciplinas', 'disciplinas')
                        ->where('disciplinas.escenarioDeportivo = :escenario_deportivo')
                        ->setParameter('escenario_deportivo', $escenarioDeportivo)
                    ;
                }
                
                return $qb;
            },
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );
        if ($tipoReserva) {
            $formOptions['data'] = $tipoReserva;
        }
        
        $form->add('disciplina', EntityType::class, $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        
        $escenarioDeportivo = $data->getEscenarioDeportivo()->getId();
        $disciplinas = array();
        foreach($data->getDisciplinasEscenarioDeportivo() as $disciplinasEscenarioDeportivo){
            array_push($disciplinas, $disciplinasEscenarioDeportivo->getDisciplina());
        }

        $this->addDivisionDisciplinaForm($form, $escenarioDeportivo, $disciplinas);
    }


}
