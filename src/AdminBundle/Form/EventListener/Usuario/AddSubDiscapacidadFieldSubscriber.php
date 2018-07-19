<?php

namespace AdminBundle\Form\EventListener\Usuario;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddSubDiscapacidadFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addSubDiscapacidadForm($form, $discapacidad) {

        $formOptions = array(
            "class" => "LogicBundle:SubDiscapacidad",
            "label" => "formulario.info_adicional.sub_discapacidad",
            "required" => false,
            'query_builder' => function (EntityRepository $repository) use ($discapacidad) {
                $discapacidad = $discapacidad ? $discapacidad : 0;
                $qb = $repository->createQueryBuilder('subDiscapacidad')
                        ->innerJoin('subDiscapacidad.discapacidad', 'discapacidad')
                        ->where('discapacidad.id = :discapacidad')
                        ->orderBy("subDiscapacidad.nombre", 'DESC')
                        ->setParameter('discapacidad', $discapacidad)
                ;
                return $qb;
            },
            'label_attr' => [
                'class' => 'label_subDiscapacidades',
            ]
        );
        $form->add('subDiscapacidad', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $discapacidad = ($data->getSubDiscapacidad()) ? $data->getSubDiscapacidad()->getDiscapacidad() : null;
        $this->addSubDiscapacidadForm($form, $discapacidad);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $discapacidad = array_key_exists('discapacidad', $data) ? $data['discapacidad'] : null;
        $this->addSubDiscapacidadForm($form, $discapacidad);
    }

}
