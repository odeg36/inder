<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoEntidad
 *
 * @ORM\Table(name="tipo_entidad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoEntidadRepository")
 */
class TipoEntidad {

    const CPN = "CPN";
    const CED = "CED";
    const END = "END";

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
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=5, nullable=true)
     */
    private $abreviatura;

    /**
     * @ORM\ManyToMany(targetEntity="TipoOrganismo", mappedBy="tipoEntidades")
     */
    private $tipoOrganismos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tipoOrganismos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoEntidad
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
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return TipoEntidad
     */
    public function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura() {
        return $this->abreviatura;
    }

    /**
     * Add tipoOrganismo
     *
     * @param \LogicBundle\Entity\TipoOrganismo $tipoOrganismo
     *
     * @return TipoEntidad
     */
    public function addTipoOrganismo(\LogicBundle\Entity\TipoOrganismo $tipoOrganismo) {
        $this->tipoOrganismos[] = $tipoOrganismo;

        return $this;
    }

    /**
     * Remove tipoOrganismo
     *
     * @param \LogicBundle\Entity\TipoOrganismo $tipoOrganismo
     */
    public function removeTipoOrganismo(\LogicBundle\Entity\TipoOrganismo $tipoOrganismo) {
        $this->tipoOrganismos->removeElement($tipoOrganismo);
    }

    /**
     * Get tipoOrganismos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoOrganismos() {
        return $this->tipoOrganismos;
    }

}
