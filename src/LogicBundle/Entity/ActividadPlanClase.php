<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadPlanClase
 *
 * @ORM\Table(name="actividad_plan_clase")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ActividadPlanClaseRepository")
 */
class ActividadPlanClase
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
     * @var string
     *
     * @ORM\Column(name="complementacion", type="string", length=255, nullable=true)
     */
    private $complementacion;

    /**
     * @var int
     *
     * @ORM\Column(name="parte", type="integer", nullable=true)
     */
    private $parte;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PlanClase", inversedBy="actividades")
     * @ORM\JoinColumn(name="plan_clase_id",referencedColumnName="id")
     */
    private $planClase;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Actividad", inversedBy="planesClase")
     * @ORM\JoinColumn(name="actividad_id",referencedColumnName="id")
     */
    private $actividad;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\ClaveObservacion", inversedBy="actividades")
     * @ORM\JoinColumn(name="clave_observacion_id",referencedColumnName="id")
     */
    private $claveObservacion;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set complementacion
     *
     * @param string $complementacion
     *
     * @return ActividadPlanClase
     */
    public function setComplementacion($complementacion)
    {
        $this->complementacion = $complementacion;

        return $this;
    }

    /**
     * Get complementacion
     *
     * @return string
     */
    public function getComplementacion()
    {
        return $this->complementacion;
    }

    /**
     * Set parte
     *
     * @param integer $parte
     *
     * @return ActividadPlanClase
     */
    public function setParte($parte) {
        $this->parte = $parte;

        return $this;
    }

    /**
     * Get parte
     *
     * @return integer
     */
    public function getParte() {
        return $this->parte;
    }

    /**
     * Set planClase
     *
     * @param string $planClase
     *
     * @return ActividadPlanClase
     */
    public function setPlanClase($planClase)
    {
        $this->planClase = $planClase;

        return $this;
    }

    /**
     * Get planClase
     *
     * @return string
     */
    public function getPlanClase()
    {
        return $this->planClase;
    }

    /**
     * Set actividad
     *
     * @param string $actividad
     *
     * @return ActividadPlanClase
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
     * Set claveObservacion
     *
     * @param string $claveObservacion
     *
     * @return ActividadPlanClase
     */
    public function setClaveObservacion($claveObservacion)
    {
        $this->claveObservacion = $claveObservacion;

        return $this;
    }

    /**
     * Get claveObservacion
     *
     * @return string
     */
    public function getClaveObservacion()
    {
        return $this->claveObservacion;
    }
}
