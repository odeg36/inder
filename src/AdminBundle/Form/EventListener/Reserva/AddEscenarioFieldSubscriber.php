<?php

namespace AdminBundle\Form\EventListener\Reserva;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEscenarioFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addEscenarioForm($form, $barrio, $escenario = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:EscenarioDeportivo',
            'placeholder'=> 'Seleccione un escenario',
            'constraints' => $noVacio,
            'label_attr' => array('class' => 'label_escenario'),
            'query_builder' => function (EntityRepository $repository) use ($barrio) {
                $qb = $repository->createQueryBuilder('escenario_deportivo');
                if ($barrio != null && $barrio != "") {
                    $qb->innerJoin('escenario_deportivo.barrio', 'barrio')
                            ->where('barrio.id = :barrio')
                            ->setParameter('barrio', $barrio);
                }
                $qb->orderBy("escenario_deportivo.nombre", 'ASC');
                return $qb;
            },
            'attr' => array(
                'class' => 'form-control',
                'onchange' => 'inder.reserva.validarMostrarDivInfo(this, true);'
            ),
        );

        if ($escenario) {
            $formOptions['data'] = $escenario;
        }
        $form->add("escenario_deportivo", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $barrio = '';
        $escenario_deportivo = '';
        $escenario_deportivo = method_exists($data, "getEscenarioDeportivo") ? $data->getEscenarioDeportivo() : null;
        if ($escenario_deportivo) {
            $barrio = ($escenario_deportivo) ? $escenario_deportivo->getBarrio()->getId() : null;
        }
        $this->addEscenarioForm($form, $barrio, $escenario_deportivo);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $municipio = array_key_exists('barrio', $data) ? $data['barrio'] : null;
        $this->addEscenarioForm($form, $municipio);
    }

}
