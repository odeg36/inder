<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\CategoriaSetAndSearch;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesPruebaSetAndSearch extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 46;
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
            "Superior +27",
            "Excelente +20 a +30",
            "Bueno +6 a +20",
            "Promedio +1 a +6",
            "Deficiente -8 a 0",
            "Pobre -19 a -8",
            "Muy pobre -15 a -20",
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:CategoriaSetAndSearch')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new CategoriaSetAndSearch();
                $entity->setNombre($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
