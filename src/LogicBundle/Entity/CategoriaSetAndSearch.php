<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaSetAndSearch
 *
 * @ORM\Table(name="categoria_set_and_search")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaSetAndSearchRepository")
 */
class CategoriaSetAndSearch {

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
     * @ORM\OneToMany(targetEntity="PruebaSetAndSearch", mappedBy="categoriaSetAndSearch")
     */
    private $pruebasSetAndSearch;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebasSetAndSearch = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CategoriaSetAndSearch
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
     * Add pruebasSetAndSearch
     *
     * @param \LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch
     *
     * @return CategoriaSetAndSearch
     */
    public function addPruebasSetAndSearch(\LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch)
    {
        $this->pruebasSetAndSearch[] = $pruebasSetAndSearch;

        return $this;
    }

    /**
     * Remove pruebasSetAndSearch
     *
     * @param \LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch
     */
    public function removePruebasSetAndSearch(\LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch)
    {
        $this->pruebasSetAndSearch->removeElement($pruebasSetAndSearch);
    }

    /**
     * Get pruebasSetAndSearch
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasSetAndSearch()
    {
        return $this->pruebasSetAndSearch;
    }
}
