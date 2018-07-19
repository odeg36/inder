<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\ClasificacionDeporte;

class FixturesClasificacionDeporte extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 56;
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
        $clasificacionesDeporte = json_decode('['
            . '{"nombre":"ADRENALINA"},'
            . '{"nombre":"ARTE Y PRECISIÓN"},'
            . '{"nombre":"COMBATE"},'
            . '{"nombre":"COOPERACIÓN OPOSICIÓN"},'
            . '{"nombre":"OPOSICIÓN"},'
            . '{"nombre":"TIEMPO Y MARCA"}'            
            . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($clasificacionesDeporte as $clasificacionDeporte) {
            $registro = $em->getRepository('LogicBundle:ClasificacionDeporte')->findOneBy(array('nombre' => $clasificacionDeporte->{'nombre'}));
            if (!$registro) {
                $object = new ClasificacionDeporte();
                $object->setNombre($clasificacionDeporte->{'nombre'});               
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
