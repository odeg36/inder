<?php

namespace AdminBundle\Form\EventListener\PlanAnualMetodologico;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddOfertaFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addOfertaForm($form, $estrategia, $oferta = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $formOptions = array(
            'required' => true,
            "constraints" => $noVacio,
            'class' => 'LogicBundle:Oferta',
            'multiple' => true,
            'label_attr' => array('class' => 'label_oferta'),
            'label' => 'formulario.oferta.title',
            'attr' => array(
                'class' => 'form-control', 'autocomplete' => 'off'
            ),
            /*'query_builder' => function (EntityRepository $repository) use ($estrategia) {
                $qb = $repository->createQueryBuilder('oferta')
                        ->innerJoin('oferta.estrategia', 'estrategia')
                        ->where('estrategia.id = :estrategia')
                        ->andWhere('oferta.planAnualMetodologico = :plan')
                        ->orderBy("oferta.nombre", 'DESC')
                        ->setParameters(array(
                            'estrategia' => $estrategia ?: 0,
                            'plan' => null
                        ))
                ;
                return $qb;
            }*/
        );

        if ($oferta) {
            $formOptions['data'] = $oferta;
        }

        $form->add('ofertas', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            $this->addOfertaForm($form, null);
            return;
        }
        $estrategia = "";
        $oferta = method_exists($data, "getOfertas") ? $data->getOfertas() : null;
        if(count($oferta) > 0){
            $estrategia = ($oferta) ? $oferta[0]->getEstrategia()->getId() : null;
        }
        
        $this->addOfertaForm($form, $estrategia, $oferta);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            return;
        }

        $estrategia = array_key_exists('estrategia', $data) ? $data['estrategia'] : null;
        $this->addOfertaForm($form, $estrategia);
    }

}
