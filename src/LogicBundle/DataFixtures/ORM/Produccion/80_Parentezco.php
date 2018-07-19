<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\Parentezco;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesParentezco extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 80;
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
        $tipos = [
            ["nombre" => "Madre"],
            ["nombre" => "Padre"],
            ["nombre" => "Hijo"],
            ["nombre" => "Hija"],
            ["nombre" => "Tio"],
            ["nombre" => "Tia"],
            ["nombre" => "Abuelo"],
            ["nombre" => "Abuela"],
            ["nombre" => "Sobrino"],
            ["nombre" => "Sobrina"],
            ["nombre" => "Nieto"],
            ["nombre" => "Nieta"],
            ["nombre" => "Primo"],
            ["nombre" => "Prima"],
            ["nombre" => "Otro"]
        ];
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:Parentezco')->findOneBy(array('nombre' => $tipo['nombre']));
            if (!$registro) {
                $object = new Parentezco();
                $object->setNombre($tipo['nombre']);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
