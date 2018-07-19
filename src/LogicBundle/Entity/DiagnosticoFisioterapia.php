<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosticoFisioterapia
 *
 * @ORM\Table(name="diagnostico_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiagnosticoFisioterapiaRepository")
 */
class DiagnosticoFisioterapia {
    
    public function __toString() {
        return $this->getDiagnosticoMedico() ? : '';
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
     * @ORM\Column(name="diagnostico_medico", type="string", length=255)
     */
    private $diagnosticoMedico;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostico_fisioterapia", type="string", length=255)
     */
    private $diagnosticoFisioterapia;

    /**
     * @var string
     *
     * @ORM\Column(name="metas", type="text")
     */
    private $metas;

    /**
     * @ORM\ManyToOne(targetEntity="MedidaMMIBalance", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="medida_mmi_balance_id", referencedColumnName="id")
     */
    private $medidaMMIBalance;
    
    /**
     * @ORM\ManyToOne(targetEntity="FichaCampoFisioterapia", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="ficha_campo_fisioterapia_id", referencedColumnName="id")
     */
    private $fichaCampoFisioterapia;


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
     * Set diagnosticoMedico
     *
     * @param string $diagnosticoMedico
     *
     * @return DiagnosticoFisioterapia
     */
    public function setDiagnosticoMedico($diagnosticoMedico)
    {
        $this->diagnosticoMedico = $diagnosticoMedico;

        return $this;
    }

    /**
     * Get diagnosticoMedico
     *
     * @return string
     */
    public function getDiagnosticoMedico()
    {
        return $this->diagnosticoMedico;
    }

    /**
     * Set diagnosticoFisioterapia
     *
     * @param string $diagnosticoFisioterapia
     *
     * @return DiagnosticoFisioterapia
     */
    public function setDiagnosticoFisioterapia($diagnosticoFisioterapia)
    {
        $this->diagnosticoFisioterapia = $diagnosticoFisioterapia;

        return $this;
    }

    /**
     * Get diagnosticoFisioterapia
     *
     * @return string
     */
    public function getDiagnosticoFisioterapia()
    {
        return $this->diagnosticoFisioterapia;
    }

    /**
     * Set metas
     *
     * @param string $metas
     *
     * @return DiagnosticoFisioterapia
     */
    public function setMetas($metas)
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * Get metas
     *
     * @return string
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Set medidaMMIBalance
     *
     * @param \LogicBundle\Entity\MedidaMMIBalance $medidaMMIBalance
     *
     * @return DiagnosticoFisioterapia
     */
    public function setMedidaMMIBalance(\LogicBundle\Entity\MedidaMMIBalance $medidaMMIBalance = null)
    {
        $this->medidaMMIBalance = $medidaMMIBalance;

        return $this;
    }

    /**
     * Get medidaMMIBalance
     *
     * @return \LogicBundle\Entity\MedidaMMIBalance
     */
    public function getMedidaMMIBalance()
    {
        return $this->medidaMMIBalance;
    }

    /**
     * Set fichaCampoFisioterapia
     *
     * @param \LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia
     *
     * @return DiagnosticoFisioterapia
     */
    public function setFichaCampoFisioterapia(\LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia = null)
    {
        $this->fichaCampoFisioterapia = $fichaCampoFisioterapia;

        return $this;
    }

    /**
     * Get fichaCampoFisioterapia
     *
     * @return \LogicBundle\Entity\FichaCampoFisioterapia
     */
    public function getFichaCampoFisioterapia()
    {
        return $this->fichaCampoFisioterapia;
    }
}
