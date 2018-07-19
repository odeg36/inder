<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoDireccion;

class FixturesTipoDireccion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 34;
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
        $TipoDireccion = json_decode('['
                . '{"nombre":"Vereda"},'
                . '{"nombre":"Barrio"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        $registro = $em->getRepository('LogicBundle:TipoDireccion')->findOneBy(array('nombre' => 'Municipio'));
        if ($registro) {            
            $registro->setNombre('Vereda');
            $manager->persist($registro);
            $manager->flush();
        }

        $registro = $em->getRepository('LogicBundle:TipoDireccion')->findOneBy(array('nombre' => 'Corregimiento '));
        if ($registro) {            
            $registro->setNombre('Barrio');
            $manager->persist($registro);
            $manager->flush();
        }

        foreach ($TipoDireccion as $tipo) {
            $registro = $em->getRepository('LogicBundle:TipoDireccion')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            if (!$registro) {
                $object = new TipoDireccion();
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
