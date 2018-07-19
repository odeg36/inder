<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDisciplinaEscenarioFieldSubscriber implements EventSubscriberInterface {
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
    
        private function addEscenarioForm($form, $escenario, $disciplina = null) {
    
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
                'class' => 'LogicBundle:Disciplina',
                'constraints' => $noVacio,
                'label_attr' => array('class' => 'label_barrio'),
                'attr' => array(
                    'class' => 'form-control',
                ),
                'query_builder' => function (EntityRepository $repository) use ($escenario) {
                    $qb = $repository->createQueryBuilder('disciplina')
                            ->innerJoin('disciplina.disciplinas', 'disciplinas')
                            ->innerJoin('disciplinas.escenarioDeportivo', 'escenarioDeportivo')
                            ->where('escenarioDeportivo.id = :escenario_deportivo')
                            ->orderBy("disciplina.nombre", 'DESC')
                            ->setParameter('escenario_deportivo', $escenario ?: 0)
                    ;

                    return $qb;
                },
                'mapped' => false,
            );
    
            if ($disciplina) {
                $formOptions['data'] = $disciplina;
            }
            $form->add("disciplina", EntityType::class, $formOptions);
        }
    
        public function preSetData(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            
            if (null === $data) {
                return;
            }
    
            

            $disciplina = method_exists($data, "getBarrio") ? $data->getBarrio() : null;

            if( count($disciplina)>0)
            {
                $escenario = ($disciplina) ? $disciplina->getMunicipio()->getId() : null;
            }else{
               $escenario = '';
               $disciplina = '';   
            }
            
            $this->addEscenarioForm($form, $escenario, $disciplina);
        }
    
        public function preSubmit(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
    
            if (null === $data) {
                return;
            }
    
            $escenario = array_key_exists('disciplina', $data) ? $data['disciplina'] : null;
            $this->addEscenarioForm($form, $escenario);
        }

}
