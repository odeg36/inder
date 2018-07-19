<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Segmentacion
 *
 * @ORM\Table(name="segmentacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SegmentacionRepository")
 */
class Segmentacion {

    public function __toString() {
        $nombre = "";
        if ($this->nombre) {
            $nombre = $this->nombre;
            if ($this->nombre == "Adultos mayores") {
               $nombre .=  ": " . $this->edadMinima ." años en adelante";
            }
            if ( $this->edadMaxima - $this->edadMinima < 50) {
               $nombre .=   ": " . $this->edadMinima . " - " . $this->edadMaxima . " años";
            }
        }
        return $nombre;
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
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Estrategia", mappedBy="segmentacion")
     */
    private $estrategias;

    /**
     * @var int
     *
     * @ORM\Column(name="edad_minima", type="integer")
     */
    private $edadMinima;

    /**
     * @var int
     *
     * @ORM\Column(name="edad_maxima", type="integer")
     */
    private $edadMaxima;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * Constructor
     */
    public function __construct() {
        $this->estrategias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Segmentacion
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Segmentacion
     */
    public function setActivo($activo) {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * Set edadMinima
     *
     * @param integer $edadMinima
     *
     * @return Segmentacion
     */
    public function setEdadMinima($edadMinima) {
        $this->edadMinima = $edadMinima;

        return $this;
    }

    /**
     * Get edadMinima
     *
     * @return integer
     */
    public function getEdadMinima() {
        return $this->edadMinima;
    }

    /**
     * Set edadMaxima
     *
     * @param integer $edadMaxima
     *
     * @return Segmentacion
     */
    public function setEdadMaxima($edadMaxima) {
        $this->edadMaxima = $edadMaxima;

        return $this;
    }

    /**
     * Get edadMaxima
     *
     * @return integer
     */
    public function getEdadMaxima() {
        return $this->edadMaxima;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Segmentacion
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Segmentacion
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Add estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return Segmentacion
     */
    public function addEstrategia(\LogicBundle\Entity\Estrategia $estrategia) {
        $this->estrategias[] = $estrategia;

        return $this;
    }

    /**
     * Remove estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     */
    public function removeEstrategia(\LogicBundle\Entity\Estrategia $estrategia) {
        $this->estrategias->removeElement($estrategia);
    }

    /**
     * Get estrategias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstrategias() {
        return $this->estrategias;
    }

}
