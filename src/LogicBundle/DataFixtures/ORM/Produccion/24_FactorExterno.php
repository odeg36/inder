<?php

namespace ITO\LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\FactorExterno;

class FixturesFactorExterno extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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

    public function getOrder() {
        return 24;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            "Tensión nerviosa", "Enfermedad", "Lesión deportiva", "Competición", "Entrenamiento", "Proceso vacacional"
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:FactorExterno')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new FactorExterno();
                $entity->setNombre($object);
                
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
