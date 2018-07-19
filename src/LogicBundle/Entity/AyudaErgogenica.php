<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AyudaErgogenica
 *
 * @ORM\Table(name="ayuda_ergogenica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AyudaErgogenicaRepository")
 */
class AyudaErgogenica
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
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="Ergogenica", mappedBy="ayudaErgogenica")
     */
    private $ergogenicas;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ergogenicas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AyudaErgogenica
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
     * Add ergogenica
     *
     * @param \LogicBundle\Entity\Ergogenica $ergogenica
     *
     * @return AyudaErgogenica
     */
    public function addErgogenica(\LogicBundle\Entity\Ergogenica $ergogenica)
    {
        $this->ergogenicas[] = $ergogenica;

        return $this;
    }

    /**
     * Remove ergogenica
     *
     * @param \LogicBundle\Entity\Ergogenica $ergogenica
     */
    public function removeErgogenica(\LogicBundle\Entity\Ergogenica $ergogenica)
    {
        $this->ergogenicas->removeElement($ergogenica);
    }

    /**
     * Get ergogenicas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getErgogenicas()
    {
        return $this->ergogenicas;
    }
}
