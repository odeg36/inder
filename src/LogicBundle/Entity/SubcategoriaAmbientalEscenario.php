<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubcategoriaAmbientalEscenario
 *
 * @ORM\Table(name="subcategoria_ambiental_escenario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubcategoriaAmbientalEscenarioRepository")
 */
class SubcategoriaAmbientalEscenario
{

    public function __toString() {
        return $this->id ? $this->id : '';
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
     * @var bool
     *
     * @ORM\Column(name="cumple", type="boolean")
     */
    private $cumple;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SubcategoriaAmbiental")
     * @ORM\JoinColumn(name="subcategoria_ambiental_id", referencedColumnName="id", )
     */
    private $subcategoriaAmbiental;

    
    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subcategoriaAmbiental = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cumple
     *
     * @param boolean $cumple
     *
     * @return SubcategoriaAmbientalEscenario
     */
    public function setCumple($cumple)
    {
        $this->cumple = $cumple;

        return $this;
    }

    /**
     * Get cumple
     *
     * @return bool
     */
    public function getCumple()
    {
        return $this->cumple;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return SubcategoriaAmbientalEscenario
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return SubcategoriaAmbientalEscenario
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;
        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
     * Set subcategoriaAmbiental
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbiental
     *
     * @return SubcategoriaAmbientalEscenario
     */
    public function setSubcategoriaAmbiental(\LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbiental = null) {
        $this->subcategoriaAmbiental = $subcategoriaAmbiental;
        return $this;
    }

    /**
     * Get subcategoriaAmbiental
     *
     * @return \LogicBundle\Entity\SubcategoriaAmbiental
     */
    public function getSubcategoriaAmbiental() {
        return $this->subcategoriaAmbiental;
    }

    
}
