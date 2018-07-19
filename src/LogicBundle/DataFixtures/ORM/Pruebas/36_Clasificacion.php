<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Clasificacion;

class FixturesClasificacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 36;
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
        $clasificaciones = json_decode('['
                . '{"nombre":"Disciplina"},'
                . '{"nombre":"Tendencia"},'
                . '{"nombre":"No Aplica"},'
                . '{"nombre":"Especial"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($clasificaciones as $clasificacion) {
            $registro = $em->getRepository('LogicBundle:Clasificacion')->findOneBy(array('nombre' => $clasificacion->{'nombre'}));
            if (!$registro) {
                $object = new Clasificacion();
                $object->setNombre($clasificacion->{'nombre'});               
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
