<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\PresenciaDolor;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesPresenciaDelDolor extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 40;
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
            "Sin dolor",
            "Dolor leve",
            "Dolor moderado",
            "Dolor severo",
            "Dolor muy severo",
            "Máximo dolor"
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:PresenciaDolor')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new PresenciaDolor();
                $entity->setNombre($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
