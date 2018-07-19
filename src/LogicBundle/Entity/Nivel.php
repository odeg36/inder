<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nivel
 *
 * @ORM\Table(name="nivel")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelRepository")
 */
class Nivel {

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
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", mappedBy="niveles")
     */
    private $planesAnualesMetodologicos;

    /**
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\Enfoque", mappedBy="niveles")
     */
    private $enfoques;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ValoracionInicial", mappedBy="nivel")
     */
    private $valoracionesIniciales;

    /**
     * Constructor
     */
    public function __construct() {
        $this->planesAnualesMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enfoques = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valoracionesIniciales = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Nivel
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     *
     * @return Nivel
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
     * Add enfoque
     *
     * @param \LogicBundle\Entity\Enfoque $enfoque
     *
     * @return Nivel
     */
    public function addEnfoque(\LogicBundle\Entity\Enfoque $enfoque) {
        $this->enfoques[] = $enfoque;

        return $this;
    }

    /**
     * Remove enfoque
     *
     * @param \LogicBundle\Entity\Enfoque $enfoque
     */
    public function removeEnfoque(\LogicBundle\Entity\Enfoque $enfoque) {
        $this->enfoques->removeElement($enfoque);
    }

    /**
     * Get enfoques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnfoques() {
        return $this->enfoques;
    }

    /**
     * Add planesAnualesMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     *
     * @return Nivel
     */
    public function addPlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico) {
        $this->planesAnualesMetodologicos[] = $planesAnualesMetodologico;

        return $this;
    }

    /**
     * Remove planesAnualesMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     */
    public function removePlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico) {
        $this->planesAnualesMetodologicos->removeElement($planesAnualesMetodologico);
    }

    /**
     * Get valoracionesIniciales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValoracionesIniciales() {
        return $this->valoracionesIniciales;
    }

    /**
     * Add valoracionesIniciale
     *
     * @param \LogicBundle\Entity\ValoracionInicial $valoracionesIniciale
     *
     * @return Nivel
     */
    public function addValoracionesIniciale(\LogicBundle\Entity\ValoracionInicial $valoracionesIniciale) {
        $this->valoracionesIniciales[] = $valoracionesIniciale;

        return $this;
    }

    /**
     * Remove valoracionesIniciale
     *
     * @param \LogicBundle\Entity\ValoracionInicial $valoracionesIniciale
     */
    public function removeValoracionesIniciale(\LogicBundle\Entity\ValoracionInicial $valoracionesIniciale) {
        $this->valoracionesIniciales->removeElement($valoracionesIniciale);
    }

}
