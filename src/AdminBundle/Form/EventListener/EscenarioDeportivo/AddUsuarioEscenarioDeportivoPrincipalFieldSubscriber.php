<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUsuarioEscenarioDeportivoPrincipalFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addUsuarioEscenarioDeportivoPrincipalForm($form, $usuario = null) {
        $noVacio = [
            new NotBlank([
                'message' => 'formulario_registro.no_vacio',
                    ])
        ];
        $formOptions = array(
            'data_class' => null,
            'class' => 'Application\Sonata\UserBundle\Entity\User',
            'label' => 'formulario.tipo.documento.acompanate',
            "required" => true,
            'mapped' => false,
            'attr' => [
                'class' => 'form-control gestor_principal',
                'autocomplete' => 'off',
                'placeholder' => '',
            ],
            'constraints' => $noVacio
        );
        if ($usuario) {
            $formOptions['data'] = $usuario;
        }

        $form->add('usuario_principal', AutocompleteType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $usuario = null;
        foreach ($data->getUsuarioEscenarioDeportivos() as $usuarioEscenarioDeportivo) {
            if ($usuarioEscenarioDeportivo->getPrincipal()) {
                $usuario = $usuarioEscenarioDeportivo->getUsuario();
            }
        }

        $this->addUsuarioEscenarioDeportivoPrincipalForm($form, $usuario);
    }

}
