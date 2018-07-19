<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SistemaJuegoLiga
 *
 * @ORM\Table(name="sistema_juego_liga")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SistemaJuegoLigaRepository")
 */
class SistemaJuegoLiga
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
     * @ORM\Column(name="tipo_sistema", type="string", length=255)
     */
    private $tipoSistema;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_derrota", type="integer")
     */
    private $puntosDerrota;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_empate", type="integer")
     */
    private $puntosEmpate;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_victoria", type="integer")
     */
    private $puntosVictoria;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_para_ganar", type="integer")
     */
    private $puntosParaGanar;

    /**
     * @var string
     *
     * @ORM\Column(name="puntos_w", type="string", length=255)
     */
    private $puntosW;

    /**
     * @var string
     *
     * @ORM\Column(name="puntos_juego_limpio", type="string", length=255)
     */
    private $puntosJuegoLimpio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="sistemaJuegosLiga")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaLiga", mappedBy="sistemaJuegoLiga")
     */
    private $encuentroSistemaLigas;

    /**
     * Constructor
     */
    public function __construct() {
        $this->encuentroSistemaLigas = new \Doctrine\Common\Collections\ArrayCollection();
    
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
     * Set tipoSistema
     *
     * @param string $tipoSistema
     *
     * @return SistemaJuegoLiga
     */
    public function setTipoSistema($tipoSistema)
    {
        $this->tipoSistema = $tipoSistema;

        return $this;
    }

    /**
     * Get tipoSistema
     *
     * @return string
     */
    public function getTipoSistema()
    {
        return $this->tipoSistema;
    }

    /**
     * Set puntosDerrota
     *
     * @param integer $puntosDerrota
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosDerrota($puntosDerrota)
    {
        $this->puntosDerrota = $puntosDerrota;

        return $this;
    }

    /**
     * Get puntosDerrota
     *
     * @return int
     */
    public function getPuntosDerrota()
    {
        return $this->puntosDerrota;
    }

    /**
     * Set puntosEmpate
     *
     * @param integer $puntosEmpate
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosEmpate($puntosEmpate)
    {
        $this->puntosEmpate = $puntosEmpate;

        return $this;
    }

    /**
     * Get puntosEmpate
     *
     * @return int
     */
    public function getPuntosEmpate()
    {
        return $this->puntosEmpate;
    }

    /**
     * Set puntosVictoria
     *
     * @param integer $puntosVictoria
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosVictoria($puntosVictoria)
    {
        $this->puntosVictoria = $puntosVictoria;

        return $this;
    }

    /**
     * Get puntosVictoria
     *
     * @return int
     */
    public function getPuntosVictoria()
    {
        return $this->puntosVictoria;
    }

    /**
     * Set puntosParaGanar
     *
     * @param integer $puntosParaGanar
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosParaGanar($puntosParaGanar)
    {
        $this->puntosParaGanar = $puntosParaGanar;

        return $this;
    }

    /**
     * Get puntosParaGanar
     *
     * @return int
     */
    public function getPuntosParaGanar()
    {
        return $this->puntosParaGanar;
    }

    /**
     * Set puntosW
     *
     * @param string $puntosW
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosW($puntosW)
    {
        $this->puntosW = $puntosW;

        return $this;
    }

    /**
     * Get puntosW
     *
     * @return string
     */
    public function getPuntosW()
    {
        return $this->puntosW;
    }

    /**
     * Set puntosJuegoLimpio
     *
     * @param string $puntosJuegoLimpio
     *
     * @return SistemaJuegoLiga
     */
    public function setPuntosJuegoLimpio($puntosJuegoLimpio)
    {
        $this->puntosJuegoLimpio = $puntosJuegoLimpio;

        return $this;
    }

    /**
     * Get puntosJuegoLimpio
     *
     * @return string
     */
    public function getPuntosJuegoLimpio()
    {
        return $this->puntosJuegoLimpio;
    }

     /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return SistemaJuegoUno
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null) {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento() {
        return $this->evento;
    }

      
    /**
     * Add encuentroSistemaLiga
     *
     * @param \LogicBundle\Entity\EncuentroSistemaLiga $encuentroSistemaLiga
     *
     * @return Evento
     */
    public function addEncuentroSistemaLiga(\LogicBundle\Entity\EncuentroSistemaLiga $encuentroSistemaLiga) {
        $this->encuentroSistemaLigas[] = $encuentroSistemaLiga;

        return $this;
    }

    /**
     * Remove encuentroSistemaLiga
     *
     * @param \LogicBundle\Entity\EncuentroSistemaLiga $encuentroSistemaLiga
     */
    public function removeEncuentroSistemaLiga(\LogicBundle\Entity\EncuentroSistemaLiga $encuentroSistemaLiga) {
        $this->encuentroSistemaLigas->removeElement($encuentroSistemaLiga);
    }

    /**
     * Get encuentroSistemaLigas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaLigas() {
        return $this->encuentroSistemaLigas;
    }
}
