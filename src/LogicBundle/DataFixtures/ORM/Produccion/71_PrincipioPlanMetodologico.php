<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\PrincipioPlanMetodologico;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesPrincipioPlanMetodologico extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 71;
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
            ["nombre" => "Fisico"],
            ["nombre" => "Técnico"],
            ["nombre" => "Táctico"],
            ["nombre" => "Teórico"]
        ];
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:PrincipioPlanMetodologico')->findOneBy(array('nombre' => $tipo['nombre']));
            if (!$registro) {
                $object = new PrincipioPlanMetodologico();
                $object->setNombre($tipo['nombre']);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
