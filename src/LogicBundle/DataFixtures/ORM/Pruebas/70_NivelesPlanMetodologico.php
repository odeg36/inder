<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\NivelPlanMetodologico;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesNivelesPlanMetodologico extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 70;
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
            ["nombre" => "Iniciación"],
            ["nombre" => "Selección Medellín (SM)"],
            ["nombre" => "Team Medellín(TM)"],
        ];
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:NivelPlanMetodologico')->findOneBy(array('nombre' => $tipo['nombre']));
            if (!$registro) {
                $object = new NivelPlanMetodologico();
                $object->setNombre($tipo['nombre']);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
