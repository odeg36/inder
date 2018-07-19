<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Programacion
 * @Gedmo\Loggable
 * @ORM\Table(name="preinscripcion_oferta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PreinscripcionOfertaRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PreinscripcionOferta {

    const NO_DEFINIDO = "No Definido";
    
    public function __toString() {
        return $this->usuario ? $this->usuario->getFullName(): '';
    }

    private $asistio;
    
    function getAsistio() {
        return $this->asistio;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("asistio")
     */
    function asistio() {
       return false;
    }
    
    function setAsistio($asistio) {
        $this->asistio = $asistio;
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     * @Gedmo\Versioned
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostico", type="text",nullable=true)
     * @Serializer\Expose
     * @Gedmo\Versioned
     */
    private $diagnostico;
    
    
    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean",nullable=true)
     * @Serializer\Expose
     * @Gedmo\Versioned
     */
    private $activo;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     * @Serializer\Expose
     * @Gedmo\Versioned
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     * @Gedmo\Versioned
     */
    protected $fechaActualizacion;

    /**
     * @ORM\ManyToOne(targetEntity="Oferta", inversedBy="preinscritos")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $oferta;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="preinscripciones")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @Serializer\Expose
     * @Gedmo\Versioned
     */
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="PreinscripcionOferta", inversedBy="acompanantes")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $inscriptor;
    
    /**
     * @ORM\OneToMany(targetEntity="PreinscripcionOferta", mappedBy="inscriptor")
     */
    private $acompanantes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acompanantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set diagnostico
     *
     * @param string $diagnostico
     *
     * @return PreinscripcionOferta
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return string
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return PreinscripcionOferta
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return PreinscripcionOferta
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return PreinscripcionOferta
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return PreinscripcionOferta
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \LogicBundle\Entity\Oferta
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return PreinscripcionOferta
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set inscriptor
     *
     * @param \LogicBundle\Entity\PreinscripcionOferta $inscriptor
     *
     * @return PreinscripcionOferta
     */
    public function setInscriptor(\LogicBundle\Entity\PreinscripcionOferta $inscriptor = null)
    {
        $this->inscriptor = $inscriptor;

        return $this;
    }

    /**
     * Get inscriptor
     *
     * @return \LogicBundle\Entity\PreinscripcionOferta
     */
    public function getInscriptor()
    {
        return $this->inscriptor;
    }

    /**
     * Add acompanante
     *
     * @param \LogicBundle\Entity\PreinscripcionOferta $acompanante
     *
     * @return PreinscripcionOferta
     */
    public function addAcompanante(\LogicBundle\Entity\PreinscripcionOferta $acompanante)
    {
        $this->acompanantes[] = $acompanante;

        return $this;
    }

    /**
     * Remove acompanante
     *
     * @param \LogicBundle\Entity\PreinscripcionOferta $acompanante
     */
    public function removeAcompanante(\LogicBundle\Entity\PreinscripcionOferta $acompanante)
    {
        $this->acompanantes->removeElement($acompanante);
    }

    /**
     * Get acompanantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcompanantes()
    {
        return $this->acompanantes;
    }
}
