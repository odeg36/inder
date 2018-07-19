<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamenFisicoMedico
 *
 * @ORM\Table(name="examen_fisico_medico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ExamenFisicoMedicoRepository")
 */
class ExamenFisicoMedico
{
    /**
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     *
     * @return ExamenFisicoMedico
     */
    public function addDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
    {
        $diagnostico->setExamenesFisicosMedicos($this);
        $this->diagnostico[] = $diagnostico;

        return $this;
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
     * @var int
     *
     * @ORM\Column(name="p_a_inicial", type="integer", nullable=true)
     */
    private $pAInicial;

    /**
     * @var int
     *
     * @ORM\Column(name="p_a_final", type="integer", nullable=true)
     */
    private $pAFinal;

    /**
     * @var int
     *
     * @ORM\Column(name="saturacion_o_dos", type="integer", nullable=true)
     */
    private $saturacionODos;

    /**
     * @var int
     *
     * @ORM\Column(name="f_c", type="integer", nullable=true)
     */
    private $fC;

    /**
     * @var int
     *
     * @ORM\Column(name="f_r", type="integer", nullable=true)
     */
    private $fR;

    /**
     * @var string
     *
     * @ORM\Column(name="cabeza_cuello", type="string", length=255, nullable=true)
     */
    private $cabezaCuello;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cabeza_cuello_observacion", type="text", nullable=true)
     */
    private $cabezaCuelloObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="cardio_pulmonal", type="string", length=255, nullable=true)
     */
    private $cardioPulmonal;

    /**
     * @var string
     *
     * @ORM\Column(name="abdominal", type="string", length=255, nullable=true)
     */
    private $abdominal;

    /**
     * @var string
     *
     * @ORM\Column(name="genito_urinario", type="string", length=255, nullable=true)
     */
    private $genitoUrinario;

    /**
     * @var string
     *
     * @ORM\Column(name="osteomuscular", type="string", length=255, nullable=true)
     */
    private $osteomuscular;

    /**
     * @var string
     *
     * @ORM\Column(name="neurologico", type="string", length=255, nullable=true)
     */
    private $neurologico;

    /**
     * @var string
     *
     * @ORM\Column(name="otros", type="text", nullable=true)
     */
    private $otros;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Diagnostico", mappedBy="ExamenesFisicosMedicos",cascade={ "persist"})
     */
    private $diagnostico;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaMedico", inversedBy="examenFisicoMedico")
     */
    private $consultaMedico;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->diagnostico = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set pAInicial
     *
     * @param integer $pAInicial
     *
     * @return ExamenFisicoMedico
     */
    public function setPAInicial($pAInicial)
    {
        $this->pAInicial = $pAInicial;

        return $this;
    }

    /**
     * Get pAInicial
     *
     * @return integer
     */
    public function getPAInicial()
    {
        return $this->pAInicial;
    }

    /**
     * Set pAFinal
     *
     * @param integer $pAFinal
     *
     * @return ExamenFisicoMedico
     */
    public function setPAFinal($pAFinal)
    {
        $this->pAFinal = $pAFinal;

        return $this;
    }

    /**
     * Get pAFinal
     *
     * @return integer
     */
    public function getPAFinal()
    {
        return $this->pAFinal;
    }

    /**
     * Set saturacionODos
     *
     * @param integer $saturacionODos
     *
     * @return ExamenFisicoMedico
     */
    public function setSaturacionODos($saturacionODos)
    {
        $this->saturacionODos = $saturacionODos;

        return $this;
    }

    /**
     * Get saturacionODos
     *
     * @return integer
     */
    public function getSaturacionODos()
    {
        return $this->saturacionODos;
    }

    /**
     * Set fC
     *
     * @param integer $fC
     *
     * @return ExamenFisicoMedico
     */
    public function setFC($fC)
    {
        $this->fC = $fC;

        return $this;
    }

    /**
     * Get fC
     *
     * @return integer
     */
    public function getFC()
    {
        return $this->fC;
    }

