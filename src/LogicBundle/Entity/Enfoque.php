<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enfoque
 *
 * @ORM\Table(name="enfoque")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EnfoqueRepository")
 */
class Enfoque
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", mappedBy="enfoque")
     */
    private $planesAnualesMetodologicos;

    /**
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\Nivel", inversedBy="enfoques")
     * @ORM\JoinColumn(name="nivel_id",referencedColumnName="id")
     */
    private $niveles;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="enfoque")
     */
    private $eventos;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->planesAnualesMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->niveles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Enfoque
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
     * @return Enfoque
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
     * Add nivel
     *
     * @param \LogicBundle\Entity\Nivel $nivel
     *
     * @return Enfoque
     */
    public function addNivel(\LogicBundle\Entity\Nivel $nivel) {
        $this->niveles[] = $nivel;

        return $this;
    }

    /**
     * Remove nivel
     *
     * @param \LogicBundle\Entity\Nivel $nivel
     */
    public function removeNivel(\LogicBundle\Entity\Nivel $nivel) {
        $this->niveles->removeElement($nivel);
    }

    /**
     * Get niveles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNiveles() {
        return $this->niveles;
    }

     /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Enfoque
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove wvento
     *
     * @param \LogicBundle\Entity\Evento $Evento
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Add planesAnualesMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     *
     * @return Enfoque
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

    /**
     * Add nivele
     *
     * @param \LogicBundle\Entity\Nivel $nivele
     *
     * @return Enfoque
     */
    public function addNivele(\LogicBundle\Entity\Nivel $nivele)
    {
        $this->niveles[] = $nivele;

        return $this;
    }

    /**
     * Remove nivele
     *
     * @param \LogicBundle\Entity\Nivel $nivele
     */
    public function removeNivele(\LogicBundle\Entity\Nivel $nivele)
    {
        $this->niveles->removeElement($nivele);
    }
}
