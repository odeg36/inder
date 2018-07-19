<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="anamnesis")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AnamnesisRepository")
 */
class Anamnesis {

    const ETOLOGIA_NEUROPATICO= 'Neuropatico';
    const ETOLOGIA_NONICEPTIVO= 'Noniceptivo';
    
    const LOCALIZACION_DOLOR_PUNTUAL= 'Puntual';
    const LOCALIZACION_DOLOR_REFERIDO= 'Referido';
    
    
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
     * @var string
     *
     * @ORM\Column(name="dominancia", type="string", length=255, nullable=true)
     */
    private $dominancia;

    /**
     * @ORM\ManyToOne(targetEntity="FaseEntrenamiento", inversedBy="anamnesis")
     * @ORM\JoinColumn(name="fase_entrenamiento_id", referencedColumnName="id", nullable=true)
     */
    private $faseEntrenamiento;

    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion_funcional", type="string", length=255, nullable=true)
     */
    private $clasificacionFuncional;

    /**
     * @ORM\ManyToOne(targetEntity="NivelIndependenciaFuncional", inversedBy="anamnesis")
     * @ORM\JoinColumn(name="nivel_independencia_funcional_id", referencedColumnName="id", nullable=true)
     */
    private $nivelIndependenciaFuncional;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="anamnesis", type="text", nullable=true)
     */
    private $anamnesis;
    
    /**
     * @ORM\ManyToOne(targetEntity="PresenciaDolor", inversedBy="anamnesis")
     * @ORM\JoinColumn(name="presencia_dolor_id", referencedColumnName="id", nullable=true)
     */
    private $presenciaDolor; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="evolucion_dolor", type="integer", nullable=true)
     */
    private $evolucionDolor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="etologia_dolor", type="string", length=255, nullable=true)
     */
    private $etologiaDolor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="localizacion_dolor", type="string", length=255, nullable=true)
     */
    private $localizacionDolor;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="anamnesis")
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
     * Set dominancia
     *
     * @param string $dominancia
     *
     * @return Anamnesis
     */
    public function setDominancia($dominancia)
    {
        $this->dominancia = $dominancia;

        return $this;
    }

    /**
     * Get dominancia
     *
     * @return string
     */
    public function getDominancia()
    {
        return $this->dominancia;
    }

    /**
     * Set clasificacionFuncional
     *
     * @param string $clasificacionFuncional
     *
     * @return Anamnesis
     */
    public function setClasificacionFuncional($clasificacionFuncional)
    {
        $this->clasificacionFuncional = $clasificacionFuncional;

        return $this;
    }

    /**
     * Get clasificacionFuncional
     *
     * @return string
     */
    public function getClasificacionFuncional()
    {
        return $this->clasificacionFuncional;
    }

    /**
     * Set anamnesis
     *
     * @param string $anamnesis
     *
     * @return Anamnesis
     */
    public function setAnamnesis($anamnesis)
    {
        $this->anamnesis = $anamnesis;

        return $this;
    }

    /**
     * Get anamnesis
     *
     * @return string
     */
    public function getAnamnesis()
    {
        return $this->anamnesis;
    }

    /**
     * Set evolucionDolor
     *
     * @param integer $evolucionDolor
     *
     * @return Anamnesis
     */
    public function setEvolucionDolor($evolucionDolor)
    {
        $this->evolucionDolor = $evolucionDolor;

        return $this;
    }

    /**
     * Get evolucionDolor
     *
     * @return integer
     */
    public function getEvolucionDolor()
    {
        return $this->evolucionDolor;
    }

    /**
     * Set etologiaDolor
     *
     * @param string $etologiaDolor
     *
     * @return Anamnesis
     */
    public function setEtologiaDolor($etologiaDolor)
    {
        $this->etologiaDolor = $etologiaDolor;

        return $this;
    }

    /**
     * Get etologiaDolor
     *
     * @return string
     */
    public function getEtologiaDolor()
    {
        return $this->etologiaDolor;
    }

    /**
     * Set localizacionDolor
     *
     * @param string $localizacionDolor
     *
     * @return Anamnesis
     */
    public function setLocalizacionDolor($localizacionDolor)
    {
        $this->localizacionDolor = $localizacionDolor;

        return $this;
    }

    /**
     * Get localizacionDolor
     *
     * @return string
     */
    public function getLocalizacionDolor()
    {
        return $this->localizacionDolor;
    }

    /**
     * Set faseEntrenamiento
     *
     * @param \LogicBundle\Entity\FaseEntrenamiento $faseEntrenamiento
     *
     * @return Anamnesis
     */
    public function setFaseEntrenamiento(\LogicBundle\Entity\FaseEntrenamiento $faseEntrenamiento = null)
    {
        $this->faseEntrenamiento = $faseEntrenamiento;

        return $this;
    }

    /**
     * Get faseEntrenamiento
     *
     * @return \LogicBundle\Entity\FaseEntrenamiento
     */
    public function getFaseEntrenamiento()
    {
        return $this->faseEntrenamiento;
    }

    /**
     * Set nivelIndependenciaFuncional
     *
     * @param \LogicBundle\Entity\NivelIndependenciaFuncional $nivelIndependenciaFuncional
     *
     * @return Anamnesis
     */
    public function setNivelIndependenciaFuncional(\LogicBundle\Entity\NivelIndependenciaFuncional $nivelIndependenciaFuncional = null)
    {
        $this->nivelIndependenciaFuncional = $nivelIndependenciaFuncional;

        return $this;
    }

    /**
     * Get nivelIndependenciaFuncional
     *
     * @return \LogicBundle\Entity\NivelIndependenciaFuncional
     */
    public function getNivelIndependenciaFuncional()
    {
        return $this->nivelIndependenciaFuncional;
    }

    /**
     * Set presenciaDolor
     *
     * @param \LogicBundle\Entity\PresenciaDolor $presenciaDolor
     *
     * @return Anamnesis
     */
    public function setPresenciaDolor(\LogicBundle\Entity\PresenciaDolor $presenciaDolor = null)
    {
        $this->presenciaDolor = $presenciaDolor;

        return $this;
    }

    /**
     * Get presenciaDolor
     *
     * @return \LogicBundle\Entity\PresenciaDolor
     */
    public function getPresenciaDolor()
    {
        return $this->presenciaDolor;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return Anamnesis
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
