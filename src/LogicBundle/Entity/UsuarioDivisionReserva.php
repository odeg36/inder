<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioDivisionReserva
 *
 * @ORM\Table(name="usuario_division_reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\UsuarioDivisionReservaRepository")
 */
class UsuarioDivisionReserva {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\DivisionReserva", inversedBy="divisionReservas", cascade={"persist"})
     * @ORM\JoinColumn(name="division_reserva_id", referencedColumnName="id" )
     */
    private $divisionReserva;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="divisionReservas", cascade={"persist"} )
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;
    private $numeroIdentificacion;
    private $tipoIdentificacion;

    function getNumeroIdentificacion() {
        return $this->numeroIdentificacion;
    }

    function setNumeroIdentificacion($numeroIdentificacion) {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    function getTipoIdentificacion() {
        if($this->getUsuario()){
            return $this->getUsuario()->getTipoIdentificacion()->getId();
        }
        
        return $this->tipoIdentificacion;
    }

    function setTipoIdentificacion($tipoIdentificacion) {
        $this->tipoIdentificacion = $tipoIdentificacion;
    }

    public function __toString() {
        return $this->getUsuario() != null ? $this->getUsuario()->nombreCompleto() : "";
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
     * Set divisionReserva.
     *
     * @param \LogicBundle\Entity\DivisionReserva|null $divisionReserva
     *
     * @return UsuarioDivisionReserva
     */
    public function setDivisionReserva(\LogicBundle\Entity\DivisionReserva $divisionReserva = null) {
        $this->divisionReserva = $divisionReserva;

        return $this;
    }

    /**
     * Get divisionReserva.
     *
     * @return \LogicBundle\Entity\DivisionReserva|null
     */
    public function getDivisionReserva() {
        return $this->divisionReserva;
    }

    /**
     * Set usuario.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $usuario
     *
     * @return UsuarioDivisionReserva
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getUsuario() {
        return $this->usuario;
    }

}
