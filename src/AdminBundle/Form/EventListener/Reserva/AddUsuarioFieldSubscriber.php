<?php

namespace AdminBundle\Form\EventListener\Reserva;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUsuarioFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addBarrioForm($form, $municipio, $barrio = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $municipio = 1;
        $formOptions = array(
            'required' => true, 
            'class' => 'LogicBundle:Barrio',
            'constraints' => $noVacio,
            'label_attr' => array('class' => 'label_barrio'),
            'attr' => array(
                'class' => 'form-control seleccion_barrio',
                'onchange' => 'inder.reserva.actualizarEscenario(this);'
            ),
            'query_builder' => function (EntityRepository $repository) use ($municipio) {
                $qb = $repository->createQueryBuilder('barrio')
                        ->innerJoin('barrio.municipio', 'municipio')
                        ->where('municipio.id = :municipio')
                        ->orderBy("barrio.nombre", 'DESC')
                        ->setParameter('municipio', $municipio ?: 0)
                ;
                return $qb;
            },
        );

        if ($barrio) {
            $formOptions['data'] = $barrio;
        }
        $form->add("barrio", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        
        if (null === $data) {
            return;
        }

        $barrio = method_exists($data, "getBarrio") ? $data->getBarrio() : null;
        $municipio = ($barrio) ? $barrio->getMunicipio()->getId() : null;
        $this->addBarrioForm($form, $municipio, $barrio);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $municipio = array_key_exists('municipio', $data) ? $data['municipio'] : null;
        $this->addBarrioForm($form, $municipio);
    }

}
