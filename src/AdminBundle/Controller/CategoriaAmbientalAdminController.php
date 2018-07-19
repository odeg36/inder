<?php

namespace AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use LogicBundle\Form\CategoriaAmbientalType;
use LogicBundle\Entity\CategoriaAmbiental;
use LogicBundle\Form\SubcategoriaAmbientalType;
use LogicBundle\Entity\SubCategoriaAmbiental;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;
use Sonata\AdminBundle\Controller\CRUDController;

class CategoriaAmbientalAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_categoriaambiental_addcampo', array('id' =>0));
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
        return $this->redirectToRoute('admin_logic_categoriaambiental_addcampo', array('id' =>$id));
    }
    function addcampoAction(Request $request, $id){
       
        if($id == 0){
            $categoriaAmbiental = new CategoriaAmbiental();
        }else{
            $categoriaAmbiental = $this->em->getRepository("LogicBundle:CategoriaAmbiental")->find($id);
        }
        
        $form = $this->createForm( CategoriaAmbientalType::class, $categoriaAmbiental, array());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                foreach($categoriaAmbiental->getSubcategoriaAmbientales() as $subCategoriaAmbiental){
                    $subCategoriaAmbiental->setCategoriaAmbiental($categoriaAmbiental);
                    $this->em->persist($subCategoriaAmbiental);
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_categoriaambiental_list');
            }
        }
        
        $subCatAmbiental = "";
        if($categoriaAmbiental != null){
            $subCatAmbiental = $categoriaAmbiental->getSubcategoriaAmbientales();
        }

        return $this->renderWithExtraParams('AdminBundle:CategoriaAmbiental:create.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
            'idescenario' => $id,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'subcategoria'=> $subCatAmbiental
        ], null);
        
    }
}
