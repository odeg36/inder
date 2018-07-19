<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\TipoTarea;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesTipoTarea extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 73;
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
            ["nombre" => "Genericas"],
            ["nombre" => "Generales"],
            ["nombre" => "Especiales"],
            ["nombre" => "Dirigidas"],
            ["nombre" => "Competitivas"]
        ];
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:TipoTarea')->findOneBy(array('nombre' => $tipo['nombre']));
            if (!$registro) {
                $object = new TipoTarea();
                $object->setNombre($tipo['nombre']);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
