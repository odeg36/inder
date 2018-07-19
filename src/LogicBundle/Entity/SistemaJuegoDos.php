<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SistemaJuegoDos
 *
 * @ORM\Table(name="sistema_juego_dos")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SistemaJuegoDosRepository")
 */
class SistemaJuegoDos
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
     * @ORM\Column(name="tipo_sistema", type="string", length=191)
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
     * @ORM\Column(name="puntosEmpate", type="integer")
     */
    private $puntosEmpate;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosVictoria", type="integer")
     */
    private $puntosVictoria;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosParaGanar", type="integer")
     */
    private $puntosParaGanar;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosW", type="integer")
     */
    private $puntosW;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosJuegoLimpio", type="integer")
     */
    private $puntosJuegoLimpio;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="sistemaJuegosDos")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", mappedBy="sistemaJuegoDos")
     */
    private $encuentrosSistemaDos;


     /**
     * Constructor
     */
    public function __construct() {
        $this->encuentrosSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SistemaJuegoDos
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
     * @return SistemaJuegoDos
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
     * @return SistemaJuegoDos
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
     * @return SistemaJuegoDos
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
     * @return SistemaJuegoDos
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
     * @param integer $puntosW
     *
     * @return SistemaJuegoDos
     */
    public function setPuntosW($puntosW)
    {
        $this->puntosW = $puntosW;

        return $this;
    }

    /**
     * Get puntosW
     *
     * @return int
     */
    public function getPuntosW()
    {
        return $this->puntosW;
    }

    /**
     * Set puntosJuegoLimpio
     *
     * @param integer $puntosJuegoLimpio
     *
     * @return SistemaJuegoDos
     */
    public function setPuntosJuegoLimpio($puntosJuegoLimpio)
    {
        $this->puntosJuegoLimpio = $puntosJuegoLimpio;

        return $this;
    }

    /**
     * Get puntosJuegoLimpio
     *
     * @return int
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
     * @return SistemaJuegoDos
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
     * Add encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDos
     *
     * @return SistemaJuegoDos
     */
    public function addEncuentrosSistemaDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDos) {
        $this->encuentrosSistemaDos[] = $encuentrosSistemaDos;

        return $this;
    }

    /**
     * Remove encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDos
     */
    public function removeEncuentrosSistemaDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDos) {
        $this->encuentrosSistemaDos->removeElement($encuentrosSistemaDos);
    }

    /**
     * Get sistemaJuegosDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentrosSistemaDos() {
        return $this->encuentrosSistemaDos;
    }

    /**
     * Add encuentrosSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDo
     *
     * @return SistemaJuegoDos
     */
    public function addEncuentrosSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDo)
    {
        $this->encuentrosSistemaDos[] = $encuentrosSistemaDo;

        return $this;
    }

    /**
     * Remove encuentrosSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDo
     */
    public function removeEncuentrosSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentrosSistemaDo)
    {
        $this->encuentrosSistemaDos->removeElement($encuentrosSistemaDo);
    }
}
