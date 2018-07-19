<?php

namespace AdminBundle\Form\EventListener\Bloqueo;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotNull;

class AddDivisionFieldSubscriber implements EventSubscriberInterface {

    public $em;

    function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addDivisionForm($form, $escenario = 0, $divisiones = []) {

        $formOptions = [
            'required' => true,
            'constraints' => new NotNull(),
            'mapped' => false,
            'class' => 'LogicBundle:Division',
            'label_attr' => array('class' => 'label_division'),
            'attr' => array(
                'class' => 'form-control seleccion_division',
            ),
            'placeholder' => '',
            'multiple' => true,
            'query_builder' => function (EntityRepository $repository) use ($escenario) {
                $escenario = $escenario ?: 0;
                $qb = $repository->createQueryBuilder('d');
                $qb->where('d.escenarioDeportivo = :escenario')
                        ->orderBy("d.nombre", 'ASC')
                        ->setParameter('escenario', $escenario)
                ;
                return $qb;
            }
        ];
        if (count($divisiones) > 0) {
            $formOptions['data'] = $divisiones;
        }
        $form->add("division", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $divisiones = [];
        $division = null;
        if (method_exists($data, "getDivisiones") && $data->getDivisiones()) {
            foreach ($data->getDivisiones() as $ofertaDivision) {
                $divisiones[] = $ofertaDivision->getDivision();
                $division = $ofertaDivision->getDivision();
            }
        }

        $escenario = ($division) ? $division->getEscenarioDeportivo()->getId() : null;
        $this->addDivisionForm($form, $escenario, $divisiones);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $divisiones = [];
        if (array_key_exists('division', $data) && count($data["division"]) > 0) {
            foreach ($data['division'] as $div) {
                $division = $this->em->getRepository('LogicBundle:Division')->findById($div);
                $divisiones[] = $division[0];
            }
        }
        $escenario = array_key_exists('escenarioDeportivo', $data) ? $data['escenarioDeportivo'] : null;
        $this->addDivisionForm($form, $escenario, $divisiones);
    }

}
