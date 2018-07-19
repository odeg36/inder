<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoTiempoEjecucion
 *
 * @ORM\Table(name="tipo_tiempo_ejecucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoTiempoEjecucionRepository")
 */
class TipoTiempoEjecucion
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Actividad", mappedBy="tipoTiempoEjecucion")
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
     * @return TipoTiempoEjecucion
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
     * @param \LogicBundle\Entity\Actividad $actividad
     *
     * @return TipoTiempoEjecucion
     */
    public function addActividad(\LogicBundle\Entity\Actividad $actividad) {
        $this->actividades[] = $actividad;

        return $this;
    }

    /**
     * Remove actividad
     *
     * @param \LogicBundle\Entity\Actividad $actividad
     */
    public function removeActividad(\LogicBundle\Entity\Actividad $actividad) {
        $this->actividades->removeElement($actividad);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades() {
        return $this->actividades;
    }

    /**
     * Add actividade
     *
     * @param \LogicBundle\Entity\Actividad $actividade
     *
     * @return TipoTiempoEjecucion
     */
    public function addActividade(\LogicBundle\Entity\Actividad $actividade)
    {
        $this->actividades[] = $actividade;

        return $this;
    }

    /**
     * Remove actividade
     *
     * @param \LogicBundle\Entity\Actividad $actividade
     */
    public function removeActividade(\LogicBundle\Entity\Actividad $actividade)
    {
        $this->actividades->removeElement($actividade);
    }
}
