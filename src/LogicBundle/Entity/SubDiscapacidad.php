<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Accion
 *
 * @ORM\Table(name="sub_discapacidad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubDiscapacidadRepository")
 */
class SubDiscapacidad {

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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Discapacidad", inversedBy="subDiscapacidades")
     * @ORM\JoinColumn(name="discapacidad_id", referencedColumnName="id", nullable=true)
     */
    private $discapacidad;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="subDiscapacidad",cascade={"persist"})
     */
    private $usuarios;

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

    /**
     * Constructor
     */
    public function __construct() {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SubDiscapacidad
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return SubDiscapacidad
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return SubDiscapacidad
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Set discapacidad
     *
     * @param \LogicBundle\Entity\Discapacidad $discapacidad
     *
     * @return SubDiscapacidad
     */
    public function setDiscapacidad(\LogicBundle\Entity\Discapacidad $discapacidad = null) {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    /**
     * Get discapacidad
     *
     * @return \LogicBundle\Entity\Discapacidad
     */
    public function getDiscapacidad() {
        return $this->discapacidad;
    }

    /**
     * Add usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return SubDiscapacidad
     */
    public function addUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     */
    public function removeUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios() {
        return $this->usuarios;
    }

}