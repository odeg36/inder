<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddComunaFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;

    public function __construct($es_requerido = "verdadero") {
        $this->es_requerido = $es_requerido;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addComunaForm($form, $comuna) {

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
            'class' => 'LogicBundle:Comuna',
            'mapped' => false,
            'label' => 'formulario.corregimiento_escenario',
            'label_attr' => array('class' => 'label_municipio'),
            'placeholder' => '',
            'empty_data' => '',
            'constraints' => $noVacio,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'attr' => array(
                'class' => 'form-control comuna',
                'onchange' => 'inder.escenarioDeportivo.actualizarComunaBarrios(this);'
            ),
        );

        if ($comuna) {
            $formOptions['data'] = $comuna;
        }

        $form->add('comuna', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        
        if (method_exists($data, "getBarrio")) {
            $comuna = ($data->getBarrio()) ? $data->getBarrio()->getComuna() : null;
        } else {
            $comuna = "";
        }
        
        $this->addComunaForm($form, $comuna);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $comuna = array_key_exists('comuna', $data) ? $data['comuna'] : null;
        $this->addComunaForm($form, $comuna);
    }

}
