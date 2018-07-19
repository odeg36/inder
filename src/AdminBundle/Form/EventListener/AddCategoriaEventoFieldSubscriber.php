<?php

namespace AdminBundle\Form\EventListener;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddCategoriaEventoFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addCategoriaForm($form, $categoria = null) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $formOptions = array(
            'class' => 'LogicBundle:CategoriaEvento',
            'placeholder' => '',
            'required'=> false,
            'mapped'=> false,
            "label" => "formulario_evento.labels.configuracion.categoria",
            'empty_data' => null,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off',
                'onchange' => 'inder.evento.subcategorias(this)',
            ]
        )
        ;

        if ($categoria) {
            $formOptions['data'] = $categoria;
        }

        $form->add('categorias', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            $this->addCategoriaForm($form, null);
            return;
        }
        $categoria = "";
        $categoria= $data;
        $this->addCategoriaForm($form, $categoria);
    }

}
