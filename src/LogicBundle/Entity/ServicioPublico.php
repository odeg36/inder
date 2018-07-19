<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServicioPublico
 *
 * @ORM\Table(name="servicio_publico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ServicioPublicoRepository")
 */
class ServicioPublico {

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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_servicio", type="boolean", nullable=true)
     */
    private $tieneServicio;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="serviciosPublicos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return ServicioPublico
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
     * Set tieneServicio
     *
     * @param boolean $tieneServicio
     *
     * @return ServicioPublico
     */
    public function setTieneServicio($tieneServicio)
    {
        $this->tieneServicio = $tieneServicio;

        return $this;
    }

    /**
     * Get tieneServicio
     *
     * @return boolean
     */
    public function getTieneServicio()
    {
        return $this->tieneServicio;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return ServicioPublico
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
