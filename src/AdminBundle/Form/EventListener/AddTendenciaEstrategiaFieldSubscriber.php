<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTendenciaEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addTendenciaForm($form, $estrategia_id, $tendencia = null) {
        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:TendenciaEstrategia',
            'placeholder' => 'seleccionar.opcion',
            'label' => 'formulario.oferta.tendencia',
            'label_attr' => array('class' => 'label_tendencia'),
            'mapped' => true,
            'attr' => array(
                'class' => 'selector_tendencia tendenciaEstrategia',
            ),
            'query_builder' => function (EntityRepository $repository) use ($estrategia_id) {
                $qb = $repository->createQueryBuilder('tendenciaEstrategia')
                        ->innerJoin('tendenciaEstrategia.estrategia', 'estrategia')
                        ->where('estrategia.id = :estrategia')
                        ->setParameter('estrategia', $estrategia_id ?: 0)
                ;
                return $qb;
            }
        );

        $form->add('tendenciaEstrategia', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            return;
        }

        $estrategia_id = ($data->getEstrategia()) ? $data->getEstrategia()->getId() : null;

        $this->addTendenciaForm($form, $estrategia_id);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        $estrategia_id = array_key_exists('estrategia', $data) ? $data['estrategia'] : null;
        $this->addTendenciaForm($form, $estrategia_id);
    }

}
