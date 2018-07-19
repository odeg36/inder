<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Enfoque;

class FixturesEnfoque extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 31;
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
                . '{"nombre":"Técnico"},'
                . '{"nombre":"Social"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();

        foreach ($tipos as $tipo) {

            $registro = $em->getRepository('LogicBundle:Enfoque')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro){
                $object = new Enfoque();            
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);    
            }
            
        }

        $manager->flush();
    }

}