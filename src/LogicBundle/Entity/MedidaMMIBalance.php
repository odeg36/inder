<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MedidaMMIBalance
 *
 * @ORM\Table(name="medida_mmi_balance")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MedidaMMIBalanceRepository")
 */
class MedidaMMIBalance {

    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @ORM\Column(name="medida_real_derecha", type="float", nullable=true)
     */
    private $medidaRealDerecha;

    /**
     * @var float
     *
     * @ORM\Column(name="medida_real_izquierda", type="float", nullable=true)
     */
    private $medidaRealIzquierda;

    /**
     * @var float
     *
     * @ORM\Column(name="medida_aparente_derecha", type="float", nullable=true)
     */
    private $medidaAparenteDerecha;

    /**
     * @var float
     *
     * @ORM\Column(name="medida_aparente_izquierda", type="float", nullable=true)
     */
    private $medidaAparenteIzquierda;

    /**
     * @var array
     *
     * @ORM\Column(name="ojos_abiertos", type="array", nullable=true)
     */
    private $ojosAbiertos;

    /**
     * @var text
     *
     * @ORM\Column(name="observacion_ojos_abiertos", type="text", nullable=true)
     */
    private $observacionOjosAbiertos;

    /**
     * @var array
     *
     * @ORM\Column(name="ojos_cerrados", type="array", nullable=true)
     */
    private $ojosCerrados;

    /**
     * @var text
     *
     * @ORM\Column(name="observacion_ojos_cerrados", type="text", nullable=true)
     */
    private $observacionOjosCerrados;

    /**
     * @ORM\OneToMany(targetEntity="DiagnosticoFisioterapia", mappedBy="medidaMMIBalance", cascade={"persist"})
     */
    private $diagnosticos;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="medidaMMIBalance")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;

    /**
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico
     *
     * @return MedidaMMIBalance
     */
    public function addDiagnostico(\LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico) {
        $diagnostico->setMedidaMMIBalance($this);
        $this->diagnosticos[] = $diagnostico;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->diagnosticos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set medidaRealDerecha
     *
     * @param float $medidaRealDerecha
     *
     * @return MedidaMMIBalance
     */
    public function setMedidaRealDerecha($medidaRealDerecha)
    {
        $this->medidaRealDerecha = $medidaRealDerecha;

        return $this;
    }

    /**
     * Get medidaRealDerecha
     *
     * @return float
     */
    public function getMedidaRealDerecha()
    {
        return $this->medidaRealDerecha;
    }

    /**
     * Set medidaRealIzquierda
     *
     * @param float $medidaRealIzquierda
     *
     * @return MedidaMMIBalance
     */
    public function setMedidaRealIzquierda($medidaRealIzquierda)
    {
        $this->medidaRealIzquierda = $medidaRealIzquierda;

        return $this;
    }

    /**
     * Get medidaRealIzquierda
     *
     * @return float
     */
    public function getMedidaRealIzquierda()
    {
        return $this->medidaRealIzquierda;
    }

    /**
     * Set medidaAparenteDerecha
     *
     * @param float $medidaAparenteDerecha
     *
     * @return MedidaMMIBalance
     */
    public function setMedidaAparenteDerecha($medidaAparenteDerecha)
    {
        $this->medidaAparenteDerecha = $medidaAparenteDerecha;

        return $this;
    }

    /**
     * Get medidaAparenteDerecha
     *
     * @return float
     */
    public function getMedidaAparenteDerecha()
    {
        return $this->medidaAparenteDerecha;
    }

    /**
     * Set medidaAparenteIzquierda
     *
     * @param float $medidaAparenteIzquierda
     *
     * @return MedidaMMIBalance
     */
    public function setMedidaAparenteIzquierda($medidaAparenteIzquierda)
    {
        $this->medidaAparenteIzquierda = $medidaAparenteIzquierda;

        return $this;
    }

    /**
     * Get medidaAparenteIzquierda
     *
     * @return float
     */
    public function getMedidaAparenteIzquierda()
    {
        return $this->medidaAparenteIzquierda;
    }

    /**
     * Set ojosAbiertos
     *
     * @param array $ojosAbiertos
     *
     * @return MedidaMMIBalance
     */
    public function setOjosAbiertos($ojosAbiertos)
    {
        $this->ojosAbiertos = $ojosAbiertos;

        return $this;
    }

    /**
     * Get ojosAbiertos
     *
     * @return array
     */
    public function getOjosAbiertos()
    {
        return $this->ojosAbiertos;
    }

    /**
     * Set observacionOjosAbiertos
     *
     * @param string $observacionOjosAbiertos
     *
     * @return MedidaMMIBalance
     */
    public function setObservacionOjosAbiertos($observacionOjosAbiertos)
    {
        $this->observacionOjosAbiertos = $observacionOjosAbiertos;

        return $this;
    }

    /**
     * Get observacionOjosAbiertos
     *
     * @return string
     */
    public function getObservacionOjosAbiertos()
    {
        return $this->observacionOjosAbiertos;
    }

    /**
     * Set ojosCerrados
     *
     * @param array $ojosCerrados
     *
     * @return MedidaMMIBalance
     */
    public function setOjosCerrados($ojosCerrados)
    {
        $this->ojosCerrados = $ojosCerrados;

        return $this;
    }

    /**
     * Get ojosCerrados
     *
     * @return array
     */
    public function getOjosCerrados()
    {
        return $this->ojosCerrados;
    }

    /**
     * Set observacionOjosCerrados
     *
     * @param string $observacionOjosCerrados
     *
     * @return MedidaMMIBalance
     */
    public function setObservacionOjosCerrados($observacionOjosCerrados)
    {
        $this->observacionOjosCerrados = $observacionOjosCerrados;

        return $this;
    }

    /**
     * Get observacionOjosCerrados
     *
     * @return string
     */
    public function getObservacionOjosCerrados()
    {
        return $this->observacionOjosCerrados;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico)
    {
        $this->diagnosticos->removeElement($diagnostico);
    }

    /**
     * Get diagnosticos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnosticos()
    {
        return $this->diagnosticos;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return MedidaMMIBalance
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
