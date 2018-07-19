<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\Estrato;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesEstratos extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 6;
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
        for ($index = 1; $index <= 6; $index++) {
            $registro = $em->getRepository('LogicBundle:Estrato')->findOneBy(array('nombre' => $index));
            if (!$registro) {
                $object = new Estrato();
                $object->setNombre($index);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
