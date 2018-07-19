<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DivisionReserva
 *
 * @ORM\Table(name="division_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DivisionReservaRepository")
 */
class DivisionReserva {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division", inversedBy="reservas", cascade={"persist"})
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Reserva", inversedBy="divisiones", cascade={"persist"})
     * @ORM\JoinColumn(name="reserva_id", referencedColumnName="id", )
     */
    private $reserva;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\UsuarioDivisionReserva", mappedBy="divisionReserva", cascade={"persist"})
     */
    private $divisionReservas;

    public function __toString() {
        return "";
    }

    function __construct($division, $reserva) {
        $this->division = $division;
        $this->reserva = $reserva;
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
     * Set division.
     *
     * @param \LogicBundle\Entity\Division|null $division
     *
     * @return DivisionReserva
     */
    public function setDivision(\LogicBundle\Entity\Division $division = null) {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division.
     *
     * @return \LogicBundle\Entity\Division|null
     */
    public function getDivision() {
        return $this->division;
    }

    /**
     * Set reserva.
     *
     * @param \LogicBundle\Entity\Reserva|null $reserva
     *
     * @return DivisionReserva
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

    /**
     * Add divisionReserva.
     *
     * @param \LogicBundle\Entity\UsuarioDivisionReserva $divisionReserva
     *
     * @return DivisionReserva
     */
    public function addDivisionReserva(\LogicBundle\Entity\UsuarioDivisionReserva $divisionReserva) {
        $this->divisionReservas[] = $divisionReserva;

        return $this;
    }

    /**
     * Remove divisionReserva.
     *
     * @param \LogicBundle\Entity\UsuarioDivisionReserva $divisionReserva
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDivisionReserva(\LogicBundle\Entity\UsuarioDivisionReserva $divisionReserva) {
        return $this->divisionReservas->removeElement($divisionReserva);
    }

    /**
     * Get divisionReservas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisionReservas() {
        return $this->divisionReservas;
    }

}
