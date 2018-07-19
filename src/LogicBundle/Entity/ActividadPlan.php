<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadPlan
 *
 * @ORM\Table(name="actividad_plan")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ActividadPlanRepository")
 */
class ActividadPlan
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="PlanMetodologico", inversedBy="actividades")
     * @ORM\JoinColumn(name="plan_metodologico_id", referencedColumnName="id")
     */
    private $planMetodologico;

    /**
     * @var string
     *
     * @ORM\Column(name="actividad", type="text")
     */
    private $actividad;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set actividad
     *
     * @param string $actividad
     *
     * @return ActividadPlan
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return string
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set planMetodologico
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return ActividadPlan
     */
    public function setPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico = null)
    {
        $this->planMetodologico = $planMetodologico;

        return $this;
    }

    /**
     * Get planMetodologico
     *
     * @return \LogicBundle\Entity\PlanMetodologico
     */
    public function getPlanMetodologico()
    {
        return $this->planMetodologico;
    }
}
