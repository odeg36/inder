<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoEnfoque;

class FixturesTipoEnfoque extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 61;
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
                . '{"nombre":"Institucional"},'
                . '{"nombre":"Eventos apoyados"},'
                . '{"nombre":"Evento de ciudad"}'
                . ']');
        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipos as $tipo) {

            $registro = $em->getRepository('LogicBundle:TipoEnfoque')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro){
                $object = new TipoEnfoque();            
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);    
            }
        }
        $manager->flush();
    }
}