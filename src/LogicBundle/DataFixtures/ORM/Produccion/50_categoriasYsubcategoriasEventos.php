<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\CategoriaEvento;
use LogicBundle\Entity\SubCategoriaEvento;


class FixturesCategoriasYsubcategoriasEventos extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 24;
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

        $categorias = json_decode('['
                . '{"nombre":"SUB 12 MASCULINO"},'
                . '{"nombre":"SUB 12 FEMENINO"},'
                . '{"nombre":"SUB 15 MASCULINO"},'
                . '{"nombre":"SUB 15 FEMENINO"},'
                . '{"nombre":"SUB 17 MASCULINO"},'
                . '{"nombre":"SUB 17 FEMENINO"},'
                . '{"nombre":"LIBRE MASCULINO"},'
                . '{"nombre":"LIBRE FEMENINO"},'
                . '{"nombre":"SUB 7"},'
                . '{"nombre":"SUB 11"},'
                . '{"nombre":"SUB 13"},'
                . '{"nombre":"SUB 15"},'
                . '{"nombre":"SUB 16 FEMENINO"},'
                . '{"nombre":"SEÑIOR MASTER (45 en adelante)"},'
                . '{"nombre":"SUB 14"},'
                . '{"nombre":"LIBRE MIXTO"},'
                . '{"nombre":"LIBRE"},'
                . '{"nombre":"LIBRE M"},'
                . '{"nombre":"MIXTO"},'
                . '{"nombre":"INDIVIDUAL"},'
                . '{"nombre":"EQUIPO"},'
                . '{"nombre":"SHOW"},'
                . '{"nombre":"COMPETENCIA"},'
                . '{"nombre":"CICLOMONTAÑISMO EXPERTOS"},'
                . '{"nombre":"CICLOMONTAÑISMO AFICIONADOS"},'
                . '{"nombre":"TRAIL MONTAÑA"},'
                . '{"nombre":"CARRERA DE AVENTURA"}'
                . ']');

        $subcategoriasMasculino12 = json_decode('['
                . '{"nombre":"SUB 12 MASCULINO"}'
                . ']');
        
        $subcategoriasFemenino12 = json_decode('['
                . '{"nombre":"SUB 12 FEMENINO"}'
                . ']');
        
        $subcategoriasMasculino15 = json_decode('['
                . '{"nombre":"SUB 15 MASCULINO"}'
                . ']');

        $subcategoriasFemenino15 = json_decode('['
                . '{"nombre":"SUB 15 FEMENINO"}'
                . ']');
        
        $subcategoriasMasculino17 = json_decode('['
                . '{"nombre":"SUB 17 MASCULINO"}'
                . ']');
        
        $subcategoriasFemenino17 = json_decode('['
                . '{"nombre":"SUB 17 FEMENINO"}'
                . ']');
        
        $subcategorias7 = json_decode('['
                . '{"nombre":"SUB 7"}'
                . ']');

        $subcategorias11 = json_decode('['
                . '{"nombre":"SUB 11"}'
                . ']');
        
        $subcategorias13 = json_decode('['
                . '{"nombre":"SUB 13"}'
                . ']');

        $subcategorias15 = json_decode('['
                . '{"nombre":"SUB 15"}'
                . ']');
                
        $subcategoriasFemenino16 = json_decode('['
                . '{"nombre":"SUB 16 FEMENINO"}'
                . ']');

        $subcategoriasSenior = json_decode('['
                . '{"nombre":"SEÑIOR MASTER (45 en adelante)"}'
                . ']');
    
        $subcategorias14 = json_decode('['
                . '{"nombre":"SUB 14"}'
                . ']');

        $subcategoriasLibreMixto = json_decode('['
                . '{"nombre":"LIBRE MIXTO"}'
                . ']');

        $subcategoriasLibre = json_decode('['
                . '{"nombre":"LIBRE"}'
                . ']');
    
        $subcategoriasLibreM = json_decode('['
                . '{"nombre":"LIBRE M"}'
                . ']');

        $subcategoriasMixto = json_decode('['
                . '{"nombre":"MIXTO"}'
                . ']');

        $subcategoriasLibreMasculino = json_decode('['
                . '{"nombre":"LIBRE MASCULINO"}'
                . ']');
        

        $subcategoriasLibreFemenino = json_decode('['
                . '{"nombre":"LIBRE FEMENINO"}'
                . ']');

                
        $subcategoriasIndividual = json_decode('['
                . '{"nombre":"CARRO DE RODILLOS 2016"},'
                . '{"nombre":"SPEED DOWN CARRILANAS"},'
                . '{"nombre":"STREET LUGE"},'
                . '{"nombre":"LONG BOARD"},'
                . '{"nombre":"GRAVITY BIKE"},'
                . '{"nombre":"PATINAJE DESCENSO ROLLERS"}'
                . ']');

        $subcategoriasEquipo = json_decode('['
                . '{"nombre":"CARROS DE RODILLOS"},'
                . '{"nombre":"SPEED DOWN CARRILANAS"}'
                . ']');

        $subcategoriasShow = json_decode('['
        . '{"nombre":"TRIAL"},'
        . '{"nombre":"FLAT LAND"}'
        . ']');

        $subcategoriasCompetencia = json_decode('['
        . '{"nombre":"DOWN HILL"}'
        . ']');


        $subcategoriasCicloMontaExperto = json_decode('['
        . '{"nombre":"OPEN MASCULINO"},'
        . '{"nombre":"OPEN FEMENINO"},'
        . '{"nombre":"MASTER A 30 A 39 AÑOS MASCULINO"},'
        . '{"nombre":"MASTER C 50 AÑOS Y MAS MASCULINO"},'
        . '{"nombre":"MASTER B 40 A 49 AÑOS MASCULINO"}'
        . ']');


        $subcategoriasCicloMontaAficionado = json_decode('['
        . '{"nombre":"JUVENIL MASCULINO 15 A 17 AÑOS"},'
        . '{"nombre":"JUVENIL FEMENINO 15 A 17 AÑOS"},'
        . '{"nombre":"SENIOR 18 A 29 AÑOS"},'
        . '{"nombre":"MASTER A 30 A 39 AÑOS FEMENINO"},'
        . '{"nombre":"MASTER B 40 A 49 AÑOS FEMENINO"},'
        . '{"nombre":"MASTER C 50 AÑOS Y MAS FEMENINO"}'
        . ']');


        $subcategoriasTrailMontana = json_decode('['
        . '{"nombre":"CAMINANTES AFICIONADOS"},'
        . '{"nombre":"CAMINANTES CON PERRO"},'
        . '{"nombre":"CORREDORES EXPERTOS"}'
        . ']');

        $subcategoriasCarreraAventura = json_decode('['
        . '{"nombre":"PAREJAS OPEN"}'
        . ']');




        $em = $this->container->get('doctrine')->getManager();

        foreach ($categorias as $categoria) {

            $registro = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
            
            if (!$registro) {

                $object = new CategoriaEvento();             
                $object->setNombre($categoria->{'nombre'});
                $manager->persist($object);
            }
            
        }
        $manager->flush();



        $masculino12 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 12 MASCULINO'));
        if($masculino12 != null )
        {
            foreach ($subcategoriasMasculino12 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($masculino12);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }


        $femenino12 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 12 FEMENINO'));
        if($femenino12 != null )
        {
            foreach ($subcategoriasFemenino12 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($femenino12);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }



        $masculino15 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 15 MASCULINO'));
        if($masculino15 != null )
        {
            foreach ($subcategoriasMasculino15 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($masculino15);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $femenino15 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 15 FEMENINO'));

        if($femenino15 != null )
        {
            foreach ($subcategoriasFemenino15 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($femenino15);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $masculino17 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 17 MASCULINO'));
        
        if($masculino17 != null )
        {
            foreach ($subcategoriasMasculino17 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($masculino17);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $femenino17 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 17 FEMENINO'));
        
        if($femenino17 != null )
        {
            foreach ($subcategoriasFemenino17 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($femenino17);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }



        $sub7 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 7'));
        
        if($sub7 != null )
        {
            foreach ($subcategorias7 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($sub7);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }




        $sub11 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 11'));
        
        if($sub11 != null )
        {
            foreach ($subcategorias11 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($sub11);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $sub13 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 13'));
        
        if($sub13 != null )
        {
            foreach ($subcategorias13 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($sub13);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $sub14 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 14'));
        
        if($sub14 != null )
        {
            foreach ($subcategorias14 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($sub14);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $sub15 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 15'));
        
        if($sub15 != null )
        {
            foreach ($subcategorias15 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($sub15);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }
        

        $subcategoriasLibreMasculino = json_decode('['
        . '{"nombre":"LIBRE MASCULINO"}'
        . ']');


        $libfemenino = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'LIBRE FEMENINO'));
        
        if($libfemenino != null )
        {
            foreach ($subcategoriasLibreFemenino as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($libfemenino);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }



        $libmascu = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'LIBRE MASCULINO'));
        
        if($libmascu != null )
        {
            foreach ($subcategoriasLibreMasculino as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($libmascu);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }



        $femenino16 = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SUB 16 FEMENINO'));
        
        if($femenino16 != null )
        {
            foreach ($subcategoriasFemenino16 as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($femenino16);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $seniorm = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SEÑIOR MASTER (45 en adelante)'));
        
        if($seniorm != null )
        {
            foreach ($subcategoriasSenior as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($seniorm);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        $libreMixto = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'LIBRE MIXTO'));
        
        if($libreMixto != null )
        {
            foreach ($subcategoriasLibreMixto as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($libreMixto);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        

        $libre = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'LIBRE'));
        
        if($libre != null )
        {
            foreach ($subcategoriasLibre as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($libre);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }



        $libreM = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'LIBRE M'));
        
        if($libreM != null )
        {
            foreach ($subcategoriasLibreM as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($libreM);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }



        $mix = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'MIXTO'));
        
        if($mix != null )
        {
            foreach ($subcategoriasMixto as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($mix);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();
        }


        

        $individual = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'INDIVIDUAL'));
        if($individual != null )
        {
            foreach ($subcategoriasIndividual as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($individual);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }


        $equipo = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'EQUIPO'));
        if($equipo != null )
        {
            foreach ($subcategoriasEquipo as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {

                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($equipo);
                    $manager->persist($object);

                }
                
            }
            $manager->flush();

        }



        $show = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'SHOW'));
        if($show != null )
        {
            foreach ( $subcategoriasShow as $categoria) {

                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {

                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($show);
                    $manager->persist($object);
                }
                
               
            }
            $manager->flush();

        }



        $competencia = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'COMPETENCIA'));
        if($competencia != null )
        {
            foreach ( $subcategoriasCompetencia as $categoria) {

                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) 
                {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($competencia);
                    $manager->persist($object);
                }
            
            }
            $manager->flush();

        }


        $montanaExperto = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'CICLOMONTAÑISMO EXPERTOS'));
        if($competencia != null )
        {
            foreach ( $subcategoriasCicloMontaExperto as $categoria) {

                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) 
                {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($montanaExperto);
                    $manager->persist($object);
                }
            
            }
            $manager->flush();

        }


        $montanaAficionado = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'CICLOMONTAÑISMO AFICIONADOS'));
        if($competencia != null )
        {
            foreach ( $subcategoriasCicloMontaAficionado as $categoria) {

                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) 
                {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($montanaAficionado);
                    $manager->persist($object);
                }
            
            }
            $manager->flush();

        }



        $trail = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'TRAIL MONTAÑA'));
        if($trail != null )
        {
            foreach ( $subcategoriasTrailMontana as $categoria) {

                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) 
                {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($trail);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }


        $carreraAventura = $em->getRepository('LogicBundle:CategoriaEvento')->findOneBy(array('nombre' => 'CARRERA DE AVENTURA'));
        
        if($trail != null )
        {
            foreach ( $subcategoriasCarreraAventura as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:SubCategoriaEvento')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) 
                {
                    $object = new SubCategoriaEvento();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setCategoria($carreraAventura);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }



    }

}
