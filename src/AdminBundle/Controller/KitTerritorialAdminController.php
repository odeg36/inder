<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use LogicBundle\Form\KitTerritorialType;
use LogicBundle\Entity\KitTerritorial;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

class KitTerritorialAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_kitterritorial_crearKitTerritorial', array('id' => 0));
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
        if($id == null){
            $id = 0;
        }
        return $this->redirectToRoute('admin_logic_kitterritorial_crearKitTerritorial', array('id' => $id));
    }

    function crearKitTerritorialAction(Request $request, $id){

        if($id == 0){
            $kitTerritorial = new KitTerritorial();
        }else{
            $kitTerritorial = $this->em->getRepository("LogicBundle:KitTerritorial")->find($id);
        }
        
        $form = $this->createForm( KitTerritorialType::class, $kitTerritorial, array(
            'kitTerritorialId' => $kitTerritorial->getId(),
            'em' => $this->container
        ));
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            
            if ($form->isValid()) {
                
                foreach($form->get('componentes') as $formComponente){
                    $componente = $formComponente->getData();
                    $componente->setKitTerritorial($kitTerritorial);
                    $this->em->persist($componente);
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_kitterritorial_list');
            }
        }
        
        return $this->render('AdminBundle:KitTerritorial:kitTerritorial.html.twig', array(
            'form' => $form->createView(),
            'kitTerritorialId' => $id ,
        ));
    }
}
