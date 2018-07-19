<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

////////entidates que necesito
use LogicBundle\Form\KitTerritorialEditarType;
use LogicBundle\Form\KitTerritorialType;
use LogicBundle\Entity\TemaPorComuna;


class TemaPorComunaAdminController extends CRUDController
{


    protected $em = null;
    
    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }

    
    /**
     * Create action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function createAction() {
        return $this->redirectToRoute('admin_logic_temaporcomuna_addform', array('id' =>0));
    }

        /**
     * Edit action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function editAction($id = null) {
        return $this->redirectToRoute('admin_logic_temaporcomuna_addform', array('id' =>$id));
        
    }


        /**
     * Show action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function showAction($id = NUll) {
        
        if($id == null ||  $id == '')
        {
            $id = 0;

            return $this->redirectToRoute('admin_logic_temaporcomuna_list', array('id' => 0));
        }
        else{

            $temaPorComuna = $this->em->getRepository("LogicBundle:TemaPorComuna");

            $temaComuna =  $temaPorComuna->createQueryBuilder('temaPorComuna')
            ->where('temaPorComuna.comuna = :comuna')
            ->setParameter('comuna', $id ?: 0)
            ->getQuery()->getResult(); 

            $comuna =  $this->em->getRepository("LogicBundle:Comuna")->createQueryBuilder('comuna')
            ->where('comuna.id = :id')
            ->setParameter('id', $id ?: 0)
            ->getQuery()->getOneOrNullResult();

            return $this->render('AdminBundle:KitTerritorial:mostrarkitTerritorial.html.twig', array(
                'temaComuna' => $temaComuna ,
                'comuna' => $comuna->getNombre() ,

            ));  
        }
    }

    
    public function listAction() {

       $temaPorComuna = $this->em->getRepository("LogicBundle:TemaPorComuna");

       $comunas =  $temaPorComuna->createQueryBuilder('temaPorComuna')
        ->groupBy('temaPorComuna.comuna')
        ->getQuery()->getResult(); 
        
          return $this->render('AdminBundle:KitTerritorial:ListakitTerritorial.html.twig', array(
                        'comunas' => $comunas 
          ));
    }

    

    function addformAction(Request $request, $id){

        if($id == 0){
            $temaPorComuna = new TemaPorComuna();
            $temaComuna = null;

        }else{
            $temaPorComuna = $this->em->getRepository("LogicBundle:TemaPorComuna")->find($id);
            $temaComuna =   $this->em->getRepository("LogicBundle:TemaPorComuna")->createQueryBuilder('temaPorComuna')
            ->where('temaPorComuna.comuna = :comuna')
            ->setParameter('comuna', $id ?: 0)
            ->getQuery()->getResult();
        }
        
        
        $form = $this->createForm(KitTerritorialType::class, $temaPorComuna, array(

            'temaComuna' => $temaComuna            
        ));

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ( $form->isValid()) {

                $mainObject = $form->getData();

                foreach($form->get('temaModelo') as $formTemaModelo){
                    $tema = new TemaPorComuna();

                    $object = $formTemaModelo->getData();

                    $tema-> setTemaModelo($object->getTemaModelo());
                    $tema-> setNivel($object->getNivel());
                    $tema-> setComuna($mainObject->getComuna());

                    $this->em->persist($tema);
                    $this->em->flush();
                }
                
                return $this->redirectToRoute('admin_logic_temaporcomuna_list', array('id' => 0));
            }
            
        }
        
        return $this->render('AdminBundle:KitTerritorial:kitTerritorial.html.twig', array(
            'form' => $form->createView() 
        ));
    }



}
