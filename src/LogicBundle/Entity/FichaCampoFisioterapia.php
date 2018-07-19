<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FichaCampoFisioterapia
 *
 * @ORM\Table(name="ficha_campo_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FichaCampoFisioterapiaRepository")
 */
class FichaCampoFisioterapia {

    public function __toString() {
        return (string) $this->getFisioterapia()->getDeportista() ?: '';
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
     * @ORM\Column(name="plan_trabajo", type="text")
     */
    private $planTrabajo;

    /**
     * @ORM\OneToMany(targetEntity="DiagnosticoFisioterapia", mappedBy="fichaCampoFisioterapia", cascade={"persist"})
     */
    private $diagnosticos;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaFisioterapia", inversedBy="fichaCampoFisioterapias")
     * @ORM\JoinColumn(name="fisioterapia_id", referencedColumnName="id")
     */
    private $fisioterapia;

    /**
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico
     *
     * @return FichaCampoFisioterapia
     */
    public function addDiagnostico(\LogicBundle\Entity\DiagnosticoFisioterapia $diagnostico) {
        $diagnostico->setFichaCampoFisioterapia($this);
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return FichaCampoFisioterapia
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
     * @return FichaCampoFisioterapia
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

    /**
     * Set evaluacion
     *
     * @param string $evaluacion
     *
     * @return FichaCampoFisioterapia
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
     * Set planTrabajo
     *
     * @param string $planTrabajo
     *
     * @return FichaCampoFisioterapia
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
     * Set fisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $fisioterapia
     *
     * @return FichaCampoFisioterapia
     */
    public function setFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $fisioterapia = null)
    {
        $this->fisioterapia = $fisioterapia;

        return $this;
    }

    /**
     * Get fisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getFisioterapia()
    {
        return $this->fisioterapia;
    }
}
