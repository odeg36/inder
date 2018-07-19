<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoEscenario;

class FixturesTipoEscenario extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 28;
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
        $tipos = json_decode('['
                . '{"nombre":"AREA DE TIRO"},'
                . '{"nombre":"BIKE TRIAL"},'
                . '{"nombre":"BOLERA"},'
                . '{"nombre":"CANCHA BALONCESTO"},'
                . '{"nombre":"CANCHA DE HOCKEY"},'
                . '{"nombre":"CANCHA DE SQUASH"},'
                . '{"nombre":"CANCHA DE TENIS"},'
                . '{"nombre":"CANCHA DE VOLEIBOL"},'
                . '{"nombre":"CANCHA DE VOLEYPLAYA"},'
                . '{"nombre":"CANCHA EN ARENILLA"},'
                . '{"nombre":"CANCHA EN GRAMA NATURAL"},'
                . '{"nombre":"CANCHA MICROFÚTBOL"},'
                . '{"nombre":"CANCHA RUBGY"},'
                . '{"nombre":"CANCHA SINTETICA"},'
                . '{"nombre":"CENTRO DE PROMOCION DE LA SALUD"},'
                . '{"nombre":"CICLORUTA"},'
                . '{"nombre":"COLISEO"},'
                . '{"nombre":"DIAMANTE DE BEISBOL"},'
                . '{"nombre":"DIAMANTE DE SOFTBOL"},'
                . '{"nombre":"FOSO ARENA"},'
                . '{"nombre":"FUENTES INTERACTIVAS"},'
                . '{"nombre":"LUDOTEKA"},'
                . '{"nombre":"MURO DE ESCALAR"},'
                . '{"nombre":"NÚCLEOS RECREATIVOS"},'
                . '{"nombre":"PARQUE INFANTIL"},'
                . '{"nombre":"PISCINA"},'
                . '{"nombre":"PISTA ATLETISMO"},'
                . '{"nombre":"PISTA DE BICICROS"},'
                . '{"nombre":"PISTA DE PATINAJE"},'
                . '{"nombre":"PISTA DE SALTO LARGO"},'
                . '{"nombre":"PISTA DE SKATE"},'
                . '{"nombre":"PISTA DE TROTE"},'
                . '{"nombre":"PLACA POLIDEPORTIVA"},'
                . '{"nombre":"SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS"},'
                . '{"nombre":"VELODROMO"},'
                . '{"nombre":"ZONA AJEDREZ"},'
                . '{"nombre":"ZONA BARRAS"},'
                . '{"nombre":"ZONA DE AEROBICOS"},'
                . '{"nombre":"ZONA LANZAMIENTO BALONCESTO"},'
                . '{"nombre":"ZONA STREET WORKOUT"},'
                . '{"nombre":"ZONA TEJO"},'
                . '{"nombre":"CHORRITOS"},'
                . '{"nombre":"GIMNASIOS"},'
                . '{"nombre":"GIMNASIO AL AIRE LIBRE"},'
                . '{"nombre":"JUEGOS INFANTILES"}'
                . ']');

        $i = 2;
        foreach ($tipos as $tipo) {
            $entity = $em->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            if (!$entity) {
                $object = new TipoEscenario();
                $object->setNombre($tipo->{'nombre'});
                $object->setCodigo($i);
                $manager->persist($object);
            }
            $i++;
        }

        $manager->flush();
    }

}
