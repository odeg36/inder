<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoFalta;

class FixturesTipoFalta extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 30;
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

                . '{"nombre":"Agresión fisica"},'
                . '{"nombre":"Agresión verbal"},'
                . '{"nombre":"Expulsión"}'
                . ']');

        $i = 1;
        foreach ($tipos as $tipo) {
            $entity = $em->getRepository('LogicBundle:TipoFalta')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            if (!$entity) 
            {
                $object = new TipoFalta();            
                $object->setNombre($tipo->{'nombre'});
                $object->setDescripcion($tipo->{'nombre'});
                //$object->setCodigo($i);
                $manager->persist($object);
                $i++;
            }
        }

        $manager->flush();
    }

}
