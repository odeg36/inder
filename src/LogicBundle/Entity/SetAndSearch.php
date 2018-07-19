<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SetAndSearch
 *
 * @ORM\Table(name="set_and_search")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SetAndSearchRepository")
 */
class SetAndSearch {

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
     * @ORM\OneToMany(targetEntity="PruebaSetAndSearch", mappedBy="setAndSearch", cascade={"persist"}, orphanRemoval=true)
     */
    private $pruebasSetAndSearch;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="setAndSearch")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;

    /**
     * Add pruebasSetAndSearch
     *
     * @param \LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch
     *
     * @return SetAndSearch
     */
    public function addPruebasSetAndSearch(\LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch) {
        $pruebasSetAndSearch->setSetAndSearch($this);
        $this->pruebasSetAndSearch[] = $pruebasSetAndSearch;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Constructor
     */
    public function __construct() {
        $this->pruebasSetAndSearch = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Remove pruebasSetAndSearch
     *
     * @param \LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch
     */
    public function removePruebasSetAndSearch(\LogicBundle\Entity\PruebaSetAndSearch $pruebasSetAndSearch) {
        $this->pruebasSetAndSearch->removeElement($pruebasSetAndSearch);
    }

    /**
     * Get pruebasSetAndSearch
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasSetAndSearch() {
        return $this->pruebasSetAndSearch;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return SetAndSearch
     */
    public function setConsultaFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia = null) {
        $this->consultaFisioterapia = $consultaFisioterapia;

        return $this;
    }

    /**
     * Get consultaFisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getConsultaFisioterapia() {
        return $this->consultaFisioterapia;
    }

}
