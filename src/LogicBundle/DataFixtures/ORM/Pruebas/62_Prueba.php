<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

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

use LogicBundle\Entity\ClasificacionDeporte;
use LogicBundle\Entity\Disciplina;
use LogicBundle\Entity\Rama;
use LogicBundle\Entity\Prueba;
use LogicBundle\Entity\DisciplinaPruebaRama;


class FixturesPrueba extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 62;
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
        ini_set('memory_limit', '2048M');

        $em = $this->container->get('doctrine')->getManager();

        $excelObj= $this->container->get('phpexcel');     
      
        $phpExcelObject = $excelObj->createPHPExcelObject(__DIR__ ."/../../../Resources/public/SISTEMA-DE-MEDICIÓN revisado.xlsx")->setActiveSheetIndex(0);
        $dataExcel = $phpExcelObject->toArray();        
        $datos = [];
        unset($dataExcel[0]);

        //Insertar campos Infraestructura
        foreach ($dataExcel as $data) {
            array_splice($data,8);
            //ClasificacionDeporte
            $clasificacionDeporte = $em->getRepository('LogicBundle:ClasificacionDeporte')->findOneBy(array('nombre' => $data[0]));
            if (!$clasificacionDeporte && $data[0] != null) 
            {
                $clasificacionDeporte = new ClasificacionDeporte();
                $clasificacionDeporte->setNombre($data[0]);
                $manager->persist($clasificacionDeporte);
                $manager->flush();
                
            }

            //Disciplina
            $disciplina = $em->getRepository('LogicBundle:Disciplina')->findOneBy(array('nombre' => $data[1]));
            if (!$disciplina && $data[1] != null) 
            {
                $disciplina = new Disciplina();
                $disciplina->setNombre($data[1]);
                $manager->persist($disciplina);
                $manager->flush();            
            }


            //Agregar ClasificacionDeporte a una Disciplina
            if($disciplina && !$disciplina->getClasificaciones()->contains($clasificacionDeporte) ){
                $disciplina->addClasificacione($clasificacionDeporte);
                $manager->persist($disciplina);
                $manager->flush();            
            }
            
            $ramas = array();
            $ramasNombre = explode(",",$data[2]);
            //Agregar Ramas
            foreach($ramasNombre as $ram){
                $rama = $em->getRepository('LogicBundle:Rama')->findOneBy(array('nombre' => $ram));
                if (!$rama && $ram != null) 
                {
                    $rama = new Rama();
                    $rama->setNombre($ram);
                    $manager->persist($rama);
                    $manager->flush();            
                }
                array_push($ramas, $rama);
            }

            //Agregar Prueba
            $prueba = $em->getRepository('LogicBundle:Prueba')->findOneBy(array('nombre' => $data[3]));
            if (!$prueba && $data[3] != null) 
            {
                $prueba = new Prueba();
                $prueba->setNombre($data[3]);
                if($data[6]){
                    $prueba->setBibliografia($data[6]);
                }

                $valoresEntrada = explode(",",$data[5]);
                $valoresSalida = explode(",",$data[4]);
                
                $prueba->setValoresEntrada(json_encode($valoresEntrada));
                $prueba->setValoresSalida(json_encode($valoresSalida));

                $manager->persist($prueba);
                $manager->flush();            
            }

            //Asignar Prueba a Rama y Disciplina
            foreach($ramas as $rama){
                if($disciplina && $prueba && $rama){
                    $disciplinaPruebaRama = $em->getRepository('LogicBundle:DisciplinaPruebaRama')->findOneBy(
                        array(
                            'disciplina' => $disciplina->getId(),
                            'prueba' => $prueba->getId(),
                            'rama' => $rama->getId()
                        )
                    );
                    if (!$disciplinaPruebaRama) 
                    {
                        $disciplinaPruebaRama = new DisciplinaPruebaRama();
                        $disciplinaPruebaRama->setDisciplina($disciplina);
                        $disciplinaPruebaRama->setPrueba($prueba);
                        $disciplinaPruebaRama->setRama($rama);
    
                        $manager->persist($disciplinaPruebaRama);
                        $manager->flush();            
                    }
                }
            }
            
                   
        }
    }


}
