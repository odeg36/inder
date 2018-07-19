<?php

namespace AdminBundle\Form\EventListener\Estrategia;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddProyectoEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addProyectoForm($form, $area_id) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Proyecto',
            'placeholder' => '',
            'label' => 'formulario.oferta.proyecto',
            'query_builder' => function (EntityRepository $repository) use ($area_id) {
                $qb = $repository->createQueryBuilder('proyecto')
                        ->innerJoin('proyecto.area', 'area')
                        ->where('area.id = :area')
                        ->setParameter('area', $area_id ?: 0)
                ;
                return $qb;
            },
            'attr' => array(
                'class' => 'seleccion_proyecto form-control',
            ),
        );
        $form->add('proyecto', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $proyecto = ($data->getProyecto()) ? $data->getProyecto() : null;
        $area = ($proyecto) ? $proyecto->getArea()->getId() : null;

        $this->addProyectoForm($form, $area);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        $area = array_key_exists('area', $data) ? $data['area'] : null;
        $this->addProyectoForm($form, $area);
    }

}
