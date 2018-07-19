<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Nivel;

class FixturesNivelesDisciplinas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 39;
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
        $niveles = json_decode('['
                . '{"nombre":"BASICO"},'
                . '{"nombre":"INTERMEDIO"},'
                . '{"nombre":"AVANZADO"},'
                . '{"nombre":"BLANCO"},'
                . '{"nombre":"AMARILLO"},'
                . '{"nombre":"NARANJADO"},'
                . '{"nombre":"VERDE"},'
                . '{"nombre":"AZUL"},'
                . '{"nombre":"PURPURA"},'
                . '{"nombre":"ROJO"},'
                . '{"nombre":"ROJO-MARRON"},'
                . '{"nombre":"MARRON"},'
                . '{"nombre":"MARRON-NEGRO"},'
                . '{"nombre":"N-I USAG"},'
                . '{"nombre":"N-II USAG"},'
                . '{"nombre":"N-III USAG"},'
                . '{"nombre":"N-IV USAG"},'
                . '{"nombre":"N-V USAG"},'
                . '{"nombre":"COMPETENCIAS"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($niveles as $nivel) {
            $registro = $em->getRepository('LogicBundle:Nivel')->findOneBy(array('nombre' => $nivel->{'nombre'}));
            if (!$registro) {
                $object = new Nivel();
                $object->setNombre($nivel->{'nombre'});               
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
