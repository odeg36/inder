<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoReserva
 *
 * @ORM\Table(name="tipo_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoReservaRepository")
 */
class TipoReserva {

    const TIPOS_RESERVA = ["Evento", "Oferta y Servicio", "Mantenimiento", "Eventos de Ciudad", "Organismo Deportivo", "Practica Libre", "Festivos"];

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
     * @var boolean
     *
     * @ORM\Column(name="bloquea_escenario", type="boolean", nullable=true)
     */
    private $bloquea;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivo", mappedBy="tipoReserva")
     */
    private $tipoReservaEscenarioDeportivos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tipoReservaEscenarioDeportivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoReserva
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
     * Add tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     *
     * @return Area
     */
    public function addTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tipoReservaEscenarioDeportivos[] = $tipoReservaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     */
    public function removeTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tipoReservaEscenarioDeportivos->removeElement($tipoReservaEscenarioDeportivo);
    }

    /**
     * Get tipoReservaEscenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoReservaEscenarioDeportivos() {
        return $this->tipoReservaEscenarioDeportivos;
    }


    /**
     * Set bloquea.
     *
     * @param bool $bloquea
     *
     * @return TipoReserva
     */
    public function setBloquea($bloquea)
    {
        $this->bloquea = $bloquea;

        return $this;
    }

    /**
     * Get bloquea.
     *
     * @return bool
     */
    public function getBloquea()
    {
        return $this->bloquea;
    }
}
