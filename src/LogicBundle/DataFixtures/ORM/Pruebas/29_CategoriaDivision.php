<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\CategoriaDivision;

class FixturesCategoriaDivision extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 29;
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

                . '{"nombre":"Categoria Division 2"},'
                . '{"nombre":"Categoria Division 1"}'

                . ']');

        $i = 1;
        foreach ($tipos as $tipo) {
            $object = new CategoriaDivision();            
            $object->setNombre($tipo->{'nombre'});
            //$object->setCodigo($i);
            $manager->persist($object);
            $i++;
        }

        $manager->flush();
    }

}
