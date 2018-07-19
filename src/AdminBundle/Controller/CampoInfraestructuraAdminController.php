<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use LogicBundle\Form\CampoInfraestructuraType;
use LogicBundle\Entity\CampoInfraestructura;
use LogicBundle\Entity\OpcionCampoInfraestructura;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;


class CampoInfraestructuraAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_campoinfraestructura_addcampo', array('id' =>0));
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
        return $this->redirectToRoute('admin_logic_campoinfraestructura_addcampo', array('id' =>$id));
    }

    function addcampoAction(Request $request, $id){
       
        if($id == 0){
            $campo = new CampoInfraestructura();
        }else{
            $campo = $this->em->getRepository("LogicBundle:CampoInfraestructura")->find($id);
        }
   
        $form = $this->createForm( CampoInfraestructuraType::class, $campo, array());
          
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
          
            if ($form->isValid()) {
			
				if(
					$form->getData()->getTipoEntrada() == "Area de texto" ||
					$form->getData()->getTipoEntrada() == "Texto" ||
					$form->getData()->getTipoEntrada() == "Fecha" ||
					$form->getData()->getTipoEntrada() == "Numero" ||
					$form->getData()->getTipoEntrada() == "" 					
				){
					foreach($form->getData()->getOpcionCampoInfraestructuras() as $opcionCampo){
						$form->getData()->removeOpcionCampoInfraestructura($opcionCampo);
					}
				}

                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_campoinfraestructura_list');
            }
        }
		$tipoEntrada = "";
		if($campo != null){
			$tipoEntrada = $campo->getTipoEntrada();
		}

        return $this->render('AdminBundle:CampoInfraestructura:create.html.twig', array(
            'form' => $form->createView(),
			'idescenario' => $id ,
			'tipoEntrada' => $tipoEntrada
        ));
    }
}
