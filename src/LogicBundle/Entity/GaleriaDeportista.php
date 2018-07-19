<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * GaleriaDeportista
 *
 * @ORM\Table(name="galeria_deportista")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\GaleriaDeportistaRepository")
 */
class GaleriaDeportista
{
    const TIPO_IMAGEN = "Imagen";
    const TIPO_VIDEO = "Video";
    
    public function __toString() {
        return $this->getFechaCreacion() ? $this->getFechaCreacion()->format("d-m-Y") : '';
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
     * @ORM\Column(name="tipo", type="string", length=30)
     */
    private $tipo;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="activa", type="boolean", nullable=true)
     */
    private $activa;
    
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="galerias")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;
    
    /**
     * @ORM\OneToMany(targetEntity="MultimediaDeportista", mappedBy="galeria", cascade={"persist"})
     */
    private $multimedias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->multimedias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return GaleriaDeportista
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return GaleriaDeportista
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
     * @return GaleriaDeportista
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
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return GaleriaDeportista
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista()
    {
        return $this->deportista;
    }

    /**
     * Add multimedia
     *
     * @param \LogicBundle\Entity\MultimediaDeportista $multimedia
     *
     * @return GaleriaDeportista
     */
    public function addMultimedia(\LogicBundle\Entity\MultimediaDeportista $multimedia)
    {
        $this->multimedias[] = $multimedia;

        return $this;
    }

    /**
     * Remove multimedia
     *
     * @param \LogicBundle\Entity\MultimediaDeportista $multimedia
     */
    public function removeMultimedia(\LogicBundle\Entity\MultimediaDeportista $multimedia)
    {
        $this->multimedias->removeElement($multimedia);
    }

    /**
     * Get multimedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMultimedias()
    {
        return $this->multimedias;
    }

    /**
     * Set activa
     *
     * @param boolean $activa
     *
     * @return GaleriaDeportista
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return boolean
     */
    public function getActiva()
    {
        return $this->activa;
    }
}
