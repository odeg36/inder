<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NivelPlanMetodologico
 *
 * @ORM\Table(name="nivel_plan_metodologico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelPlanMetodologicoRepository")
 */
class NivelPlanMetodologico {

    public function __toString() {
        return (string)$this->getId() ?: "";
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
     * @ORM\ManyToOne(targetEntity="PlanMetodologico", inversedBy="nivelPlanMetodologicos", cascade={"persist"})
     * @ORM\JoinColumn(name="plan_metodologico_id", referencedColumnName="id")
     */
    private $planMetodologico;
    
    /**
     * @ORM\ManyToOne(targetEntity="NivelPlan", inversedBy="nivelPlanMetodologicos")
     * @ORM\JoinColumn(name="nivel_plan_id", referencedColumnName="id")
     */
    private $nivelPlan;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologicoSubPrincipio", mappedBy="nivelPlanMetodologico", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $nivelPlanMetodologicoSubPrincipios;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologicoComponente", mappedBy="nivelPlanMetodologico", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $nivelPlanMetodologicoComponentes;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelFaseComponente", mappedBy="nivelPlanMetodologico", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $nivelPlanMetodologicoFaseComponentes;

    /**
     * Add nivelPlanMetodologicoSubPrincipio.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $nivelPlanMetodologicoSubPrincipio
     *
     * @return NivelPlanMetodologico
     */
    public function addNivelPlanMetodologicoSubPrincipio(\LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $nivelPlanMetodologicoSubPrincipio)
    {
        $nivelPlanMetodologicoSubPrincipio->setNivelPlanMetodologico($this);
        
        $this->nivelPlanMetodologicoSubPrincipios[] = $nivelPlanMetodologicoSubPrincipio;

        return $this;
    }
    
    /**
     * Add nivelPlanMetodologicoComponete.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoComponente $nivelPlanMetodologicoComponete
     *
     * @return NivelPlanMetodologico
     */
    public function addNivelPlanMetodologicoComponente(\LogicBundle\Entity\NivelPlanMetodologicoComponente $nivelPlanMetodologicoComponente)
    {
        $nivelPlanMetodologicoComponente->setNivelPlanMetodologico($this);
        
        $this->nivelPlanMetodologicoComponentes[] = $nivelPlanMetodologicoComponente;

        return $this;
    }
    
    /**
     * Add nivelPlanMetodologicoFaseComponente.
     *
     * @param \LogicBundle\Entity\NivelFaseComponente $nivelPlanMetodologicoFaseComponente
     *
     * @return NivelPlanMetodologico
     */
    public function addNivelPlanMetodologicoFaseComponente(\LogicBundle\Entity\NivelFaseComponente $nivelPlanMetodologicoFaseComponente)
    {
        $nivelPlanMetodologicoFaseComponente->setNivelPlanMetodologico($this);
        
        $this->nivelPlanMetodologicoFaseComponentes[] = $nivelPlanMetodologicoFaseComponente;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nivelPlanMetodologicoSubPrincipios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nivelPlanMetodologicoComponentes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nivelPlanMetodologicoFaseComponentes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico|null $planMetodologico
     *
     * @return NivelPlanMetodologico
     */
    public function setPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico = null)
    {
        $this->planMetodologico = $planMetodologico;

        return $this;
    }

    /**
     * Get planMetodologico.
     *
     * @return \LogicBundle\Entity\PlanMetodologico|null
     */
    public function getPlanMetodologico()
    {
        return $this->planMetodologico;
    }

    /**
     * Set nivelPlan.
     *
     * @param \LogicBundle\Entity\NivelPlan|null $nivelPlan
     *
     * @return NivelPlanMetodologico
     */
    public function setNivelPlan(\LogicBundle\Entity\NivelPlan $nivelPlan = null)
    {
        $this->nivelPlan = $nivelPlan;

        return $this;
    }

    /**
     * Get nivelPlan.
     *
     * @return \LogicBundle\Entity\NivelPlan|null
     */
    public function getNivelPlan()
    {
        return $this->nivelPlan;
    }

    /**
     * Remove nivelPlanMetodologicoSubPrincipio.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $nivelPlanMetodologicoSubPrincipio
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivelPlanMetodologicoSubPrincipio(\LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $nivelPlanMetodologicoSubPrincipio)
    {
        return $this->nivelPlanMetodologicoSubPrincipios->removeElement($nivelPlanMetodologicoSubPrincipio);
    }

    /**
     * Get nivelPlanMetodologicoSubPrincipios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNivelPlanMetodologicoSubPrincipios()
    {
        return $this->nivelPlanMetodologicoSubPrincipios;
    }

    /**
     * Remove nivelPlanMetodologicoComponente.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoComponente $nivelPlanMetodologicoComponente
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivelPlanMetodologicoComponente(\LogicBundle\Entity\NivelPlanMetodologicoComponente $nivelPlanMetodologicoComponente)
    {
        return $this->nivelPlanMetodologicoComponentes->removeElement($nivelPlanMetodologicoComponente);
    }

    /**
     * Get nivelPlanMetodologicoComponentes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNivelPlanMetodologicoComponentes()
    {
        return $this->nivelPlanMetodologicoComponentes;
    }

    /**
     * Remove nivelPlanMetodologicoFaseComponente.
     *
     * @param \LogicBundle\Entity\NivelFaseComponente $nivelPlanMetodologicoFaseComponente
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivelPlanMetodologicoFaseComponente(\LogicBundle\Entity\NivelFaseComponente $nivelPlanMetodologicoFaseComponente)
    {
        return $this->nivelPlanMetodologicoFaseComponentes->removeElement($nivelPlanMetodologicoFaseComponente);
    }

    /**
     * Get nivelPlanMetodologicoFaseComponentes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNivelPlanMetodologicoFaseComponentes()
    {
        return $this->nivelPlanMetodologicoFaseComponentes;
    }
}
