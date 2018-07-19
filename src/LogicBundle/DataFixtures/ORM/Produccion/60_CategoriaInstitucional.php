<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogicBundle\Entity\CategoriaInstitucional;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixturesCategoriaInstitucional extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 60;
    }

    /**
     * @var ContainerInterface
     */
    private $container;
    private $excel;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $excelObj = $this->container->get('phpexcel');
        $phpExcelObject = $excelObj->createPHPExcelObject(__DIR__ . "/../../../Resources/public/institucional.xlsx")->setActiveSheetIndex(0);

        $dataExcel = $phpExcelObject->toArray();

        unset($dataExcel[0]);

        $em = $this->container->get('doctrine')->getManager();
        foreach ($dataExcel as $data) {
            $nombreCategoria = $data[0];
            $registro = $em->getRepository('LogicBundle:CategoriaInstitucional')->findOneBy(array('nombre' => $nombreCategoria));
            if (!$registro) {
                $entity = new CategoriaInstitucional();
                $entity->setNombre($nombreCategoria);
                $manager->persist($entity);
            }
        }
        $manager->flush();
    }

}
