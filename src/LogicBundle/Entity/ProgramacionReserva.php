<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProgramacionReserva
 *
 * @ORM\Table(name="programacion_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ProgramacionReservaRepository")
 */
class ProgramacionReserva {

    public function getHorarioDia() {
        if ($this->getInicioManana() && $this->getFinTarde()) {
            return $this->getDia()->getNombre() . " " . $this->getInicioManana()->format("H:i") . " - " . $this->getFinTarde()->format("H:i");
        } else if ($this->getInicioManana()) {
            return $this->getDia()->getNombre() . " " . $this->getInicioManana()->format("H:i") . " - " . $this->getFinManana()->format("H:i");
        } else if ($this->getInicioTarde()) {
            return $this->getDia()->getNombre() . " " . $this->getInicioTarde()->format("H:i") . " - " . $this->getFinTarde()->format("H:i");
        }
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="inicioManana", type="time", nullable=true)
     */
    private $inicioManana;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="finManana", type="time", nullable=true)
     */
    private $finManana;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="inicioTarde", type="time", nullable=true)
     */
    private $inicioTarde;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="finTarde", type="time", nullable=true)
     */
    private $finTarde;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Reserva", inversedBy="programaciones", cascade={"persist"})
     * @ORM\JoinColumn(name="reserva_id", referencedColumnName="id", )
     */
    private $reserva;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Dia", inversedBy="programaciones");
     * @ORM\JoinColumn(name="dia_id", referencedColumnName="id", )
     */
    private $dia;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    public function __toString() {
        return "";
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
     * Set inicioManana.
     *
     * @param \DateTime|null $inicioManana
     *
     * @return ProgramacionReserva
     */
    public function setInicioManana($inicioManana = null) {
        $this->inicioManana = $inicioManana;

        return $this;
    }

    /**
     * Get inicioManana.
     *
     * @return \DateTime|null
     */
    public function getInicioManana() {
        return $this->inicioManana;
    }

    /**
     * Set finManana.
     *
     * @param \DateTime|null $finManana
     *
     * @return ProgramacionReserva
     */
    public function setFinManana($finManana = null) {
        $this->finManana = $finManana;

        return $this;
    }

    /**
     * Get finManana.
     *
     * @return \DateTime|null
     */
    public function getFinManana() {
        return $this->finManana;
    }

    /**
     * Set inicioTarde.
     *
     * @param \DateTime|null $inicioTarde
     *
     * @return ProgramacionReserva
     */
    public function setInicioTarde($inicioTarde = null) {
        $this->inicioTarde = $inicioTarde;

        return $this;
    }

    /**
     * Get inicioTarde.
     *
     * @return \DateTime|null
     */
    public function getInicioTarde() {
        return $this->inicioTarde;
    }

    /**
     * Set finTarde.
     *
     * @param \DateTime|null $finTarde
     *
     * @return ProgramacionReserva
     */
    public function setFinTarde($finTarde = null) {
        $this->finTarde = $finTarde;

        return $this;
    }

    /**
     * Get finTarde.
     *
     * @return \DateTime|null
     */
    public function getFinTarde() {
        return $this->finTarde;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ProgramacionReserva
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return ProgramacionReserva
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Set dia.
     *
     * @param \LogicBundle\Entity\Dia|null $dia
     *
     * @return ProgramacionReserva
     */
    public function setDia(\LogicBundle\Entity\Dia $dia = null) {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia.
     *
     * @return \LogicBundle\Entity\Dia|null
     */
    public function getDia() {
        return $this->dia;
    }

    /**
     * Set reserva.
     *
     * @param \LogicBundle\Entity\Reserva|null $reserva
     *
     * @return ProgramacionReserva
     */
    public function setReserva(\LogicBundle\Entity\Reserva $reserva = null) {
        $this->reserva = $reserva;

        return $this;
    }

    /**
     * Get reserva.
     *
     * @return \LogicBundle\Entity\Reserva|null
     */
    public function getReserva() {
        return $this->reserva;
    }

}
