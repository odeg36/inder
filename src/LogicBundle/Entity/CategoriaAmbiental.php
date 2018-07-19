<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaAmbiental
 *
 * @ORM\Table(name="categoria_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaAmbientalRepository")
 */
class CategoriaAmbiental
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
     * @ORM\OneToMany(targetEntity="SubcategoriaAmbiental", cascade={ "persist"}, mappedBy="categoriaAmbiental", orphanRemoval=true)
     */
    private $subcategoriaAmbientales;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaAmbiental", mappedBy="categoriaAmbiental")
     */
    private $escenarioCategoriaAmbientales;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subcategoriaAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioCategoriaAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaAmbiental
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
     * Add subcategoriaAmbientale
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbientale
     *
     * @return CategoriaAmbiental
     */
    public function addSubcategoriaAmbientale(\LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbientale)
    {
        $this->subcategoriaAmbientales[] = $subcategoriaAmbientale;

        return $this;
    }

    /**
     * Remove subcategoriaAmbientale
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbientale
     */
    public function removeSubcategoriaAmbientale(\LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbientale)
    {
        $this->subcategoriaAmbientales->removeElement($subcategoriaAmbientale);
    }

    /**
     * Get subcategoriaAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubcategoriaAmbientales()
    {
        return $this->subcategoriaAmbientales;
    }

    /**
     * Add escenarioCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale
     *
     * @return CategoriaAmbiental
     */
    public function addEscenarioCategoriaAmbientale(\LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale)
    {
        $this->escenarioCategoriaAmbientales[] = $escenarioCategoriaAmbientale;

        return $this;
    }

    /**
     * Remove escenarioCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale
     */
    public function removeEscenarioCategoriaAmbientale(\LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale)
    {
        $this->escenarioCategoriaAmbientales->removeElement($escenarioCategoriaAmbientale);
    }

    /**
     * Get escenarioCategoriaAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaAmbientales()
    {
        return $this->escenarioCategoriaAmbientales;
    }
}
