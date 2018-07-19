<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaveObservacion
 *
 * @ORM\Table(name="clave_observacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ClaveObservacionRepository")
 */
class ClaveObservacion
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ActividadPlanClase", mappedBy="claveObservacion")
     */
    private $actividades;


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
     * @return ClaveObservacion
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
     * Add actividad
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividad
     *
     * @return ClaveObservacion
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
     * Add actividade
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $actividade
     *
     * @return ClaveObservacion
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
