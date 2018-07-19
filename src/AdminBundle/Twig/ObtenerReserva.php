<?php

namespace AdminBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Twig_Extension;
use Twig_SimpleFunction;

class ObtenerReserva extends \Twig_Extension {

    protected $container;

    public function __construct(Container $container = null) {
        $this->container = $container;
    }

    public function getFunctions() {

        return array(new \Twig_SimpleFunction('obtenerReserva', array($this, 'getReserva')));
    }

    public function getReserva($id) {
        $em = $this->container->get('doctrine')->getManager();
        $reservaUsuario = $em->getRepository('LogicBundle:Reserva')->createQueryBuilder('reserva')
        	->where('reserva.usuario = :idUsuario')
			->orderBy("reserva.id", 'DESC')
        	->setParameter('idUsuario', $id)
			->getQuery()
			->setMaxResults(2)
			->getResult();        
        return $reservaUsuario;
    }

    public function getName() {
        return 'get_reserva';
    }
    
}
