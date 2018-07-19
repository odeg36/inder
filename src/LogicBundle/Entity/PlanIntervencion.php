<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanIntervencion
 *
 * @ORM\Table(name="plan_intervencion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PlanIntervencionRepository")
 */
class PlanIntervencion
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
     * @var string
     *
     * @ORM\Column(name="invtervencionDeportista", type="text", nullable=true)
     */
    private $invtervencionDeportista;

    /**
     * @var string
     *
     * @ORM\Column(name="invtervencionEntrenador", type="text", nullable=true)
     */
    private $invtervencionEntrenador;

    /**
     * @var string
     *
     * @ORM\Column(name="invtervencionFamilia", type="text", nullable=true)
     */
    private $invtervencionFamilia;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaPsicologia", inversedBy="planIntervencion")
     * @ORM\JoinColumn(name="psicologia_id", referencedColumnName="id")
     */
    private $psicologia;
    

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
     * Set invtervencionDeportista
     *
     * @param string $invtervencionDeportista
     *
     * @return PlanIntervencion
     */
    public function setInvtervencionDeportista($invtervencionDeportista)
    {
        $this->invtervencionDeportista = $invtervencionDeportista;

        return $this;
    }

    /**
     * Get invtervencionDeportista
     *
     * @return string
     */
    public function getInvtervencionDeportista()
    {
        return $this->invtervencionDeportista;
    }

    /**
     * Set invtervencionEntrenador
     *
     * @param string $invtervencionEntrenador
     *
     * @return PlanIntervencion
     */
    public function setInvtervencionEntrenador($invtervencionEntrenador)
    {
        $this->invtervencionEntrenador = $invtervencionEntrenador;

        return $this;
    }

    /**
     * Get invtervencionEntrenador
     *
     * @return string
     */
    public function getInvtervencionEntrenador()
    {
        return $this->invtervencionEntrenador;
    }

    /**
     * Set invtervencionFamilia
     *
     * @param string $invtervencionFamilia
     *
     * @return PlanIntervencion
     */
    public function setInvtervencionFamilia($invtervencionFamilia)
    {
        $this->invtervencionFamilia = $invtervencionFamilia;

        return $this;
    }

    /**
     * Get invtervencionFamilia
     *
     * @return string
     */
    public function getInvtervencionFamilia()
    {
        return $this->invtervencionFamilia;
    }

    /**
     * Set psicologia
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia $psicologia
     *
     * @return PlanIntervencion
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
}
