<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoEntidad;

class FixturesTiposEntidad extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 4;
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
        $tipoIdentidades = json_decode('['
                . '{"nombre":"Clubes de entidades no deportivas", "abreviatura": "CED"},'
                . '{"nombre":"Otras organizaciones legalmente constituidas", "abreviatura": "END"},'
                . '{"nombre":"Club personas naturales", "abreviatura": "CPN"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
        foreach ($tipoIdentidades as $tipoIdentidad) {
            $registro = $em->getRepository('LogicBundle:TipoEntidad')->findOneBy(array('abreviatura' => $tipoIdentidad->{'abreviatura'}));
            if (!$registro) {
                $object = new TipoEntidad();
                $object->setNombre($tipoIdentidad->{'nombre'});
                $object->setAbreviatura($tipoIdentidad->{'abreviatura'});
                $manager->persist($object);

                $this->setReference('tipo_entidad_' . $tipoIdentidad->{'abreviatura'}, $object);
            } else {
                $this->setReference('tipo_entidad_' . $tipoIdentidad->{'abreviatura'}, $registro);
            }
        }
        $manager->flush();
    }

}
