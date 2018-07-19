<?php

namespace AdminBundle\Form\EventListener\PlanAnualMetodologico;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEstrategiaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addEstrategiaForm($form, $estrategia = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'LogicBundle:Estrategia',
            'placeholder' => '',
            'label' => 'formulario_plan_anual_metodologico.labels.paso_cuatro.estrategia',
            'required' => true,
            'empty_data' => null,
            'mapped' => false,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('e')
                                ->Where('e.activo = true');
            },
            //'data' => $options['estrategias'],
            'attr' => [
                'onchange' => 'inder.plan_anual_metodologico.actualizarOfertas(this);',
                'class' => 'form-control', 'autocomplete' => 'off'
            ]
        );

        if ($estrategia) {
            $formOptions['data'] = $estrategia;
        }

        $form->add('estrategia', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            $this->addEstrategiaForm($form, null);
            return;
        }

        $estrategia = "";
        $estrategia = $data;
        /* if (method_exists($data, "getOfertas")) {
          if(count($data->getEstrategias()) > 0){
          $estrategia = ($data->getOfertas()) ? $data->getOfertas()[0]->getEstrategia() : null;
          }
          } */

        $this->addEstrategiaForm($form, $estrategia);
    }

    /* public function preSubmit(FormEvent $event) {
      $form = $event->getForm();
      $data = $event->getData();

      if (null === $data) {
      return;
      }

      $estrategia = array_key_exists('estrategia', $data) ? $data['estrategia'] : null;
      $this->addEstrategiaForm($form, $estrategia);
      } */
}
