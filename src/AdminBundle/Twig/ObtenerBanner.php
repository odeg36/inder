<?php

namespace AdminBundle\Twig;

use LogicBundle\Entity\VistaBanner;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class ObtenerBanner extends Twig_Extension {

    protected $container = null;
    protected $em = null;
    protected $trans = null;

    public function setContainer(ContainerInterface $container = null) {

        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
        $this->trans = $container->get("translator");
    }

    public function __construct(Container $container = null) {
        $this->setContainer($container);
    }

    public function getFunctions() {

        return array(new Twig_SimpleFunction('obtenerBanner', array($this, 'getBanner')));
    }

    public function getBanner($id) {

        $id;


        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $now = new \DateTime();

        if (!$user) {
            $dql = "SELECT u FROM ApplicationSonataUserBundle:User u 
            WHERE u.id = :id";
            $parameters = [];
            $parameters["id"] = $id;
            $responseBuilder = $this->container->get('ito.tools.response.builder');
            $user = $responseBuilder->getItem($dql, $parameters);
        }


        if ($user) {
            $barrio = $user->getBarrio();
            $phat = false;
            if (!$barrio) {
                $phat = false;
            }
            $comuna = $barrio ? $barrio->getComuna() : null;
            if (!$comuna) {
                $phat = false;
            }

            $dql = "SELECT b FROM LogicBundle:Banner b
                JOIN b.comunas c
                WHERE c.id = '" . ($comuna ? $comuna->getId() : 0 ). "'
                AND b.fechaInicio <= '" . $now->format('Y-m-d') . "' 
                AND b.fechaFin >=  '" . $now->format('Y-m-d') . "'
                ORDER BY b.fechaFin ASC ";

            $query = $this->em->createQuery($dql);
            $banners = $query->getResult();

            if (count($banners) > 0) {
                foreach ($banners as $banner) {

                    $dql = "SELECT vb FROM LogicBundle:VistaBanner vb
                    WHERE vb.banner = '" . $banner->getId() . "'
                    AND vb.usuario = '" . $user->getId() . "'
                    AND vb.fecha BETWEEN '" . $now->format('Y-m-d') . " 00:00:00.000000' AND '" . $now->format('Y-m-d') . " 23:59:59.999999'";

                    $query = $this->em->createQuery($dql);
                    $visitabanner = $query->getResult();

                    if (count($visitabanner) <= 0) {

                        $dql = "SELECT vb FROM LogicBundle:VistaBanner vb
                        WHERE vb.banner = '" . $banner->getId() . "'
                        AND vb.usuario = '" . $user->getId() . "'
                        ";

                        $query = $this->em->createQuery($dql);
                        $visitabanner = $query->getResult();

                        if (count($visitabanner) <= $banner->getVecesVisto()) {
                            $vista = new VistaBanner();
                            $vista->setFecha($now);
                            $vista->setDispositivo("web");
                            $vista->setUsuario($user);
                            $vista->setBanner($banner);


                            $this->em->persist($vista);
                            $this->em->flush();

                            $phat = $banner->getImagenWeb();
                            break;
                        }
                    }
                }
            }
        }
        return $phat;
    }

    public function getName() {
        return 'get_banner';
    }

}
