<?php

namespace LogicBundle\Controller;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Form\DeportistaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganismoDeportistaController extends Controller {

    protected $session = null;
    protected $em = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->session = $container->get("session");
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * @Route("/tipo/identificacion/{valor}/{id}", name="deportista_tipo_identificacion", options={"expose"=true})
     */
    public function tipoIdentificacionAction($valor, $id) {
        $response = new Response();

        $this->session->set($id, $valor);
        $json = [$id => $this->session->get($id)];
        $response->setContent(json_encode($json));
        return $response;
    }

    /**
     * @Route("/nuevo/deportista", name="nuevo_deportista", options={"expose"=true})
     */
    public function nuevoDeportistaAction(Request $request) {
        $uniqId = $request->get("uniqId", null);

        $usuario = new User();

        $form = $this->createForm(DeportistaType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $grupo = $this->em->getRepository("ApplicationSonataUserBundle:Group")->findOneByName(Group::GRUPO_DEPORITISTA);
                
                $usuario = $form->getData();
                
                $usuario->setRoles([$this->container->getParameter("rol_deportista")]);
                $uniqId = uniqid();
                $encoder = $this->container->get('inder.encoder');
                $password = $encoder->encodePassword($uniqId, $usuario->getSalt());
                $usuario->setPassword($password);

                if($grupo){
                    $usuario->addGroup($grupo);
                }
                
                $this->em->persist($usuario);

                $this->em->flush();

                $template = $this->render("AdminBundle:Usuario/Formulario:mensaje.deportista.guardado.html.twig");

                $json = [
                    'usuario' => $usuario,
                    'template' => $template
                ];

                $serializer = $this->container->get('jms_serializer');
                $json = $serializer->serialize($json, 'json');
                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        return $this->render("AdminBundle:Usuario/Formulario:nuevo.deportista.html.twig", array(
                    'form' => $form->createView(),
                    'uniqId' => $uniqId
        ));
    }

}
