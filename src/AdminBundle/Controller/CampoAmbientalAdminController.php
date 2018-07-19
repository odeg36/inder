<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use LogicBundle\Form\CampoAmbientalType;
use LogicBundle\Entity\CampoAmbiental;
use LogicBundle\Entity\OpcionCampoAmbiental;
use Symfony\Component\HttpFoundation\Request;


use LogicBundle\Entity\TipoReservaEscenarioDeportivo;
use LogicBundle\Entity\DisciplinasEscenarioDeportivo;
use LogicBundle\Entity\TendenciaEscenarioDeportivo;
use LogicBundle\Entity\UsuarioEscenarioDeportivo;
use LogicBundle\Entity\ArchivoEscenario;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

class CampoAmbientalAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_campoambiental_addcampo', array('id' =>0));
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
        return $this->redirectToRoute('admin_logic_campoambiental_addcampo', array('id' =>$id));
    }

    function addcampoAction(Request $request, $id){
       
        if($id == 0){
            $campo = new CampoAmbiental();
        }else{
            $campo = $this->em->getRepository("LogicBundle:CampoAmbiental")->find($id);
        }
        
        $form = $this->createForm( CampoAmbientalType::class, $campo, array());
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
					foreach($form->getData()->getOpcionesCampo() as $opcionCampo){
						$form->getData()->removeOpcionesCampo($opcionCampo);
					}
				}
                foreach($campo->getOpcionesCampo() as $opcionesCampo){
                    $opcionesCampo->setCampo($campo);
                    $this->em->persist($opcionesCampo);
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_campoambiental_list');
            }
        }
		$tipoEntrada = "";
		if($campo != null){
			$tipoEntrada = $campo->getTipoEntrada();
		}

        return $this->render('AdminBundle:Campo:create.html.twig', array(
            'form' => $form->createView(),
			'idescenario' => $id ,
			'tipoEntrada' => $tipoEntrada
        ));
    }


}
