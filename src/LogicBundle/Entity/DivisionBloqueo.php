<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DivisionBloqueo
 *
 * @ORM\Table(name="division_bloqueo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DivisionBloqueoRepository")
 */
class DivisionBloqueo {
    
    public function __toString() {
        return $this->getDivision() ? $this->getDivision()->getNombre() : "";
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division", inversedBy="bloqueos", cascade={"persist"})
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\BloqueoEscenario", inversedBy="divisionesBLoqueo", cascade={"persist"})
     * @ORM\JoinColumn(name="bloqueo_escenario_id", referencedColumnName="id" )
     */
    private $bloqueos;
    
    function __construct($division, $bloqueos) {
        $this->division = $division;
        $this->bloqueos = $bloqueos;
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
     * @return DivisionBloqueo
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
     * Set bloqueos.
     *
     * @param \LogicBundle\Entity\BloqueoEscenario|null $bloqueos
     *
     * @return DivisionBloqueo
     */
    public function setBloqueos(\LogicBundle\Entity\BloqueoEscenario $bloqueos = null) {
        $this->bloqueos = $bloqueos;

        return $this;
    }

    /**
     * Get bloqueos.
     *
     * @return \LogicBundle\Entity\BloqueoEscenario|null
     */
    public function getBloqueos() {
        return $this->bloqueos;
    }

}
