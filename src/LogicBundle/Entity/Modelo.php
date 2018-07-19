<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo
 *
 * @ORM\Table(name="modelo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ModeloRepository")
 */
class Modelo
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
     * @ORM\Column(name="nombre", type="string", length=191)
     */
    private $nombre;


    //////// relacion con componente

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Componente", mappedBy="modelo");
     */
    private $componente;

    
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
     * @return Modelo
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
     * Constructor
     */
    public function __construct() {
        $this->componente = new \Doctrine\Common\Collections\ArrayCollection();
    }

    ////////relacion con componente


     /**
     * Add componente
     *
     * @param \LogicBundle\Entity\Componente $componente
     *
     * @return Modelo
     */
    public function addComponente(\LogicBundle\Entity\Componente $componente) {
        $this->componente[] = $componente;

        return $this;
    }

    /**
     * Remove componente
     *
     * @param \LogicBundle\Entity\Componente $componente
     */
    public function removeComponente(\LogicBundle\Entity\Componente $componente) {
        $this->componente->removeElement($componente);
    }

    /**
     * Get componente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponente() {
        return $this->componente;
    }


}
