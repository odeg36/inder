<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="puntuacion_set_and_search")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PuntuacionSetAndSearchRepository")
 */
class PuntuacionSetAndSearch {
    
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
     * @var float
     *
     * @ORM\Column(name="valor", type="float", nullable=true)
     */
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="PruebaSetAndSearch", inversedBy="puntuaciones")
     * @ORM\JoinColumn(name="prueba_set_and_search_id", referencedColumnName="id")
     */
    private $pruebaSetAndSearch;


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
     * Set valor
     *
     * @param float $valor
     *
     * @return PuntuacionSetAndSearch
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set pruebaSetAndSearch
     *
     * @param \LogicBundle\Entity\PruebaSetAndSearch $pruebaSetAndSearch
     *
     * @return PuntuacionSetAndSearch
     */
    public function setPruebaSetAndSearch(\LogicBundle\Entity\PruebaSetAndSearch $pruebaSetAndSearch = null)
    {
        $this->pruebaSetAndSearch = $pruebaSetAndSearch;

        return $this;
    }

    /**
     * Get pruebaSetAndSearch
     *
     * @return \LogicBundle\Entity\PruebaSetAndSearch
     */
    public function getPruebaSetAndSearch()
    {
        return $this->pruebaSetAndSearch;
    }
}
