<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clasificacion
 *
 * @ORM\Table(name="clasificacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ClasificacionRepository")
 */
class Clasificacion
{
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", mappedBy="clasificacion")
     */
    private $planesAnualesMetodologicos;


    /**
     * Constructor
     */
    public function __construct() {
        $this->planesAnualesMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Clasificacion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     *
     * @return Clasificacion
     */
    public function addPlanAnualMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico) {
        $this->planesAnualesMetodologicos[] = $planAnualMetodologico;

        return $this;
    }

    /**
     * Remove planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     */
    public function removePlanAnualMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico) {
        $this->planesAnualesMetodologicos->removeElement($planAnualMetodologico);
    }

    /**
     * Get planesAnualesMetodologicos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanesAnualesMetodologicos() {
        return $this->planesAnualesMetodologicos;
    }

    /**
     * Add planesAnualesMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     *
     * @return Clasificacion
     */
    public function addPlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico)
    {
        $this->planesAnualesMetodologicos[] = $planesAnualesMetodologico;

        return $this;
    }

    /**
     * Remove planesAnualesMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     */
    public function removePlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico)
    {
        $this->planesAnualesMetodologicos->removeElement($planesAnualesMetodologico);
    }
}
