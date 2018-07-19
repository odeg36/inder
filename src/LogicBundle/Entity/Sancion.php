<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sancion
 *
 * @ORM\Table(name="sancion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SancionRepository")
 */
class Sancion
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
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;


    ////relacion con sancionEvento 
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SancionEvento", mappedBy="sancion")
     */
    private $sancionEvento;
    
    
     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoFalta", inversedBy="sancion")
     * @ORM\JoinColumn(name="tipoFalta_id",referencedColumnName="id")
     */
    private $tipoFalta;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sancionEvento = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Sanciones
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Sanciones
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }



    ////// sancionEvento

    /**
     * Add sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return Sancion
     */
    public function addSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento)
    {
        $this->sancionEvento[] = $sancionEvento;

        return $this;
    }

    /**
     * Remove sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     */
    public function removeSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento)
    {
        $this->sancionEvento->removeElement($sancionEvento);
    }

    /**
     * Get sancionEvento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSancionEvento()
    {
        return $this->sancionEvento;
    }
    
    
    
    /**
     * Set tipoFalta
     *
     * @param \LogicBundle\Entity\TipoFalta $tipoFalta
     *
     * @return Sancion
     */
    public function setTipoFalta(\LogicBundle\Entity\TipoFalta $tipoFalta = null) {
        $this->tipoFalta = $tipoFalta;

        return $this;
    }

    /**
     * Get tipoFalta
     *
     * @return \LogicBundle\Entity\TipoFalta
     */
    public function getTipoFalta() {
        return $this->tipoFalta;
    }

    
}
