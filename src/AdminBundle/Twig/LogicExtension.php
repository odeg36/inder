<?php

namespace AdminBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class LogicExtension extends \Twig_Extension {

    protected $container;
    protected $trans;
    
    public function __construct(Container $container = null) {
        $this->container = $container;
        $this->trans = $container->get("translator");
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('tiposIdentificacion', array($this, 'getTiposIdentificacion')),
            new \Twig_SimpleFunction('uniqId', array($this, 'uniqId')),
            new \Twig_SimpleFunction('setNull', array($this, 'setNull')),
            new \Twig_SimpleFunction('valorBoolean', array($this, 'valorBoolean')),
            new \Twig_SimpleFunction('calcularEdad', array($this, 'calcularEdad')),
            new \Twig_SimpleFunction('esOfertaFormador', array($this, 'esOfertaFormador')),
            new \Twig_SimpleFunction('genero', array($this, 'genero')),
            new \Twig_SimpleFunction('buscarValorObject', array($this, 'buscarValorObject')),
            new \Twig_SimpleFunction('renderizarDiaEscenario', array($this, 'renderizarDiaEscenario'))
        );
    }

    public function getTiposIdentificacion() {
        $em = $this->container->get('doctrine')->getManager();
        $tipos = $em->getRepository('LogicBundle:TipoIdentificacion')->findBy(array(), array('nombre' => 'ASC'));
        return $tipos;
    }

    public function esOfertaFormador($oferta) {
        $em = $this->container->get('doctrine')->getManager();
        $formador = $this->container->get('security.token_storage')->getToken()->getUser();
        $ofertaFormador = $em->getRepository('LogicBundle:Oferta')->findByFormador($oferta, $formador);
        return $ofertaFormador ? true : false;
    }

    public function uniqId() {
        $fecha = new \DateTime();

        $fecha = $fecha->format("U");
        $randon = rand(0, 99999999999999999);
        return sha1($randon . $fecha);
    }

    public function getName() {
        return 'inder_funciones';
    }

    public function setNull() {
        return null;
    }

    public function valorBoolean($valor = null) {
        if ($valor) {
            return 'Si';
        }

        return 'No';
    }

    public function calcularEdad($fecha = null) {
        if (!$fecha) {
            return '';
        }

        if (gettype($fecha) == "object") {
            $fecha = $fecha->format("Y-m-d");
        }

        $tiempo = strtotime($fecha);
        $ahora = time();
        $edad = ($ahora - $tiempo) / (60 * 60 * 24 * 365.25);
        $edad = floor($edad);
        return $edad;
    }

    public function genero($genero = null) {
        if (!$genero) {
            return '';
        }

        switch ($genero) {
            case "f":
                return $this->trans->trans("gender_female");

                break;
            case "m":
                return $this->trans->trans("gender_male");

                break;
            case "u":
                return $this->trans->trans("gender_undefined");

                break;

            default:
                return '';
                break;
        }
    }
    
    public function buscarValorObject($object, $key) {
        $metodo = 'get' . $key;
        if(method_exists($object, $metodo)){
            return $object->$metodo();
        }
        
        return '';
    }
    
    public function renderizarDiaEscenario($objeto, $key){
        return $objeto->$key();
    }
}
