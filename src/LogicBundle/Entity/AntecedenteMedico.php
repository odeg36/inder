<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AntecedenteMedico
 *
 * @ORM\Table(name="antecedente_medico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AntecedenteMedicoRepository")
 */
class AntecedenteMedico
{
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
     * @ORM\Column(name="patologico", type="text", nullable=true)
     */
    private $patologico;

    /**
     * @var string
     *
     * @ORM\Column(name="quirurgico", type="text", nullable=true)
     */
    private $quirurgico;

    /**
     * @var string
     *
     * @ORM\Column(name="alergico", type="text", nullable=true)
     */
    private $alergico;

    /**
     * @var string
     *
     * @ORM\Column(name="farmacologico", type="text", nullable=true)
     */
    private $farmacologico;

    /**
     * @var string
     *
     * @ORM\Column(name="toxicologico", type="text", nullable=true)
     */
    private $toxicologico;

    /**
     * @var string
     *
     * @ORM\Column(name="familiar", type="text", nullable=true)
     */
    private $familiar;

    /**
     * @var string
     *
     * @ORM\Column(name="osteomuscular", type="text", nullable=true)
     */
    private $osteomuscular;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaMedico", inversedBy="antecedenteMedico")
     */
    private $consultaMedico;

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
     * Set patologico
     *
     * @param string $patologico
     *
     * @return AntecedenteMedico
     */
    public function setPatologico($patologico)
    {
        $this->patologico = $patologico;

        return $this;
    }

    /**
     * Get patologico
     *
     * @return string
     */
    public function getPatologico()
    {
        return $this->patologico;
    }

    /**
     * Set quirurgico
     *
     * @param string $quirurgico
     *
     * @return AntecedenteMedico
     */
    public function setQuirurgico($quirurgico)
    {
        $this->quirurgico = $quirurgico;

        return $this;
    }

    /**
     * Get quirurgico
     *
     * @return string
     */
    public function getQuirurgico()
    {
        return $this->quirurgico;
    }

    /**
     * Set alergico
     *
     * @param string $alergico
     *
     * @return AntecedenteMedico
     */
    public function setAlergico($alergico)
    {
        $this->alergico = $alergico;

        return $this;
    }

    /**
     * Get alergico
     *
     * @return string
     */
    public function getAlergico()
    {
        return $this->alergico;
    }

    /**
     * Set farmacologico
     *
     * @param string $farmacologico
     *
     * @return AntecedenteMedico
     */
    public function setFarmacologico($farmacologico)
    {
        $this->farmacologico = $farmacologico;

        return $this;
    }

    /**
     * Get farmacologico
     *
     * @return string
     */
    public function getFarmacologico()
    {
        return $this->farmacologico;
    }

    /**
     * Set toxicologico
     *
     * @param string $toxicologico
     *
     * @return AntecedenteMedico
     */
    public function setToxicologico($toxicologico)
    {
        $this->toxicologico = $toxicologico;

        return $this;
    }

    /**
     * Get toxicologico
     *
     * @return string
     */
    public function getToxicologico()
    {
        return $this->toxicologico;
    }

    /**
     * Set familiar
     *
     * @param string $familiar
     *
     * @return AntecedenteMedico
     */
    public function setFamiliar($familiar)
    {
        $this->familiar = $familiar;

        return $this;
    }

    /**
     * Get familiar
     *
     * @return string
     */
    public function getFamiliar()
    {
        return $this->familiar;
    }

    /**
     * Set osteomuscular
     *
     * @param string $osteomuscular
     *
     * @return AntecedenteMedico
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
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return AntecedenteMedico
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
