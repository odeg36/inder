<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Antecedente
 *
 * @ORM\Table(name="antecedente")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AntecedenteRepository")
 */
class Antecedente
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
     * @var string
     *
     * @ORM\Column(name="motivo_consulta", type="text")
     */
    private $motivoConsulta;

    /**
     * @var bool
     *
     * @ORM\Column(name="sobre_seso_personal", type="boolean")
     */
    private $sobrePesoPersonal;

    /**
     * @var bool
     *
     * @ORM\Column(name="diabetes_personal", type="boolean")
     */
    private $diabetesPersonal;

    /**
     * @var bool
     *
     * @ORM\Column(name="enfermedad_cardiaca_personal", type="boolean")
     */
    private $enfermedadCardiacaPersonal;

    /**
     * @var bool
     *
     * @ORM\Column(name="dislipidemias_personal", type="boolean")
     */
    private $dislipidemiasPersonal;

    /**
     * @var bool
     *
     * @ORM\Column(name="hipertension_arterial_personal", type="boolean")
     */
    private $hipertensionArterialPersonal;

    /**
     * @var string
     *
     * @ORM\Column(name="otros_personal", type="text", nullable=true)
     */
    private $otrosPersonal;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="sobre_eso_familiar", type="boolean")
     */
    private $sobrePesoFamiliar;

    /**
     * @var bool
     *
     * @ORM\Column(name="diabetes_familiar", type="boolean")
     */
    private $diabetesFamiliar;

    /**
     * @var bool
     *
     * @ORM\Column(name="enfermedad_cardiaca_familiar", type="boolean")
     */
    private $enfermedadCardiacaFamiliar;

    /**
     * @var bool
     *
     * @ORM\Column(name="dislipidemias_familiar", type="boolean")
     */
    private $dislipidemiasFamiliar;

    /**
     * @var bool
     *
     * @ORM\Column(name="hipertension_arterial_familiar", type="boolean")
     */
    private $hipertensionArterialFamiliar;

    /**
     * @var string
     *
     * @ORM\Column(name="otros_familiar", type="text", nullable=true)
     */
    private $otrosFamiliar;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="antecedente")
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
     * Set sobrePesoPersonal
     *
     * @param boolean $sobrePesoPersonal
     *
     * @return Antecedente
     */
    public function setSobrePesoPersonal($sobrePesoPersonal)
    {
        $this->sobrePesoPersonal = $sobrePesoPersonal;

        return $this;
    }

    /**
     * Get sobrePesoPersonal
     *
     * @return boolean
     */
    public function getSobrePesoPersonal()
    {
        return $this->sobrePesoPersonal;
    }

    /**
     * Set diabetesPersonal
     *
     * @param boolean $diabetesPersonal
     *
     * @return Antecedente
     */
    public function setDiabetesPersonal($diabetesPersonal)
    {
        $this->diabetesPersonal = $diabetesPersonal;

        return $this;
    }

    /**
     * Get diabetesPersonal
     *
     * @return boolean
     */
    public function getDiabetesPersonal()
    {
        return $this->diabetesPersonal;
    }

    /**
     * Set enfermedadCardiacaPersonal
     *
     * @param boolean $enfermedadCardiacaPersonal
     *
     * @return Antecedente
     */
    public function setEnfermedadCardiacaPersonal($enfermedadCardiacaPersonal)
    {
        $this->enfermedadCardiacaPersonal = $enfermedadCardiacaPersonal;

        return $this;
    }

    /**
     * Get enfermedadCardiacaPersonal
     *
     * @return boolean
     */
    public function getEnfermedadCardiacaPersonal()
    {
        return $this->enfermedadCardiacaPersonal;
    }

    /**
     * Set dislipidemiasPersonal
     *
     * @param boolean $dislipidemiasPersonal
     *
     * @return Antecedente
     */
    public function setDislipidemiasPersonal($dislipidemiasPersonal)
    {
        $this->dislipidemiasPersonal = $dislipidemiasPersonal;

        return $this;
    }

    /**
     * Get dislipidemiasPersonal
     *
     * @return boolean
     */
    public function getDislipidemiasPersonal()
    {
        return $this->dislipidemiasPersonal;
    }

    /**
     * Set hipertensionArterialPersonal
     *
     * @param boolean $hipertensionArterialPersonal
     *
     * @return Antecedente
     */
    public function setHipertensionArterialPersonal($hipertensionArterialPersonal)
    {
        $this->hipertensionArterialPersonal = $hipertensionArterialPersonal;

        return $this;
    }

    /**
     * Get hipertensionArterialPersonal
     *
     * @return boolean
     */
    public function getHipertensionArterialPersonal()
    {
        return $this->hipertensionArterialPersonal;
    }

    /**
     * Set otrosPersonal
     *
     * @param string $otrosPersonal
     *
     * @return Antecedente
     */
    public function setOtrosPersonal($otrosPersonal)
    {
        $this->otrosPersonal = $otrosPersonal;

        return $this;
    }

    /**
     * Get otrosPersonal
     *
     * @return string
     */
    public function getOtrosPersonal()
    {
        return $this->otrosPersonal;
    }

    /**
     * Set sobrePesoFamiliar
     *
     * @param boolean $sobrePesoFamiliar
     *
     * @return Antecedente
     */
    public function setSobrePesoFamiliar($sobrePesoFamiliar)
    {
        $this->sobrePesoFamiliar = $sobrePesoFamiliar;

        return $this;
    }

    /**
     * Get sobrePesoFamiliar
     *
     * @return boolean
     */
    public function getSobrePesoFamiliar()
    {
        return $this->sobrePesoFamiliar;
    }

    /**
     * Set diabetesFamiliar
     *
     * @param boolean $diabetesFamiliar
     *
     * @return Antecedente
     */
    public function setDiabetesFamiliar($diabetesFamiliar)
    {
        $this->diabetesFamiliar = $diabetesFamiliar;

        return $this;
    }

    /**
     * Get diabetesFamiliar
     *
     * @return boolean
     */
    public function getDiabetesFamiliar()
    {
        return $this->diabetesFamiliar;
    }

    /**
     * Set enfermedadCardiacaFamiliar
     *
     * @param boolean $enfermedadCardiacaFamiliar
     *
     * @return Antecedente
     */
    public function setEnfermedadCardiacaFamiliar($enfermedadCardiacaFamiliar)
    {
        $this->enfermedadCardiacaFamiliar = $enfermedadCardiacaFamiliar;

        return $this;
    }

    /**
     * Get enfermedadCardiacaFamiliar
     *
     * @return boolean
     */
    public function getEnfermedadCardiacaFamiliar()
    {
        return $this->enfermedadCardiacaFamiliar;
    }

    /**
     * Set dislipidemiasFamiliar
     *
     * @param boolean $dislipidemiasFamiliar
     *
     * @return Antecedente
     */
    public function setDislipidemiasFamiliar($dislipidemiasFamiliar)
    {
        $this->dislipidemiasFamiliar = $dislipidemiasFamiliar;

        return $this;
    }

    /**
     * Get dislipidemiasFamiliar
     *
     * @return boolean
     */
    public function getDislipidemiasFamiliar()
    {
        return $this->dislipidemiasFamiliar;
    }

    /**
     * Set hipertensionArterialFamiliar
     *
     * @param boolean $hipertensionArterialFamiliar
     *
     * @return Antecedente
     */
    public function setHipertensionArterialFamiliar($hipertensionArterialFamiliar)
    {
        $this->hipertensionArterialFamiliar = $hipertensionArterialFamiliar;

        return $this;
    }

    /**
     * Get hipertensionArterialFamiliar
     *
     * @return boolean
     */
    public function getHipertensionArterialFamiliar()
    {
        return $this->hipertensionArterialFamiliar;
    }

    /**
     * Set otrosFamiliar
     *
     * @param string $otrosFamiliar
     *
     * @return Antecedente
     */
    public function setOtrosFamiliar($otrosFamiliar)
    {
        $this->otrosFamiliar = $otrosFamiliar;

        return $this;
    }

    /**
     * Get otrosFamiliar
     *
     * @return string
     */
    public function getOtrosFamiliar()
    {
        return $this->otrosFamiliar;
    }

    /**
     * Set motivoConsulta
     *
     * @param string $motivoConsulta
     *
     * @return Antecedente
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
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return Antecedente
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
