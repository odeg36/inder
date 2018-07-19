<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanClase
 *
 * @ORM\Table(name="plan_clase")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PlanClaseRepository")
 */
class PlanClase
{
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime")
     */
    private $fechaFin;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ActividadPlanClase", mappedBy="planClase")
     */
    private $actividades;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Oferta", inversedBy="planesClase")
     * @ORM\JoinColumn(name="oferta_id",referencedColumnName="id")
     */
    private $oferta;


    /**
     * Constructor
     */
    public function __construct() {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return PlanClase
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return PlanClase
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return PlanClase
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Add actividad
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividad
     *
     * @return PlanClase
     */
    public function addActividad(\LogicBundle\Entity\ActividadPlanClase $actividad) {
        $this->actividades[] = $actividad;

        return $this;
    }

    /**
     * Remove actividad
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividad
     */
    public function removeActividad(\LogicBundle\Entity\ActividadPlanClase $actividad)
    {
        $this->actividad->removeElement($actividad);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades()
    {
        return $this->actividades;
    }

    /**
     * Set oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return PlanClase
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null) {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \LogicBundle\Entity\Oferta
     */
    public function getOferta() {
        return $this->oferta;
    }

    /**
     * Add actividade
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividade
     *
     * @return PlanClase
     */
    public function addActividade(\LogicBundle\Entity\ActividadPlanClase $actividade)
    {
        $this->actividades[] = $actividade;

        return $this;
    }

    /**
     * Remove actividade
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividade
     */
    public function removeActividade(\LogicBundle\Entity\ActividadPlanClase $actividade)
    {
        $this->actividades->removeElement($actividade);
    }
}
