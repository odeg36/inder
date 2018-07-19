<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoReservaEscenarioDeportivo
 *
 * @ORM\Table(name="tipo_reserva_escenario_deportivo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoReservaEscenarioDeportivoRepository")
 */
class TipoReservaEscenarioDeportivo
{

    public function __toString() {
        return $this->getTipoReserva()->getNombre() ? $this->getTipoReserva()->getNombre() : '';
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="tipoReservaEscenarioDeportivos")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoReserva", inversedBy="tipoReservaEscenarioDeportivos")
     * @ORM\JoinColumn(name="tipo_reserva_id", referencedColumnName="id", )
     */
    private $tipoReserva;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision", mappedBy="tipoReservaEscenarioDeportivo")
     */
    private $tipoReservaEscenarioDeportivoDivisiones;
    

    
    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoReserva = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoReservaEscenarioDeportivoDivisiones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tipoReservaEscenarioDeportivoDivision
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision
     *
     * @return TipoReservaEscenarioDeportivo
     */
    public function addTipoReservaEscenarioDeportivoDivision(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision) {
        $this->tipoReservaEscenarioDeportivoDivisiones[] = $tipoReservaEscenarioDeportivoDivision;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivoDivision
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision
     */
    public function removeTipoReservaEscenarioDeportivoDivision(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision) {
        $this->tipoReservaEscenarioDeportivoDivisiones->removeElement($tipoReservaEscenarioDeportivoDivision);
    }

    /**
     * Get tipoReservaEscenarioDeportivoDivisiones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoReservaEscenarioDeportivoDivisiones() {
        return $this->tipoReservaEscenarioDeportivoDivisiones;
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
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return TipoReservaEscenarioDeportivo
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;
        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
     * Set tipoReserva
     *
     * @param \LogicBundle\Entity\TipoReserva $tipoReserva
     *
     * @return TipoReservaEscenarioDeportivo
     */
    public function setTipoReserva(\LogicBundle\Entity\TipoReserva $tipoReserva = null) {
        $this->tipoReserva = $tipoReserva;
        return $this;
    }

    /**
     * Get tipoReserva
     *
     * @return \LogicBundle\Entity\TipoReserva
     */
    public function getTipoReserva() {
        return $this->tipoReserva;
    }

    

    /**
     * Add tipoReservaEscenarioDeportivoDivisione.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione
     *
     * @return TipoReservaEscenarioDeportivo
     */
    public function addTipoReservaEscenarioDeportivoDivisione(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione)
    {
        $this->tipoReservaEscenarioDeportivoDivisiones[] = $tipoReservaEscenarioDeportivoDivisione;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivoDivisione.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTipoReservaEscenarioDeportivoDivisione(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione)
    {
        return $this->tipoReservaEscenarioDeportivoDivisiones->removeElement($tipoReservaEscenarioDeportivoDivisione);
    }
}
