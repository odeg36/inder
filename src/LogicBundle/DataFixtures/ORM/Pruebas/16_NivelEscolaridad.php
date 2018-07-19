<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\NivelEscolaridad;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesNivelEscolaridad extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 16;
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
        for ($index = 1; $index <= 10; $index++) {
            $object = new NivelEscolaridad();
            $object->setNombre("Nivel de escolaridad " . $index);
            $manager->persist($object);
        }
        $manager->flush();
    }

}
