<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\SubDiscapacidad;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesSubDiscapacidad extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 14;
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
        $discapacidades = $em->getRepository('LogicBundle:Discapacidad')->findAll();
        for ($index = 1; $index <= 15; $index++) {
            $object = new SubDiscapacidad();
            $object->setNombre("SubDiscapacidad " . $index);
            $object->setDiscapacidad($discapacidades[array_rand($discapacidades)]);
            $manager->persist($object);
        }
        $manager->flush();
    }

}
