<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\Segmentacion;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesSegmentacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 55;
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
        $tipos = [
            ["nombre" => "Primera infancia", "edad_minima" => 0, "edad_maxima" => 5],
            ["nombre" => "Segunda infancia", "edad_minima" => 6, "edad_maxima" => 12],
            ["nombre" => "Adolescencia", "edad_minima" => 13, "edad_maxima" => 17],
            ["nombre" => "Juventud", "edad_minima" => 18, "edad_maxima" => 28],
            ["nombre" => "Adultos", "edad_minima" => 29, "edad_maxima" => 54],
            ["nombre" => "Adultos mayores", "edad_minima" => 55, "edad_maxima" => 150],
            ["nombre" => "Familia", "edad_minima" => 0, "edad_maxima" => 150],
            ["nombre" => "Escenario deportivos y recreativos", "edad_minima" => 0, "edad_maxima" => 150],
            ["nombre" => "Eventos de ciudad", "edad_minima" => 0, "edad_maxima" => 150],
            ["nombre" => "Planeación y presupuesto participativo", "edad_minima" => 0, "edad_maxima" => 150],
        ];
        foreach ($tipos as $tipo) {
            $registro = $em->getRepository('LogicBundle:Segmentacion')->findOneBy(array('nombre' => $tipo['nombre']));
            if (!$registro) {
                $object = new Segmentacion();
                $object->setNombre($tipo['nombre']);
                $object->setEdadMinima($tipo['edad_minima']);
                $object->setEdadMaxima($tipo['edad_maxima']);
                $object->setActivo(true);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }

}
