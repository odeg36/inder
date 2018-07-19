<?php

namespace AdminBundle\Form\EventListener\PlanAnualMetodologico;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddComponenteFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addComponenteForm($form, $planAnualMetodologico, $componente = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Componente',
            'placeholder' => '',
            'required' => true,
            "constraints" => $noVacio,
            'label' => 'formulario_plan_anual_metodologico.labels.paso_tres.componente',
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ],
            'query_builder' => function (EntityRepository $repository) use ($planAnualMetodologico) {
                $qb = $repository->createQueryBuilder('componente')
                        ->innerJoin('componente.planAnualMetodologico', 'planAnualMetodologico')
                        ->where('planAnualMetodologico.id = :planAnualMetodologico')
                        ->orderBy("componente.nombre", 'DESC')
                        ->setParameter('planAnualMetodologico', $planAnualMetodologico ?: 0)
                ;
                return $qb;
            }
        );
        if ($componente) {
            $formOptions['data'] = $componente;
        }

        $form->add('componente', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            $this->addComponenteForm($form, null);
            return;
        }

        $planAnualMetodologico = "";
        $componentes = method_exists($data, "getComponente") ? $data->getComponente() : null;

        if (count($componentes) > 0) {
            $planAnualMetodologico = ($componentes) ? $componentes->getPlanAnualMetodologico()->getId() : null;
        }

        $this->addComponenteForm($form, $planAnualMetodologico, $componentes); //$data->getComponente()->getPlanAnualMetodologico()
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            return;
        }

        $planAnualMetodologico = array_key_exists('planAnualMetodologico', $data) ? $data['planAnualMetodologico'] : null;
        $this->addComponenteForm($form, $planAnualMetodologico);
    }

}
