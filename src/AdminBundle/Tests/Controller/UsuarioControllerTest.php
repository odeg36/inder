<?php

namespace AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsuarioControllerTest extends WebTestCase {

    public function testRegistro() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registro');
    }

}