    /**
     * Set fR
     *
     * @param integer $fR
     *
     * @return ExamenFisicoMedico
     */
    public function setFR($fR)
    {
        $this->fR = $fR;

        return $this;
    }

    /**
     * Get fR
     *
     * @return integer
     */
    public function getFR()
    {
        return $this->fR;
    }

    /**
     * Set cabezaCuello
     *
     * @param string $cabezaCuello
     *
     * @return ExamenFisicoMedico
     */
    public function setCabezaCuello($cabezaCuello)
    {
        $this->cabezaCuello = $cabezaCuello;

        return $this;
    }

    /**
     * Get cabezaCuello
     *
     * @return string
     */
    public function getCabezaCuello()
    {
        return $this->cabezaCuello;
    }

    /**
     * Set cabezaCuelloObservacion
     *
     * @param string $cabezaCuelloObservacion
     *
     * @return ExamenFisicoMedico
     */
    public function setCabezaCuelloObservacion($cabezaCuelloObservacion)
    {
        $this->cabezaCuelloObservacion = $cabezaCuelloObservacion;

        return $this;
    }

    /**
     * Get cabezaCuelloObservacion
     *
     * @return string
     */
    public function getCabezaCuelloObservacion()
    {
        return $this->cabezaCuelloObservacion;
    }

    /**
     * Set cardioPulmonal
     *
     * @param string $cardioPulmonal
     *
     * @return ExamenFisicoMedico
     */
    public function setCardioPulmonal($cardioPulmonal)
    {
        $this->cardioPulmonal = $cardioPulmonal;

        return $this;
    }

    /**
     * Get cardioPulmonal
     *
     * @return string
     */
    public function getCardioPulmonal()
    {
        return $this->cardioPulmonal;
    }

    /**
     * Set abdominal
     *
     * @param string $abdominal
     *
     * @return ExamenFisicoMedico
     */
    public function setAbdominal($abdominal)
    {
        $this->abdominal = $abdominal;

        return $this;
    }

    /**
     * Get abdominal
     *
     * @return string
     */
    public function getAbdominal()
    {
        return $this->abdominal;
    }

    /**
     * Set genitoUrinario
     *
     * @param string $genitoUrinario
     *
     * @return ExamenFisicoMedico
     */
    public function setGenitoUrinario($genitoUrinario)
    {
        $this->genitoUrinario = $genitoUrinario;

        return $this;
    }

    /**
     * Get genitoUrinario
     *
     * @return string
     */
    public function getGenitoUrinario()
    {
        return $this->genitoUrinario;
    }

    /**
     * Set osteomuscular
     *
     * @param string $osteomuscular
     *
     * @return ExamenFisicoMedico
     */
    public function setOsteomuscular($osteomuscular)
    {
        $this->osteomuscular = $osteomuscular;

        return $this;
    }

    /**
     * Get osteomuscular
     *
     * @return string
     */
    public function getOsteomuscular()
    {
        return $this->osteomuscular;
    }

    /**
     * Set neurologico
     *
     * @param string $neurologico
     *
     * @return ExamenFisicoMedico
     */
    public function setNeurologico($neurologico)
    {
        $this->neurologico = $neurologico;

        return $this;
    }

    /**
     * Get neurologico
     *
     * @return string
     */
    public function getNeurologico()
    {
        return $this->neurologico;
    }

    /**
     * Set otros
     *
     * @param string $otros
     *
     * @return ExamenFisicoMedico
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;

        return $this;
    }

    /**
     * Get otros
     *
     * @return string
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
    {
        $this->diagnostico->removeElement($diagnostico);
    }

    /**
     * Get diagnostico
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return ExamenFisicoMedico
     */
    public function setConsultaMedico(\LogicBundle\Entity\ConsultaMedico $consultaMedico = null)
    {
        $this->consultaMedico = $consultaMedico;

        return $this;
    }

    /**
     * Get consultaMedico
     *
     * @return \LogicBundle\Entity\ConsultaMedico
     */
    public function getConsultaMedico()
    {
        return $this->consultaMedico;
    }
}
