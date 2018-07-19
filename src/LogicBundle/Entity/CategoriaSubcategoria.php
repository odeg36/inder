<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaSubcategoria
 *
 * @ORM\Table(name="categoria_subcategoria")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaSubcategoriaRepository")
 */
class CategoriaSubcategoria
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaEvento", inversedBy="categoriaSubcategorias")
     * @ORM\JoinColumn(name="categoria_evento_id",referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\SubCategoriaEvento", mappedBy="categoriaSubcategorias")
     */
    private $subcategorias;

    /**
     * @ORM\ManyToMany(targetEntity="Evento", mappedBy="categoriaSubcategorias")
     */
    private $evento;


    public function __construct() {
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evento = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoria
     *
     * @param \LogicBundle\Entity\CategoriaEvento $categoria
     *
     * @return CategoriaSubcategoria
     */
    public function setCategoria(\LogicBundle\Entity\CategoriaEvento $categoria = null) {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \LogicBundle\Entity\CategoriaEvento
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Add subcategoria
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     *
     * @return CategoriaSubcategoria
     */
    public function addSubcategoria(\LogicBundle\Entity\SubCategoriaEvento $subcategoria) {
        $this->subcategorias[] = $subcategoria;

        return $this;
    }

    /**
     * Remove subcategoria
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento $subcategoria
     */
    public function removeSubcategoria(\LogicBundle\Entity\SubCategoriaEvento $subcategoria) {
        $this->subcategorias->removeElement($subcategoria);
    }

    /**
     * Get subcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubcategorias() {
        return $this->subcategorias;
    }

    /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return CategoriaSubcategoria
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;
        return $this;
    }

    /**
     * Remove evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Get evento.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvento()
    {
        return $this->evento;
    }
}
