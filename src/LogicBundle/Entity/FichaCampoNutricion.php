<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FichaCampoNutricion
 *
 * @ORM\Table(name="ficha_campo_nutricion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FichaCampoNutricionRepository")
 */
class FichaCampoNutricion
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
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="evaluacion", type="text", nullable=true)
     */
    private $evaluacion;

    /**
     * @var string
     *
     * @ORM\Column(name="metodologia", type="text", nullable=true)
     */
    private $metodologia;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_trabajo", type="text")
     */
    private $planTrabajo;
    
    /**
     * @ORM\ManyToOne(targetEntity="ConsultaNutricion", inversedBy="fichaCampoNutriciones")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\OneToMany(targetEntity="Diagnostico", mappedBy="fichaCampoNutricion", cascade={"persist"})
     */
    private $diagnosticos;
    
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
     * Set evaluacion
     *
     * @param string $evaluacion
     *
     * @return FichaCampoNutricion
     */
    public function setEvaluacion($evaluacion)
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    /**
     * Get evaluacion
     *
     * @return string
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * Set metodologia
     *
     * @param string $metodologia
     *
     * @return FichaCampoNutricion
     */
    public function setMetodologia($metodologia)
    {
        $this->metodologia = $metodologia;

        return $this;
    }

    /**
     * Get metodologia
     *
     * @return string
     */
    public function getMetodologia()
    {
        return $this->metodologia;
    }

    /**
     * Set planTrabajo
     *
     * @param string $planTrabajo
     *
     * @return FichaCampoNutricion
     */
    public function setPlanTrabajo($planTrabajo)
    {
        $this->planTrabajo = $planTrabajo;

        return $this;
    }

    /**
     * Get planTrabajo
     *
     * @return string
     */
    public function getPlanTrabajo()
    {
        return $this->planTrabajo;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return FichaCampoNutricion
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

    /**
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     *
     * @return FichaCampoNutricion
     */
    public function addDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
    {
        $this->diagnosticos[] = $diagnostico;

        return $this;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return FichaCampoNutricion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return FichaCampoNutricion
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }
}
