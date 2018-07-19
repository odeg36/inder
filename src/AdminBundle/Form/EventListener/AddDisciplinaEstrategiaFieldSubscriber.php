<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddDisciplinaEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addDisciplinaForm($form, $estrategia_id, $disciplina = null) {
        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:DisciplinaEstrategia',
            'placeholder' => 'seleccionar.opcion',
            'label' => 'formulario.oferta.disciplina',
            'label_attr' => array('class' => 'label_disciplina'),
            'mapped' => true,
            'attr' => array(
                'class' => 'selector_disciplina disciplinaEstrategia',
            ),
            'query_builder' => function (EntityRepository $repository) use ($estrategia_id) {
                $qb = $repository->createQueryBuilder('disciplinaEstrategia')
                        ->innerJoin('disciplinaEstrategia.estrategia', 'estrategia')
                        ->where('estrategia.id = :estrategia')
                        ->setParameter('estrategia', $estrategia_id ?: -1)
                ;
                return $qb;
            }
        );

        $form->add('disciplinaEstrategia', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            return;
        }

        $estrategia_id = ($data->getEstrategia()) ? $data->getEstrategia()->getId() : null;

        $this->addDisciplinaForm($form, $estrategia_id);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        $estrategia_id = array_key_exists('estrategia', $data) ? $data['estrategia'] : null;

        $this->addDisciplinaForm($form, $estrategia_id);
    }

}
