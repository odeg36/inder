<?php

namespace AdminBundle\Form\EventListener\Reserva;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDisciplinaEscenarioFieldSubscriber implements EventSubscriberInterface {
    
        
    
        public static function getSubscribedEvents() {
            return array(
                FormEvents::PRE_SET_DATA => 'preSetData',
            );
        }
    
        private function addEscenarioForm($form, $escenario, $disciplina = null) {
    
            $formOptions = array(
                'required' => false,
                'class' => 'LogicBundle:Disciplina',
                'label_attr' => array('class' => 'label_barrio'),
                'attr' => array(
                    'class' => 'form-control',
                ),
                'empty_data' => null,
                'query_builder' => function (EntityRepository $repository) use ($escenario) {
                    $qb = $repository->createQueryBuilder('disciplina')
                            ->where('disciplina.id = :id')
                            ->orderBy("disciplina.nombre", 'DESC')
                            ->setParameter('id', 0)
                    ;
                    
                    return $qb;
                },
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
                $this->addEscenarioForm($form,0,null);
                return;
            }
    
            $disciplina = method_exists($data, "getDisciplina") ? $data->getDisciplina() : null;
            $escenario = method_exists($data, "getEscenarioDeportivo") ? $data->getEscenarioDeportivo() : null;

            $this->addEscenarioForm($form,$escenario,$disciplina);

        }
    


}
