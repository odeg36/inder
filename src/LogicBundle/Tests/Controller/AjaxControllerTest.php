<?php

namespace LogicBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase {

    public function testRegistropublico() {
        $client = static::createClient();

        $crawler = $client->request('GET', 'registropublico');
    }

}
