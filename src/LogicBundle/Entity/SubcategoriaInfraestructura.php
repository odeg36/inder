<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\CampoInfraestructura;

/**
 * SubcategoriaInfraestructura
 *
 * @ORM\Table(name="subcategoria_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubcategoriaInfraestructuraRepository")
 */
class SubcategoriaInfraestructura
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaInfraestructura",cascade={ "persist"}, inversedBy="subInfraestructuras")
     * @ORM\JoinColumn(name="categoria_infraestructura_id", referencedColumnName="id", )
     */
    private $categoriaInfraestructura;
 
    /**
     * Many subcategorias have Many campoInfraestructuras.
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\CampoInfraestructura",cascade={ "persist"}, inversedBy="subInfraestructuraCampos")
     * @ORM\JoinTable(name="subcategoria_infraestructura_campos")
     */
    private $campoInfraestructuras;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaSubCategoriaInfraestructura", mappedBy="subcategoriaInfraestructura")
     */
    private $escenarioCategoriaSubcategorias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campoInfraestructuras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioCategoriaSubcategorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SubcategoriaInfraestructura
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
     * Set categoriaInfraestructura
     *
     * @param \LogicBundle\Entity\CategoriaInfraestructura $categoriaInfraestructura
     *
     * @return SubcategoriaInfraestructura
     */
    public function setCategoriaInfraestructura(\LogicBundle\Entity\CategoriaInfraestructura $categoriaInfraestructura = null)
    {
        $this->categoriaInfraestructura = $categoriaInfraestructura;

        return $this;
    }

    /**
     * Get categoriaInfraestructura
     *
     * @return \LogicBundle\Entity\CategoriaInfraestructura
     */
    public function getCategoriaInfraestructura()
    {
        return $this->categoriaInfraestructura;
    }

    /**
     * Add campoInfraestructura
     *
     * @param \LogicBundle\Entity\CampoInfraestructura $campoInfraestructura
     *
     * @return SubcategoriaInfraestructura
     */
    public function addCampoInfraestructura(\LogicBundle\Entity\CampoInfraestructura $campoInfraestructura)
    {
        $this->campoInfraestructuras[] = $campoInfraestructura;

        return $this;
    }

    /**
     * Remove campoInfraestructura
     *
     * @param \LogicBundle\Entity\CampoInfraestructura $campoInfraestructura
     */
    public function removeCampoInfraestructura(\LogicBundle\Entity\CampoInfraestructura $campoInfraestructura)
    {
        $this->campoInfraestructuras->removeElement($campoInfraestructura);
    }

    /**
     * Get campoInfraestructuras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoInfraestructuras()
    {
        return $this->campoInfraestructuras;
    }

    /**
     * Add escenarioCategoriaSubcategoria
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubcategoria
     *
     * @return SubcategoriaInfraestructura
     */
    public function addEscenarioCategoriaSubcategoria(\LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubcategoria)
    {
        $this->escenarioCategoriaSubcategorias[] = $escenarioCategoriaSubcategoria;

        return $this;
    }

    /**
     * Remove escenarioCategoriaSubcategoria
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubcategoria
     */
    public function removeEscenarioCategoriaSubcategoria(\LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubcategoria)
    {
        $this->escenarioCategoriaSubcategorias->removeElement($escenarioCategoriaSubcategoria);
    }

    /**
     * Get escenarioCategoriaSubcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaSubcategorias()
    {
        return $this->escenarioCategoriaSubcategorias;
    }
}
