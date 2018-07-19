<?php

namespace ITO\LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\AyudaErgogenica;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesAyudaErgogenica extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        return 27;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            "Nutricionales", "Farmacológicas", "Fisiológicas", "Psicológicas", "Mecánicas"
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:AyudaErgogenica')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new AyudaErgogenica();
                $entity->setNombre($object);
                
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
