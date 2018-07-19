<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddProyectoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addProyectoForm($form, $area_id, $proyecto = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Proyecto',
            'placeholder' => '',
            'label' => 'formulario.oferta.proyecto',
            'label_attr' => array('class' => 'label_proyecto'),
            'query_builder' => function (EntityRepository $repository) use ($area_id) {
                $area_id = $area_id ?: 0;
                $qb = $repository->createQueryBuilder('proyecto')
                        ->innerJoin('proyecto.area', 'area')
                        ->where('area.id = :area')
                        ->setParameter('area', $area_id ?: 0)
                ;
                return $qb;
            },
            'mapped' => false,
            'constraints' => $noVacio,
            'attr' => array(
                'onchange' => 'inder.oferta.actualizarEstrategias(this);',
                'class' => 'form-control',
            ),
        );
        if ($proyecto) {
            $formOptions['data'] = $proyecto;
        }
        $form->add('proyecto', EntityType::class, $formOptions);
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
            $proyecto = ($elementoCobertura) ? $elementoCobertura->getProyecto() : null;
        } else {
            $proyecto = ($elementoCobertura) ? $elementoCobertura->getEstrategia()->getProyecto() : null;
        }

        $area_id = ($proyecto) ? $proyecto->getArea()->getId() : null;

        $this->addProyectoForm($form, $area_id, $proyecto);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        $area_id = array_key_exists('area', $data) ? $data['area'] : null;
        $this->addProyectoForm($form, $area_id);
    }

}
