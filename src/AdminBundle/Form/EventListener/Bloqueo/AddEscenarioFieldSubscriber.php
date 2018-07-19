<?php

namespace AdminBundle\Form\EventListener\Bloqueo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEscenarioFieldSubscriber implements EventSubscriberInterface {

    private $user = null;
    private $em = null;

    public function __construct($user, $em) {
        $this->user = $user;
        $this->em = $em;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addEscenarioForm($form, $escenario) {

        $user = $this->user;
        $formOptions = array(
            'required' => true,
            'constraints' => [new NotBlank()],
            'placeholder' => '',
            'class' => 'LogicBundle:EscenarioDeportivo',
            'attr' => array(
                'class' => 'form-control seleccion_escenario escenario_oferta',
                'onchange' => 'inder.oferta.obtenerDivisiones(this);'
            ),
            'query_builder' => function(EntityRepository $er) use ($user) {
                $query = $er->createQueryBuilder('e');
                if ($user->hasRole('ROLE_GESTOR_ESCENARIO')) {
                    $query->join("e.usuarioEscenarioDeportivos", "ued")
                            ->where("ued.usuario = :usuario")
                            ->setParameter("usuario", $user);
                }
                return $query;
            },
        );

        if ($escenario) {
            $formOptions['data'] = $escenario;
        }
        $form->add("escenarioDeportivo", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $escenarioDeportivo = method_exists($data, "getEscenarioDeportivo") ? $data->getEscenarioDeportivo() : null;

        $barrio = '';
        if ($escenarioDeportivo) {
            $barrio = ($escenarioDeportivo) ? $escenarioDeportivo->getBarrio()->getId() : null;
        }
        $this->addEscenarioForm($form, $escenarioDeportivo);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $escenario = array_key_exists('escenarioDeportivo', $data) ? $data['escenarioDeportivo'] : null;
        $this->addEscenarioForm($form, $escenario);
    }

}
