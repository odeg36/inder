<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OfertaDivision
 *
 * @ORM\Table(name="oferta_division")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OfertaDivisionRepository")
 */
class OfertaDivision {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division", inversedBy="divisiones", cascade={"persist"})
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Oferta", inversedBy="divisiones", cascade={"persist"})
     * @ORM\JoinColumn(name="oferta_id", referencedColumnName="id", )
     */
    private $oferta;

    public function __toString() {
        return  "";
    }

    function __construct($division, $oferta) {
        $this->division = $division;
        $this->oferta = $oferta;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set division.
     *
     * @param \LogicBundle\Entity\Division|null $division
     *
     * @return OfertaDivision
     */
    public function setDivision(\LogicBundle\Entity\Division $division = null) {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division.
     *
     * @return \LogicBundle\Entity\Division|null
     */
    public function getDivision() {
        return $this->division;
    }

    /**
     * Set oferta.
     *
     * @param \LogicBundle\Entity\Oferta|null $oferta
     *
     * @return OfertaDivision
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null) {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta.
     *
     * @return \LogicBundle\Entity\Oferta|null
     */
    public function getOferta() {
        return $this->oferta;
    }

}
