<?php

namespace ITO\LogicBundle\DataFixtures\ORM\Pruebas\Client_1;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOAuthClientData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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

    public function getOrder() {
        return 11;
    }

    public function load(ObjectManager $manager) {

        $client_name = "web client";
        $client_redirect_uris = array("http://localhost");
        $client_grant_types = array("authorization_code", "password", "refresh_token", "token", "client_credentials");
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client_web = $clientManager->createClient();
        $client_web->setName($client_name);
        $client_web->setRedirectUris($client_redirect_uris);
        $client_web->setAllowedGrantTypes($client_grant_types);
        //$client_web->setType("web");
        //$client_web->setTrusted(1);
        $clientManager->updateClient($client_web);

        $client_name = "ios client";
        $client_redirect_uris = array("http://localhost");
        $client_grant_types = array("authorization_code", "password", "refresh_token", "token", "client_credentials");
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client_ios = $clientManager->createClient();
        $client_ios->setName($client_name);
        $client_ios->setRedirectUris($client_redirect_uris);
        $client_ios->setAllowedGrantTypes($client_grant_types);
        //$client_ios->setType("ios");
        //$client_ios->setTrusted(1);
        $clientManager->updateClient($client_ios);

        $client_name = "android client";
        $client_redirect_uris = array("http://localhost");
        $client_grant_types = array("authorization_code", "password", "refresh_token", "token", "client_credentials");
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client_android = $clientManager->createClient();
        $client_android->setName($client_name);
        $client_android->setRedirectUris($client_redirect_uris);
        $client_android->setAllowedGrantTypes($client_grant_types);
        //$client_android->setType("android");
        //$client_android->setTrusted(1);
        $clientManager->updateClient($client_android);
    }

}
