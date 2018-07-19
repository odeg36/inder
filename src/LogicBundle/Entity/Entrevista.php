<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrevista
 *
 * @ORM\Table(name="entrevista")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EntrevistaRepository")
 */
class Entrevista
{
    const MOTIVO_REMITIDO = "Remitido";
    const MOTIVO_SOLICITUD = "Solicitd deportiva";
    const MOTIVO_EVALUACION = "Evaluación medico deportiva";

    public function __toString() {
        return (string)$this->getPsicologia()->getDeportista() ? : '';
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
     * @ORM\Column(name="motivoConsulta", type="string", length=30)
     */
    private $motivoConsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="descrpcion", type="text", nullable=true)
     */
    private $descrpcion;

    /**
     * @var string
     *
     * @ORM\Column(name="hipotesis", type="text", nullable=true)
     */
    private $hipotesis;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaPsicologia", inversedBy="entrevista")
     * @ORM\JoinColumn(name="psicologia_id", referencedColumnName="id")
     */
    private $psicologia;
    
    /**
     * @ORM\OneToMany(targetEntity="TestPractico", mappedBy="entrevista", cascade={"persist"})
     */
    private $testPracticos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->testPracticos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set motivoConsulta
     *
     * @param string $motivoConsulta
     *
     * @return Entrevista
     */
    public function setMotivoConsulta($motivoConsulta)
    {
        $this->motivoConsulta = $motivoConsulta;

        return $this;
    }

    /**
     * Get motivoConsulta
     *
     * @return string
     */
    public function getMotivoConsulta()
    {
        return $this->motivoConsulta;
    }

    /**
     * Set descrpcion
     *
     * @param string $descrpcion
     *
     * @return Entrevista
     */
    public function setDescrpcion($descrpcion)
    {
        $this->descrpcion = $descrpcion;

        return $this;
    }

    /**
     * Get descrpcion
     *
     * @return string
     */
    public function getDescrpcion()
    {
        return $this->descrpcion;
    }

    /**
     * Set hipotesis
     *
     * @param string $hipotesis
     *
     * @return Entrevista
     */
    public function setHipotesis($hipotesis)
    {
        $this->hipotesis = $hipotesis;

        return $this;
    }

    /**
     * Get hipotesis
     *
     * @return string
     */
    public function getHipotesis()
    {
        return $this->hipotesis;
    }

    /**
     * Set psicologia
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia $psicologia
     *
     * @return Entrevista
     */
    public function setPsicologia(\LogicBundle\Entity\ConsultaPsicologia $psicologia = null)
    {
        $this->psicologia = $psicologia;

        return $this;
    }

    /**
     * Get psicologia
     *
     * @return \LogicBundle\Entity\ConsultaPsicologia
     */
    public function getPsicologia()
    {
        return $this->psicologia;
    }

    /**
     * Add testPractico
     *
     * @param \LogicBundle\Entity\TestPractico $testPractico
     *
     * @return Entrevista
     */
    public function addTestPractico(\LogicBundle\Entity\TestPractico $testPractico)
    {
        $this->testPracticos[] = $testPractico;

        return $this;
    }

    /**
     * Remove testPractico
     *
     * @param \LogicBundle\Entity\TestPractico $testPractico
     */
    public function removeTestPractico(\LogicBundle\Entity\TestPractico $testPractico)
    {
        $this->testPracticos->removeElement($testPractico);
    }

    /**
     * Get testPracticos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestPracticos()
    {
        return $this->testPracticos;
    }
}
