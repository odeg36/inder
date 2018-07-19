<?php

namespace ITO\LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\CategoriaTestControlFisioterapia;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesCategoriaTestControl extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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

    public function getOrder() {
        return 27;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            "Sentadilla profunda" => 'FMS',
            "Paso de barrera" => 'FMS',
            "Tijera en línea" => 'FMS',
            "Movilidad del hombre" => 'FMS',
            "Movilidad de pierna activo" => 'FMS',
            "Estabilidad de hombre" => 'FMS',
            "Estabilidad rotatorio" => 'FMS',
            "Resistentes flexores" => 'CORE',
            "Puente prono" => 'CORE',
            "Resistencia de extensores" => 'CORE',
            "Puente lateral" => 'CORE',
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $key => $object) {
            $registro = $em->getRepository('LogicBundle:CategoriaTestControlFisioterapia')->findOneBy(array('nombre' => $key));
            if (!$registro) {
                $entity = new CategoriaTestControlFisioterapia();
                $entity->setNombre($key);
                $entity->setTipo($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
