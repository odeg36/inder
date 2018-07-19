<?php

namespace LogicBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Repository\TendenciaRepository;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tendencia
 *
 * @ORM\Table(name="tendencia")
 * @ORM\Entity(repositoryClass="TendenciaRepository")
 */
class Tendencia
{    
    /////////////// ******** MODIFICADO ************////////////
    
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * Add tendenciaEstrategium
     *
     * @param TendenciaEstrategia $tendenciaEstrategium
     *
     * @return Tendencia
     */
    public function addTendenciaEstrategium(TendenciaEstrategia $tendenciaEstrategium) {
        $tendenciaEstrategium->setTendencias($this);
        $this->tendenciaEstrategia[] = $tendenciaEstrategium;

        return $this;
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TendenciaEstrategia", mappedBy="tendencia")
     */
    private $estrategias;

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
     * @ORM\ManyToMany(targetEntity="OrganizacionDeportiva", mappedBy="tendencias")
     */
    private $organizacionDeportivas;

     /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TendenciaEscenarioDeportivo", mappedBy="tendencia")
     */
    private $tendencias;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->estrategias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizacionDeportivas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendencias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Tendencia
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
     * @return Tendencia
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
     * @return Tendencia
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
     * @param \LogicBundle\Entity\TendenciaEstrategia $estrategia
     *
     * @return Tendencia
     */
    public function addEstrategia(\LogicBundle\Entity\TendenciaEstrategia $estrategia) {
        $this->estrategias[] = $estrategia;

        return $this;
    }

    /**
     * Remove estrategia
     *
     * @param \LogicBundle\Entity\TendenciaEstrategia $estrategia
     */
    public function removeEstrategia(\LogicBundle\Entity\TendenciaEstrategia $estrategia) {
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

    /**
     * Add organizacionDeportiva
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva
     *
     * @return Tendencia
     */
    public function addOrganizacionDeportiva(\LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva)
    {
        $this->organizacionDeportivas[] = $organizacionDeportiva;

        return $this;
    }

    /**
     * Remove organizacionDeportiva
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva
     */
    public function removeOrganizacionDeportiva(\LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva)
    {
        $this->organizacionDeportivas->removeElement($organizacionDeportiva);
    }

    /**
     * Get organizacionDeportivas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizacionDeportivas()
    {
        return $this->organizacionDeportivas;
    }

    /**
     * Add tendencia
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendencia
     *
     * @return Tendencia
     */
    public function addTendencia(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendencia)
    {
        $this->tendencias[] = $tendencia;

        return $this;
    }

    /**
     * Remove tendencia
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendencia
     */
    public function removeTendencia(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendencia)
    {
        $this->tendencias->removeElement($tendencia);
    }

    /**
     * Get tendencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTendencias()
    {
        return $this->tendencias;
    }
}
