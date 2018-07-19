<?php

namespace LogicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Repository\PosturaRepository;

/**
 * Postura
 *
 * @ORM\Table(name="postura")
 * @ORM\Entity(repositoryClass="PosturaRepository")
 */
class Postura {

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
     * @ORM\OneToMany(targetEntity="PruebaPostura", mappedBy="postura", cascade={"persist"}, orphanRemoval=true)
     */
    private $pruebaPosturas;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="postura")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;

    /**
     * Add pruebaPostura
     *
     * @param \LogicBundle\Entity\PruebaPostura $pruebaPostura
     *
     * @return Postura
     */
    public function addPruebaPostura(\LogicBundle\Entity\PruebaPostura $pruebaPostura) {
        $pruebaPostura->setPostura($this);
        $this->pruebaPosturas[] = $pruebaPostura;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////
    /**
     * Constructor
     */
    public function __construct() {
        $this->pruebaPosturas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Remove pruebaPostura
     *
     * @param \LogicBundle\Entity\PruebaPostura $pruebaPostura
     */
    public function removePruebaPostura(\LogicBundle\Entity\PruebaPostura $pruebaPostura) {
        $this->pruebaPosturas->removeElement($pruebaPostura);
    }

    /**
     * Get pruebaPosturas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebaPosturas() {
        return $this->pruebaPosturas;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return Postura
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
