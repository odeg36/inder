<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\OrientacionSexual;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesOrientacionesSexuales extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 7;
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
        $tipos = json_decode('['
                . '{"nombre":"Homosexual"},'
                . '{"nombre":"Bisexual"},'
                . '{"nombre":"Intersexual"}'
                . ']');

        foreach ($tipos as $tipo) {
            $object = new OrientacionSexual();
            $object->setNombre($tipo->{'nombre'});
            $manager->persist($object);
        }
        $manager->flush();
    }

}
