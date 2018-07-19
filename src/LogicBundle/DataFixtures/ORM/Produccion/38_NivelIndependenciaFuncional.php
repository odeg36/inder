<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\NivelIndependenciaFuncional;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesNivelIndependenciaFuncional extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 30;
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
            "F",
            "PC",
            "C",
            "V",
            "A"
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:NivelIndependenciaFuncional')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new NivelIndependenciaFuncional();
                $entity->setNombre($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
