<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaSentadillaArranque
 *
 * @ORM\Table(name="Categoria_sentadilla_arranque")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaSentadillaArranqueRepository")
 */
class CategoriaSentadillaArranque {

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
     * @ORM\OneToMany(targetEntity="PruebaSentadillaArranque", mappedBy="categoriaSentadillaArranque")
     */
    private $pruebasSentadillaArranque;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebasSentadillaArranque = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaSentadillaArranque
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
     * Add pruebasSentadillaArranque
     *
     * @param \LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque
     *
     * @return CategoriaSentadillaArranque
     */
    public function addPruebasSentadillaArranque(\LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque)
    {
        $this->pruebasSentadillaArranque[] = $pruebasSentadillaArranque;

        return $this;
    }

    /**
     * Remove pruebasSentadillaArranque
     *
     * @param \LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque
     */
    public function removePruebasSentadillaArranque(\LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque)
    {
        $this->pruebasSentadillaArranque->removeElement($pruebasSentadillaArranque);
    }

    /**
     * Get pruebasSentadillaArranque
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasSentadillaArranque()
    {
        return $this->pruebasSentadillaArranque;
    }
}
