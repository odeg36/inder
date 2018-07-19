<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Municipios
 *
 * @ORM\Table(name="municipios")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MunicipiosRepository")
 */
class Municipio {

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
     * @ORM\Column(name="nombremunicipio", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Barrio", mappedBy="municipio");
     */
    private $barrios;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Comuna", mappedBy="municipio");
     */
    private $comunas;

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
     * @return Municipio
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
     * Constructor
     */
    public function __construct() {
        $this->barrios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add barrio.
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return Municipio
     */
    public function addBarrio(\LogicBundle\Entity\Barrio $barrio) {
        $this->barrios[] = $barrio;

        return $this;
    }

    /**
     * Remove barrio.
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBarrio(\LogicBundle\Entity\Barrio $barrio) {
        return $this->barrios->removeElement($barrio);
    }

    /**
     * Get barrios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBarrios() {
        return $this->barrios;
    }

    /**
     * Add comuna.
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return Municipio
     */
    public function addComuna(\LogicBundle\Entity\Comuna $comuna) {
        $this->comunas[] = $comuna;

        return $this;
    }

    /**
     * Remove comuna.
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComuna(\LogicBundle\Entity\Comuna $comuna) {
        return $this->comunas->removeElement($comuna);
    }

    /**
     * Get comunas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComunas() {
        return $this->comunas;
    }

}
