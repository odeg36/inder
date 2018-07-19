<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuentroSistemaLiga
 *
 * @ORM\Table(name="encuentro_sistema_liga")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuentroSistemaLigaRepository")
 */
class EncuentroSistemaLiga
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicial", type="date")
     */
    private $fechaInicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_final", type="date")
     */
    private $fechaFinal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time")
     */
    private $hora;

     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SistemaJuegoLiga", inversedBy="encuentroSistemaLigas")
     * @ORM\JoinColumn(name="sistema_juego_liga_id",referencedColumnName="id")
     */
    private $sistemaJuegoLiga;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadoEncuentroSistemaLiga", mappedBy="encuentroSistemaLiga")
     */
    private $resultadoEncuentroSistemaLigas;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->resultadoEncuentroSistemaLigas = new \Doctrine\Common\Collections\ArrayCollection();
    
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
     * Set fechaInicial
     *
     * @param \DateTime $fechaInicial
     *
     * @return EncuentroSistemaLiga
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * Get fechaInicial
     *
     * @return \DateTime
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     *
     * @return EncuentroSistemaLiga
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return EncuentroSistemaLiga
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }


     /**
     * Set sistemaJuegoLiga
     *
     * @param \LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga
     *
     * @return EncuentroSistemaLiga
     */
    public function setSistemaJuegoLiga(\LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga = null) {
        $this->sistemaJuegoLiga = $sistemaJuegoLiga;

        return $this;
    }

    /**
     * Get sistemaJuegoLiga
     *
     * @return \LogicBundle\Entity\SistemaJuegoLiga
     */
    public function getSistemaJuegoLiga() {
        return $this->sistemaJuegoLiga;
    }

      
    /**
     * Add resultadoEncuentroSistemaLiga
     *
     * @param \LogicBundle\Entity\ResultadoEncuentroSistemaLiga $resultadoEncuentroSistemaLiga
     *
     * @return EncuentroSistemaLiga
     */
    public function addResultadoEncuentroSistemaLiga(\LogicBundle\Entity\ResultadoEncuentroSistemaLiga $resultadoEncuentroSistemaLiga) {
        $this->resultadoEncuentroSistemaLigas[] = $resultadoEncuentroSistemaLiga;

        return $this;
    }

    /**
     * Remove resultadoEncuentroSistemaLiga
     *
     * @param \LogicBundle\Entity\ResultadoEncuentroSistemaLiga $resultadoEncuentroSistemaLiga
     */
    public function removeResultadoEncuentroSistemaLiga(\LogicBundle\Entity\ResultadoEncuentroSistemaLiga $resultadoEncuentroSistemaLiga) {
        $this->resultadoEncuentroSistemaLigas->removeElement($resultadoEncuentroSistemaLiga);
    }

    /**
     * Get resultadoEncuentroSistemaLigas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadoEncuentroSistemaLigas() {
        return $this->resultadoEncuentroSistemaLigas;
    }

}
