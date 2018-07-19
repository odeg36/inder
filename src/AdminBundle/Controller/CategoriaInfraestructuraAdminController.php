<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use LogicBundle\Form\CategoriaInfraestructuraType;
use LogicBundle\Entity\CategoriaInfraestructura;
use LogicBundle\Form\SubCategoriaInfraestructuraType;
use LogicBundle\Entity\SubcategoriaInfraestructura;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

class CategoriaInfraestructuraAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_categoriainfraestructura_addcampo', array('id' =>0));
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
        return $this->redirectToRoute('admin_logic_categoriainfraestructura_addcampo', array('id' =>$id));
    }



    function addcampoAction(Request $request, $id){
       
        if($id == 0){
            $categoriaInfraestructura = new CategoriaInfraestructura();
        }else{
            $categoriaInfraestructura = $this->em->getRepository("LogicBundle:CategoriaInfraestructura")->find($id);
        }
        $form = $this->createForm( CategoriaInfraestructuraType::class, $categoriaInfraestructura, array());
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted()) {
            
            if ($form->isValid()) {
			
				$this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_categoriainfraestructura_list');
            }
        }
        
        $subCatInfraestructura = "";
        if($categoriaInfraestructura != null){
            $subCatInfraestructura = $categoriaInfraestructura->getSubInfraestructuras();
        }
        
        return $this->renderWithExtraParams('AdminBundle:CategoriaInfraestructura:create.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
            'idescenario' => $id,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'subcategoria'=> $subCatInfraestructura
        ], null);
    }
}
