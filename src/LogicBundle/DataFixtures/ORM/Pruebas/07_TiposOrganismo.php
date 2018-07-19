<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoOrganismo;

class FixturesTipoOrganismo extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 7;
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

        $tipos = [
            ["nombre" => "Órgano de Administración", "tipoEntidades" => ["CPN"], "min" => 3, "max" => 5, "abreviatura" => "OA"],
            ["nombre" => "Órgano de Control", "tipoEntidades" => ["CPN"], "min" => 1, "max" => 1, "abreviatura" => "OC"],
            ["nombre" => "Comisión  disciplinaria", "tipoEntidades" => ["CPN", "CED"], "min" => 3, "max" => 3, "abreviatura" => "CD"],
            ["nombre" => "Representante Legal", "tipoEntidades" => ["CED"], "min" => 1, "max" => 1, "abreviatura" => "RL"],
            ["nombre" => "Administrador o Coordinador Deportivo", "tipoEntidades" => ["CED"], "min" => 1, "max" => 1, "abreviatura" => "ACD"]
        ];

        foreach ($tipos as $tipo) {
            $object = new TipoOrganismo();
            $object->setNombre($tipo['nombre']);
            $object->setMinimo($tipo['min']);
            $object->setMaximo($tipo['max']);
            $object->setAbreviatura($tipo['abreviatura']);

            foreach ($tipo["tipoEntidades"] as $key => $tipoEntidad) {
                $object->addTipoEntidade($this->getReference('tipo_entidad_' . $tipoEntidad));
            }

            $manager->persist($object);
        }
        $manager->flush();
    }

}
