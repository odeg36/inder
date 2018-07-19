<?php

namespace LogicBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Repository\TendenciaEstrategiaRepository;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TendenciaEstrategia
 *
 * @ORM\Table(name="tendencia_estrategia")
 * @ORM\Entity(repositoryClass="TendenciaEstrategiaRepository")
 */
class TendenciaEstrategia {

    /////////////// ******** MODIFICADO ************////////////

    public function __toString() {
        return $this->getTendencia() ? $this->getTendencia()->getNombre() : '';
    }

    //////////////********** FIN MODIFICADO *******///////////

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity="Tendencia", inversedBy="estrategias")
     * @ORM\JoinColumn(name="tendencia_id", referencedColumnName="id")
     */
    private $tendencia;

    /**
     * @ORM\ManyToOne(targetEntity="Estrategia", inversedBy="tendencias")
     * @ORM\JoinColumn(name="estrategia_id", referencedColumnName="id")
     */
    private $estrategia;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="tendenciaEstrategia")
     */
    private $ofertas;

    /**
     * @var DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set coberturaMinima
     *
     * @param integer $coberturaMinima
     *
     * @return TendenciaEstrategia
     */
    public function setCoberturaMinima($coberturaMinima)
    {
        $this->coberturaMinima = $coberturaMinima;

        return $this;
    }

    /**
     * Get coberturaMinima
     *
     * @return integer
     */
    public function getCoberturaMinima()
    {
        return $this->coberturaMinima;
    }

    /**
     * Set coberturaMaxima
     *
     * @param integer $coberturaMaxima
     *
     * @return TendenciaEstrategia
     */
    public function setCoberturaMaxima($coberturaMaxima)
    {
        $this->coberturaMaxima = $coberturaMaxima;

        return $this;
    }

    /**
     * Get coberturaMaxima
     *
     * @return integer
     */
    public function getCoberturaMaxima()
    {
        return $this->coberturaMaxima;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return TendenciaEstrategia
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
     * @return TendenciaEstrategia
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
     * Set tendencia
     *
     * @param \LogicBundle\Entity\Tendencia $tendencia
     *
     * @return TendenciaEstrategia
     */
    public function setTendencia(\LogicBundle\Entity\Tendencia $tendencia = null)
    {
        $this->tendencia = $tendencia;

        return $this;
    }

    /**
     * Get tendencia
     *
     * @return \LogicBundle\Entity\Tendencia
     */
    public function getTendencia()
    {
        return $this->tendencia;
    }

    /**
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return TendenciaEstrategia
     */
    public function setEstrategia(\LogicBundle\Entity\Estrategia $estrategia = null)
    {
        $this->estrategia = $estrategia;

        return $this;
    }

    /**
     * Get estrategia
     *
     * @return \LogicBundle\Entity\Estrategia
     */
    public function getEstrategia()
    {
        return $this->estrategia;
    }

    /**
     * Add oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return TendenciaEstrategia
     */
    public function addOferta(\LogicBundle\Entity\Oferta $oferta)
    {
        $this->ofertas[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     */
    public function removeOferta(\LogicBundle\Entity\Oferta $oferta)
    {
        $this->ofertas->removeElement($oferta);
    }

    /**
     * Get ofertas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertas()
    {
        return $this->ofertas;
    }
}
