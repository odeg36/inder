<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\CategoriaAmbiental;
use LogicBundle\Entity\SubcategoriaAmbiental;
use LogicBundle\Entity\CampoAmbiental;
use LogicBundle\Entity\OpcionCampoAmbiental;

class FixturesAmbiental extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 25;
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
        $phpExcelObject = $excelObj->createPHPExcelObject(__DIR__ . "/../../../Resources/public/inderambiental_datos.xlsx")->setActiveSheetIndex(0);
        $dataExcel = $phpExcelObject->toArray();
        $datos = [];
        unset($dataExcel[0]);

        //Insertar campos Ambiental
        foreach ($dataExcel as $data) {
            array_splice($data, 15);

            if (isset($data[4])) {
                $nombreCampo = $data[4];
                $tipoEntrada = $data[5];
                $campoAmbiental = $manager->getRepository('LogicBundle:CampoAmbiental')->findBy(
                        array(
                            'nombre' => $nombreCampo
                ));

                if ($campoAmbiental == null) {

                    $campo_object = new CampoAmbiental();

                    $campo_object->setTipoEntrada($tipoEntrada);
                    $campo_object->setNombre($nombreCampo);

                    if (isset($data[7])) {
                        $opcion_uno = new OpcionCampoAmbiental();
                        $opcion_uno->setOpcion($data[7]);
                        $campo_object->addOpcionesCampo($opcion_uno);
                    }

                    if (isset($data[8])) {
                        $opcion_dos = new OpcionCampoAmbiental();
                        $opcion_dos->setOpcion($data[8]);
                        $campo_object->addOpcionesCampo($opcion_dos);
                    }

                    if (isset($data[9])) {
                        $opcion_tres = new OpcionCampoAmbiental();
                        $opcion_tres->setOpcion($data[9]);
                        $campo_object->addOpcionesCampo($opcion_tres);
                    }

                    if (isset($data[10])) {
                        $opcion_cuatro = new OpcionCampoAmbiental();
                        $opcion_cuatro->setOpcion($data[10]);
                        $campo_object->addOpcionesCampo($opcion_cuatro);
                    }

                    $manager->persist($campo_object);
                    $manager->flush();
                }
            }
        }

        $nombreCategoriaAmbiental = null;
        $nombreSubCategoria = null;
        $categoriaAmbientalCurrent = new CategoriaAmbiental;
        $subcategoriaAmbientalCurrent = new SubcategoriaAmbiental;


        //Insertar Categoria ambiental
        foreach ($dataExcel as $data) {
            array_splice($data, 15);

            if (isset($data[3])) {

                if ($subcategoriaAmbientalCurrent != null) {
                    if ($nombreSubCategoria != null) {

                        $subcategoriaAmbientalCurrent->setNombre($nombreSubCategoria);


                        $categoriaAmbientalCurrent->addSubcategoriaAmbientale($subcategoriaAmbientalCurrent);
                        //Instaciar nuevamente
                        $subcategoriaAmbientalCurrent = new SubcategoriaAmbiental;
                    }
                }

                $categoriAmbiental = null;
                $siHizoQuery = false;
                if ($nombreCategoriaAmbiental != null) {
                    if ($nombreCategoriaAmbiental != $data[2]) {
                        $siHizoQuery = true;
                        $categoriAmbiental = $manager->getRepository('LogicBundle:CategoriaAmbiental')->findBy(
                                array(
                                    'nombre' => $nombreCategoriaAmbiental
                        ));
                    }
                } else {
                    $nombreCategoriaAmbiental = $data[2];
                }
                $nombreSubCategoria = $data[3];
                //Insertamos la categoria
                if ($categoriAmbiental == null && $siHizoQuery == true) {
                    if (isset($data[4])) {
                        $nombreCampo = $data[4];
                        $campoAmbientalQuery = $manager->getRepository('LogicBundle:CampoAmbiental')->findBy(
                                array(
                                    'nombre' => $nombreCampo
                        ));


                        if ($campoAmbientalQuery) {
                            $campoAmbiental = current($campoAmbientalQuery);
                            $subcategoriaAmbientalCurrent->addCampoAmbientale($campoAmbiental);
                        }
                    }

                    $categoriaAmbientalCurrent->setNombre($nombreCategoriaAmbiental);

                    $manager->persist($categoriaAmbientalCurrent);
                    $manager->flush();
                    //Instaciar nuevamente

                    $categoriaAmbientalCurrent = new CategoriaAmbiental;
                    $nombreCategoriaAmbiental = $data[2];
                } else {
                    if (isset($data[4])) {
                        $nombreCampo = $data[4];
                        $campoAmbientalQuery = $manager->getRepository('LogicBundle:CampoAmbiental')->findBy(
                                array(
                                    'nombre' => $nombreCampo
                        ));


                        if ($campoAmbientalQuery) {
                            $campoAmbiental = current($campoAmbientalQuery);
                            $subcategoriaAmbientalCurrent->addCampoAmbientale($campoAmbiental);
                        }
                    }
                }
            } else {
                $nombreCampo = $data[4];
                if ($nombreCampo != null) {

                    $campoAmbientalQuery = $manager->getRepository('LogicBundle:CampoAmbiental')->findBy(
                            array(
                                'nombre' => $nombreCampo
                    ));

                    if ($campoAmbientalQuery) {

                        $campoAmbiental = current($campoAmbientalQuery);

                        $subcategoriaAmbientalCurrent->addCampoAmbientale($campoAmbiental);
                    }
                }
            }
        }
    }

}
