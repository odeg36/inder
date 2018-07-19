<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\ClasificacionOrganizacion;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesClasificacionOrganizacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 30;
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
                . '{"nombre":"En desarrollo"},'
                . '{"nombre":"Nivel medio"},'
                . '{"nombre":"Nivel alto"}'
                . ']');

        $tiposAnteriores = json_decode('['
                            . '{"nombre":"Iniciación"},'
                            . '{"nombre":"Desarrollo"},'
                            . '{"nombre":"Reconocimiento"}'
                            . ']');

        $em = $this->container->get('doctrine')->getManager();
        $i = 0;
        foreach ($tiposAnteriores as $tipoAnterior) {
            $registroAnterior = $em->getRepository('LogicBundle:ClasificacionOrganizacion')->findOneBy(array('nombre' => $tipoAnterior->{'nombre'}));
            
            if ($registroAnterior)
            {
                $registroAnterior->setNombre($tipos[$i]->{'nombre'});
                $manager->persist($registroAnterior);
                $i++;
            }
        }

        foreach ($tipos as $tipo) {

            $registro = $em->getRepository('LogicBundle:ClasificacionOrganizacion')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro)
            {
                $object = new ClasificacionOrganizacion();
                $object->setNombre($tipo->{'nombre'});
                $manager->persist($object);
            }
            
        }
        $manager->flush();
    }

}