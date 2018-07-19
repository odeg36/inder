<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Disciplina
 *
 * @ORM\Table(name="categoria_institucional")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaInstitucionalRepository")
 */
class CategoriaInstitucional {

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\InstitucionalEstrategia", mappedBy="categoriaInstitucional",cascade={"persist"})
     */
    private $estrategias;

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
     * @return CategoriaInstitucional
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return CategoriaInstitucional
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
     * @return CategoriaInstitucional
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
     * @param \LogicBundle\Entity\InstitucionalEstrategia $estrategia
     *
     * @return CategoriaInstitucional
     */
    public function addEstrategia(\LogicBundle\Entity\InstitucionalEstrategia $estrategia) {
        $this->estrategias[] = $estrategia;

        return $this;
    }

    /**
     * Remove estrategia
     *
     * @param \LogicBundle\Entity\InstitucionalEstrategia $estrategia
     */
    public function removeEstrategia(\LogicBundle\Entity\InstitucionalEstrategia $estrategia) {
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
