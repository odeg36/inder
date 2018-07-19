<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use LogicBundle\Form\PlanAnualMetodologicoType;
use LogicBundle\Entity\PlanAnualMetodologico;
use LogicBundle\Entity\Area;
use LogicBundle\Entity\Estrategia;
use LogicBundle\Entity\Enfoque;
use LogicBundle\Entity\Nivel;
use LogicBundle\Entity\Contenido;
use LogicBundle\Entity\Actividad;
use LogicBundle\Entity\TipoTiempoEjecucion;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

class PlanAnualMetodologicoAdminController extends CRUDController
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
        return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>0));
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
        return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>$id));
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
    public function showAction($id = NULL) {
        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if($id == 0){
            $planAnualMetodologico = new PlanAnualMetodologico();
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
        }        
        
        return $this->render('AdminBundle:PlanAnualMetodologico:mostrar_guia_metodologica.html.twig', [            
            'idPam' => $id,
            'pam' => $planAnualMetodologico, 
            'nombrePam' => $planAnualMetodologico->getNombre()
        ]);
    }

    function addpaso1Action(Request $request, $id){
        
        if($id == 0){
            $planAnualMetodologico = new PlanAnualMetodologico();
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
        }

        $form = $this->createForm( PlanAnualMetodologicoType::class, $planAnualMetodologico, array(
            'paso' => 1, 
            'planAnualMetodologicoId' => $planAnualMetodologico->getId(),
            'em' => $this->container
        ));
        
        $form->handleRequest($request);
        
        $enfoque = $planAnualMetodologico->getEnfoque();
        $clasificacion = $planAnualMetodologico->getClasificacion();
 
        if ($form->isSubmitted()) {
            $enfoqueField = $form->get("enfoque")->getData();
            $clasificacionField = $form->get("clasificacion")->getData();

            if($enfoqueField != null){
                if($enfoqueField->getId() == 1){
                    if(!$clasificacionField){
                        $form->get("clasificacion")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.select_clasificacion'))
                        );
                    }
                    $enfoque = $enfoqueField;
                }
            }
            if ($clasificacionField != null) {
                if($clasificacionField->getId() == 1){
                    $disciplina = $form->get("disciplina")->getData();
                    $niveles = $form->get("niveles")->getData();

                    if(!$disciplina){
                        $form->get("disciplina")->addError(
                            new FormError($this->trans->trans('formulario.oferta.select_disciplina'))
                        );
                    }
                    if(!$niveles){
                        $form->get("niveles")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.select_nivel'))
                        );
                    }
                }
                $clasificacion = $clasificacionField;
            }
          
            if ($form->isValid()) {
                if ($clasificacionField != null) {
                    if($clasificacionField->getId() != 1){
                        if ($planAnualMetodologico->getDisciplina()) {
                            $planAnualMetodologico->setDisciplina(null);
                        }
                        if ($planAnualMetodologico->getNiveles()) {
                            foreach ($planAnualMetodologico->getNiveles() as $nivel) {
                                $planAnualMetodologico->removeNivel($nivel);
                            }
                        }
                    }
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso2', array(
                    'id' =>$planAnualMetodologico->getId()

            ));
            
            }
         }

        $planesSociales = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")
        ->createQueryBuilder('pam')
        ->join('pam.enfoque', 'e')
        ->where('e.nombre = :nombre')
        ->setParameter('nombre','Social')
        ->getQuery()->getResult()
        ;

        return $this->render('AdminBundle:PlanAnualMetodologico/Pasos:paso_1.html.twig', array(
            'form' => $form->createView(),
            'idplananualmetodologico' => $id ,
            'enfoque' => $enfoque,
            'clasificacion' => $clasificacion,
            'nombre' => $planAnualMetodologico->getNombre(),
            'cantidadsocial' => count($planesSociales) + 1
        ));
     }
     
    function addpaso2Action(Request $request, $id){
        if($id == 0){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>0));
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
            if($planAnualMetodologico === null){
                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' => 0));
            }
        }
        
        $form = $this->createForm(PlanAnualMetodologicoType::class, $planAnualMetodologico, array(
            'paso' => 2, 
            'planAnualMetodologicoId' => $planAnualMetodologico->getId(),
            'enfoqueId' => $planAnualMetodologico->getEnfoque()->getId(),
            'em' => $this->container
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            $enfoque = $planAnualMetodologico->getEnfoque()->getId();
            
            if($enfoque == 1){
            
                $ponderaciones = 0;

                foreach($planAnualMetodologico->getComponentes() as $componente){
                    $ponderacion = $componente->getPonderacion();
                    $ponderaciones += $ponderacion;
                }

                if($ponderaciones < 99.94){
                    $componentes = $form->get("componentes");
                    foreach($componentes as $componente){
                        $componente->get("ponderacion")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.validar_ponderacion'))
                        );
                    }
                }

                if($ponderaciones > 100.11){
                    $componentes = $form->get("componentes");
                    foreach($componentes as $componente){
                        $componente->get("ponderacion")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.validar_ponderaciones'))
                        );
                    }
                }
            }

            if ($form->isValid()) {
                
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso3', array(
                    'id' =>$planAnualMetodologico->getId(),
                ));
                
            }
        }
     
        return $this->render('AdminBundle:PlanAnualMetodologico/Pasos:paso_2.html.twig', array(
            'form' => $form->createView(),
            'idplananualmetodologico' => $id,
            'nombre' => $planAnualMetodologico->getNombre(),
            'enfoqueId' =>  $planAnualMetodologico->getEnfoque()->getId()
        ));
        
    }
 
    function addpaso3Action(Request $request, $id){
        if($id == 0){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>0));
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
            if($planAnualMetodologico === null){
                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' => 0));
            }
        }

        $contenidos = array();
        foreach($planAnualMetodologico->getComponentes() as $componente){
            foreach($componente->getContenidos() as $contenido){
                array_push( $contenidos, $contenido);
            }
        }
 
        $form = $this->createForm(PlanAnualMetodologicoType::class, $planAnualMetodologico, array(
            'paso' => 3, 
            'planAnualMetodologicoId' => $planAnualMetodologico->getId(),
            'enfoqueId' => $planAnualMetodologico->getEnfoque()->getId(),
            'contenidos' =>  $contenidos,
            'em' => $this->container
        ));

        $form->handleRequest($request);
    
        if ($form->isSubmitted() ) {

            $enfoque = $planAnualMetodologico->getEnfoque()->getId();
            
            if($enfoque == 1){
            
                $ponderaciones = 0;
                
                foreach($form->get("contenidos") as $contenido){
                    $ponderacion = $contenido->getData()->getPonderacion();
                    $ponderaciones += $ponderacion;
                }
                
                if($ponderaciones < 99.94){
                    $contenidos = $form->get("contenidos");
                    foreach($contenidos as $contenido){
                        $contenido->get("ponderacion")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.validar_ponderacion'))
                        );
                    }
                }
                
                if($ponderaciones > 100.11){
                    $contenidos = $form->get("contenidos");
                    foreach($contenidos as $contenido){
                        $contenido->get("ponderacion")->addError(
                            new FormError($this->trans->trans('formulario_plan_anual_metodologico.validar_ponderaciones'))
                        );
                    }
                }
            }

            if ($form->isValid()) {

                foreach($form->get('contenidos') as $formContenido){
                    $contenido = $formContenido->getData();
                    $this->em->persist($contenido);
                }
                
                $this->em->flush();
    
                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso4', array(
                    'id' =>$planAnualMetodologico->getId(),
                ));
                
            }
        }
        
        return $this->render('AdminBundle:PlanAnualMetodologico/Pasos:paso_3.html.twig', array(
        'form' => $form->createView(),
        'idplananualmetodologico' => $id,
        'nombre' => $planAnualMetodologico->getNombre(),
        'enfoqueId' =>  $planAnualMetodologico->getEnfoque()->getId()
        ));
    }
 
    function addpaso4Action(Request $request, $id){
        if($id == 0){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>0));
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
            if($planAnualMetodologico === null){
                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' => 0));
            }
        }

        $ofertas = array();
        foreach($planAnualMetodologico->getOfertas() as $oferta){
            array_push( $ofertas, $oferta);
        }
        
        $estrategias = array();
        foreach($planAnualMetodologico->getOfertas() as $oferta){
            $estrategia = $oferta->getEstrategia();
            array_push($estrategias, $estrategia);
        }
        
        $form = $this->createForm(PlanAnualMetodologicoType::class, $planAnualMetodologico, array(
            'paso' => 4, 
            'planAnualMetodologicoId' => $planAnualMetodologico->getId(),
            'enfoqueId' => $planAnualMetodologico->getEnfoque()->getId(),
            'ofertas' => $ofertas,
            'estrategias' => $estrategias,
            'em' => $this->container
        ));
 
        $form->handleRequest($request);
 
        if ($form->isSubmitted() ) {
            
            if ($form->isValid()) {
                
                foreach($form->get('estrategias') as $formEstrategia){
                    $ofertas = $formEstrategia->get('ofertas')->getData();
                    foreach($ofertas as $oferta){
                        $oferta->setPlanAnualMetodologico($planAnualMetodologico);
                        $this->em->persist($oferta);
                    }
                }

                $this->em->flush();
    
                return $this->redirectToRoute('admin_logic_plananualmetodologico_planAnualMetodologicoTerminado', array(
                    'id' =>$planAnualMetodologico->getId(),
                )); 
                
            }
        }

        return $this->render('AdminBundle:PlanAnualMetodologico/Pasos:paso_4.html.twig', array(
            'form' => $form->createView(),
            'idplananualmetodologico' => $id,
            'nombre' => $planAnualMetodologico->getNombre(),
            'enfoqueId' =>  $planAnualMetodologico->getEnfoque()->getId()
        ));
    }

    function planAnualMetodologicoTerminadoAction(Request $request, $id){
        if($id == 0){
            return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' =>0));
        }else{
            $planAnualMetodologico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")->find($id);
            if($planAnualMetodologico === null){
                return $this->redirectToRoute('admin_logic_plananualmetodologico_addpaso1', array('id' => 0));
            }
        }
         
        $actividades = $this->em->getRepository("LogicBundle:Actividad")
        ->createQueryBuilder('a')
        ->join('a.contenido', 'con')
        ->join('con.componente', 'com')
        ->join('com.planAnualMetodologico', 'planAnualMetodologico')
        ->where('planAnualMetodologico.id = :id_pam')
        ->setParameter('id_pam',$planAnualMetodologico->getId())
        ->getQuery()->getResult()
        ;
        
        return $this->render('AdminBundle:PlanAnualMetodologico:plananualterminado.html.twig', array(
            'idplananualmetodologico' => $id,
            'nombre' => $planAnualMetodologico->getNombre(),
            'actividades' => $actividades
        ));
    }


}
