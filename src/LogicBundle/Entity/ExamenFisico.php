<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="examen_fisico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ExamenFisicoRepository")
 */
class ExamenFisico {

    const SISTEMA_TUGOMENTARIO_ALTERADO = 'Alterado';
    const SISTEMA_TUGOMENTARIO_NO_ALTERADO = 'No alterado';
    
    
    public function __toString() {
        return (string) $this->getConsultaFisioterapia()->getDeportista() ?: '';
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
     * @var float
     *
     * @ORM\Column(name="p_a_inicial", type="float", nullable=true)
     */
    private $pAInicial;
    
    /**
     * @var float
     *
     * @ORM\Column(name="p_a_final", type="float", nullable=true)
     */
    private $pAFinal;
    
    /**
     * @var float
     *
     * @ORM\Column(name="f_c", type="float", nullable=true)
     */
    private $fC;
    
    /**
     * @var float
     *
     * @ORM\Column(name="f_r", type="float", nullable=true)
     */
    private $fR;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sistema_tugomentario", type="string", length=255, nullable=true)
     */
    private $sistemaTugomentario;  
    
    /**
     * @var text
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="evaluacion_osteomuscular", type="text", nullable=true)
     */
    private $evaluacionOsteomuscular;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="examenFisico")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;


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
     * @param float $pAInicial
     *
     * @return ExamenFisico
     */
    public function setPAInicial($pAInicial)
    {
        $this->pAInicial = $pAInicial;

        return $this;
    }

    /**
     * Get pAInicial
     *
     * @return float
     */
    public function getPAInicial()
    {
        return $this->pAInicial;
    }

    /**
     * Set pAFinal
     *
     * @param float $pAFinal
     *
     * @return ExamenFisico
     */
    public function setPAFinal($pAFinal)
    {
        $this->pAFinal = $pAFinal;

        return $this;
    }

    /**
     * Get pAFinal
     *
     * @return float
     */
    public function getPAFinal()
    {
        return $this->pAFinal;
    }

    /**
     * Set fC
     *
     * @param float $fC
     *
     * @return ExamenFisico
     */
    public function setFC($fC)
    {
        $this->fC = $fC;

        return $this;
    }

    /**
     * Get fC
     *
     * @return float
     */
    public function getFC()
    {
        return $this->fC;
    }

    /**
     * Set fR
     *
     * @param float $fR
     *
     * @return ExamenFisico
     */
    public function setFR($fR)
    {
        $this->fR = $fR;

        return $this;
    }

    /**
     * Get fR
     *
     * @return float
     */
    public function getFR()
    {
        return $this->fR;
    }

    /**
     * Set sistemaTugomentario
     *
     * @param string $sistemaTugomentario
     *
     * @return ExamenFisico
     */
    public function setSistemaTugomentario($sistemaTugomentario)
    {
        $this->sistemaTugomentario = $sistemaTugomentario;

        return $this;
    }

    /**
     * Get sistemaTugomentario
     *
     * @return string
     */
    public function getSistemaTugomentario()
    {
        return $this->sistemaTugomentario;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return ExamenFisico
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set evaluacionOsteomuscular
     *
     * @param string $evaluacionOsteomuscular
     *
     * @return ExamenFisico
     */
    public function setEvaluacionOsteomuscular($evaluacionOsteomuscular)
    {
        $this->evaluacionOsteomuscular = $evaluacionOsteomuscular;

        return $this;
    }

    /**
     * Get evaluacionOsteomuscular
     *
     * @return string
     */
    public function getEvaluacionOsteomuscular()
    {
        return $this->evaluacionOsteomuscular;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return ExamenFisico
     */
    public function setConsultaFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia = null)
    {
        $this->consultaFisioterapia = $consultaFisioterapia;

        return $this;
    }

    /**
     * Get consultaFisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getConsultaFisioterapia()
    {
        return $this->consultaFisioterapia;
    }
}
