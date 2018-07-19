<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FuenteFinanciacion
 *
 * @ORM\Table(name="fuente_financiacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FuenteFinanciacionRepository")
 */
class FuenteFinanciacion {

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="fuenteFinanciacion")
     */
    private $ofertas;

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="fuenteFinanciacion")
     */
    private $eventos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FuenteFinanciacion
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
     * @return FuenteFinanciacion
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
     * @return FuenteFinanciacion
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
     * Add oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return FuenteFinanciacion
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

    /**
     * Add Evento
     *
     * @param \LogicBundle\Entity\Evento $eventos
     *
     * @return Rama
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento)
    {
        $this->eventos[] = $evento;

        return $this;
    }


    /**
     * Remove evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento)
    {
        $this->eventos->removeElement($Evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos()
    {
        return $this->eventos;
    }

}
