<?php

namespace ITO\OAuthServerBundle\EventListener;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ClientRequestListener
 *
 * @author mherran
 */
class ClientRequestListener {

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

        if ($controller[0] instanceof ClientAuthenticatedController) {
            $authetincated = false;
            $clients = $this->container->get('doctrine')->getRepository('ITOOAuthServerBundle:Client')->findAll();


            //Esto se puede mejorar si se logra el postpersist en la entidad client
            foreach ($clients as $client) {
                $authorization = 'Basic ' . base64_encode($client->getClientId() . ':' . $client->getClientSecret());
                $req_auth = $event->getRequest()->headers->get('authorization');
                if ($authorization === $req_auth) {
                    $authetincated = true;
                }
            }
            if ($authetincated) {
                $event->getRequest()->attributes->set('client', $client);
            } else {
                throw new AccessDeniedHttpException($this->trans->trans('message.api.error.access.token.invalido'), null, 403);
            }
        }
    }

}
