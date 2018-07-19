<?php

namespace LogicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="home", options={"expose"=true})
     */
    public function indexAction() {
        return $this->render('LogicBundle:Default:index.html.twig');
    }

}
