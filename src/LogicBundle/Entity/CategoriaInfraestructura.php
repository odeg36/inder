<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaInfraestructura
 *
 * @ORM\Table(name="categoria_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaInfraestructuraRepository")
 */
class CategoriaInfraestructura
{
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
     * @ORM\Column(name="importancia_relativa", type="integer", nullable=true)
     */
    private $importanciaRelativa;

    /**
     * @ORM\OneToMany(targetEntity="SubcategoriaInfraestructura",cascade={ "persist"}, mappedBy="categoriaInfraestructura", orphanRemoval=true)
     */
    private $subInfraestructuras;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaInfraestructura", mappedBy="categoriaInfraestructura")
     */
    private $escenarioCategoriaInfraestructura;


    public function __construct() {
        $this->subInfraestructuras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nombre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioCategoriaInfraestructura = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CategoriaInfraestructura
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
     * Get subInfraestructuras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubInfraestructuras() {
        return $this->subInfraestructuras;
    }
        
    /**
     * Add subInfraestructura
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructura
     *
     * @return subInfraestructura
     */
    public function addSubInfraestructura(SubcategoriaInfraestructura $subInfraestructura)
    {   
        $subInfraestructura->setCategoriaInfraestructura($this);
        $this->subInfraestructuras[] = $subInfraestructura;

        return $this;
    }

    /**
     * Remove subInfraestructura
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructura
     */
    public function removeSubInfraestructura(SubcategoriaInfraestructura $subInfraestructura)
    {
        $this->subInfraestructuras->removeElement($subInfraestructura);
    }


    /**
     * Add escenarioCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura
     *
     * @return EscenarioCategoriaInfraestructura
     */
    public function addEscenarioCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura) {
        $this->escenarioCategoriaInfraestructura[] = $escenarioCategoriaInfraestructura;

        return $this;
    }

    /**
     * Remove EscenarioCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura
     */
    public function removeEscenarioCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura) {
        $this->escenarioCategoriaInfraestructura->removeElement($escenarioCategoriaInfraestructura);
    }

    /**
     * Get escenarioCategoriaInfraestructura
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaInfraestructura() {
        return $this->escenarioCategoriaInfraestructura;
    }

    /**
     * Set importanciaRelativa
     *
     * @param integer $importanciaRelativa
     *
     * @return CategoriaInfraestructura
     */
    public function setImportanciaRelativa($importanciaRelativa)
    {
        $this->importanciaRelativa = $importanciaRelativa;

        return $this;
    }

    /**
     * Get importanciaRelativa
     *
     * @return integer
     */
    public function getImportanciaRelativa()
    {
        return $this->importanciaRelativa;
    }
}
