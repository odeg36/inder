<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\CampoUsuario;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesCamposUsuario extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 21;
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
        $container = $this->container;
        $tiposCampos = $container->getParameter('tipos_campos');
        $campos = json_decode('['
                . '{"nombre":"Estatura","nombreMapeado":"estatura","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Peso","nombreMapeado":"peso","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Indice de masa corporal","nombreMapeado":"indiceMasaCorporal","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Fuma","nombreMapeado":"fuma","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Consume bebidas alcohólicas","nombreMapeado":"consumeBebidasAlcoholicas","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Padece enfermedades crónicas","nombreMapeado":"padeceEnfermedadesCronicas","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Consume medicamentos","nombreMapeado":"consumeMedicamentos","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Tipo de sangre y RH","nombreMapeado":"tipoSangre","tipo":"' . $tiposCampos['entidad'] . '"}'
                . ']');
        foreach ($campos as $campo) {
            $object = new CampoUsuario();
            $object->setNombre($campo->{'nombre'});
            $object->setNombreMapeado($campo->{'nombreMapeado'});
            $object->setTipo($campo->{'tipo'});
            $manager->persist($object);
        }
        $manager->flush();
    }

}
