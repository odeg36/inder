<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEscenarioFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;
    private $mappeado;

    public function __construct($es_requerido = "verdadero", $mappeado = true) {
        $this->es_requerido = $es_requerido;
        $this->mappeado = $mappeado;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addEscenarioForm($form, $barrio, $escenarioDeportivo = null) {

        if ($this->es_requerido == "verdadero") {
            $noVacio = array(
                new NotBlank(array(
                    'message' => 'formulario_registro.no_vacio',
                        ))
            );
        } else {
            $noVacio = array();
        }

        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:EscenarioDeportivo',
            'constraints' => $noVacio,
            'label_attr' => array('class' => 'label_escenario'),
            'attr' => array(
                'class' => 'form-control',
                'onchange' => 'inder.reserva.validarMostrarDivInfo(this);'
            ),
            'query_builder' => function (EntityRepository $repository) use ($barrio) {
                $qb = $repository->createQueryBuilder('escenario_deportivo')
                        ->innerJoin('escenario_deportivo.barrio', 'barrio')
                        ->where('barrio.id = :barrio')
                        ->orderBy("escenario_deportivo.nombre", 'DESC')
                        ->setParameter('barrio', $barrio ?: 0)
                ;
                return $qb;
            },
            'mapped' => $this->mappeado,
            'attr' => array(
                'class' => 'form-control',
            ),
        );

        if ($escenarioDeportivo) {
            $formOptions['data'] = $escenarioDeportivo;
        }
        $form->add("escenarioDeportivo", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }



        $escenarioDeportivo = method_exists($data, "getEscenarioDeportivo") ? $data->getEscenarioDeportivo() : null;

        if ($escenarioDeportivo) {
            $barrio = ($escenarioDeportivo) ? $escenarioDeportivo->getBarrio()->getId() : null;
        } else {
            $barrio = '';
        }
        $this->addEscenarioForm($form, $barrio, $escenarioDeportivo);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $barrio = array_key_exists('barrio', $data) ? $data['barrio'] : null;
        $this->addEscenarioForm($form, $barrio);
    }

}
