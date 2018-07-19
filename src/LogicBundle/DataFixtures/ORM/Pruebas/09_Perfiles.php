<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Perfil;

class FixturesPerfiles extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 9;
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
                . '{"nombre":"Presidente"},'
                . '{"nombre":"Vicepresidente"},'
                . '{"nombre":"Tesorero"},'
                . '{"nombre":"Secretario"},'
                . '{"nombre":"Vocal"},'
                . '{"nombre":"Revisor Fiscal"},'
                . '{"nombre":"Contador"},'
                . '{"nombre":"Comisión Disciplinaria"}'
                . ']');

        foreach ($tipos as $tipo) {
            $object = new Perfil();
            $object->setNombre($tipo->{'nombre'});
            $manager->persist($object);
        }
        $manager->flush();
    }

}
