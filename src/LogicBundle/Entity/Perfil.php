<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilesOrganigrama
 *
 * @ORM\Table(name="perfil")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PerfilRepository")
 */
class Perfil {

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
     * @ORM\OneToMany(targetEntity="PerfilOrganismo", mappedBy="perfil")
     */
    protected $perfilOrganismos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->perfilOrganismos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Perfil
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
     * Add perfilOrganismo
     *
     * @param \LogicBundle\Entity\PerfilOrganismo $perfilOrganismo
     *
     * @return Perfil
     */
    public function addPerfilOrganismo(\LogicBundle\Entity\PerfilOrganismo $perfilOrganismo) {
        $this->perfilOrganismos[] = $perfilOrganismo;

        return $this;
    }

    /**
     * Remove perfilOrganismo
     *
     * @param \LogicBundle\Entity\PerfilOrganismo $perfilOrganismo
     */
    public function removePerfilOrganismo(\LogicBundle\Entity\PerfilOrganismo $perfilOrganismo) {
        $this->perfilOrganismos->removeElement($perfilOrganismo);
    }

    /**
     * Get perfilOrganismos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfilOrganismos() {
        return $this->perfilOrganismos;
    }

}
