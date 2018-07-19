<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Psicologia
 *
 * @ORM\Table(name="consulta_psicologia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ConsultaPsicologiaRepository")
 */
class ConsultaPsicologia
{
    public function __toString() {
        if($this->getFechaCreacion()){
            return $this->getFechaCreacion()->format("d/m/Y") . ' - ' . $this->getDeportista(); 
        }
        
        return (string)$this->getId();
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
     * @var bool
     *
     * @ORM\Column(name="finalizado", type="boolean", nullable=true)
     */
    protected $finalizado;
    
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="psicologias")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;
    
    /**
     * @ORM\OneToOne(targetEntity="Entrevista", mappedBy="psicologia")
     */
    private $entrevista;
    
    /**
     * @ORM\OneToOne(targetEntity="DiagnosticoPsicologia", mappedBy="psicologia")
     */
    private $diagnostico;
    
    /**
     * @ORM\OneToOne(targetEntity="PlanIntervencion", mappedBy="psicologia")
     */
    private $planIntervencion;
    
    /**
     * @ORM\OneToOne(targetEntity="Remision", mappedBy="psicologia")
     */
    private $remision;
    
    /**
     * @ORM\OneToMany(targetEntity="FichaCampoPsicologia", mappedBy="psicologia")
     */
    private $fichaCampoPsicologias;
    
    /**
     * @ORM\OneToMany(targetEntity="NotaPsicologia", mappedBy="psicologia")
     */
    private $notaPsicologias;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichaCampoPsicologias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notaPsicologias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return ConsultaPsicologia
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ConsultaPsicologia
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
     * @return ConsultaPsicologia
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
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ConsultaPsicologia
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista()
    {
        return $this->deportista;
    }

    /**
     * Set entrevista
     *
     * @param \LogicBundle\Entity\Entrevista $entrevista
     *
     * @return ConsultaPsicologia
     */
    public function setEntrevista(\LogicBundle\Entity\Entrevista $entrevista = null)
    {
        $this->entrevista = $entrevista;

        return $this;
    }

    /**
     * Get entrevista
     *
     * @return \LogicBundle\Entity\Entrevista
     */
    public function getEntrevista()
    {
        return $this->entrevista;
    }

    /**
     * Set diagnostico
     *
     * @param \LogicBundle\Entity\DiagnosticoPsicologia $diagnostico
     *
     * @return ConsultaPsicologia
     */
    public function setDiagnostico(\LogicBundle\Entity\DiagnosticoPsicologia $diagnostico = null)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return \LogicBundle\Entity\DiagnosticoPsicologia
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set planIntervencion
     *
     * @param \LogicBundle\Entity\PlanIntervencion $planIntervencion
     *
     * @return ConsultaPsicologia
     */
    public function setPlanIntervencion(\LogicBundle\Entity\PlanIntervencion $planIntervencion = null)
    {
        $this->planIntervencion = $planIntervencion;

        return $this;
    }

    /**
     * Get planIntervencion
     *
     * @return \LogicBundle\Entity\PlanIntervencion
     */
    public function getPlanIntervencion()
    {
        return $this->planIntervencion;
    }

    /**
     * Set remision
     *
     * @param \LogicBundle\Entity\Remision $remision
     *
     * @return ConsultaPsicologia
     */
    public function setRemision(\LogicBundle\Entity\Remision $remision = null)
    {
        $this->remision = $remision;

        return $this;
    }

    /**
     * Get remision
     *
     * @return \LogicBundle\Entity\Remision
     */
    public function getRemision()
    {
        return $this->remision;
    }

    /**
     * Add fichaCampoPsicologia
     *
     * @param \LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia
     *
     * @return ConsultaPsicologia
     */
    public function addFichaCampoPsicologia(\LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia)
    {
        $this->fichaCampoPsicologias[] = $fichaCampoPsicologia;

        return $this;
    }

    /**
     * Remove fichaCampoPsicologia
     *
     * @param \LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia
     */
    public function removeFichaCampoPsicologia(\LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia)
    {
        $this->fichaCampoPsicologias->removeElement($fichaCampoPsicologia);
    }

    /**
     * Get fichaCampoPsicologias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichaCampoPsicologias()
    {
        return $this->fichaCampoPsicologias;
    }

    /**
     * Add notaPsicologia
     *
     * @param \LogicBundle\Entity\NotaPsicologia $notaPsicologia
     *
     * @return ConsultaPsicologia
     */
    public function addNotaPsicologia(\LogicBundle\Entity\NotaPsicologia $notaPsicologia)
    {
        $this->notaPsicologias[] = $notaPsicologia;

        return $this;
    }

    /**
     * Remove notaPsicologia
     *
     * @param \LogicBundle\Entity\NotaPsicologia $notaPsicologia
     */
    public function removeNotaPsicologia(\LogicBundle\Entity\NotaPsicologia $notaPsicologia)
    {
        $this->notaPsicologias->removeElement($notaPsicologia);
    }

    /**
     * Get notaPsicologias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotaPsicologias()
    {
        return $this->notaPsicologias;
    }
}
