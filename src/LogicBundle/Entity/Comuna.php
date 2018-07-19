<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comuna
 *
 * @ORM\Table(name="comuna")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ComunaRepository")
 */
class Comuna {

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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Municipio", inversedBy="comunas")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", nullable=true)
     */
    private $municipio;
    
    ////////// relacion con kitTerritorial
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\KitTerritorial", mappedBy="comuna");
     */
    private $kitTerritorial;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Encuesta", mappedBy="comuna");
     */
    private $encuestas;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Barrio", mappedBy="comuna");
     */
    private $barrios;

    /**
     * Many Banner have Many Comuna.
     * @ORM\ManyToMany(targetEntity="Banner", mappedBy="comunas")
     */
    private $banners;

    /**
     * Constructor
     */
    public function __construct() {
        $this->kitTerritorial = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuestas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->banners = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $nombre
     *
     * @return Comuna
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

    //////************* relacion con kitTerritorial *******//////////

    /**
     * Add kitTerritorial
     *
     * @param \LogicBundle\Entity\KitTerritorial $kitTerritorial
     *
     * @return Comuna
     */
    public function addKitTerritorial(\LogicBundle\Entity\KitTerritorial $kitTerritorial) {
        $this->kitTerritorial[] = $kitTerritorial;

        return $this;
    }

    /**
     * Remove kitTerritorial
     *
     * @param \LogicBundle\Entity\KitTerritorial $kitTerritorial
     */
    public function removeKitTerritorial(\LogicBundle\Entity\KitTerritorial $kitTerritorial) {
        $this->kitTerritorial->removeElement($kitTerritorial);
    }

    /**
     * Get kitTerritorial
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKitTerritorial() {
        return $this->kitTerritorial;
    }

    /**
     * Add encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $encuesta
     *
     * @return Camouna
     */
    public function addEncuesta(\LogicBundle\Entity\Encuesta $encuesta) {
        $this->encuestas[] = $encuesta;

        return $this;
    }

    /**
     * Remove encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $Encuesta
     */
    public function removeEncuesta(\LogicBundle\Entity\Encuesta $encuesta) {
        $this->encuestas->removeElement($encuesta);
    }

    /**
     * Get encuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestas() {
        return $this->encuestas;
    }

    /**
     * Add Banner
     *
     * @param \LogicBundle\Entity\Banner $banner
     *
     * @return Comuna
     */
    public function addBanner(\LogicBundle\Entity\Banner $banner) {
        $this->banners[] = $banner;

        return $this;
    }

    /**
     * Remove Banner
     *
     * @param \LogicBundle\Entity\Banner $banner
     */
    public function removeBanner(\LogicBundle\Entity\Banner $banner) {
        $this->banners->removeElement($banner);
    }

    /**
     * Get Banners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBanners() {
        return $this->banners;
    }

    /**
     * Add barrio.
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return Comuna
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
     * Set municipio.
     *
     * @param \LogicBundle\Entity\Municipio|null $municipio
     *
     * @return Comuna
     */
    public function setMunicipio(\LogicBundle\Entity\Municipio $municipio = null) {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio.
     *
     * @return \LogicBundle\Entity\Municipio|null
     */
    public function getMunicipio() {
        return $this->municipio;
    }

}
