<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Encuesta
 *
 * @ORM\Table(name="encuesta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuestaRepository")
 */
class Encuesta
{
    const SEMANAL        = "Semanal"; // 
    const MENSUAL        = "Mensual"; // 
    const TRIMESTRAL     = "Trimestral"; // 
    const SEMESTRAL      = "Semestral"; //
   
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
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime $fecha_inicio
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_inico", type="date")
     */
    private $fechaInicio;

    /**
     * @var integer
     *
     * @ORM\Column(name="duracion", type="integer") 
     */
    private $duracion;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="tipo_periodicidad", type="string", length=255) 
     */
    private $tipoPeriodicidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="muestra_comuna", type="integer", nullable=true) 
     */
    private $muestraComuna;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="muestra_estrategia", type="integer", nullable=true ) 
     */
    private $muestraEstrategia;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="muestra_oferta", type="integer", nullable=true ) 
     */
    private $muestraOferta;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuestaPregunta", mappedBy="encuesta",cascade={"persist"}, orphanRemoval=true)
     */
    private $encuestaPreguntas;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Comuna", inversedBy="encuestas")
     * @ORM\JoinColumn(name="comuna_id",referencedColumnName="id", )
     */
    private $comuna;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="encuestas")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id" )
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Estrategia", inversedBy="encuestas")
     * @ORM\JoinColumn(name="estrategia_id", referencedColumnName="id" , nullable=true)
     */
    private $estrategia;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Oferta", inversedBy="encuestas")
     * @ORM\JoinColumn(name="oferta_id", referencedColumnName="id", nullable=true )
     */
    private $oferta; 

    /**
    * Constructor
    */
    public function __construct() {
       
        $this->encuestaPreguntas = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * @return Encuesta
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Encuesta
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Encuesta
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set tipoPeriodicidad
     *
     * @param string $tipoPeriodicidad
     *
     * @return Encuesta
     */
    public function setTipoPeriodicidad($tipoPeriodicidad)
    {
        $this->tipoPeriodicidad = $tipoPeriodicidad;

        return $this;
    }

    /**
     * Get tipoPeriodicidad
     *
     * @return tipoPeriodicidad
     */
    public function getTipoPeriodicidad()
    {
        return $this->tipoPeriodicidad;
    }

    /**
     * Set duracion
     *
     * @param integer $duracion
     *
     * @return Encuesta
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return integer
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Get muestraComuna
     *
     * @return integer
     */
    public function getMuestraComuna()
    {
        return $this->muestraComuna;
    }

    /**
     * Set muestraComuna
     *
     * @param integer $muestraComuna
     *
     * @return Encuesta
     */
    public function setMuestraComuna($muestraComuna)
    {
        $this->muestraComuna = $muestraComuna;

        return $this;
    }

    /**
     * Get muestraEstrategia
     *
     * @return muestraEstrategia
     */
    public function getMuestraEstrategia()
    {
        return $this->muestraEstrategia;
    }

    /**
     * Set muestraEstrategia
     *
     * @param integer $muestraEstrategia
     *
     * @return Encuesta
     */
    public function setMuestraEstrategia($muestraEstrategia)
    {
        $this->muestraEstrategia = $muestraEstrategia;

        return $this;
    }

    /**
     * Set muestraOferta
     *
     * @param integer $muestraOferta
     *
     * @return Encuesta
     */
    public function setMuestraOferta($muestraOferta)
    {
        $this->muestraOferta = $muestraOferta;

        return $this;
    }

    /**
     * Get muestraOferta
     *
     * @return integer
     */
    public function getMuestraOferta()
    {
        return $this->muestraOferta;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Area
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
     * Remove encuestaPregunta
     *
     * @param \LogicBundle\Entity\EncuestaPregunta $encuestaPregunta
     */
    public function removeEncuestaPregunta(\LogicBundle\Entity\EncuestaPregunta $encuestaPregunta) {
        $this->encuestaPreguntas->removeElement($encuestaPregunta);
    }

    /**
     * Get encuestaPreguntas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestaPreguntas() {
        return $this->encuestaPreguntas;
    }

    /**
     * Add encuestaPregunta
     *
     * @param \LogicBundle\Entity\EncuestaPregunta $encuestaPregunta
     *
     * @return Encuesta
     */
    public function addEncuestaPregunta(\LogicBundle\Entity\EncuestaPregunta $encuestaPregunta) {
        $encuestaPregunta->setEncuesta($this);
        $this->encuestaPreguntas[] = $encuestaPregunta;

        return $this;
    }

    /**
     * Set Comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return Encuesta
     */
    public function setComuna(\LogicBundle\Entity\Comuna $comuna = null) {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return \LogicBundle\Entity\Comuna
     */
    public function getComuna() {
        return $this->comuna;
    }
    
    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Encuesta
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User $usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return Encuesta
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
     * Set oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return Encuesta
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null) {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \LogicBundle\Entity\Oferta
     */
    public function getOferta() {
        return $this->oferta;
    }
    

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * Obtener dias por el tipo de periodicidad
     */
    public function diasPeriodo(){

        switch ($this->tipoPeriodicidad) {
            case self::SEMANAL:
                return 7;
                break;
            case self::MENSUAL:
                return 30;
                break;
            case self::TRIMESTRAL:
                return 90;
                break;
            case self::SEMESTRAL:
                return 180;
                break;
        }

    }
 
}
