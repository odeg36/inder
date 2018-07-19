<?php

namespace LogicBundle\DataFixtures\ORM\Pruebas;

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

class FixturesGuiaMetodologica extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 53;
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
        
    /*    $excelObj= $this->container->get('phpexcel');        
        $phpExcelObject = $excelObj->createPHPExcelObject(__DIR__ ."/../../../Resources/public/consolidado_guías_metodológicas.xlsx")->setActiveSheetIndex(0);
                
        $dataExcel = $phpExcelObject->toArray();        
        
        unset($dataExcel[0]);
        //Insertar guia metodologica
        foreach ($dataExcel as $data) {
            array_splice($data,19);
           
           $anio = date('Y');
           if(isset($data[1]) && isset($data[3]) && isset($data[5]) && isset($data[7])){
                $nombreEnfoque = $data[1];
                $nombreClasificacion = $data[3];
                $nombreDisciplina = $data[5];
                $nombreNivel = $data[7];

                $nombreGuia =  $data[1]." - ".$data[5]." - ".$data[7]. " - ".$anio;
                
                $enfoque = $manager->getRepository('LogicBundle:Enfoque')->findOneBy(
                    array(
                        'nombre' => $nombreEnfoque
                    ));
                $clasificacion = $manager->getRepository('LogicBundle:Clasificacion')->findOneBy(
                    array(
                        'nombre' => $nombreClasificacion
                    ));
                $disciplina = $manager->getRepository('LogicBundle:Disciplina')->findOneBy(
                    array(
                        'nombre' => $nombreDisciplina
                    ));
                $nivel = $manager->getRepository('LogicBundle:Nivel')->findOneBy(
                    array(
                        'nombre' => $nombreNivel
                    ));
                $guiaMetodologica = $manager->getRepository('LogicBundle:PlanAnualMetodologico')->findOneBy(
                    array(
                    'nombre' => $nombreGuia
                    ));        
                
                if ($guiaMetodologica == null) {

                    $guia_object = new PlanAnualMetodologico();
                    
                    $guia_object->setEnfoque($enfoque);
                    $guia_object->setClasificacion($clasificacion);
                    $guia_object->setDisciplina($disciplina);
                    $guia_object->addNivel($nivel);
                    $guia_object->setNombre($nombreGuia);
                    $guia_object->setPonderacionComponentes(1);
                    $guia_object->setPonderacionContenidos(1);
                    $guia_object->setEstado(1);
                    
                    $manager->persist($guia_object);
                    $manager->flush();
                }else{
                    $guia_object = $guiaMetodologica;
                }
            }

            if(isset($data[9]) && isset($data[10])){
                $nombreComponente = $data[9];
                $objetivoComponente = $data[10];

                $componenteQuery = $manager->getRepository('LogicBundle:Componente')->createQueryBuilder('com')
                ->join('com.planAnualMetodologico', 'pam')                  
                ->where('com.nombre = :nombreComponente')
                ->andWhere('pam.id = :idPam')
                ->setParameter('nombreComponente', $nombreComponente)
                ->setParameter('idPam', $guia_object->getId())
                ->getQuery()->getResult();

                if(!$componenteQuery){
                    $componente = new Componente;
                    
                    $componente->setNombre($nombreComponente);
                    if ($objetivoComponente != null) {
                        $componente->setObjetivo($objetivoComponente);
                    }
                    $componente->setPlanAnualMetodologico($guia_object);

                    $manager->persist($componente);
                    $manager->flush();
                }

            }

            if (isset($data[12]) && isset($data[13])) {
                $nombreContenido = $data[12];
                $objetivo = $data[13];

                $contenidoQuery = $manager->getRepository('LogicBundle:Contenido')->createQueryBuilder('con')
                ->join('con.componente', 'com')                  
                ->where('con.nombre = :nombreContenido')
                ->andWhere('com.id = :idComponente')
                ->setParameter('nombreContenido', $nombreContenido)
                ->setParameter('idComponente', $componente->getId())
                ->getQuery()->getResult();

                if(!$contenidoQuery){
                    $contenido = new Contenido;

                    $contenido->setNombre($nombreContenido);
                    $contenido->setComponente($componente);
                    if ($objetivo != null) {
                        $contenido->setObjetivo($objetivo);
                    }

                    $manager->persist($contenido);
                    $manager->flush();
                }
            }

            if (isset($data[15]) && isset($data[16]) && isset($data[17]) && isset($data[18])) {
                $nombreActividad = $data[15];
                $duracion = $data[16];
                $indicador = $data[17];
                $evaluacion = $data[18];

                $tiempoEjecucionTrans = $manager->getRepository('LogicBundle:TipoTiempoEjecucion')->findOneBy(
                    array(
                        'nombre' => 'Transversal'
                    ));

                $tiempoEjecucionSesion = $manager->getRepository('LogicBundle:TipoTiempoEjecucion')->findOneBy(
                    array(
                        'nombre' => 'Sesiones'
                    ));

                $actividadQuery = $manager->getRepository('LogicBundle:Actividad')->createQueryBuilder('a')
                ->join('a.contenido', 'con')                  
                ->where('a.nombre = :nombreActividad')
                ->andWhere('con.id = :idContenido')
                ->setParameter('nombreActividad', $nombreActividad)
                ->setParameter('idContenido', $contenido->getId())
                ->getQuery()->getResult();

                if(!$actividadQuery){
                    $actividad = new Actividad;

                    $actividad->setNombre($nombreActividad);
                    $actividad->setIndicador($indicador);
                    $actividad->setMetodoEvaluacion($evaluacion);
                    $actividad->setContenido($contenido);
                    if (is_numeric($duracion)) {
                        $actividad->setDuracion($duracion);
                        $actividad->setTipoTiempoEjecucion($tiempoEjecucionSesion);
                    }else{
                        $actividad->setDuracion(0);
                        $actividad->setTipoTiempoEjecucion($tiempoEjecucionTrans);
                    }

                    $manager->persist($actividad);
                    $manager->flush();
                }
            }
        }
        */
    }
}