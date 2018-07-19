<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultadosEncuentroSistemaDosOtros
 *
 * @ORM\Table(name="resultados_encuentro_sistema_dos_otros")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadosEncuentroSistemaDosOtrosRepository")
 */
class ResultadosEncuentroSistemaDosOtros
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
     * @var int
     *
     * @ORM\Column(name="puntosAfavor", type="integer")
     */
    private $puntosAfavor;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosEnContra", type="integer")
     */
    private $puntosEnContra;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosJuegoLimpio", type="integer")
     */
    private $puntosJuegoLimpio;

     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", inversedBy="resultadosEncuentroSistemaDosOtros")
     * @ORM\JoinColumn(name="encuentroSistemaDos_id",referencedColumnName="id")
     */
    private $encuentroSistemaDos;


     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="resultadosEncuentroSistemaDosOtros")
     * @ORM\JoinColumn(name="equipoEvento_id",referencedColumnName="id")
     */
    private $equipoEvento;


    /**
     * @var int
     *
     * @ORM\Column(name="identificadorSistemaJuego", type="integer")
     */
    private $identificadorSistemaJuego;

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
     * Set puntosAfavor
     *
     * @param integer $puntosAfavor
     *
     * @return ResultadosEncuentroSistemaDosOtros
     */
    public function setPuntosAfavor($puntosAfavor)
    {
        $this->puntosAfavor = $puntosAfavor;

        return $this;
    }

    /**
     * Get puntosAfavor
     *
     * @return int
     */
    public function getPuntosAfavor()
    {
        return $this->puntosAfavor;
    }

    /**
     * Set puntosEnContra
     *
     * @param integer $puntosEnContra
     *
     * @return ResultadosEncuentroSistemaDosOtros
     */
    public function setPuntosEnContra($puntosEnContra)
    {
        $this->puntosEnContra = $puntosEnContra;

        return $this;
    }

    /**
     * Get puntosEnContra
     *
     * @return int
     */
    public function getPuntosEnContra()
    {
        return $this->puntosEnContra;
    }




    /**
     * Set encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos
     *
     * @return ResultadosEncuentroSistemaDosOtros
     */
    public function setEncuentroSistemaDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos = null) {
        $this->encuentroSistemaDos = $encuentroSistemaDos;

        return $this;
    }


    /**
     * Get encuentroSistemaDos
     *
     * @return \LogicBundle\Entity\EncuentroSistemaDos
     */
    public function getEncuentroSistemaDos() {
        return $this->encuentroSistemaDos;
    }


    /**
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return ResultadosEncuentroSistemaDosOtros
     */
    public function setEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento = null) {
        $this->equipoEvento = $equipoEvento;

        return $this;
    }


    /**
     * Get equipoEvento
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getEquipoEvento() {
        return $this->equipoEvento;
    }
    
    
    /**
     * Set puntosJuegoLimpio
     *
     * @param integer $puntosJuegoLimpio
     *
     * @return ResultadosEncuentroSistemaDosOtros
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
     * Set identificadorSistemaJuego
     *
     * @param integer $identificadorSistemaJuego
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setIdentificadorSistemaJuego($identificadorSistemaJuego)
    {
        $this->identificadorSistemaJuego = $identificadorSistemaJuego;

        return $this;
    }

    /**
     * Get puntosJuegoLimpio
     *
     * @return int
     */
    public function getIdentificadorSistemaJuego()
    {
        return $this->identificadorSistemaJuego;
    }
}
