<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoIdentificacion;

class FixturesTiposIdentificacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 3;
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
                . '{"abreviatura":"CC" ,"nombre":"Cédula de Ciudadanía"},'
                . '{"abreviatura":"CE" ,"nombre":"Cédula de Extranjería"},'
                . '{"abreviatura":"NT" ,"nombre":"NIT"},'
                . '{"abreviatura":"PS" ,"nombre":"Pasaporte"},'
                . '{"abreviatura":"RC" ,"nombre":"Registro Civil"},'
                . '{"abreviatura":"NU" ,"nombre":"Numero unico de identificacion personal"},'
                . '{"abreviatura":"TI" ,"nombre":"Tarjeta de Identidad"}'
                . ']');

        $em = $this->container->get('doctrine')->getManager();
//        foreach ($tipos as $tipo) {
//            $registro = $em->getRepository('LogicBundle:TipoIdentificacion')->findOneBy(array('abreviatura' => $tipo->{'abreviatura'}));
//            if (!$registro) {
//                $object = new TipoIdentificacion();
//                $object->setAbreviatura($tipo->{'abreviatura'});
//                $object->setNombre($tipo->{'nombre'});
//                $manager->persist($object);
//            }
//        }
        $manager->flush();
    }

}
