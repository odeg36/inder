<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RespuestaFuncional
 *
 * @ORM\Table(name="respuesta_funcional")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RespuestaFuncionalRepository")
 */
class RespuestaFuncional
{
    
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
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
     * @var bool
     *
     * @ORM\Column(name="mareo", type="boolean")
     */
    private $mareo;

    /**
     * @var string
     *
     * @ORM\Column(name="mareo_observacion", type="text", nullable=true)
     */
    private $mareoObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="cansancio", type="boolean")
     */
    private $cansancio;

    /**
     * @var string
     *
     * @ORM\Column(name="cansancio_observacion", type="text", nullable=true)
     */
    private $cansancioObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="vision_borrosa", type="boolean")
     */
    private $visionBorrosa;

    /**
     * @var string
     *
     * @ORM\Column(name="vision_borrosa_observacion", type="text", nullable=true)
     */
    private $visionBorrosaObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="perdida_conocimiento", type="boolean")
     */
    private $perdidaConocimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="perdida_conocimiento_observacion", type="text", nullable=true)
     */
    private $perdidaConocimientoObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="dolor_cabeza", type="boolean")
     */
    private $dolorCabeza;

    /**
     * @var string
     *
     * @ORM\Column(name="dolor_cabeza_observacion", type="text", nullable=true)
     */
    private $dolorCabezaObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="vomito", type="boolean")
     */
    private $vomito;

    /**
     * @var string
     *
     * @ORM\Column(name="vomito_observacion", type="text", nullable=true)
     */
    private $vomitoObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="diarrea", type="boolean")
     */
    private $diarrea;

    /**
     * @var string
     *
     * @ORM\Column(name="diarrea_observacion", type="text", nullable=true)
     */
    private $diarreaObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="dht", type="boolean")
     */
    private $dht;

    /**
     * @var string
     *
     * @ORM\Column(name="dht_observacion", type="text", nullable=true)
     */
    private $dhtObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="otros", type="boolean")
     */
    private $otros;

    /**
     * @var string
     *
     * @ORM\Column(name="otros_observacion", type="text", nullable=true)
     */
    private $otrosObservacion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="respuestaFuncional")
     * @ORM\JoinColumn(name="respuesta_funcional_id", referencedColumnName="id")
     */
    private $nutricion;


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
     * Set mareo
     *
     * @param boolean $mareo
     *
     * @return RespuestaFuncional
     */
    public function setMareo($mareo)
    {
        $this->mareo = $mareo;

        return $this;
    }

    /**
     * Get mareo
     *
     * @return boolean
     */
    public function getMareo()
    {
        return $this->mareo;
    }

    /**
     * Set mareoObservacion
     *
     * @param string $mareoObservacion
     *
     * @return RespuestaFuncional
     */
    public function setMareoObservacion($mareoObservacion)
    {
        $this->mareoObservacion = $mareoObservacion;

        return $this;
    }

    /**
     * Get mareoObservacion
     *
     * @return string
     */
    public function getMareoObservacion()
    {
        return $this->mareoObservacion;
    }

    /**
     * Set cansancio
     *
     * @param boolean $cansancio
     *
     * @return RespuestaFuncional
     */
    public function setCansancio($cansancio)
    {
        $this->cansancio = $cansancio;

        return $this;
    }

    /**
     * Get cansancio
     *
     * @return boolean
     */
    public function getCansancio()
    {
        return $this->cansancio;
    }

    /**
     * Set cansancioObservacion
     *
     * @param string $cansancioObservacion
     *
     * @return RespuestaFuncional
     */
    public function setCansancioObservacion($cansancioObservacion)
    {
        $this->cansancioObservacion = $cansancioObservacion;

        return $this;
    }

    /**
     * Get cansancioObservacion
     *
     * @return string
     */
    public function getCansancioObservacion()
    {
        return $this->cansancioObservacion;
    }

    /**
     * Set visionBorrosa
     *
     * @param boolean $visionBorrosa
     *
     * @return RespuestaFuncional
     */
    public function setVisionBorrosa($visionBorrosa)
    {
        $this->visionBorrosa = $visionBorrosa;

        return $this;
    }

    /**
     * Get visionBorrosa
     *
     * @return boolean
     */
    public function getVisionBorrosa()
    {
        return $this->visionBorrosa;
    }

    /**
     * Set visionBorrosaObservacion
     *
     * @param string $visionBorrosaObservacion
     *
     * @return RespuestaFuncional
     */
    public function setVisionBorrosaObservacion($visionBorrosaObservacion)
    {
        $this->visionBorrosaObservacion = $visionBorrosaObservacion;

        return $this;
    }

    /**
     * Get visionBorrosaObservacion
     *
     * @return string
     */
    public function getVisionBorrosaObservacion()
    {
        return $this->visionBorrosaObservacion;
    }

    /**
     * Set perdidaConocimiento
     *
     * @param boolean $perdidaConocimiento
     *
     * @return RespuestaFuncional
     */
    public function setPerdidaConocimiento($perdidaConocimiento)
    {
        $this->perdidaConocimiento = $perdidaConocimiento;

        return $this;
    }

    /**
     * Get perdidaConocimiento
     *
     * @return boolean
     */
    public function getPerdidaConocimiento()
    {
        return $this->perdidaConocimiento;
    }

    /**
     * Set perdidaConocimientoObservacion
     *
     * @param string $perdidaConocimientoObservacion
     *
     * @return RespuestaFuncional
     */
    public function setPerdidaConocimientoObservacion($perdidaConocimientoObservacion)
    {
        $this->perdidaConocimientoObservacion = $perdidaConocimientoObservacion;

        return $this;
    }

    /**
     * Get perdidaConocimientoObservacion
     *
     * @return string
     */
    public function getPerdidaConocimientoObservacion()
    {
        return $this->perdidaConocimientoObservacion;
    }

    /**
     * Set dolorCabeza
     *
     * @param boolean $dolorCabeza
     *
     * @return RespuestaFuncional
     */
    public function setDolorCabeza($dolorCabeza)
    {
        $this->dolorCabeza = $dolorCabeza;

        return $this;
    }

    /**
     * Get dolorCabeza
     *
     * @return boolean
     */
    public function getDolorCabeza()
    {
        return $this->dolorCabeza;
    }

    /**
     * Set dolorCabezaObservacion
     *
     * @param string $dolorCabezaObservacion
     *
     * @return RespuestaFuncional
     */
    public function setDolorCabezaObservacion($dolorCabezaObservacion)
    {
        $this->dolorCabezaObservacion = $dolorCabezaObservacion;

        return $this;
    }

    /**
     * Get dolorCabezaObservacion
     *
     * @return string
     */
    public function getDolorCabezaObservacion()
    {
        return $this->dolorCabezaObservacion;
    }

    /**
     * Set vomito
     *
     * @param boolean $vomito
     *
     * @return RespuestaFuncional
     */
    public function setVomito($vomito)
    {
        $this->vomito = $vomito;

        return $this;
    }

    /**
     * Get vomito
     *
     * @return boolean
     */
    public function getVomito()
    {
        return $this->vomito;
    }

    /**
     * Set vomitoObservacion
     *
     * @param string $vomitoObservacion
     *
     * @return RespuestaFuncional
     */
    public function setVomitoObservacion($vomitoObservacion)
    {
        $this->vomitoObservacion = $vomitoObservacion;

        return $this;
    }

    /**
     * Get vomitoObservacion
     *
     * @return string
     */
    public function getVomitoObservacion()
    {
        return $this->vomitoObservacion;
    }

    /**
     * Set diarrea
     *
     * @param boolean $diarrea
     *
     * @return RespuestaFuncional
     */
    public function setDiarrea($diarrea)
    {
        $this->diarrea = $diarrea;

        return $this;
    }

    /**
     * Get diarrea
     *
     * @return boolean
     */
    public function getDiarrea()
    {
        return $this->diarrea;
    }

    /**
     * Set diarreaObservacion
     *
     * @param string $diarreaObservacion
     *
     * @return RespuestaFuncional
     */
    public function setDiarreaObservacion($diarreaObservacion)
    {
        $this->diarreaObservacion = $diarreaObservacion;

        return $this;
    }

    /**
     * Get diarreaObservacion
     *
     * @return string
     */
    public function getDiarreaObservacion()
    {
        return $this->diarreaObservacion;
    }

    /**
     * Set dht
     *
     * @param boolean $dht
     *
     * @return RespuestaFuncional
     */
    public function setDht($dht)
    {
        $this->dht = $dht;

        return $this;
    }

    /**
     * Get dht
     *
     * @return boolean
     */
    public function getDht()
    {
        return $this->dht;
    }

    /**
     * Set dhtObservacion
     *
     * @param string $dhtObservacion
     *
     * @return RespuestaFuncional
     */
    public function setDhtObservacion($dhtObservacion)
    {
        $this->dhtObservacion = $dhtObservacion;

        return $this;
    }

    /**
     * Get dhtObservacion
     *
     * @return string
     */
    public function getDhtObservacion()
    {
        return $this->dhtObservacion;
    }

    /**
     * Set otros
     *
     * @param boolean $otros
     *
     * @return RespuestaFuncional
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;

        return $this;
    }

    /**
     * Get otros
     *
     * @return boolean
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Set otrosObservacion
     *
     * @param string $otrosObservacion
     *
     * @return RespuestaFuncional
     */
    public function setOtrosObservacion($otrosObservacion)
    {
        $this->otrosObservacion = $otrosObservacion;

        return $this;
    }

    /**
     * Get otrosObservacion
     *
     * @return string
     */
    public function getOtrosObservacion()
    {
        return $this->otrosObservacion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return RespuestaFuncional
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }
}
