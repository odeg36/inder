<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoReserva;

class FixturesTiposReserva extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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
        $em = $this->container->get('doctrine')->getManager();
        
        $tipos = json_decode('['
                . '{"nombre":"Evento"},'
                . '{"nombre":"Oferta y Servicio"},'
                . '{"nombre":"Mantenimiento"},'
                . '{"nombre":"Eventos de Ciudad"},'
                . '{"nombre":"Organismo Deportivo"},'
                . '{"nombre":"Practica Libre"},'
                . '{"nombre":"Festivos"}'
                . ']');
        foreach ($tipos as $tipo) {

            $registro = $em->getRepository('LogicBundle:TipoReserva')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro) 
            {
                $object = new TipoReserva();
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);
            }
           
        }
        $manager->flush();
    }

}
