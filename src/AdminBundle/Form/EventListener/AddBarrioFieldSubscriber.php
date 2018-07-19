<?php

namespace AdminBundle\Form\EventListener;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddBarrioFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;
    private $mappeado;
    private $on_change;
    private $request;

    public function __construct($es_requerido = "verdadero", $mappeado = true, $onChange = 'inder.reserva.actualizarEscenario(this);', $request = null) {
        $this->es_requerido = $es_requerido;
        $this->mappeado = $mappeado;
        $this->on_change = $onChange;
        $this->request = $request;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addBarrioForm($form, $municipio = 0, $comuna = 0, $barrio = null) {

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
            'mapped' => $this->mappeado,
            'required' => false,
            'class' => 'LogicBundle:Barrio',
            'constraints' => $noVacio,
            'label_attr' => array('class' => 'label_barrio'),
            'attr' => array(
                'class' => 'form-control seleccion_barrio',
                'onchange' => $this->on_change,
            ),
            'query_builder' => function (EntityRepository $repository) use ($municipio, $comuna) {
                $vereda = false;
                $request = $this->request;
                if ($request) {
                    $parametros = $this->request->request;
                    foreach ($parametros->getIterator() as $key => $arrayParametros) {
                        if (is_array($arrayParametros) && array_key_exists('direccionOcomuna', $arrayParametros)) {
                            $vereda = $arrayParametros['direccionOcomuna'] == User::COMUNA;
                        }
                    }
                }
                $qb = $repository->createQueryBuilder('barrio');
                if ($municipio) {

                    $qb->innerJoin('barrio.municipio', 'municipio')
                            ->where('municipio.id = :municipio')
                            ->andWhere('barrio.habilitado = :habilitado')
                            ->setParameter('habilitado', true)
                            ->orderBy("barrio.nombre", 'ASC')
                            ->setParameter('municipio', $municipio ?: 0)
                    ;
                    if ($vereda) {
                        $qb->andWhere('barrio.esVereda = :es_vereda')
                                ->setParameter('es_vereda', true);
                    }
                    return $qb;
                } else if ($comuna) {
                    $qb->innerJoin('barrio.comuna', 'comuna')
                            ->where('comuna.id = :comuna')
                            ->andWhere('barrio.habilitado = :habilitado')
                            ->setParameter('habilitado', true)
                            ->orderBy("barrio.nombre", 'ASC')
                            ->setParameter('comuna', $comuna ?: 0)
                    ;
                    if ($vereda) {
                        $qb->andWhere('barrio.esVereda = :es_vereda')
                                ->setParameter('es_vereda', true);
                    }
                    return $qb;
                } else {
                    $qb->innerJoin('barrio.municipio', 'municipio')
                            ->where('municipio.id = :municipio')
                            ->andWhere('barrio.habilitado = :habilitado')
                            ->setParameter('habilitado', true)
                            ->orderBy("barrio.nombre", 'ASC')
                            ->setParameter('municipio', 0)
                    ;
                    if ($vereda) {
                        $qb->andWhere('barrio.esVereda = :es_vereda')
                                ->setParameter('es_vereda', true);
                    }
                    return $qb;
                }
            }
        );

        if ($barrio) {
            $formOptions['data'] = $barrio;
        }
        $form->add("barrio", EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        if (method_exists($data, "getBarrio") || (method_exists($data, "getBarrio") && $data->getBarrio())) {
            $barrio = $data->getBarrio();
        } else if (method_exists($data, "getPuntoAtencion") && $data->getPuntoAtencion()) {
            $barrio = $data->getPuntoAtencion()->getBarrio();
        } else if (method_exists($data, "getUsuario")) {
            $barrio = $data->getEscenarioDeportivo() ? $data->getEscenarioDeportivo()->getBarrio() : "";
        } else {
            $barrio = "";
        }

        $comuna = ($barrio && $barrio->getComuna()) ? $barrio->getComuna()->getId() : null;
        $municipio = ($barrio) ? $barrio->getMunicipio()->getId() : null;
        $this->addBarrioForm($form, $municipio, $comuna, $barrio);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $municipio = array_key_exists('municipio', $data) ? $data['municipio'] : null;
        $comuna = array_key_exists('comuna', $data) ? $data['comuna'] : null;
        $this->addBarrioForm($form, $municipio, $comuna);
    }

}
