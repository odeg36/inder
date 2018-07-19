<?php

namespace AdminBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class ObtenerOFerta extends \Twig_Extension {

    protected $container;

    public function __construct(Container $container = null) {
        $this->container = $container;
    }

    public function getFunctions() {

        return array(new \Twig_SimpleFunction('obtenerOferta', array($this, 'getOferta')));
    }

    public function getOferta($id) {
        $em = $this->container->get('doctrine')->getManager();
        $oferta = $em->getRepository('LogicBundle:Oferta')->find($id);
        return $oferta;
    }

    public function getName() {
        return 'get_oferta';
    }

}
