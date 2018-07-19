<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Municipio;
use LogicBundle\Entity\Barrio;

class FixturesPaisDepartamentosMunicipiosZonas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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
    /*    $string = file_get_contents(__DIR__ . "/../../../Resources/public/municipiosBarrios.json");
        $json_departamentos = json_decode($string, true);

        foreach ($json_departamentos as $dato) {
            foreach ($dato['departamentos'] as $departamento) {
                foreach ($departamento['ciudades'] as $municipio) {
                    $municipio_object = new Municipio();
                    $municipio_object->setNombre($municipio['nombre']);
                    $manager->persist($municipio_object);
                    $manager->flush();
                    foreach ($municipio['barrios'] as $barrio) {
                        $barrio_object = new Barrio();
                        $barrio_object->setMunicipio($municipio_object);
                        $barrio_object->setNombre($barrio['contenido']);
                        $barrio_object->setHabilitado(TRUE);
                        $manager->persist($barrio_object);
                        $manager->flush();
                    }
                }
            }
        }*/
    }

}