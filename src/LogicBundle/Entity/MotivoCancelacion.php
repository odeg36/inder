<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotivoCancelacion
 *
 * @ORM\Table(name="motivo_cancelacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MotivoCancelacionRepository")
 */
class MotivoCancelacion
{
    public function __toString() {
        return $this->motivo ? $this->motivo : '';
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
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=255)
     */
    private $motivo;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Reserva", mappedBy="motivoCancelacion")
     */
    private $reservas;


    /**
     * Constructor
     */
    public function __construct() {
        $this->reservas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set motivo.
     *
     * @param string $motivo
     *
     * @return MotivoCancelacion
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo.
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Add reserva
     *
     * @param \LogicBundle\Entity\Reserva $reserva
     *
     * @return MotivoCancelacion
     */
    public function addReserva(\LogicBundle\Entity\Reserva $reserva)
    {
        $this->reservas[] = $reserva;

        return $this;
    }

    /**
     * Remove reserva
     *
     * @param \LogicBundle\Entity\Reserva $reserva
     */
    public function removeReserva(\LogicBundle\Entity\Reserva $reserva)
    {
        $this->reservas->removeElement($reserva);
    }

    /**
     * Get reservas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservas()
    {
        return $this->reservas;
    }
}
