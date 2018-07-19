<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaNivel
 *
 * @ORM\Table(name="categoria_nivel")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaNivelRepository")
 */
class CategoriaNivel
{
    public function __toString() {
        return $this->getNombre() ? : '';
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
     * @ORM\OneToMany(targetEntity="ValoracionInicial", mappedBy="categoriaNivel")
     */
    private $valoracionesIniciales;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valoracionesIniciales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaNivel
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
     * Add valoracionesIniciale
     *
     * @param \LogicBundle\Entity\ValoracionInicial $valoracionesIniciale
     *
     * @return CategoriaNivel
     */
    public function addValoracionesIniciale(\LogicBundle\Entity\ValoracionInicial $valoracionesIniciale)
    {
        $this->valoracionesIniciales[] = $valoracionesIniciale;

        return $this;
    }

    /**
     * Remove valoracionesIniciale
     *
     * @param \LogicBundle\Entity\ValoracionInicial $valoracionesIniciale
     */
    public function removeValoracionesIniciale(\LogicBundle\Entity\ValoracionInicial $valoracionesIniciale)
    {
        $this->valoracionesIniciales->removeElement($valoracionesIniciale);
    }

    /**
     * Get valoracionesIniciales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValoracionesIniciales()
    {
        return $this->valoracionesIniciales;
    }
}
