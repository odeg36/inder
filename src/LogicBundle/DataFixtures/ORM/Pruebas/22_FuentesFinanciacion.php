<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\FuenteFinanciacion;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesFuentesFinanciacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 22;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $campos = json_decode('['
                . '{"nombre":"PL Y PP"},'
                . '{"nombre":"Ordinario"}'
                . ']');
        foreach ($campos as $campo) {
            $object = new FuenteFinanciacion();
            $object->setNombre($campo->{'nombre'});
            $manager->persist($object);
        }
        $manager->flush();
    }

}
