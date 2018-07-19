<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoEscenario;
use LogicBundle\Entity\CategoriaInfraTipoEscenario;

class FixturesCategoriasInfraTipoEscenario extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 63;
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
            
        $categoriasChorrito = json_decode('['
        
                        . '{"nombre":"Fecha de construcción"},'
                        . '{"nombre":"Constructor"},'
                        . '{"nombre":"Obra básica inicial"},'
                        . '{"nombre":"Intervenciones"},'
                        . '{"nombre":"Observaciones"},'
                        . '{"nombre":"Norma RETIE"},'
                        . '{"nombre":"Norma NSR 10"},'
                        . '{"nombre":"Sistema de ahorro de energía"},'
                        . '{"nombre":"Sistema de ahorro de agua"},'
                        . '{"nombre":"CALIFICACIÓN GENERAL ESCENARIO"},'
                        . '{"nombre":"Riesgo Estructural"},'
                        . '{"nombre":"Superficie de Juego"},'
                        . '{"nombre":"Elementos del Escenario"},'
                        . '{"nombre":"Sistema Hidráulico"},'
                        . '{"nombre":"Áreas Complementarias"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Elementos de drenaje"},'
                        . '{"nombre":"Cerrajería"},'
                        . '{"nombre":"Urbanístico"},'
                        . '{"nombre":"Arquitectónico"},'
                        . '{"nombre":"Estructural"},'
                        . '{"nombre":"Eléctrico"},'
                        . '{"nombre":"Hidrosanitario"},'
                        . '{"nombre":"Alumbrado Publico"},'
                        . '{"nombre":"Alcantarillado"},'
                        . '{"nombre":"Manual de mantenimiento de uso y mantenimiento del escenario deportivo y recreativo"},'
                        . '{"nombre":"Nombre del Profesional Técnico"},'
                        . '{"nombre":"Nombre del Coordinador Equipo Plan Maestro"}'
                        . ']');

        $categoriasGimnasios = json_decode('['
        
                        . '{"nombre":"Cuneta"},'
                        . '{"nombre":"Cárcamo"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');

        $categoriasJuegos = json_decode('['
        
                        . '{"nombre":"Fecha de construcción"},'
                        . '{"nombre":"Constructor"},'
                        . '{"nombre":"Obra básica inicial"},'
                        . '{"nombre":"Intervenciones"},'
                        . '{"nombre":"Observaciones"},'
                        . '{"nombre":"Norma RETIE"},'
                        . '{"nombre":"Norma NSR 10"},'
                        . '{"nombre":"Sistema de ahorro de energía"},'
                        . '{"nombre":"Sistema de ahorro de agua"},'
                        . '{"nombre":"CALIFICACIÓN GENERAL ESCENARIO"},'
                        . '{"nombre":"Riesgo Estructural"},'
                        . '{"nombre":"Superficie de Juego"},'
                        . '{"nombre":"Mobiliario juegos infantiles"},'
                        . '{"nombre":"Elementos de drenaje"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"},'
                        . '{"nombre":"Áreas Complementarias"},'
                        . '{"nombre":"Urbanístico"},'
                        . '{"nombre":"Arquitectónico"},'
                        . '{"nombre":"Estructural"},'
                        . '{"nombre":"Eléctrico"},'
                        . '{"nombre":"Hidrosanitario"},'
                        . '{"nombre":"Alumbrado Publico"},'
                        . '{"nombre":"Alcantarillado"},'
                        . '{"nombre":"Manual de mantenimiento de uso y mantenimiento del escenario deportivo y recreativo"},'
                        . '{"nombre":"Nombre del Profesional Técnico"},'
                        . '{"nombre":"Nombre del Coordinador Equipo Plan Maestro"}'    
                        . ']');


        $categoriasLudotekas = json_decode('['
        
                        . '{"nombre":"Fecha de construcción"},'
                        . '{"nombre":"Constructor"},'
                        . '{"nombre":"Obra básica inicial"},'
                        . '{"nombre":"Intervenciones"},'
                        . '{"nombre":"Norma RETIE"},'
                        . '{"nombre":"Norma NSR 10"},'
                        . '{"nombre":"Sistema de ahorro de energía"},'
                        . '{"nombre":"Sistema de ahorro de agua"},'
                        . '{"nombre":"CALIFICACIÓN GENERAL ESCENARIO"},'
                        . '{"nombre":"Riesgo Estructural"},'
                        . '{"nombre":"Área de juego"},'
                        . '{"nombre":"Áreas complementarias"},'
                        . '{"nombre":"Cubiertas"},'
                        . '{"nombre":"Elementos de drenaje"},'
                        . '{"nombre":"Sistema hidrosanitario"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Sistema hidrosanitario"},'
                        . '{"nombre":"Cerrajería"},'
                        . '{"nombre":"Urbanístico"},'
                        . '{"nombre":"Arquitectónico"},'
                        . '{"nombre":"Estructural"},'
                        . '{"nombre":"Eléctrico"},'
                        . '{"nombre":"Hidrosanitario"},'
                        . '{"nombre":"Alumbrado Publico"},'
                        . '{"nombre":"Alcantarillado"},'
                        . '{"nombre":"Manual de mantenimiento de uso y mantenimiento del escenario deportivo y recreativo"},'
                        . '{"nombre":"Nombre del Profesional Técnico"},'
                        . '{"nombre":"Nombre del Coordinador Equipo Plan Maestro"}'    
                        . ']');
        
        $categoriasPiscinas = json_decode('['
        
                        . '{"nombre":"Fecha de construcción"},'
                        . '{"nombre":"Constructor"},'
                        . '{"nombre":"Obra básica inicial"},'
                        . '{"nombre":"Intervenciones"},'
                        . '{"nombre":"Observaciones"},'
                        . '{"nombre":"Norma RETIE"},'
                        . '{"nombre":"Norma NSR 10"},'
                        . '{"nombre":"Sistema de ahorro de energía"},'
                        . '{"nombre":"Sistema de ahorro de agua"},'
                        . '{"nombre":"CALIFICACIÓN GENERAL ESCENARIO"},'
                        . '{"nombre":"Riesgo Estructural"},'
                        . '{"nombre":"Área de juego y elementos del escenario"},'
                        . '{"nombre":"Área de juego"},'
                        . '{"nombre":"Elementos del Escenario"},'
                        . '{"nombre":"Sistema Hidráulico"},'
                        . '{"nombre":"Áreas complementarias"},'
                        . '{"nombre":"Sistema hidrosanitario"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Elementos de drenaje"},'
                        . '{"nombre":"Mobiliario Deportivo"},'
                        . '{"nombre":"Cerrajería"},'
                        . '{"nombre":"Urbanístico"},'
                        . '{"nombre":"Arquitectónico"},'
                        . '{"nombre":"Estructural"},'
                        . '{"nombre":"Eléctrico"},'
                        . '{"nombre":"Hidrosanitario"},'
                        . '{"nombre":"Alumbrado Publico"},'
                        . '{"nombre":"Alcantarillado"},'
                        . '{"nombre":"Manual de mantenimiento de uso y mantenimiento del escenario deportivo y recreativo"},'
                        . '{"nombre":"Nombre del Profesional Técnico"},'
                        . '{"nombre":"Nombre del Coordinador Equipo Plan Maestro"}'    
                        . ']');



            $categoriasSkate = json_decode('['
        
                        . '{"nombre":"INFRAESTRUCTURA Y EQUIPAMIENTOS"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');


            $categoriasTrote = json_decode('['
        
                        . '{"nombre":"Cuneta"},'
                        . '{"nombre":"Cárcamo"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');


            $categoriasTrote = json_decode('['
                        . '{"nombre":"Cuneta"},'
                        . '{"nombre":"Cárcamo"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');


            $categoriasPlacas = json_decode('['
                        . '{"nombre":"Cuneta"},'
                        . '{"nombre":"Cárcamo"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistema hidrosanitario"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');



            $categoriasSedes = json_decode('['
                        . '{"nombre":"Cuneta"},'
                        . '{"nombre":"Cárcamo"},'
                        . '{"nombre":"Cerramientos"},'
                        . '{"nombre":"Sistema hidrosanitario"},'
                        . '{"nombre":"Sistemas eléctricos"},'
                        . '{"nombre":"Importancia relativa entre grupos"},'
                        . '{"nombre":"Calificación general"},'
                        . '{"nombre":"Variable Auxiliar"},'
                        . '{"nombre":"Cerrajería"}'
                        . ']');

            

            $query = $manager->getRepository('LogicBundle:CategoriaInfraTipoEscenario')->createQueryBuilder('cit')               
            ->getQuery()->getResult();

            if(count($query) == 0)
            {
                $tipoChorrito = $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "CHORRITOS"));
                
                if($tipoChorrito != null)
                {
    
                    foreach ($categoriasChorrito as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoChorrito);
                            $manager->persist($object);
                        }
                        
                    }
                   
                }
    
    
                $tipoGimnasio = $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "GIMNASIOS"));
                
                if($tipoGimnasio != null)
                {
                    foreach ($categoriasGimnasios as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoGimnasio);
                            $manager->persist($object);
                        }       
                    } 
                }
    
    
                $tipoJuegoInfantil = $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "JUEGOS INFANTILES"));
                
                if($tipoJuegoInfantil != null)
                {
                    foreach ($categoriasJuegos as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoJuegoInfantil);
                            $manager->persist($object);
                        }       
                    } 
                }
    
    
                $tipoLudoteka =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "LUDOTEKA"));
            
                if($tipoLudoteka != null)
                {
                    foreach ($categoriasLudotekas as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoLudoteka);
                            $manager->persist($object);
                        }       
                    } 
                }
    
    
                $tipoPiscinas =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "PISCINA"));
            
                if($tipoPiscinas != null)
                {
                    foreach ($categoriasPiscinas as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoPiscinas);
                            $manager->persist($object);
                        }       
                    } 
                }
    
    
                $tipoSkate =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "PISTA DE SKATE"));
            
                if($tipoSkate != null)
                {
                    foreach ($categoriasSkate as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoSkate);
                            $manager->persist($object);
                        }       
                    } 
                }
                
    
                $tipoTrote =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "PISTA DE TROTE"));
            
                if($tipoTrote != null)
                {
                    foreach ($categoriasTrote as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoTrote);
                            $manager->persist($object);
                        }       
                    } 
                }
    
    
                $tipoPlaca =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "PLACA POLIDEPORTIVA"));
            
                if($tipoPlaca != null)
                {
                    foreach ($categoriasPlacas as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoPlaca);
                            $manager->persist($object);
                        }       
                    } 
                }
                
    
                $tipoSedes =  $manager->getRepository('LogicBundle:TipoEscenario')->findOneBy(array('nombre' => "SEDES ADMINISTRATIVAS Y ESPACIOS COMPLEMENTARIOS"));
            
                if($tipoSedes != null)
                {
                    foreach ($categoriasSedes as $categoria) {
    
                        $consulta = $em->getRepository('LogicBundle:CategoriaInfraestructura')->findOneBy(array('nombre' => $categoria->{'nombre'}));
    
                        if ($consulta != null) 
                        {
                            $object = new CategoriaInfraTipoEscenario();            
                            $object->setCategoriaInfraestructura($consulta);
                            $object->setTipoEscenario($tipoSedes);
                            $manager->persist($object);
                        }       
                    } 
                }
                
                $manager->flush();
            }
        
    }

}