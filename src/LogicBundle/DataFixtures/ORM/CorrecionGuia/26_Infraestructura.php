<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\Municipio;
use LogicBundle\Entity\Barrio;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Form\CargarMultiplesUsuariosType;
use ReflectionClass;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use LogicBundle\Entity\CategoriaInfraestructura;
use LogicBundle\Entity\SubcategoriaInfraestructura;
use LogicBundle\Entity\CampoInfraestructura;
use LogicBundle\Entity\OpcionCampoInfraestructura;
use LogicBundle\Entity\CategoriaDisciplina;
use LogicBundle\Entity\Disciplina;

class FixturesInfraestructura extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 26;
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

        $phpExcelObject = $excelObj->createPHPExcelObject(__DIR__ . "/../../../Resources/public/Infraestructura_copia.xlsx")->setActiveSheetIndex(0);

        // $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row[1]);
        //Escenarios deportivos  = Categoria Disiplina

        $em = $this->container->get('doctrine')->getManager();
        $categoriaDisciplinaObject = $em->getRepository('LogicBundle:CategoriaDisciplina')->findOneBy(array('nombre' => 'Escenarios deportivos'));
        if (!$categoriaDisciplinaObject) {
            $categoriaDisciplinaObject = new CategoriaDisciplina();
            $categoriaDisciplinaObject->setNombre('Escenarios deportivos');
            $manager->persist($categoriaDisciplinaObject);
            $manager->flush();
        }

        $dataExcel = $phpExcelObject->toArray();

        $datos = [];
        unset($dataExcel[0]);

        //Insertar campos Infraestructura
        foreach ($dataExcel as $data) {
            array_splice($data, 15);

            if (isset($data[5]) && isset($data[6])) {
                $nombreCampo = $data[5];
                $tipoEntrada = $data[6];

                $campoInfraestructuraQuery = $manager->getRepository('LogicBundle:CampoInfraestructura')->findBy(
                        array(
                            'nombre' => $nombreCampo
                ));

                if ($campoInfraestructuraQuery == null) {

                    $campo_object = new CampoInfraestructura();

                    $campo_object->setTipoEntrada($tipoEntrada);
                    $campo_object->setNombre($nombreCampo);

                    if ($tipoEntrada == 'Radio Button') {

                        if (isset($data[7])) {
                            $opcion_uno = new OpcionCampoInfraestructura();
                            $opcion_uno->setOpcion($data[7]);
                            $campo_object->addOpcionCampoInfraestructura($opcion_uno);
                        }

                        if (isset($data[8])) {
                            $opcion_dos = new OpcionCampoInfraestructura();
                            $opcion_dos->setOpcion($data[8]);
                            $campo_object->addOpcionCampoInfraestructura($opcion_dos);
                        }

                        if (isset($data[9])) {
                            $opcion_tres = new OpcionCampoInfraestructura();
                            $opcion_tres->setOpcion($data[9]);
                            $campo_object->addOpcionCampoInfraestructura($opcion_tres);
                        }

                        if (isset($data[10])) {
                            $opcion_cuatro = new OpcionCampoInfraestructura();
                            $opcion_cuatro->setOpcion($data[10]);
                            $campo_object->addOpcionCampoInfraestructura($opcion_cuatro);
                        }
                    }

                    $manager->persist($campo_object);
                    $manager->flush();
                }
            }
        }

        foreach ($dataExcel as $data) {
            array_splice($data, 15);

            if (isset($data[3])) {
                $categoriaNombre = $data[3];

                $categoriaConsulta = $manager->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(
                    array(
                        'nombre' => $categoriaNombre
                    ));

                if ($categoriaConsulta == null) {
                    $categoriaInfraestructura = new CategoriaInfraestructura;
                    $categoriaInfraestructura->setNombre($categoriaNombre);

                    $manager->persist($categoriaInfraestructura);
                    $manager->flush();
                }else{
                    $categoriaInfraestructura = $categoriaConsulta;
                }
            }

            if (isset($data[4])) {
                $subCategoriaNombre = $data[4];

                $subCategoriaConsulta = $manager->getRepository('LogicBundle:SubcategoriaInfraestructura')
                    ->createQueryBuilder('sub')             
                    ->where('sub.nombre = :nombreSubCategoria')
                    ->andWhere('sub.categoriaInfraestructura = :idCat')
                    ->setParameter('nombreSubCategoria', $subCategoriaNombre)
                    ->setParameter('idCat', $categoriaInfraestructura->getId())
                    ->getQuery()->getOneOrNullResult();

                if($subCategoriaConsulta == null){
                    $subcategoriaInfraestructura = new SubcategoriaInfraestructura;
                    $subcategoriaInfraestructura->setCategoriaInfraestructura($categoriaInfraestructura);
                    $subcategoriaInfraestructura->setNombre($subCategoriaNombre);

                    $manager->persist($subcategoriaInfraestructura);
                    $manager->flush();
                }else{
                    $subcategoriaInfraestructura = $subCategoriaConsulta;
                }
            }

            if (isset($data[5])) {
                $nombreCampo = $data[5];

                $campoInfraestructuraCreado = $manager->getRepository('LogicBundle:CampoInfraestructura')->findOneBy(
                    array(
                        'nombre' => $nombreCampo
                    ));

                $campoInfraestructuraQuery = $manager->getRepository('LogicBundle:CampoInfraestructura')
                    ->createQueryBuilder('campo')
                    ->innerJoin('campo.subInfraestructuraCampos', 'sub')                  
                    ->where('campo.id = :idCampo')
                    ->andWhere('sub.id = :idSub')
                    ->setParameter('idCampo', $campoInfraestructuraCreado->getId())
                    ->setParameter('idSub', $subcategoriaInfraestructura->getId())
                    ->getQuery()->getResult();

                if ($campoInfraestructuraQuery == null) {
                    $subcategoriaInfraestructura->addCampoInfraestructura($campoInfraestructuraCreado);
                    
                    $manager->persist($subcategoriaInfraestructura);
                    $manager->flush();
                }
            }
        }
    }
}
