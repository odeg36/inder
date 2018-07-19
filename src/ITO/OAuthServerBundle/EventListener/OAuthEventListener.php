<?php

use FOS\OAuthServerBundle\Event\OAuthEvent;

/**
 * Description of OAuthEventListener
 *
 * @author mherran
 */
class OAuthEventListener {

    public function onPreAuthorizationProcess(OAuthEvent $event) {
        if ($user = $this->getUser($event)) {
            $event->setAuthorizedClient(
                    $user->isAuthorizedClient($event->getClient())
            );
        }
    }

    public function onPostAuthorizationProcess(OAuthEvent $event) {
        if ($event->isAuthorizedClient()) {
            if (null !== $client = $event->getClient()) {
                $user = $this->getUser($event);
                $user->addClient($client);
                $user->save();
            }
        }
    }

    protected function getUser(OAuthEvent $event) {
        return UserQuery::create()
                        ->filterByUsername($event->getUser()->getUsername())
                        ->findOne();
    }

}
