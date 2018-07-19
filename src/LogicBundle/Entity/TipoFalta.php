<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoFalta
 *
 * @ORM\Table(name="tipofalta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoFaltaRepository")
 */
class TipoFalta
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;


    /**
     * @var integer
     *
     * @ORM\Column(name="puntosJuegolimpio", type="integer", nullable=true)
     */
    private $puntosJuegolimpio;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroJugador", mappedBy="tipoFalta")
     */
    private $faltasEncuentroJugador;

    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Sancion", mappedBy="tipoFalta")
     */
    private $sancion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaTres", mappedBy="tipoFalta")
     */
    private $faltasEncuentroSistemaTres;
  
    
    /**
     * Constructor
    */
    
    public function __construct() {
            $this->faltasEncuentroJugador = new \Doctrine\Common\Collections\ArrayCollection();
            $this->faltasEncuentroSistemaTres = new \Doctrine\Common\Collections\ArrayCollection();
            $this->sancion = new \Doctrine\Common\Collections\ArrayCollection();
         
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
     * @return TipoFalta
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
     * @return TipoFalta
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



    /**
     * Add faltasEncuentroJugador
     *
     * @param \LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador
     *
     * @return TipoFalta
     */
    public function addFaltasEncuentroJugador(\LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador) {
        $this->faltasEncuentroJugador[] = $faltasEncuentroJugador;

        return $this;
    }

    /**
     * Remove faltasEncuentroJugador
     *
     * @param \LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador
     */
    public function removeFaltasEncuentroJugador(\LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador) {
        $this->faltasEncuentroJugador->removeElement($faltasEncuentroJugador);
    }

    /**
     * Get faltasEncuentroJugador
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroJugador() {
        return $this->faltasEncuentroJugador;
    }
    

    /**
     * Add faltasEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres
     *
     * @return TipoFalta
     */
    public function addFaltasEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres) {
        $this->faltasEncuentroSistemaTres[] = $faltasEncuentroSistemaTres;
        return $this;
    }
    
    
    /**
     * Remove faltasEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres
     */
    public function removeFaltasEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres) {
        $this->faltasEncuentroSistemaTres->removeElement($faltasEncuentroSistemaTres);
    }

    /**
     * Get faltasEncuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaTres() {
        return $this->faltasEncuentroSistemaTres;
    }

    /**
     * Add sancion
     *
     * @param \LogicBundle\Entity\Sancion $sancion
     *
     * @return TipoFalta
     */
    public function addSancion(\LogicBundle\Entity\Sancion $sancion) {
        $this->sancion[] = $sancion;

        return $this;
    }

    /**
     * Remove sancion
     *
     * @param \LogicBundle\Entity\Sancion $sancion
     */
    public function removeSancion(\LogicBundle\Entity\Sancion $sancion) {
        $this->sancion->removeElement($sancion);
    }

    /**
     * Get sancion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSancion() {
        return $this->sancion;
    }

    

    /**
     * Add faltasEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre
     *
     * @return TipoFalta
     */
    public function addFaltasEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre)
    {
        $this->faltasEncuentroSistemaTres[] = $faltasEncuentroSistemaTre;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre
     */
    public function removeFaltasEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre)
    {
        $this->faltasEncuentroSistemaTres->removeElement($faltasEncuentroSistemaTre);
    }


    /**
     * Set puntosJuegolimpio
     *
     * @param integer $puntosJuegolimpio
     *
     * @return TipoFalta
     */
    public function setPuntosJuegolimpio($puntosJuegolimpio) {
        $this->puntosJuegolimpio = $puntosJuegolimpio;

        return $this;
    }

    /**
     * Get puntosJuegolimpio
     *
     * @return integer
     */
    public function getPuntosJuegolimpio() {
        return $this->puntosJuegolimpio;
    }
}
