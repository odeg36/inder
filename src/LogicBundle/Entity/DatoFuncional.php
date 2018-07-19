<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatoFuncional
 *
 * @ORM\Table(name="dato_funcional")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DatoFuncionalRepository")
 */
class DatoFuncional
{
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
    }
    
    const DATOS_URINARIOS_NORMAL = 'Normal';
    const DATOS_URINARIOS_ALTERADO = 'Alterada';
    
    const DATOS_CM_REGULAR = 'Regular';
    const DATOS_CM_IREGULAR = 'Iregular';
    const DATOS_CM_NA = 'No aplica';

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
     * @ORM\Column(name="normal", type="boolean")
     */
    private $normal;

    /**
     * @var string
     *
     * @ORM\Column(name="normal_observacion", type="text", nullable=true)
     */
    private $normalObservacion;

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
     * @ORM\Column(name="estrenimiento", type="boolean")
     */
    private $estrenimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="estrenimiento_observacion", type="text", nullable=true)
     */
    private $estrenimientoObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="gastritis", type="boolean")
     */
    private $gastritis;

    /**
     * @var string
     *
     * @ORM\Column(name="gastritis_observacion", type="text", nullable=true)
     */
    private $gastritisObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="colon", type="boolean")
     */
    private $colon;

    /**
     * @var string
     *
     * @ORM\Column(name="colon_observacion", type="text", nullable=true)
     */
    private $colonObservacion;

    /**
     * @var bool
     *
     * @ORM\Column(name="funcionUrinaria", type="string", length=30)
     */
    private $funcionUrinaria;

    /**
     * @var string
     *
     * @ORM\Column(name="funcionUrinaria_observacion", type="text", nullable=true)
     */
    private $funcionUrinariaObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="cicloMestrual", type="string", length=30)
     */
    private $cicloMestrual;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cicloMestrualFrecuencia", type="text", nullable=true)
     */
    private $cicloMestrualFrecuencia;

    /**
     * @var string
     *
     * @ORM\Column(name="cicloMestrualDuracion", type="text", nullable=true)
     */
    private $cicloMestrualDuracion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="datoFuncional")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
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
     * Set normal
     *
     * @param boolean $normal
     *
     * @return DatoFuncional
     */
    public function setNormal($normal)
    {
        $this->normal = $normal;

        return $this;
    }

    /**
     * Get normal
     *
     * @return boolean
     */
    public function getNormal()
    {
        return $this->normal;
    }

    /**
     * Set normalObservacion
     *
     * @param string $normalObservacion
     *
     * @return DatoFuncional
     */
    public function setNormalObservacion($normalObservacion)
    {
        $this->normalObservacion = $normalObservacion;

        return $this;
    }

    /**
     * Get normalObservacion
     *
     * @return string
     */
    public function getNormalObservacion()
    {
        return $this->normalObservacion;
    }

    /**
     * Set diarrea
     *
     * @param boolean $diarrea
     *
     * @return DatoFuncional
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
     * @return DatoFuncional
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
     * Set estrenimiento
     *
     * @param boolean $estrenimiento
     *
     * @return DatoFuncional
     */
    public function setEstrenimiento($estrenimiento)
    {
        $this->estrenimiento = $estrenimiento;

        return $this;
    }

    /**
     * Get estrenimiento
     *
     * @return boolean
     */
    public function getEstrenimiento()
    {
        return $this->estrenimiento;
    }

    /**
     * Set estrenimientoObservacion
     *
     * @param string $estrenimientoObservacion
     *
     * @return DatoFuncional
     */
    public function setEstrenimientoObservacion($estrenimientoObservacion)
    {
        $this->estrenimientoObservacion = $estrenimientoObservacion;

        return $this;
    }

    /**
     * Get estrenimientoObservacion
     *
     * @return string
     */
    public function getEstrenimientoObservacion()
    {
        return $this->estrenimientoObservacion;
    }

    /**
     * Set gastritis
     *
     * @param boolean $gastritis
     *
     * @return DatoFuncional
     */
    public function setGastritis($gastritis)
    {
        $this->gastritis = $gastritis;

        return $this;
    }

    /**
     * Get gastritis
     *
     * @return boolean
     */
    public function getGastritis()
    {
        return $this->gastritis;
    }

    /**
     * Set gastritisObservacion
     *
     * @param string $gastritisObservacion
     *
     * @return DatoFuncional
     */
    public function setGastritisObservacion($gastritisObservacion)
    {
        $this->gastritisObservacion = $gastritisObservacion;

        return $this;
    }

    /**
     * Get gastritisObservacion
     *
     * @return string
     */
    public function getGastritisObservacion()
    {
        return $this->gastritisObservacion;
    }

    /**
     * Set colon
     *
     * @param boolean $colon
     *
     * @return DatoFuncional
     */
    public function setColon($colon)
    {
        $this->colon = $colon;

        return $this;
    }

    /**
     * Get colon
     *
     * @return boolean
     */
    public function getColon()
    {
        return $this->colon;
    }

    /**
     * Set colonObservacion
     *
     * @param string $colonObservacion
     *
     * @return DatoFuncional
     */
    public function setColonObservacion($colonObservacion)
    {
        $this->colonObservacion = $colonObservacion;

        return $this;
    }

    /**
     * Get colonObservacion
     *
     * @return string
     */
    public function getColonObservacion()
    {
        return $this->colonObservacion;
    }

    /**
     * Set funcionUrinaria
     *
     * @param string $funcionUrinaria
     *
     * @return DatoFuncional
     */
    public function setFuncionUrinaria($funcionUrinaria)
    {
        $this->funcionUrinaria = $funcionUrinaria;

        return $this;
    }

    /**
     * Get funcionUrinaria
     *
     * @return string
     */
    public function getFuncionUrinaria()
    {
        return $this->funcionUrinaria;
    }

    /**
     * Set funcionUrinariaObservacion
     *
     * @param string $funcionUrinariaObservacion
     *
     * @return DatoFuncional
     */
    public function setFuncionUrinariaObservacion($funcionUrinariaObservacion)
    {
        $this->funcionUrinariaObservacion = $funcionUrinariaObservacion;

        return $this;
    }

    /**
     * Get funcionUrinariaObservacion
     *
     * @return string
     */
    public function getFuncionUrinariaObservacion()
    {
        return $this->funcionUrinariaObservacion;
    }

    /**
     * Set cicloMestrual
     *
     * @param string $cicloMestrual
     *
     * @return DatoFuncional
     */
    public function setCicloMestrual($cicloMestrual)
    {
        $this->cicloMestrual = $cicloMestrual;

        return $this;
    }

    /**
     * Get cicloMestrual
     *
     * @return string
     */
    public function getCicloMestrual()
    {
        return $this->cicloMestrual;
    }

    /**
     * Set cicloMestrualFrecuencia
     *
     * @param string $cicloMestrualFrecuencia
     *
     * @return DatoFuncional
     */
    public function setCicloMestrualFrecuencia($cicloMestrualFrecuencia)
    {
        $this->cicloMestrualFrecuencia = $cicloMestrualFrecuencia;

        return $this;
    }

    /**
     * Get cicloMestrualFrecuencia
     *
     * @return string
     */
    public function getCicloMestrualFrecuencia()
    {
        return $this->cicloMestrualFrecuencia;
    }

    /**
     * Set cicloMestrualDuracion
     *
     * @param string $cicloMestrualDuracion
     *
     * @return DatoFuncional
     */
    public function setCicloMestrualDuracion($cicloMestrualDuracion)
    {
        $this->cicloMestrualDuracion = $cicloMestrualDuracion;

        return $this;
    }

    /**
     * Get cicloMestrualDuracion
     *
     * @return string
     */
    public function getCicloMestrualDuracion()
    {
        return $this->cicloMestrualDuracion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return DatoFuncional
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
