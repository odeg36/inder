<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDivisionTendenciaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addDivisionTendenciaForm($form,  $escenarioDeportivo = null, $tendencias = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Tendencia',
            "required" => false,
            "mapped" => false,
            "constraints" => $noVacio,
            'label' => 'formulario_escenario_deportivo.labels.paso_tres.division.tendencias',
            'empty_data' => null,
            'multiple' => true,
            'query_builder' => function (EntityRepository $repository) use ($escenarioDeportivo) {
                if($escenarioDeportivo == null){
                    $qb = $repository->createQueryBuilder('tendencia')
                        ->innerJoin('tendencia.tendencias', 'tendencias')
                    ;
                }else{
                    $qb = $repository->createQueryBuilder('tendencia')
                    ->innerJoin('tendencia.tendencias', 'tendencias')
                    ->where('tendencias.escenarioDeportivo = :escenario_deportivo')
                    ->setParameter('escenario_deportivo', $escenarioDeportivo)
                ;
                }
                
                return $qb;
            },
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
        
        $escenarioDeportivo = $data->getEscenarioDeportivo()->getId();
        $tendencias = array();
        foreach($data->getTendenciasEscenarioDeportivo() as $tendenciasEscenarioDeportivo){
            array_push($tendencias, $tendenciasEscenarioDeportivo->getTendencia());
        }

        $this->addDivisionTendenciaForm($form, $escenarioDeportivo, $tendencias);
    }


}
