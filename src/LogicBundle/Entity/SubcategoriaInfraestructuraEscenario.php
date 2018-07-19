<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubcategoriaInfraestructuraEscenario
 *
 * @ORM\Table(name="subcategoria_infraestructura_escenario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubcategoriaInfraestructuraEscenarioRepository")
 */
class SubcategoriaInfraestructuraEscenario
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
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=255)
     */
    private $area;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var int
     *
     * @ORM\Column(name="calificacion", type="integer")
     */
    private $calificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="importanciaRelativa", type="string", length=255)
     */
    private $importanciaRelativa;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SubcategoriaInfraestructura")
     * @ORM\JoinColumn(name="subcategoria_infraestructura_id", referencedColumnName="id", )
     */
    private $subcategoriaInfraestructura;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subcategoriaInfraestructura = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set area
     *
     * @param string $area
     *
     * @return SubcategoriaInfraestructuraEscenario
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return SubcategoriaInfraestructuraEscenario
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set calificacion
     *
     * @param integer $calificacion
     *
     * @return SubcategoriaInfraestructuraEscenario
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return int
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Set importanciaRelativa
     *
     * @param string $importanciaRelativa
     *
     * @return SubcategoriaInfraestructuraEscenario
     */
    public function setImportanciaRelativa($importanciaRelativa)
    {
        $this->importanciaRelativa = $importanciaRelativa;

        return $this;
    }

    /**
     * Get importanciaRelativa
     *
     * @return string
     */
    public function getImportanciaRelativa()
    {
        return $this->importanciaRelativa;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return SubcategoriaInfraestructuraEscenario
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
     * @return SubcategoriaInfraestructuraEscenario
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
     * Set subcategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subcategoriaInfraestructura
     *
     * @return SubcategoriaInfraestructuraEscenario
     */
    public function setSubcategoriaInfraestructura(\LogicBundle\Entity\SubcategoriaInfraestructura $subcategoriaInfraestructura = null) {
        $this->subcategoriaInfraestructura = $subcategoriaInfraestructura;
        return $this;
    }

    /**
     * Get subcategoriaInfraestructura
     *
     * @return \LogicBundle\Entity\SubcategoriaInfraestructura
     */
    public function getSubcategoriaInfraestructura() {
        return $this->subcategoriaInfraestructura;
    }
 
}
