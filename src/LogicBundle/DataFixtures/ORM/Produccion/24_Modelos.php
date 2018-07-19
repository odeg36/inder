<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Modelo;

class FixturesModelo extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 24;
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
                . '{"nombre":"LEGAL"},'
                . '{"nombre":"EQUITATIVA"},'
                . '{"nombre":"SOSTENIBLE"},'
                . '{"nombre":"SEGURA"}'
                . ']');
        $em = $this->container->get('doctrine')->getManager();

        foreach ($tipos as $tipo) {
           
            $registro = $em->getRepository('LogicBundle:Modelo')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro) 
            {
                $object = new Modelo();             
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);
            }
            
        }
        $manager->flush();
    }

}
