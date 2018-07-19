<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoSangre;

class FixturesTipoSangre extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 1;
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
        $tipoSangre = json_decode('['
                . '{"nombre":"A Positiva"},'
                . '{"nombre":"A Negativo"},'
                . '{"nombre":"B Positivo"},'
                . '{"nombre":"B Negativo"},'
                . '{"nombre":"O Positivo"},'
                . '{"nombre":"O Negativo"},'
                . '{"nombre":"AB Positivo"},'
                . '{"nombre":"AB Negativo"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipoSangre as $tipo) {
            $registro = $em->getRepository('LogicBundle:TipoSangre')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            if (!$registro) {
                $object = new TipoSangre();
                $object->setNombre($tipo->{'nombre'});
                $object->setFechaCreacion(new \DateTime('now'));
                $object->setFechaActualizacion(new \DateTime('now'));                
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
