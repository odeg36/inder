<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Disciplina;
use LogicBundle\Entity\CategoriaDisciplina;

class FixturesDisciplinas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 5;
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
        
                        . '{"nombre":"BALONCESTO"},'
                        . '{"nombre":"BEISBOL"},'
                        . '{"nombre":"FÚTBOL"},'
                        . '{"nombre":"FÚTBOL DE SALÓN"},'
                        . '{"nombre":"RUGBY"},'
                        . '{"nombre":"ULTIMATE"},'
                        . '{"nombre":"VOLEIBOL"},'
                        . '{"nombre":"AJEDREZ"},'
                        . '{"nombre":"AGILITY"},'
                        . '{"nombre":"LUCHA OLÍMPICA"},'
                        . '{"nombre":"JUDO"},'
                        . '{"nombre":"TAEKWONDO"},'
                        . '{"nombre":"KARATE"},'
                        . '{"nombre":"BOXEO"},'
                        . '{"nombre":"CICLISMO"},'
                        . '{"nombre":"CICLOMONTAÑISMO"},'
                        . '{"nombre":"LEVANTAMIENTO DE PESAS"},'
                        . '{"nombre":"BICICROSS"},'
                        . '{"nombre":"ATLETISMO SALTO"},'
                        . '{"nombre":"ATLETISMO LANZAMIENTOS"},'
                        . '{"nombre":"ATLETISMO VELOCIDAD"},'
                        . '{"nombre":"ATLETISMO RESISTENCIA"},'
                        . '{"nombre":"SKATEBOARDING"},'
                        . '{"nombre":"TRIAL"},'
                        . '{"nombre":"SLACK LINE"},'
                        . '{"nombre":"PARKOUR"},'
                        . '{"nombre":"STREET WOURKOUT"},'
                        . '{"nombre":"ORIENTACIÓN DEPORTIVA"},'
                        . '{"nombre":"FREESTYLE FRISBEE"},'
                        . '{"nombre":"CAPOEIRA"},'
                        . '{"nombre":"FRESTYLE FOOTBALL"},'
                        . '{"nombre":"BMX FRESTYLE"},'
                        . '{"nombre":"TIRO CON ARCO"},'
                        . '{"nombre":"PORRISMO"},'
                        . '{"nombre":"KARATE Y TAEKWONDO (ARTE)"},'
                        . '{"nombre":"TEJO"},'
                        . '{"nombre":"ATLETISMO ADAPTADO"},'
                        . '{"nombre":"NATACIÓN ADAPTADA"},'
                        . '{"nombre":"BÁDMINTON"},'
                        . '{"nombre":"TENIS DE MESA"},'
                        . '{"nombre":"TENIS DE CAMPO"},'
                        . '{"nombre":"PATINAJE"}'
                        . ']');
        
                $categoria = new CategoriaDisciplina();
                $categoria->setNombre("Escenarios Deportivos");
                $manager->persist($categoria);
                foreach ($tipos as $tipo) {
                    $object = new Disciplina();            
                    $object->setNombre($tipo->{'nombre'});
                    $manager->persist($object);
                }
        
                $manager->flush();
    }

}
