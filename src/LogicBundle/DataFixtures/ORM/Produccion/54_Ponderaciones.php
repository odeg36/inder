<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\PlanAnualMetodologico;
use LogicBundle\Entity\Componente;
use LogicBundle\Entity\Contenido;
use LogicBundle\Entity\Actividad;
use LogicBundle\Entity\Enfoque;
use LogicBundle\Entity\Clasificacion;
use LogicBundle\Entity\Disciplina;
use LogicBundle\Entity\Nivel;
use LogicBundle\Entity\TipoTiempoEjecucion;

class FixturesPonderaciones extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 54;
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

        $guiasMetodologicas = $manager->getRepository('LogicBundle:PlanAnualMetodologico')
        ->createQueryBuilder('pam')
        ->getQuery()->getResult();
        foreach ($guiasMetodologicas as $guiaMetodologica) {
            $a = count($guiaMetodologica->getComponentes());
            foreach ($guiaMetodologica->getComponentes() as $componente) {
                $ponderacion = 100/$a;
                $ponderacion = number_format($ponderacion, 2);
                $componente->setPonderacion($ponderacion);
                $manager->persist($componente);
                $manager->flush();
            }
        }

        $componentes = $manager->getRepository('LogicBundle:Componente')
        ->createQueryBuilder('c')
        ->getQuery()->getResult();
        foreach ($componentes as $componente) {
            $b = count($componente->getContenidos());
            foreach ($componente->getContenidos() as $contenido) {
                $ponderacion = 100/$b;
                $ponderacion = number_format($ponderacion, 2);
                $contenido->setPonderacion($ponderacion);
                $manager->persist($contenido);
                $manager->flush();
            }
        }
        
    }
}