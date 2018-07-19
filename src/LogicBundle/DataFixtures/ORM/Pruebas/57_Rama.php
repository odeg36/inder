<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Rama;

class FixturesRama extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 57;
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
        $ramas = json_decode('['
            . '{"nombre":"MASCULINA"},'
            . '{"nombre":"FEMENINA"},'
            . '{"nombre":"MIXTO"}'
            . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($ramas as $rama) {
            $registro = $em->getRepository('LogicBundle:Rama')->findOneBy(array('nombre' => $rama->{'nombre'}));
            if (!$registro) {
                $object = new Rama();
                $object->setNombre($rama->{'nombre'});               
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
