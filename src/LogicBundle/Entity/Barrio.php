<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Barrios
 *
 * @ORM\Table(name="barrios")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\BarriosRepository")
 */
class Barrio {

    public function __toString() {
        if ($this->getNombre() && $this->getComuna()) {
            return $this->getNombre() . ' - ' . $this->getComuna();
        } else if ($this->getNombre() && $this->getMunicipio()) {
            return $this->getNombre();
        }

        return $this->getNombre() ?: '';
    }

    public function getNombreConComuna() {
        return $this->comuna ? $this->nombre . ' , ' . $this->comuna : $this->nombre;
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Municipio", inversedBy="barrios")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", nullable=true)
     */
    private $municipio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Comuna", inversedBy="barrios")
     * @ORM\JoinColumn(name="comuna_id", referencedColumnName="id", nullable=true)
     */
    private $comuna;

    /**
     * @var string
     *
     * @ORM\Column(name="nombrebarrio", type="string", length=255)
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="habilitado", type="boolean")
     */
    private $habilitado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="es_vereda", type="boolean", options={"default" : false})
     */
    private $esVereda;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PuntoAtencion", mappedBy="barrio");
     */
    private $puntoAtencion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Habitacional", mappedBy="barrio");
     */
    private $habitaciones;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="barrio");
     */
    private $usuarios;

    /**
     * Constructor
     */
    public function __construct() {
        $this->puntoAtencion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->habitaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Barrio
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
     * Set habilitado
     *
     * @param boolean $habilitado
     *
     * @return Barrio
     */
    public function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return boolean
     */
    public function getHabilitado() {
        return $this->habilitado;
    }

    /**
     * Set municipio
     *
     * @param \LogicBundle\Entity\Municipio $municipio
     *
     * @return Barrio
     */
    public function setMunicipio(\LogicBundle\Entity\Municipio $municipio = null) {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \LogicBundle\Entity\Municipio
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     * Set comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return Barrio
     */
    public function setComuna(\LogicBundle\Entity\Comuna $comuna = null) {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return \LogicBundle\Entity\Comuna
     */
    public function getComuna() {
        return $this->comuna;
    }

    /**
     * Add puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return Barrio
     */
    public function addPuntoAtencion(\LogicBundle\Entity\PuntoAtencion $puntoAtencion) {
        $this->puntoAtencion[] = $puntoAtencion;

        return $this;
    }

    /**
     * Remove puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     */
    public function removePuntoAtencion(\LogicBundle\Entity\PuntoAtencion $puntoAtencion) {
        $this->puntoAtencion->removeElement($puntoAtencion);
    }

    /**
     * Get puntoAtencion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuntoAtencion() {
        return $this->puntoAtencion;
    }

    /**
     * Add habitacione
     *
     * @param \LogicBundle\Entity\Habitacional $habitacione
     *
     * @return Barrio
     */
    public function addHabitacione(\LogicBundle\Entity\Habitacional $habitacione) {
        $this->habitaciones[] = $habitacione;

        return $this;
    }

    /**
     * Remove habitacione
     *
     * @param \LogicBundle\Entity\Habitacional $habitacione
     */
    public function removeHabitacione(\LogicBundle\Entity\Habitacional $habitacione) {
        $this->habitaciones->removeElement($habitacione);
    }

    /**
     * Get habitaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHabitaciones() {
        return $this->habitaciones;
    }

    /**
     * Add usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Barrio
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


    /**
     * Set esVereda.
     *
     * @param bool $esVereda
     *
     * @return Barrio
     */
    public function setEsVereda($esVereda)
    {
        $this->esVereda = $esVereda;

        return $this;
    }

    /**
     * Get esVereda.
     *
     * @return bool
     */
    public function getEsVereda()
    {
        return $this->esVereda;
    }
}
