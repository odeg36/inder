<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NivelPlan
 *
 * @ORM\Table(name="nivel_plan")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelPlanRepository")
 */
class NivelPlan
{
    public function __toString() {
        return $this->getNombre() ? : '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologico", mappedBy="nivelPlan")
     */
    private $nivelPlanMetodologicos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nivelPlanMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return NivelPlan
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico
     *
     * @return NivelPlan
     */
    public function addNivelPlanMetodologico(\LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico)
    {
        $this->nivelPlanMetodologicos[] = $nivelPlanMetodologico;

        return $this;
    }

    /**
     * Remove nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivelPlanMetodologico(\LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico)
    {
        return $this->nivelPlanMetodologicos->removeElement($nivelPlanMetodologico);
    }

    /**
     * Get nivelPlanMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNivelPlanMetodologicos()
    {
        return $this->nivelPlanMetodologicos;
    }
}
