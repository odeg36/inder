<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoRoles
 *
 * @ORM\Table(name="evento_rol")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EventoRolRepository")
 */
class EventoRol
{

    public function __toString() {
        return $this->rol ? $this->rol : '';    
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
     * @ORM\Column(name="rol", type="string", length=255)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="eventoRoles", cascade={"persist"})
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="eventoRoles")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", )
     */
    private $usuario;

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
     * Set rol
     *
     * @param string $rol
     *
     * @return EventoRoles
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return EventoRol
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null) {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento() {
        return $this->evento;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return EventoRol
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
}
