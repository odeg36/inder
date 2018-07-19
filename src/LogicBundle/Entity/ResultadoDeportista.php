<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ResultadoDeportista
 *
 * @ORM\Table(name="resultado_deportista")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadoDeportistaRepository")
 */
class ResultadoDeportista {

    public function __toString() {
        return $this->torneo ? $this->torneo : '';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="resultadosDeportista")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;

    /**
     * @var string
     *
     * @ORM\Column(name="torneo", type="string", length=255)
     */
    private $torneo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;
    
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="posicionLograda", type="string", length=255)
     */
    private $posicionLograda;

    /**
     * @var string
     *
     * @ORM\Column(name="marcaTiempo", type="string", length=255)
     */
    private $marcaTiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=255)
     */
    private $unidad;

    /**
     * @var bool
     *
     * @ORM\Column(name="esFogueo", type="boolean")
     */
    private $esFogueo;

    private $nombreDeportista;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set torneo.
     *
     * @param string $torneo
     *
     * @return ResultadoDeportista
     */
    public function setTorneo($torneo)
    {
        $this->torneo = $torneo;

        return $this;
    }

    /**
     * Get torneo.
     *
     * @return string
     */
    public function getTorneo()
    {
        return $this->torneo;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return ResultadoDeportista
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ResultadoDeportista
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set posicionLograda.
     *
     * @param string $posicionLograda
     *
     * @return ResultadoDeportista
     */
    public function setPosicionLograda($posicionLograda)
    {
        $this->posicionLograda = $posicionLograda;

        return $this;
    }

    /**
     * Get posicionLograda.
     *
     * @return string
     */
    public function getPosicionLograda()
    {
        return $this->posicionLograda;
    }

    /**
     * Set marcaTiempo.
     *
     * @param string $marcaTiempo
     *
     * @return ResultadoDeportista
     */
    public function setMarcaTiempo($marcaTiempo)
    {
        $this->marcaTiempo = $marcaTiempo;

        return $this;
    }

    /**
     * Get marcaTiempo.
     *
     * @return string
     */
    public function getMarcaTiempo()
    {
        return $this->marcaTiempo;
    }

    /**
     * Set unidad.
     *
     * @param string $unidad
     *
     * @return ResultadoDeportista
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad.
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set esFogueo.
     *
     * @param bool $esFogueo
     *
     * @return ResultadoDeportista
     */
    public function setEsFogueo($esFogueo)
    {
        $this->esFogueo = $esFogueo;

        return $this;
    }

    /**
     * Get esFogueo.
     *
     * @return bool
     */
    public function getEsFogueo()
    {
        return $this->esFogueo;
    }

    /**
     * Set deportista.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $deportista
     *
     * @return ResultadoDeportista
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getDeportista()
    {
        return $this->deportista;
    }
}
