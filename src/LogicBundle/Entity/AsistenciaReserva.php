<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * AsistenciaReserva
 *
 * @ORM\Table(name="asistencia_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AsistenciaReservaRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class AsistenciaReserva
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     * @Serializer\Expose
     */
    private $fecha;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="asistio", type="boolean", nullable=true)
     * @Serializer\Expose
     */
    private $asistio = false;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="asistenciaReservas")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * @Serializer\Expose
     */
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="Reserva", inversedBy="asistenciaReservas")
     * @ORM\JoinColumn(name="reserva_id", referencedColumnName="id")
     */
    private $reserva;
    
    /**
     * @ORM\ManyToOne(targetEntity="DiaReserva", inversedBy="asistenciaReservas")
     * @ORM\JoinColumn(name="dia_reserva_id", referencedColumnName="id")
     */
    private $diaReserva;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AsistenciaReserva
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return AsistenciaReserva
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set reserva
     *
     * @param \LogicBundle\Entity\Reserva $reserva
     *
     * @return AsistenciaReserva
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
     * Set diaReserva
     *
     * @param \LogicBundle\Entity\DiaReserva $diaReserva
     *
     * @return AsistenciaReserva
     */
    public function setDiaReserva(\LogicBundle\Entity\DiaReserva $diaReserva = null)
    {
        $this->diaReserva = $diaReserva;

        return $this;
    }

    /**
     * Get diaReserva
     *
     * @return \LogicBundle\Entity\DiaReserva
     */
    public function getDiaReserva()
    {
        return $this->diaReserva;
    }

    /**
     * Set asistio
     *
     * @param boolean $asistio
     *
     * @return AsistenciaReserva
     */
    public function setAsistio($asistio)
    {
        $this->asistio = $asistio;

        return $this;
    }

    /**
     * Get asistio
     *
     * @return boolean
     */
    public function getAsistio()
    {
        return $this->asistio;
    }
}
