<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTipoReservaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addTipoReservaForm($form,  $escenarioDeportivo = null, $tipoReserva = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:TipoReserva',
            "required" => false,
            "mapped" => false,
            'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tipo_reserva',
            'empty_data' => null,
            'multiple' => false,
            'query_builder' => function (EntityRepository $repository) use ($escenarioDeportivo) {
                if($escenarioDeportivo  == null){
                    $qb = $repository->createQueryBuilder('tipoReserva')
                            ->innerJoin('tipoReserva.tipoReservaEscenarioDeportivos', 'tipoReservaEscenarioDeportivos')
                    ;
                } else{
                    $qb = $repository->createQueryBuilder('tipoReserva')
                    ->innerJoin('tipoReserva.tipoReservaEscenarioDeportivos', 'tipoReservaEscenarioDeportivos')
                    ->where('tipoReservaEscenarioDeportivos.escenarioDeportivo = :escenario_deportivo')
                    ->setParameter('escenario_deportivo', $escenarioDeportivo )
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
        
        $form->add('tipoReserva', EntityType::class, $formOptions);

    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $escenarioDeportivo = $data->getTipoReservaEscenarioDeportivo()->getEscenarioDeportivo()->getId();
        $tipoReserva = null;
        if($data->getTipoReservaEscenarioDeportivo()->getTipoReserva()){
            $tipoReserva = $data->getTipoReservaEscenarioDeportivo()->getTipoReserva();
        }

        $this->addTipoReservaForm($form, $escenarioDeportivo, $tipoReserva);
    }


}
