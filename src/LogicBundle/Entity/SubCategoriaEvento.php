<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubCategoriaEvento
 *
 * @ORM\Table(name="sub_categoria_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubCategoriaEventoRepository")
 */
class SubCategoriaEvento
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
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\CategoriaSubcategoria", inversedBy="subcategorias", cascade={"persist"})
     * @ORM\JoinTable(name="categoriaSubcategorias_subcategoriasEvento",
     *      inverseJoinColumns={@ORM\JoinColumn(name="categoria_subcategoria_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="subcategoria_evento_id", referencedColumnName="id")}
     *      )
     */
    private $categoriaSubcategorias;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaEvento", inversedBy="subcategorias")
     * @ORM\JoinColumn(name="categoria_evento_id",referencedColumnName="id")
     */
    private $categoria;

    
    /**
     * Constructor
     */
    public function __construct() {
        $this->evento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoriaSubcategorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SubCategoriaEvento
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
     * @return SubCategoriaEvento
     */
    public function addCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria)
    {
        $categoriaSubcategoria->addSubcategoria($this);
        $this->categoriaSubcategorias[] = $categoriaSubcategoria;

        return $this;
    }

    /**
     * Remove categoriaSubcategoria
     *
     * @param \LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria
     */
    public function removeCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria)
    {
        $this->categoriaSubcategorias->removeElement($categoriaSubcategoria);
    }

    /**
     * Get categoriaSubcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriaSubcategorias()
    {
        return $this->categoriaSubcategorias;
    }

    /**
     * Set categoria
     *
     * @param \LogicBundle\Entity\CategoriaEvento $categoria
     *
     * @return SubCategoriaEvento
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
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return SubCategoriaEvento
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento)
    {
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
}
