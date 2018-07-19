<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\TipoFalta;
use LogicBundle\Entity\Sancion;


class FixturesSancionesYfaltas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

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

        $tipoFalta = json_decode('['
                . '{"nombre":"Leve" , "descripcion": "NO ACATAR ÓRDENES ARBÍTRALES, UTILIZACIÓN DE FUERZA DESMEDIDA EN SITUACIONES DE JUEGO, INTERVENIR ILEGALMENTE EN UNA JUGADA COMPROMETIDA DE GOL Y TODAS AQUELLAS CONDUCTAS CLARAMENTE CONTRARIAS A NORMAS DEPORTIVAS QUE NO ESTÉN INCURSAS EN LA CALIFI 2. EL JUGADOR O MIEMBRO DEL CUERPO TÉCNICO QUE SALGA EXPULSADO DE UN PARTIDO, SERÁ SUSPENDIDO AUTOMÁTICAMENTE PARA LA FECHA SIGUIENTE Y QUEDARÁ PENDIENTE DE LA SANCIÓN IMPUESTA MEDIANTE RESOLUCIÓN DE LA COMISIÓN DISCIPLINARIA.1. INCUMPLIR REITERADAMENTE LAS ÓRDENES E INSTRUCCIONES IMPARTIDAS POR EL CUERPO ARBITRAL Y/O LA ORGANIZACIÓN DE LOS JUEGOS." , "puntos": "20" },'
                . '{"nombre":"Grave", "descripcion": "1. INCUMPLIR REITERADAMENTE LAS ÓRDENES E INSTRUCCIONES IMPARTIDAS POR EL CUERPO ARBITRAL Y/O LA ORGANIZACIÓN DE LOS JUEGOS.FALTA GRAVE 2 2. REALIZAR EN ACTOS NOTORIOS Y PÚBLICOS QUE ATENTEN A LA DIGNIDAD Y DECORO DEPORTIVO, COMO SON ENTRE OTROS. (REMITIRSE A LA CARTA FUNDAMENTAL)", "puntos": "50" },'
                . '{"nombre":"Muy Grave", "descripcion": "MUY GRAVE 1 AGRESIÓN FÍSICA A JUECES, MIEMBROS DE CUERPOS TÉCNICOS, PERSONAL ACOMPAÑANTE, COMPAÑEROS DE EQUIPOS, MIEMBROS DEL EQUIPO CONTRARIO, BARRAS Y MIEMBROS DE LA ORGANIZACIÓN, Y/O INCITAR A BATALLAS CAMPALES DENTRO O FUERA DEL TERRENO DE JUEGO.   FALTA MUY GRAVE 2 2. EL JUGADOR O MIEMBRO DEL CUERPO TÉCNICO QUE ACTÚE EN UN PARTIDO ESTANDO SUSPENDIDO SERÁ PENALIZADO Y SU EQUIPO PERDERÁ LOS PUNTOS DE LOS PARTIDOS EN LOS QUE HAYA ACTUADO.   FALTA MUY GRAVE 3 3. LAS ACTUACIONES DIRIGIDAS A PREDETERMINAR, MEDIANTE PRECIO, INTIMIDACIÓN O SIMPLES ACUERDOS, EL RESULTADO DE UNA PRUEBA O COMPETICIÓN.   FALTA MUY GRAVE 4 4. LA FALSIFICACIÓN, ADULTERACIÓN DE DOCUMENTOS O LA SUPLANTACIÓN DE UN JUGADOR SERÁ PENALIZADA CON LA EXPULSIÓN DEL EQUIPO DEL TORNEO.   FALTA MUY GRAVE 5 5. EL JUGADOR, MIEMBRO DEL CUERPO TÉCNICO O PERSONAL ACOMPAÑANTE QUE SE ENCUENTRE CON CUALQUIER CLASE DE ARMA DENTRO DEL CAMPO DE JUEGO O EN SUS INSTALACIONES ANEXAS, SERÁ SANCIONADO EL EQUIPO CON LA EXPULSIÓN DEL TORNEO." , "puntos": "100" }'
                . ']');



        $leves = json_decode('['
        . '{"nombre":"NO ACATAR ÓRDENES ARBÍTRALES"},'
        . '{"nombre":"USO DESMEDIDO DE LA FUERZA"},'
        . '{"nombre":"SINTERVENIR ILEGALMENTE EN UNA JUGADA DE GOL"},'
        . '{"nombre":"EXPULSION"}'
        . ']');


        $graves = json_decode('['
        . '{"nombre":"INCUMPLIMIENTO REITERADO"},'
        . '{"nombre":"INJURIA VERBAL O CORPORAL"},'
        . '{"nombre":"AGREDIR FISICAMENTE SIN BALON"},'
        . '{"nombre":"HACER DECLARACIONES PRIVADAS O PUBLICAS"},'
        . '{"nombre":"EFECTOS DEL ALCOHOL"}'
        . ']');


        $muyGraves = json_decode('['
        . '{"nombre":"AGRESIÓN FISICA"},'
        . '{"nombre":"JUGADOR O MIEMBRO DEL CUERPO TECNICO SUSPENDIDO"},'
        . '{"nombre":"SOBORNO"},'
        . '{"nombre":"FALSIFICACION O SUPLANTACION"},'
        . '{"nombre":"FALSIFICACION O SUPLANTACION"},'
        . '{"nombre":"ARMAS DENTRO DEL CAMPO DE JUEGO"},'
        . '{"nombre":"INJURIA NO VERBAL"}'
        . ']');


        $em = $this->container->get('doctrine')->getManager();


        

        foreach ($tipoFalta as $falta) {
            
            $registro = $em->getRepository('LogicBundle:TipoFalta')->findOneBy(array('nombre' => $falta->{'nombre'}));

            if (!$registro) {
                $object = new TipoFalta();             
                $object->setNombre($falta->{'nombre'});
                $object->setDescripcion($falta->{'descripcion'});
                $object->setPuntosJuegolimpio($falta->{'puntos'});
                $manager->persist($object);
            }
            
        }

        $manager->flush();

    
        $leve = $em->getRepository('LogicBundle:TipoFalta')->findOneBy(array('nombre' => 'Leve'));

        if($leve != null )
        {
            foreach($leves as $categoria) {

                $registro = $em->getRepository('LogicBundle:Sancion')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {
                    $object = new Sancion();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setTipoFalta($leve);
                    $manager->persist($object);
                }
          }
            $manager->flush();

        }


        $grave = $em->getRepository('LogicBundle:TipoFalta')->findOneBy(array('nombre' => 'Grave'));
        if($grave != null )
        {
            foreach ($graves as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:Sancion')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {

                    $object = new Sancion();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setTipoFalta($grave);
                    $manager->persist($object);
                }
                
            }
            $manager->flush();

        }


        $muygrave = $em->getRepository('LogicBundle:TipoFalta')->findOneBy(array('nombre' => 'Muy Grave'));
        
        if($muygrave != null )
        {
            foreach ($muyGraves as $categoria) {
                
                $registro = $em->getRepository('LogicBundle:Sancion')->findOneBy(array('nombre' => $categoria->{'nombre'}));
                
                if (!$registro) {

                    $object = new Sancion();             
                    $object->setNombre($categoria->{'nombre'});
                    $object->setTipoFalta($muygrave);
                    $manager->persist($object);
                }

            }
            $manager->flush();

        }








    }

}
