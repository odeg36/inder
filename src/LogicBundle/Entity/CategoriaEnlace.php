<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaEnlace
 *
 * @ORM\Table(name="categoria_enlace")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaEnlaceRepository")
 */
class CategoriaEnlace
{

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Enlace", mappedBy="categoriaEnlace")
     */
    private $enlaces;

    /**
     * Constructor
     */
    public function __construct() {
        $this->enlaces = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaEnlace
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
     * Add Enlace
     *
     * @param \LogicBundle\Entity\Enlace $enlace
     *
     * @return Enfoque
     */
    public function addEnlace(\LogicBundle\Entity\Enlace $enlace) {
        $this->enlaces[] = $enlace;

        return $this;
    }

    /**
     * Remove enlace
     *
     * @param \LogicBundle\Entity\Enlace $enlace
     */
    public function removeEnlace(\LogicBundle\Entity\Enlace $enlace) {
        $this->enlaces->removeElement($enlace);
    }

    /**
     * Get enlaces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnlaces() {
        return $this->enlaces;
    }
}
