<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Dia;

class FixturesDias extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 8;
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
        $tipos = json_decode('['
                . '{"nombre":"Lunes", "numero":1},'
                . '{"nombre":"Martes", "numero":2},'
                . '{"nombre":"Miércoles", "numero":3},'
                . '{"nombre":"Jueves", "numero":4},'
                . '{"nombre":"Viernes", "numero":5},'
                . '{"nombre":"Sábado", "numero":6},'
                . '{"nombre":"Domingo", "numero":0}'
                . ']');
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:Dia')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            if (!$registro) {
                $object = new Dia();
                $object->setNombre($tipo->{'nombre'});
                $object->setNumero($tipo->{'numero'});
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
