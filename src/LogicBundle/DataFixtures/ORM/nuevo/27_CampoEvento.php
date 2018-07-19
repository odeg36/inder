<?php

namespace LogicBundle\DataFixtures\ORM\nuevo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LogicBundle\Entity\CampoEvento;

class FixturesCampoEvento extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 27;
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
        $container = $this->container;
        $tiposCampos = $container->getParameter('tipos_campos');
        $tipos = json_decode('['
                . '{"nombre":"¿Pertenece a la comunidad LGBTI?","nombreMapeado":"perteneceLGBTI","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Estatura","nombreMapeado":"estatura","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Peso","nombreMapeado":"peso","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Indice masa corporal","nombreMapeado":"indiceMasaCorporal","tipo":"' . $tiposCampos['numerico'] . '"},'
                . '{"nombre":"Consume bebidas alcohólicas","nombreMapeado":"consumeBebidasAlcoholicas","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Desplazado","nombreMapeado":"desplazado","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Tipo de desplazado","nombreMapeado":"tipoDesplazado","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Barrio","nombreMapeado":"barrio","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Padece enfermedades crónicas","nombreMapeado":"padeceEnfermedadesCronicas","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Consume medicamentos","nombreMapeado":"consumeMedicamentos","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Tipo de sangre y RH","nombreMapeado":"tipoSangre","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Medio de transporte","nombreMapeado":"medioTransporte","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Punto de recolección","nombreMapeado":"puntoRecoleccion","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Establecimiento educativo","nombreMapeado":"establecimientoEducativo","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Nivel de escolaridad","nombreMapeado":"nivelEscolaridad","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Estrato","nombreMapeado":"estrato","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Nombre de club o equipo","nombreMapeado":"nombreClubEquipo","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Nombre del carro","nombreMapeado":"nombreCarro","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Rama","nombreMapeado":"rama","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Rol","nombreMapeado":"rol","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Categoría y subcategorías","nombreMapeado":"categoria","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Ocupación","nombreMapeado":"ocupacion","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Jefe cabeza de hogar","nombreMapeado":"jefeCabezaHogar","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Teléfono/móvil","nombreMapeado":"telefonoContacto","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Grado que cursa","nombreMapeado":"gradoCursa","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Talla del pantalón","nombreMapeado":"tallaPantalon","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Talla de camisa","nombreMapeado":"tallaCamisa","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Talla de zapatos","nombreMapeado":"tallaZapatos","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Número de matrícula","nombreMapeado":"numeroMatricula","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Municipio","nombreMapeado":"municipio","tipo":"' . $tiposCampos['entidad'] . '"},'
                . '{"nombre":"Dirección","nombreMapeado":"direccion","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Sexo","nombreMapeado":"sexo","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Discapacitados","nombreMapeado":"discapacitado","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Tipo de discapacidad","nombreMapeado":"tipoDiscapacidad","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Subtipo de discapacidad","nombreMapeado":"subDiscapacidad","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Adjuntar documentos","nombreMapeado":"adjuntarDocumentos","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Correo electrónico","nombreMapeado":"correoElectronico","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Fecha nacimiento","nombreMapeado":"fechaNacimiento","tipo":"' . $tiposCampos['texto'] . '"},'
                . '{"nombre":"Fuma","nombreMapeado":"fuma","tipo":"' . $tiposCampos['boolean'] . '"},'
                . '{"nombre":"Nº licencia de ciclismo expedida por la federación","nombreMapeado":"licenciaCiclismo","tipo":"' . $tiposCampos['numerico'] . '"}'
                . ']');
        
        $em = $this->container->get('doctrine')->getManager();
        
        foreach ($tipos as $tipo) {

            $registro = $em->getRepository('LogicBundle:CampoEvento')->findOneBy(array('nombre' => $tipo->{'nombre'}));
            
            if (!$registro)
            {
                $object = new CampoEvento();            
                $object->setNombre($tipo->{'nombre'});
                $object->setNombreMapeado($tipo->{'nombreMapeado'});
                $object->setTipo($tipo->{'tipo'});
                $manager->persist($object);
            }elseif($registro->getNombreMapeado() == null && $registro->getTipo() == null){
                $registro->setNombreMapeado($tipo->{'nombreMapeado'});
                $registro->setTipo($tipo->{'tipo'});
                $manager->persist($registro);
            }
            
        }
        $manager->flush();
    }
}