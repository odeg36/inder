<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\VariableGlobal;

class FixturesVariableGlobal extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 3;
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
        $variableGlobal = json_decode('['
                . '{"nombre":"Bloqueo Reserva"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($variableGlobal as $variable) {
            $registro = $em->getRepository('LogicBundle:VariableGlobal')->findOneBy(array('nombre' => $variable->{'nombre'}));
            if (!$registro) {
                $object = new VariableGlobal();
                $object->setNombre($variable->{'nombre'});
                $object->setDato1(new \DateTime('now'));
                $object->setDato2(new \DateTime('now'));                
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
