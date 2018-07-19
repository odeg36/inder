<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaTestControlFisioterapia
 *
 * @ORM\Table(name="categoria_test_control_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaTestControlFisioterapiaRepository")
 */
class CategoriaTestControlFisioterapia {

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
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="PruebaControlFisioterapia", mappedBy="categoriaTestControlFisioterapia")
     */
    private $pruebaControlFisioterapias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebaControlFisioterapias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaTestControlFisioterapia
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
     * Add pruebaControlFisioterapia
     *
     * @param \LogicBundle\Entity\PruebaControlFisioterapia $pruebaControlFisioterapia
     *
     * @return CategoriaTestControlFisioterapia
     */
    public function addPruebaControlFisioterapia(\LogicBundle\Entity\PruebaControlFisioterapia $pruebaControlFisioterapia)
    {
        $this->pruebaControlFisioterapias[] = $pruebaControlFisioterapia;

        return $this;
    }

    /**
     * Remove pruebaControlFisioterapia
     *
     * @param \LogicBundle\Entity\PruebaControlFisioterapia $pruebaControlFisioterapia
     */
    public function removePruebaControlFisioterapia(\LogicBundle\Entity\PruebaControlFisioterapia $pruebaControlFisioterapia)
    {
        $this->pruebaControlFisioterapias->removeElement($pruebaControlFisioterapia);
    }

    /**
     * Get pruebaControlFisioterapias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebaControlFisioterapias()
    {
        return $this->pruebaControlFisioterapias;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return CategoriaTestControlFisioterapia
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
