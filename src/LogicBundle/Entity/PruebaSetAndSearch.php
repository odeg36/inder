<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PruebaSetAndSearch
 *
 * @ORM\Table(name="prueba_set_and_search")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaSetAndSearchRepository")
 */
class PruebaSetAndSearch {

    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @ORM\ManyToOne(targetEntity="SetAndSearch", inversedBy="pruebasSetAndSearch")
     * @ORM\JoinColumn(name="estabilidad_lumbo_pelvica_id", referencedColumnName="id")
     */
    private $setAndSearch;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaSetAndSearch", inversedBy="pruebasSetAndSearch")
     * @ORM\JoinColumn(name="categoria_set_and_search_id", referencedColumnName="id")
     */
    private $categoriaSetAndSearch;

    /**
     * @ORM\OneToMany(targetEntity="PuntuacionSetAndSearch", mappedBy="pruebaSetAndSearch", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $puntuaciones;


    /**
     * Add puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionSetAndSearch $puntuacione
     *
     * @return PruebaSetAndSearch
     */
    public function addPuntuacione(\LogicBundle\Entity\PuntuacionSetAndSearch $puntuacione)
    {
        $puntuacione->setPruebaSetAndSearch($this);
        $this->puntuaciones[] = $puntuacione;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puntuaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set setAndSearch
     *
     * @param \LogicBundle\Entity\SetAndSearch $setAndSearch
     *
     * @return PruebaSetAndSearch
     */
    public function setSetAndSearch(\LogicBundle\Entity\SetAndSearch $setAndSearch = null)
    {
        $this->setAndSearch = $setAndSearch;

        return $this;
    }

    /**
     * Get setAndSearch
     *
     * @return \LogicBundle\Entity\SetAndSearch
     */
    public function getSetAndSearch()
    {
        return $this->setAndSearch;
    }

    /**
     * Set categoriaSetAndSearch
     *
     * @param \LogicBundle\Entity\CategoriaSetAndSearch $categoriaSetAndSearch
     *
     * @return PruebaSetAndSearch
     */
    public function setCategoriaSetAndSearch(\LogicBundle\Entity\CategoriaSetAndSearch $categoriaSetAndSearch = null)
    {
        $this->categoriaSetAndSearch = $categoriaSetAndSearch;

        return $this;
    }

    /**
     * Get categoriaSetAndSearch
     *
     * @return \LogicBundle\Entity\CategoriaSetAndSearch
     */
    public function getCategoriaSetAndSearch()
    {
        return $this->categoriaSetAndSearch;
    }

    /**
     * Remove puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionSetAndSearch $puntuacione
     */
    public function removePuntuacione(\LogicBundle\Entity\PuntuacionSetAndSearch $puntuacione)
    {
        $this->puntuaciones->removeElement($puntuacione);
    }

    /**
     * Get puntuaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuntuaciones()
    {
        return $this->puntuaciones;
    }
}
