<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\CategoriaSentadillaArranque;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesPruebaSentadillaArranque extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 46;
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

        $objects = [
            "Punta metartastal",
            "Pie pronado",
            "Pie supinado",
            "Rodilla valgo",
            "Rodilla varo",
            "Brazos adelantados",
            "Tronco hacia anteriorizado",
            "Alineación lumbopelvica",
            "Aplanamiento lumbar",
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('LogicBundle:CategoriaSentadillaArranque')->findOneBy(array('nombre' => $object));
            if (!$registro) {
                $entity = new CategoriaSentadillaArranque();
                $entity->setNombre($object);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
