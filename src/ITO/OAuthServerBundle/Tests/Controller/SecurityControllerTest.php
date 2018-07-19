<?php

namespace ITO\OAuthServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase {

    public function testLogin() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/oauth/v2/auth_login');
    }

}
