<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * InstitucionalEstrategia
 *
 * @ORM\Table(name="institucional_estrategia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\InstitucionalEstrategiaRepository")
 */
class InstitucionalEstrategia {

    public function __toString() {
        return $this->getCategoriaInstitucional() ? $this->getCategoriaInstitucional()->getNombre() : '';
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaInstitucional", inversedBy="estrategias")
     * @ORM\JoinColumn(name="categoria_institucional_id", referencedColumnName="id")
     */
    private $categoriaInstitucional;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Estrategia", inversedBy="institucionalEstrategias")
     * @ORM\JoinColumn(name="estrategia_id", referencedColumnName="id")
     */
    private $estrategia;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="institucionalEstrategia")
     */
    private $ofertas;

    /**
     * @var int
     *
     * @ORM\Column(name="cobertura_minima", type="integer")
     */
    private $coberturaMinima;

    /**
     * @var int
     *
     * @ORM\Column(name="cobertura_maxima", type="integer")
     */
    private $coberturaMaxima;

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
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set coberturaMinima
     *
     * @param integer $coberturaMinima
     *
     * @return InstitucionalEstrategia
     */
    public function setCoberturaMinima($coberturaMinima) {
        $this->coberturaMinima = $coberturaMinima;

        return $this;
    }

    /**
     * Get coberturaMinima
     *
     * @return integer
     */
    public function getCoberturaMinima() {
        return $this->coberturaMinima;
    }

    /**
     * Set coberturaMaxima
     *
     * @param integer $coberturaMaxima
     *
     * @return InstitucionalEstrategia
     */
    public function setCoberturaMaxima($coberturaMaxima) {
        $this->coberturaMaxima = $coberturaMaxima;

        return $this;
    }

    /**
     * Get coberturaMaxima
     *
     * @return integer
     */
    public function getCoberturaMaxima() {
        return $this->coberturaMaxima;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return InstitucionalEstrategia
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
     * @return InstitucionalEstrategia
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
     * Set categoriaInstitucional
     *
     * @param \LogicBundle\Entity\CategoriaInstitucional $categoriaInstitucional
     *
     * @return InstitucionalEstrategia
     */
    public function setCategoriaInstitucional(\LogicBundle\Entity\CategoriaInstitucional $categoriaInstitucional = null) {
        $this->categoriaInstitucional = $categoriaInstitucional;

        return $this;
    }

    /**
     * Get categoriaInstitucional
     *
     * @return \LogicBundle\Entity\CategoriaInstitucional
     */
    public function getCategoriaInstitucional() {
        return $this->categoriaInstitucional;
    }

    /**
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return InstitucionalEstrategia
     */
    public function setEstrategia(\LogicBundle\Entity\Estrategia $estrategia = null) {
        $this->estrategia = $estrategia;

        return $this;
    }

    /**
     * Get estrategia
     *
     * @return \LogicBundle\Entity\Estrategia
     */
    public function getEstrategia() {
        return $this->estrategia;
    }

    /**
     * Add oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return InstitucionalEstrategia
     */
    public function addOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     */
    public function removeOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas->removeElement($oferta);
    }

    /**
     * Get ofertas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertas() {
        return $this->ofertas;
    }

}
