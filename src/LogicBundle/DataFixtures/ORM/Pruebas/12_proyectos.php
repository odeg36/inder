<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Proyecto;

class FixturesProyectos extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 12;
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
        $em = $this->container->get('doctrine')->getManager();
        $areas = $em->getRepository('LogicBundle:Area')->findAll();
        for ($index = 1; $index <= 30; $index++) {
            $object = new Proyecto();
            $object->setNombre("Proyecto " . $index);
            $object->setCodigo($index);
            $object->setDescripcion("Descripción proyecto " . $index);
            $object->setActivo(true);
            $object->setArea($areas[array_rand($areas)]);
            $manager->persist($object);
        }
        $manager->flush();
    }

}
