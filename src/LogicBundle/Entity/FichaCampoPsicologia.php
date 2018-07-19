<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FichaCampoPsicologia
 *
 * @ORM\Table(name="ficha_campo_psicologia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FichaCampoPsicologiaRepository")
 */
class FichaCampoPsicologia
{
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
     * @ORM\Column(name="hipotesis", type="text", nullable=true)
     */
    private $hipotesis;

    /**
     * @var string
     *
     * @ORM\Column(name="constatacionContextual", type="text", nullable=true)
     */
    private $constatacionContextual;

    /**
     * @var string
     *
     * @ORM\Column(name="planIntervencion", type="text", nullable=true)
     */
    private $planIntervencion;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaPsicologia", inversedBy="fichaCampoPsicologias")
     * @ORM\JoinColumn(name="psicologia_id", referencedColumnName="id")
     */
    private $psicologia;

    /**
     * @ORM\OneToMany(targetEntity="DiagnosticoFichaPsicologia", mappedBy="fichaCampoPsicologia", cascade={"persist"})
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
     * Set hipotesis
     *
     * @param string $hipotesis
     *
     * @return FichaCampoPsicologia
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
     * Set constatacionContextual
     *
     * @param string $constatacionContextual
     *
     * @return FichaCampoPsicologia
     */
    public function setConstatacionContextual($constatacionContextual)
    {
        $this->constatacionContextual = $constatacionContextual;

        return $this;
    }

    /**
     * Get constatacionContextual
     *
     * @return string
     */
    public function getConstatacionContextual()
    {
        return $this->constatacionContextual;
    }

    /**
     * Set planIntervencion
     *
     * @param string $planIntervencion
     *
     * @return FichaCampoPsicologia
     */
    public function setPlanIntervencion($planIntervencion)
    {
        $this->planIntervencion = $planIntervencion;

        return $this;
    }

    /**
     * Get planIntervencion
     *
     * @return string
     */
    public function getPlanIntervencion()
    {
        return $this->planIntervencion;
    }

    /**
     * Set psicologia
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia $psicologia
     *
     * @return FichaCampoPsicologia
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
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoFichaPsicologia $diagnostico
     *
     * @return FichaCampoPsicologia
     */
    public function addDiagnostico(\LogicBundle\Entity\DiagnosticoFichaPsicologia $diagnostico)
    {
        $this->diagnosticos[] = $diagnostico;

        return $this;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoFichaPsicologia $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\DiagnosticoFichaPsicologia $diagnostico)
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
     * @return FichaCampoPsicologia
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
     * @return FichaCampoPsicologia
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
