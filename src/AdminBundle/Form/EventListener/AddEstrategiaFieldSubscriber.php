<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEstrategiaFieldSubscriber implements EventSubscriberInterface {
    private $mapped;
    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    function __construct($mapped=false) {
        $this->mapped = $mapped;
    }

    private function addEstrategiaForm($form, $proyecto_id, $estrategia = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Estrategia',
            'placeholder' => '',
            'label' => 'formulario.oferta.estrategia',
            'mapped' => $this->mapped,
            'label_attr' => array('class' => 'label_estrategia'),
            'attr' => array(
                'onchange' => 'inder.oferta.actualizarDisciplinas(this);',
                'class' => 'form-control estrategia-form',
            ),
            'constraints' => $noVacio,
            'query_builder' => function (EntityRepository $repository) use ($proyecto_id) {
                $qb = $repository->createQueryBuilder('estrategia')
                        ->innerJoin('estrategia.proyecto', 'proyecto')
                        ->where('proyecto.id = :proyecto')
                        ->setParameter('proyecto', $proyecto_id ?: 0)
                ;
                return $qb;
            }
        );

        if ($estrategia) {
            $formOptions['data'] = $estrategia;
        }

        $form->add('estrategia', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        $soloEstrategia = false;

        if (null === $data) {
            return;
        }

        $elementoCobertura = ($data->getDisciplinaEstrategia()) ? $data->getDisciplinaEstrategia() : null;
        if ($elementoCobertura == null) {
            $elementoCobertura = ($data->getTendenciaEstrategia()) ? $data->getTendenciaEstrategia() : null;
        }

        if ($elementoCobertura == null) {
            $soloEstrategia = true;
            $elementoCobertura = ($data->getEstrategia()) ? $data->getEstrategia() : null;
        }

        if ($soloEstrategia) {
            $estrategia = ($elementoCobertura) ? $elementoCobertura : null;
        } else {
            $estrategia = ($elementoCobertura) ? $elementoCobertura->getEstrategia() : null;
        }

        $proyecto_id = ($estrategia) ? $estrategia->getProyecto()->getId() : null;

        $this->addEstrategiaForm($form, $proyecto_id, $estrategia);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        $proyecto_id = array_key_exists('proyecto', $data) ? $data['proyecto'] : null;
        $this->addEstrategiaForm($form, $proyecto_id);
    }

}
