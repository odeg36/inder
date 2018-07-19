<?php

namespace AdminBundle\Form\EventListener\Usuario;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddDiscapacidadFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addDiscapacidadForm($form, $discapacidad) {
        $formOptions = array(
            "class" => "LogicBundle:Discapacidad",
            "label" => "formulario.info_adicional.tipo_discapacidad",
            'label_attr' => array('class' => 'label_subDiscapacidades'),
            "required" => false,
            'mapped' => false,
            'attr' => [
                'onchange' => 'inder.preinscripcion.actualizarSubDiscapacidades(this);',
            ],
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                                ->orderBy('u.nombre', 'ASC');
            },
        );

        if ($discapacidad) {
            $formOptions['data'] = $discapacidad;
        }

        $form->add('discapacidad', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $discapacidad = ($data->getSubDiscapacidad()) ? $data->getSubDiscapacidad()->getDiscapacidad() : null;
        $this->addDiscapacidadForm($form, $discapacidad);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $discapacidad = array_key_exists('discapacidad', $data) ? $data['discapacidad'] : null;
        $this->addDiscapacidadForm($form, $discapacidad);
    }

}
