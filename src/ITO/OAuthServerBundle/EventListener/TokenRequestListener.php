<?php

namespace ITO\OAuthServerBundle\EventListener;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of TokenRequestListener
 *
 * @author mherran
 */
class TokenRequestListener {

    protected $container = null;
    protected $trans = null;

    public function __construct(ContainerInterface $container = null) {
        $this->container = $container;
        $this->trans = $container->get("translator");
    }

    public function onKernelController(FilterControllerEvent $event) {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {

            $securityContext = $this->container->get('security.authorization_checker');

            if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
                return true;
            } else {
                throw new AccessDeniedHttpException($this->trans->trans('message.api.error.accion.invalida.cliente'), null, null);
            }
        }
    }

}
