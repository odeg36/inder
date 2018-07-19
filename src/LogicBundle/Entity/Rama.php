<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rama
 *
 * @ORM\Table(name="rama")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RamaRepository")
 */
class Rama {

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="rama")
     */
    private $eventos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinaPruebaRama", mappedBy="rama")
     */
    private $disciplinasPruebasRamas;

    /**
     * @ORM\OneToMany(targetEntity="PlanMetodologico", mappedBy="rama")
     */
    private $planMetodologicos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinasPruebasRamas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Rama
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add evento.
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Rama
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento.
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        return $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Add disciplinasPruebasRama.
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     *
     * @return Rama
     */
    public function addDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama) {
        $this->disciplinasPruebasRamas[] = $disciplinasPruebasRama;

        return $this;
    }

    /**
     * Remove disciplinasPruebasRama.
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama) {
        return $this->disciplinasPruebasRamas->removeElement($disciplinasPruebasRama);
    }

    /**
     * Get disciplinasPruebasRamas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinasPruebasRamas() {
        return $this->disciplinasPruebasRamas;
    }

    /**
     * Add planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return Rama
     */
    public function addPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico) {
        $this->planMetodologicos[] = $planMetodologico;

        return $this;
    }

    /**
     * Remove planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico) {
        return $this->planMetodologicos->removeElement($planMetodologico);
    }

    /**
     * Get planMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanMetodologicos() {
        return $this->planMetodologicos;
    }

}
