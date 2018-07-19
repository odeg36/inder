<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaEvento
 *
 * @ORM\Table(name="categoria_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaEventoRepository")
 */
class CategoriaEvento
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\CategoriaSubcategoria", mappedBy="categoria", cascade={ "persist"})
     */
    private $categoriaSubcategorias;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SubCategoriaEvento", mappedBy="categoria", cascade={ "persist"})
     */
    private $subcategorias;

    
    public function __construct() {
        $this->categoriaSubcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaEvento
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
     * Add categoriaSubcategoria
     *
     * @param \LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria
     *
     * @return CategoriaEvento
     */
    public function addCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria) {
        $this->categoriaSubcategorias[] = $categoriaSubcategoria;
        return $this;
    }

    /**
     * Remove categoriaSubcategoria
     *
     * @param \LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria
     */
    public function removeCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria) {
        $this->categoriaSubcategorias->removeElement($categoriaSubcategoria);
    }

    /**
     * Get categoriaSubcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriaSubcategorias() {
        return $this->categoriaSubcategorias;
    }   

    /**
     * Add subcategoria
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     *
     * @return CategoriaEvento
     */
    public function addSubCategoriaEvento(\LogicBundle\Entity\SubCategoriaEvento $subcategoria) {
        $this->subcategorias[] = $subcategoria;
        return $this;
    }

    /**
     * Remove subcategoria
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     */
    public function removeSubCategoriaEvento(\LogicBundle\Entity\SubCategoriaEvento $subcategoria) {
        $this->subcategorias->removeElement($subcategoria);
    }

    /**
     * Get subcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategoriaEvento() {
        return $this->subcategorias;
    }  

    /**
     * Add subcategoria.
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     *
     * @return CategoriaEvento
     */
    public function addSubcategoria(\LogicBundle\Entity\SubCategoriaEvento $subcategoria)
    {
        $this->subcategorias[] = $subcategoria;

        return $this;
    }

    /**
     * Remove subcategoria.
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubcategoria(\LogicBundle\Entity\SubCategoriaEvento $subcategoria)
    {
        return $this->subcategorias->removeElement($subcategoria);
    }

    /**
     * Get subcategorias.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubcategorias()
    {
        return $this->subcategorias;
    }
}
