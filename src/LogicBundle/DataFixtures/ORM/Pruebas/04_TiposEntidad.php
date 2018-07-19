<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoEntidad;

class FixturesTiposEntidad extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 4;
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
        $tipoIdentidades = json_decode('['
                . '{"nombre":"Clubes de entidades no deportivas", "abreviatura": "CED"},'
                . '{"nombre":"Club personas naturales", "abreviatura": "CPN"}'
                . ']');

        foreach ($tipoIdentidades as $tipoIdentidad) {
            $object = new TipoEntidad();
            $object->setNombre($tipoIdentidad->{'nombre'});
            $object->setAbreviatura($tipoIdentidad->{'abreviatura'});
            $manager->persist($object);

            $this->setReference('tipo_entidad_' . $tipoIdentidad->{'abreviatura'}, $object);
        }
        $manager->flush();
    }

}
