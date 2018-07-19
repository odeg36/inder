<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DiaReserva
 *
 * @ORM\Table(name="dia_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiaReservaRepository")
 */
class DiaReserva {

    public function __toString() {
        return (string) $this->dia ? (string) $this->dia : '';
    }

    public function getDiaHoras() {
        return $this->dia->getNombre() . " " . $this->getReserva()->getHoraInicial()->format('H:i') . " - " . $this->getReserva()->getHoraFinal()->format('H:i');
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Dia", inversedBy="diaReserva");
     */
    private $dia;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Reserva", inversedBy="diaReserva")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $reserva;
    
    /**
     * @ORM\OneToMany(targetEntity="AsistenciaReserva", mappedBy="diaReserva")
     */
    private $asistenciaReservas;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asistenciaReservas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dia
     *
     * @param \LogicBundle\Entity\Dia $dia
     *
     * @return DiaReserva
     */
    public function setDia(\LogicBundle\Entity\Dia $dia = null)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \LogicBundle\Entity\Dia
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set reserva
     *
     * @param \LogicBundle\Entity\Reserva $reserva
     *
     * @return DiaReserva
     */
    public function setReserva(\LogicBundle\Entity\Reserva $reserva = null)
    {
        $this->reserva = $reserva;

        return $this;
    }

    /**
     * Get reserva
     *
     * @return \LogicBundle\Entity\Reserva
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Add asistenciaReserva
     *
     * @param \LogicBundle\Entity\AsistenciaReserva $asistenciaReserva
     *
     * @return DiaReserva
     */
    public function addAsistenciaReserva(\LogicBundle\Entity\AsistenciaReserva $asistenciaReserva)
    {
        $this->asistenciaReservas[] = $asistenciaReserva;

        return $this;
    }

    /**
     * Remove asistenciaReserva
     *
     * @param \LogicBundle\Entity\AsistenciaReserva $asistenciaReserva
     */
    public function removeAsistenciaReserva(\LogicBundle\Entity\AsistenciaReserva $asistenciaReserva)
    {
        $this->asistenciaReservas->removeElement($asistenciaReserva);
    }

    /**
     * Get asistenciaReservas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsistenciaReservas()
    {
        return $this->asistenciaReservas;
    }
}
