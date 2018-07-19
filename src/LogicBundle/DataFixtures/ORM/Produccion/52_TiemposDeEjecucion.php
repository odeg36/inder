<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoTiempoEjecucion;

class FixturesTiemposEjecucion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 52;
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
    
            $tiempos = json_decode('['
                    . '{"nombre":"Sesiones"},'
                    . '{"nombre":"Transversal"}'
                    . ']');
    
            foreach ($tiempos as $tiempo) {
    
                $registro = $em->getRepository('LogicBundle:TipoTiempoEjecucion')->findOneBy(array('nombre' => $tiempo->{'nombre'}));
                
                if (!$registro) {
                    $object = new TipoTiempoEjecucion();            
                    $object->setNombre($tiempo->{'nombre'});
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
    }
}
