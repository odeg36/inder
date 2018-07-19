<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\ClasificacionDeporte;
use LogicBundle\Entity\Disciplina;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesClasificacionesDeportes extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 10;
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
        $string = file_get_contents(__DIR__ . "/../../../Resources/public/json/clasificaciones.json");
        $json_clasificaciones = json_decode($string, true);

        $i = 1;
        $em = $this->container->get('doctrine')->getManager();
        foreach ($json_clasificaciones as $clasificacion => $deportes) {
            $clasificacion_object = $em->getRepository('LogicBundle:ClasificacionDeporte')->findOneBy(array('nombre' => $clasificacion));
            if (!$clasificacion_object) {
                $clasificacion_object = new ClasificacionDeporte();
                $clasificacion_object->setNombre($clasificacion);
            }
            foreach ($deportes as $deporte) {
                $deporte = strtoupper($deporte);
                $deporte_object = $em->getRepository('LogicBundle:Disciplina')->findOneBy(array('nombre' => $deporte));
                if (!$deporte_object) {
                    $deporte_object = new Disciplina();
                    $deporte_object->setNombre($deporte);
                    $em->persist($deporte_object);
                    $manager->flush();
                }
                if ($clasificacion_object->getId() && $deporte_object) {
                    $sql = " 
                        SELECT disciplina_id
                          FROM clasificaciones__disciplinas
                          where clasificacion_deporte_id = " . $clasificacion_object->getId() . " 
                          and disciplina_id = " . $deporte_object->getId() . " 
                    ";

                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll();
                    if (count($resultado) < 1) {
                        $clasificacion_object->addDisciplina($deporte_object);
                    }
                }
            }
            $i ++;
            $em->persist($clasificacion_object);
        }
        $manager->flush();
    }

}
