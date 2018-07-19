<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddInstitucionalEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addInstitucionalForm($form, $estrategia_id, $institucional = null) {
        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:InstitucionalEstrategia',
            'placeholder' => 'seleccionar.opcion',
            'label' => 'formulario.oferta.institucional',
            'label_attr' => array('class' => 'label_disciplina'),
            'mapped' => true,
            'attr' => array(
                'class' => 'selector_institucional institucionalEstrategia',
            ),
            'query_builder' => function (EntityRepository $repository) use ($estrategia_id) {
                $qb = $repository->createQueryBuilder('institucionalEstrategia')
                        ->innerJoin('institucionalEstrategia.estrategia', 'estrategia')
                        ->where('estrategia.id = :estrategia')
                        ->setParameter('estrategia', $estrategia_id ?: 0)
                ;
                return $qb;
            }
        );

        $form->add('institucionalEstrategia', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            return;
        }

        $estrategia_id = ($data->getEstrategia()) ? $data->getEstrategia()->getId() : null;

        $this->addInstitucionalForm($form, $estrategia_id);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        $estrategia_id = array_key_exists('estrategia', $data) ? $data['estrategia'] : null;
        $this->addInstitucionalForm($form, $estrategia_id);
    }

}
