<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\OpcionCampo as OpcionCampo;


/**
 * Campo
 *
 * @ORM\Table(name="campo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoRepository")
 */
class Campo
{
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
     * @ORM\Column(name="tipo_entrada", type="string", length=255)
     */
    private $tipoEntrada;


    
     /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\OpcionCampo", mappedBy="campo")
     */
    private $opcionesCampo;
     
     
    public function __construct() {
        $this->opcionesCampo = new \Doctrine\Common\Collections\ArrayCollection();

        
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
     * @return Campo
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
     * Set tipoEntrada
     *
     * @param string $tipoEntrada
     *
     * @return Campo
     */
    public function setTipoEntrada($tipoEntrada)
    {
        $this->tipoEntrada = $tipoEntrada;

        return $this;
    }

    /**
     * Get tipoEntrada
     *
     * @return string
     */
    public function getTipoEntrada()
    {
        return $this->tipoEntrada;
    }
     /**
     * Add opcionCampo
     *
     * @param \LogicBundle\Entity\OpcionCampo $opcionCampo
     *
     * @return Campo
     */
    public function addOpcionCampo(\LogicBundle\Entity\OpcionCampo $opcionCampo) {
        $this->opcionesCampo[] = $opcionCampo;

        return $this;
    }

    /**
     * Remove opcionCampo
     *
     * @param \LogicBundle\Entity\OpcionCampo $opcionCampo
     */
    public function removeOpcionCampo(\LogicBundle\Entity\OpcionCampo $opcionCampo) {
        $this->opcionesCampo->removeElement($opcionCampo);
    }

    /**
     * Get opcionesCampo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpcionesCampo() {
        return $this->opcionesCampo;
    }



    /**
     * Add opcionesCampo
     *
     * @param \LogicBundle\Entity\OpcionCampo $opcionesCampo
     *
     * @return Campo
     */
    public function addOpcionesCampo(\LogicBundle\Entity\OpcionCampo $opcionesCampo)
    {
        $this->opcionesCampo[] = $opcionesCampo;

        return $this;
    }

    /**
     * Remove opcionesCampo
     *
     * @param \LogicBundle\Entity\OpcionCampo $opcionesCampo
     */
    public function removeOpcionesCampo(\LogicBundle\Entity\OpcionCampo $opcionesCampo)
    {
        $this->opcionesCampo->removeElement($opcionesCampo);
    }
}
