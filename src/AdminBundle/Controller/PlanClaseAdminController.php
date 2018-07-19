<?php

namespace AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use LogicBundle\Form\PlanClaseType;
use LogicBundle\Entity\PlanClase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

class PlanClaseAdminController extends CRUDController {

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
        return $this->redirectToRoute('admin_logic_planclase_crearPlanClase', array('id' => 0));
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
        if ($id == null) {
            $id = 0;
        }
        return $this->redirectToRoute('admin_logic_planclase_crearPlanClase', array('id' => $id));
    }

    function crearPlanClaseAction(Request $request, $id) {
        $planClase = $this->em->getRepository("LogicBundle:PlanClase")->find($id);
        if (!$planClase) {
            $planClase = new PlanClase();
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $roles = $this->getUser()->getRoles();

        if ($planClase->getId() != null) {

            $planAnualTecnico = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")
                            ->createQueryBuilder('pam')
                            ->join('pam.componentes', 'com')
                            ->join('com.contenidos', 'con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('pam.enfoque = 1')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getOneOrNullResult()
            ;

            $planAnualSocial = $this->em->getRepository("LogicBundle:PlanAnualMetodologico")
                            ->createQueryBuilder('pam')
                            ->join('pam.componentes', 'com')
                            ->join('com.contenidos', 'con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('pam.enfoque = 2')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getOneOrNullResult()
            ;

            if ($planAnualTecnico == null && $planAnualSocial == null) {
                $idPlanAnualTecnico = null;
                $idPlanAnualSocial = null;
            } else {
                $idPlanAnualTecnico = $planAnualTecnico->getId();
                $idPlanAnualSocial = $planAnualSocial->getId();
            }

            $componenteTecnico = $this->em->getRepository("LogicBundle:Componente")
                            ->createQueryBuilder('com')
                            ->join('com.contenidos', 'con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('com.planAnualMetodologico = :id_plan_anual')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->setParameter('id_plan_anual', $idPlanAnualTecnico)
                            ->getQuery()->getOneOrNullResult()
            ;

            $modelo = $this->em->getRepository("LogicBundle:Modelo")
                            ->createQueryBuilder('m')
                            ->join('m.componente', 'com')
                            ->join('com.contenidos', 'con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('com.planAnualMetodologico = :id_plan_anual')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->setParameter('id_plan_anual', $idPlanAnualSocial)
                            ->getQuery()->getOneOrNullResult()
            ;

            $componenteSocial = $this->em->getRepository("LogicBundle:Componente")
                            ->createQueryBuilder('com')
                            ->join('com.contenidos', 'con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('com.planAnualMetodologico = :id_plan_anual')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->setParameter('id_plan_anual', $idPlanAnualSocial)
                            ->getQuery()->getOneOrNullResult()
            ;

            if ($componenteTecnico == null && $componenteSocial == null) {
                $idComponenteTecnico = null;
                $idComponenteSocial = null;
            } else {
                $idComponenteTecnico = $componenteTecnico->getId();
                $idComponenteSocial = $componenteSocial->getId();
            }

            $contenidoTecnico = $this->em->getRepository("LogicBundle:Contenido")
                            ->createQueryBuilder('con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('con.componente = :id_componente')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->setParameter('id_componente', $idComponenteTecnico)
                            ->getQuery()->getOneOrNullResult()
            ;

            $contenidoSocial = $this->em->getRepository("LogicBundle:Contenido")
                            ->createQueryBuilder('con')
                            ->join('con.actividades', 'a')
                            ->join('a.planesClase', 'ap')
                            ->join('ap.planClase', 'pc')
                            ->where('pc.id = :id_plan_clase')
                            ->andWhere('con.componente = :id_componente')
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->setParameter('id_componente', $idComponenteSocial)
                            ->getQuery()->getOneOrNullResult()
            ;

            $estrategia = $planClase->getOferta()->getEstrategia();

            $actividadesParte1 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 1)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();

            $actividadesParte2 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 2)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();

            $actividadesParte3 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 3)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();

            $actividadesParte4 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 4)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();

            $actividadesParte5 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 5)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();

            $actividadesParte6 = $this->em->getRepository("LogicBundle:ActividadPlanClase")
                            ->createQueryBuilder('apc')
                            ->where('apc.parte = :parte')
                            ->andWhere('apc.planClase = :id_plan_clase')
                            ->setParameter('parte', 6)
                            ->setParameter('id_plan_clase', $planClase->getId())
                            ->getQuery()->getResult();
        } else {
            $planAnualTecnico = null;
            $planAnualSocial = null;
            $idPlanAnualTecnico = null;
            $idPlanAnualSocial = null;
            $estrategia = null;
            $componenteTecnico = null;
            $modelo = null;
            $componenteSocial = null;
            $idComponenteTecnico = null;
            $idComponenteSocial = null;
            $contenidoTecnico = null;
            $contenidoSocial = null;
            $actividadesParte1 = null;
            $actividadesParte2 = null;
            $actividadesParte3 = null;
            $actividadesParte4 = null;
            $actividadesParte5 = null;
            $actividadesParte6 = null;
        }

        $form = $this->createForm(PlanClaseType::class, $planClase, array(
            'planClaseId' => $planClase->getId(),
            'em' => $this->container,
            'userId' => $id,
            'pamTecnico' => $planAnualTecnico,
            'pamSocial' => $planAnualSocial,
            'componenteTecnico' => $componenteTecnico,
            'componenteSocial' => $componenteSocial,
            'contenidoTecnico' => $contenidoTecnico,
            'contenidoSocial' => $contenidoSocial,
            'modelo' => $modelo,
            'roles' => $roles,
            'estrategia' => $estrategia,
            'actividadesParte1' => $actividadesParte1,
            'actividadesParte2' => $actividadesParte2,
            'actividadesParte3' => $actividadesParte3,
            'actividadesParte4' => $actividadesParte4,
            'actividadesParte5' => $actividadesParte5,
            'actividadesParte6' => $actividadesParte6
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                foreach ($form->get('actividades') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(1);
                    $this->em->persist($actividad);
                }
                foreach ($form->get('actividades2') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(2);
                    $this->em->persist($actividad);
                }
                foreach ($form->get('actividades3') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(3);
                    $this->em->persist($actividad);
                }
                foreach ($form->get('actividades4') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(4);
                    $this->em->persist($actividad);
                }
                foreach ($form->get('actividades5') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(5);
                    $this->em->persist($actividad);
                }
                foreach ($form->get('actividades6') as $formActividad) {
                    $actividad = $formActividad->getData();
                    $actividad->setPlanClase($planClase);
                    $actividad->setParte(6);
                    $this->em->persist($actividad);
                }
                $this->em->persist($form->getData());
                $this->em->flush();

                return $this->redirectToRoute('admin_logic_planclase_list');
            }
        }

        return $this->render('AdminBundle:PlanClase:planClases.html.twig', array(
                    'form' => $form->createView(),
                    'planClaseId' => $planClase->getId(),
        ));
    }

}
