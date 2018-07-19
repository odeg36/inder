<?php

namespace AdminBundle\Form\EventListener\Reserva;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddMunicipioFieldSubscriber implements EventSubscriberInterface {



    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addMunicipioForm($form, $municipio) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $formOptions = array(
            'required' => true,
            'class' => 'LogicBundle:Municipio',
            'mapped' => false,
            'label_attr' => array('class' => 'label_municipio'),
            'placeholder' => '',
            'empty_data' => '',
            'constraints' => $noVacio,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('m')
                                ->orderBy('m.nombre', 'ASC');
            },
            'attr' => array(
                'class' => 'form-control',
                'onchange' => 'inder.reserva.actualizarBarriosEscenarioReserva(this);'
            ),
        );

        if ($municipio) {
            $formOptions['data'] = $municipio;
        }

        $form->add('municipio', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        if (method_exists($data, "getBarrio")) {

            $municipio = ($data->getBarrio()) ? $data->getBarrio()->getMunicipio() : null;

        } else {
            $municipio = "";
        }
        $this->addMunicipioForm($form, $municipio);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $municipio = array_key_exists('municipio', $data) ? $data['municipio'] : null;
        $this->addMunicipioForm($form, $municipio);
    }

}
