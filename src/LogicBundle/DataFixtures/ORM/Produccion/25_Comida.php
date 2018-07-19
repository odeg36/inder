<?php

namespace ITO\LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\Comida;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesComida extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        return 25;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            "Tragos", "Desayuno", "Media mañana", "Almuerzo", "Algo", "Comida", "Merienda"
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:Comida')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new Comida();
                $entity->setNombre($object);
                
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
