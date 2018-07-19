<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SistemaJuegoUno
 *
 * @ORM\Table(name="sistema_juego_uno")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SistemaJuegoUnoRepository")
 */
class SistemaJuegoUno
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="sistemaJuegosUno")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaUno", mappedBy="sistemaJuegoUno")
     */
    private $encuentrosSistemaUno;

     /**
     * Constructor
     */
    public function __construct() {
        $this->encuentrosSistemaUno = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SistemaJuego
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
     * @return SistemaJuego
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
     * @return SistemaJuego
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
     * @return SistemaJuego
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
     * @return SistemaJuego
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
     * Add encuentroSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaJuegoUno
     *
     * @return SistemaJuegoUno
     */
    public function addEncuentroSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno) {
        $this->encuentrosSistemaUno[] = $encuentroSistemaUno;

        return $this;
    }

    /**
     * Remove EncuentroSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $sistemaJuego
     */
    public function removeEncuentroSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno) {
        $this->encuentrosSistemaUno->removeElement($encuentroSistemaUno);
    }

    /**
     * Get sistemaJuegosUno
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentrosSistemaUno() {
        return $this->encuentrosSistemaUno;
    }

    /**
     * Add encuentrosSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentrosSistemaUno
     *
     * @return SistemaJuegoUno
     */
    public function addEncuentrosSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentrosSistemaUno)
    {
        $this->encuentrosSistemaUno[] = $encuentrosSistemaUno;

        return $this;
    }

    /**
     * Remove encuentrosSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentrosSistemaUno
     */
    public function removeEncuentrosSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentrosSistemaUno)
    {
        $this->encuentrosSistemaUno->removeElement($encuentrosSistemaUno);
    }
}
