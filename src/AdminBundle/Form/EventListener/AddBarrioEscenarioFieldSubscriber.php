<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddBarrioEscenarioFieldSubscriber implements EventSubscriberInterface {

    
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
    
        private function addBarrioForm($form, $barrio) {
    
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
                'class' => 'LogicBundle:Barrio',
                'mapped' => false,
                'label_attr' => array('class' => 'label_escenario'),
                'placeholder' => '',
                'empty_data' => '',
                'constraints' => $noVacio,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                                    ->orderBy('m.nombre', 'ASC');
                },
                'attr' => array(
                    'class' => 'form-control',
                    'onchange' => 'inder.oferta.actualizarEscenario(this);'
                ),
            );
    
            if ($barrio) {
                $formOptions['data'] = $barrio;
            }
    
            $form->add('barrio', EntityType::class, $formOptions);
        }
    
        public function preSetData(FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();

        
            if (null === $data) {
                return;
            }
            if (method_exists($data, "getescenarioDeportivo")) {

                if(count($data->getescenarioDeportivo()) > 0  ){

                    $barrio = ($data->getescenarioDeportivo()) ? $data->getescenarioDeportivo()->getBarrio() : null;
                }else{

                    $barrio = "";    
                }

            } else {
                $barrio = "";
            }
            $this->addBarrioForm($form, $barrio);
        }
    
        public function preSubmit(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
    
            if (null === $data) {
                return;
            }
    
            $barrio = array_key_exists('barrio', $data) ? $data['barrio'] : null;
            $this->addBarrioForm($form, $barrio);
        }
    
}