<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\MovimientoPosicion;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesMovimientoPosicion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 42;
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

        $objects = [
            "Flexión, rotación y extensión del cuello",
            "Flexión y extensión del hombro",
            "Albudición y rotación dol hombro",
            "Flexión y extensión de codo. Supinación y pronación radio cubital",
            "Flexión y extensión de la muñeca",
            "Flexión y extensión de los dedos",
            "Flexión y extensión del tronco",
            "Flexión, albudición y rotación externa de la cadera",
            "Eversión e inversión del tobillo",
            "Flexión plantal y dorsal del tobillo",
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:MovimientoPosicion')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new MovimientoPosicion();
                $entity->setNombre($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
