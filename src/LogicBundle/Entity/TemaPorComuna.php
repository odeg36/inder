<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TemaPorComuna
 *
 * @ORM\Table(name="tema_por_comuna")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TemaPorComunaRepository")
 */
class TemaPorComuna
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
     * @var int
     *
     * @ORM\Column(name="nivel", type="integer")
     */
    private $nivel;


    ///////relacion con comuna
    /**
     * @ORM\ManyToOne(targetEntity="Comuna");
     * @ORM\JoinColumn(name="comuna_id", referencedColumnName="id")
     */
    private $comuna;

    ////relacion con TemaModelo
    /**
     * @ORM\ManyToOne(targetEntity="TemaModelo", inversedBy="temaPorComuna");
     * @ORM\JoinColumn(name="temaModelo_id", referencedColumnName="id")
     */
    private $temaModelo;

    ////relacion con planAnualMetodologico

    /**
     * Many TemaPorComuna have Many PlanAnualMetodologico.
     * @ORM\ManyToMany(targetEntity="PlanAnualMetodologico")
     * @ORM\JoinTable(name="temaPorComuna_PlanAnualMetodologico",  
     *     joinColumns={@ORM\JoinColumn(name="planAnualMetodologico_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="temaPorComuna_id", referencedColumnName="id")}
     * )
     */

    private $planAnualMetodologico;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->planAnualMetodologico = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nivel
     *
     * @param integer $nivel
     *
     * @return TemaPorComuna
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return integer
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return TemaPorComuna
     */
    public function setComuna(\LogicBundle\Entity\Comuna $comuna = null)
    {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return \LogicBundle\Entity\Comuna
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * Set temaModelo
     *
     * @param \LogicBundle\Entity\TemaModelo $temaModelo
     *
     * @return TemaPorComuna
     */
    public function setTemaModelo(\LogicBundle\Entity\TemaModelo $temaModelo = null)
    {
        $this->temaModelo = $temaModelo;

        return $this;
    }

    /**
     * Get temaModelo
     *
     * @return \LogicBundle\Entity\TemaModelo
     */
    public function getTemaModelo()
    {
        return $this->temaModelo;
    }

    /**
     * Add planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     *
     * @return TemaPorComuna
     */
    public function addPlanAnualMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico)
    {
        $this->planAnualMetodologico[] = $planAnualMetodologico;

        return $this;
    }

    /**
     * Remove planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     */
    public function removePlanAnualMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico)
    {
        $this->planAnualMetodologico->removeElement($planAnualMetodologico);
    }

    /**
     * Get planAnualMetodologico
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanAnualMetodologico()
    {
        return $this->planAnualMetodologico;
    }
}
