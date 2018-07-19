<?php

namespace ServicesBundle\Controller\Banner;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use ITO\APIBundle\Tools\ResponseBuilder as ResponseBuilder;
use ITO\OAuthServerBundle\Interfaces\ClientAuthenticatedController;
use ITO\OAuthServerBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\HttpFoundation\Request as Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

use LogicBundle\Entity\VistaBanner;

class BannerController extends Controller implements  TokenAuthenticatedController {

    protected $container = null;
    protected $em = null;
    protected $trans = null;
    protected $protocolo = "http://";

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
        $this->trans = $container->get("translator");
    }

    /**
     * Catalogo
     *
     * @ApiDoc(
     *  section="Banner",
     *  resource=true,
     *  description="Obtiene un banner para mostrar al Usuario",
     *  statusCodes = {
     *     200 = "Ok",
     *     400 = "Errores"
     *  }     
     * )
     * 
     * @Get("/banner/{user_id}/{device}")
     */
    public function getBannerAction(Request $request,$user_id,$device) {
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $servidor = $_SERVER["HTTP_HOST"];
        $now = new \DateTime();
        
        $view = $this->view($this->trans->trans("message.api.error.no.encontrado"), 200);        
        if(!$user){
            $dql = "SELECT u FROM ApplicationSonataUserBundle:User u 
            WHERE u.id = :id";
            $parameters = [];
            $parameters["id"] = $user_id;
            
            $responseBuilder = $this->container->get('ito.tools.response.builder');
            $user = $responseBuilder->getItem($dql, $parameters);
        }
        if($user){
            $barrio = $user->getBarrio();
            if($barrio == null){                
                $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                $view = $this->view($error, 404);
                return $this->handleView($view);
            } else { 
                $comuna = $barrio->getComuna();    
                if(!$comuna){
                    $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                    $view = $this->view($error, 404);
                    return $this->handleView($view);
                } else {
                                    
                    $dql = "SELECT b.id, b.fechaInicio, b.nombre, b.fechaFin, b.vecesVisto, b.imagenWeb, b.imagenMobil, c.nombre as nombre_comuna, c.id as id_comuna FROM LogicBundle:Banner b
                        JOIN b.comunas c
                        WHERE c.id = '".$comuna->getId()."'
                        AND b.fechaInicio <= '".$now->format('Y-m-d')."' 
                        AND b.fechaFin >=  '".$now->format('Y-m-d')."'
                        ORDER BY b.fechaFin ASC ";
                        
                    $query = $this->em->createQuery($dql);
                    $banners = $query->getResult();
                    $requestBanner = array();
                    if(count($banners) > 0){
                        foreach($banners as $banner){
                    
                            $dql = "SELECT vb FROM LogicBundle:VistaBanner vb
                            WHERE vb.banner = '".$banner['id']."'
                            AND vb.usuario = '".$user->getId()."'";

                            /*if ($banner['imagenWeb'] != null) {
                                $rutaImage = $this->protocolo.$servidor."/uploads/".$banner['imagenWeb'];
                                $banner['imagenWeb'] = $rutaImage;
                            }
                            if ($banner['imagenMobil'] != null) {
                                $rutaImage = $this->protocolo.$servidor."/uploads/".$banner['imagenMobil'];
                                $banner['imagenMobil'] = $rutaImage;
                            }*/
                            if ($banner['fechaInicio'] != null) {$banner['fechaInicio'] = $banner['fechaInicio']->format('Y-m-d');}
                            if ($banner['fechaFin'] != null) {$banner['fechaFin'] = $banner['fechaFin']->format('Y-m-d');}
                            $query = $this->em->createQuery($dql);
                            $visitabanner = $query->getResult();
                            array_push($requestBanner, $banner);
                            if(count($visitabanner) <= $banner['vecesVisto']){
                                $vista = new VistaBanner();
                                $vista->setFecha($now);
                                $vista->setDispositivo($device);
                                $vista->setUsuario($user);
                                $ban = $this->em->getRepository("LogicBundle:Banner")->find($banner['id']);
                                $vista->setBanner($ban);
                                
                                $this->em->persist($vista);
                                $this->em->flush();
                                $view = $this->view($banner, 200);
                                break;
                            }else{
                                $view = $this->view($banner, 200);
                            }
                        }
                    }else{                        
                        $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
                        $view = $this->view($error, 404);
                        return $this->handleView($view);
                    }
                }
            }
        }else{
            $error = array('error' =>$this->trans->trans("api.mensajes.error.no_encontrado"));
            $view = $this->view($error, 404);
            return $this->handleView($view);
        }        
        return $this->handleView($view);
    }
}