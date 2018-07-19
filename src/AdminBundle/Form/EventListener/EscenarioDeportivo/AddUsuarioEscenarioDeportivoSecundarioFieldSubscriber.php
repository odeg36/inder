<?php

namespace AdminBundle\Form\EventListener\EscenarioDeportivo;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUsuarioEscenarioDeportivoSecundarioFieldSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    private function addUsuarioEscenarioDeportivoSecundarioForm($form, $usuarios = null) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formOptions = array(
            'class' => 'ApplicationSonataUserBundle:User',
            'choice_label' => function ($usuario) {
                return $usuario->getFullnameIdentificacion();
            },
            'placeholder' => '',
            'label' => 'formulario_escenario_deportivo.labels.paso_dos.secundarios',
            'empty_data' => null,
            'multiple' => true,
            'mapped' => false,
            'attr' => [
                'class' => 'form-control', 'autocomplete' => 'off'
            ],
            'query_builder' => function (EntityRepository $repository) {
                $rol = 'ROLE_GESTOR_ESCENARIO';
                $qb = $repository->createQueryBuilder('u');
                $qb
                        ->leftJoin('u.groups', 'g')
                        ->where($qb->expr()->orX(
                                        $qb->expr()->like('u.roles', ':roles')
                                        , $qb->expr()->like('g.roles', ':roles')
                        ))
                        ->setParameter('roles', '%"' . $rol . '"%')
                ;
                return $qb;
            }
        );
        if ($usuarios) {
            $formOptions['data'] = $usuarios;
        }

        $form->add('usuario_secundario', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $usuarios = array();
        foreach ($data->getUsuarioEscenarioDeportivos() as $usuarioEscenarioDeportivo) {
            if (!$usuarioEscenarioDeportivo->getPrincipal()) {
                array_push($usuarios, $usuarioEscenarioDeportivo->getUsuario());
            }
        }

        $this->addUsuarioEscenarioDeportivoSecundarioForm($form, $usuarios);
    }

}
