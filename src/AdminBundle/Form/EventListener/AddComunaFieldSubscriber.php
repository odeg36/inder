<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddComunaFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;
    private $mapeado;

    public function __construct($es_requerido = "verdadero",$mapeado = false) {
        $this->es_requerido = $es_requerido;
        $this->mapeado = $mapeado;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addComunaForm($form, $comuna, $municipio = null) {
        if ($this->es_requerido == "verdadero") {
            $noVacio = array(
                new NotBlank(array(
                    'message' => 'formulario_registro.no_vacio',
                        ))
            );
        } else {
            $noVacio = array();
        }
        $formOptions = array(
            'required' => false,
            'class' => 'LogicBundle:Comuna',
            'mapped' => $this->mapeado,
            'label' => 'formulario.corregimiento',
            'label_attr' => array('class' => 'label_comuna'),
            'placeholder' => '',
            'empty_data' => '',
            'constraints' => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($municipio) {
                return $er->createQueryBuilder('c')
                                ->where('c.municipio = :municipio ')
                                ->orderBy('c.nombre', 'ASC')
                                ->setParameter('municipio', $municipio ?: 0)
                ;
            },
            'attr' => array(
                'class' => 'form-control comuna comuna-select',
                'onchange' => 'inder.oferta.actualizarComunaBarrios(this);'
            ),
        );

        if ($comuna) {
            $formOptions['data'] = $comuna;
        }

        $form->add('comuna', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $comuna = "";
        if (method_exists($data, "getBarrio")) {
            $comuna = ($data->getBarrio()) ? $data->getBarrio()->getComuna() : null;
        }
        if (method_exists($data, "getComuna")) {
            $comuna = ($data->getComuna()) ?: null;
        }
        $municipio = null;
        if (method_exists($data, "getMunicipio")) {
            $municipio = ($data->getMunicipio()) ? $data->getMunicipio()->getId() : null;
        }
        $this->addComunaForm($form, $comuna, $municipio);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $comuna = array_key_exists('comuna', $data) ? $data['comuna'] : null;
        $municipio = array_key_exists('municipio', $data) ? $data['municipio'] : null;
        $this->addComunaForm($form, $comuna, $municipio);
    }

}
