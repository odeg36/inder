<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultadosEncuentrosSistemaDosVoleibol
 *
 * @ORM\Table(name="resultados_encuentros_sistema_dos_voleibol")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadosEncuentrosSistemaDosVoleibolRepository")
 */
class ResultadosEncuentrosSistemaDosVoleibol
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
     * @ORM\Column(name="setUno", type="integer")
     */
    private $setUno;

    /**
     * @var int
     *
     * @ORM\Column(name="setDos", type="integer")
     */
    private $setDos;

    /**
     * @var int
     *
     * @ORM\Column(name="setTres", type="integer")
     */
    private $setTres;

    /**
     * @var int
     *
     * @ORM\Column(name="setCuatro", type="integer")
     */
    private $setCuatro;

    /**
     * @var int
     *
     * @ORM\Column(name="setCinco", type="integer")
     */
    private $setCinco;


    /**
     * @var int
     *
     * @ORM\Column(name="setAfavor", type="integer")
     */
    private $setsAfavor;

    /**
     * @var int
     *
     * @ORM\Column(name="setEnContra", type="integer")
     */
    private $setsEnContra;

    /**
     * @var int
     *
     * @ORM\Column(name="puntosJuegoLimpio", type="integer")
     */
    private $puntosJuegoLimpio;

    
    /**
     * @var int
     *
     * @ORM\Column(name="identificadorSistemaJuego", type="integer")
     */
    private $identificadorSistemaJuego;


     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", inversedBy="resultadosEncuentrosSistemaDosVoleibol")
     * @ORM\JoinColumn(name="encuentroSistemaDos_id",referencedColumnName="id")
     */
    private $encuentroSistemaDos;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="resultadosEncuentrosSistemaDosVoleibol")
     * @ORM\JoinColumn(name="equipoEvento_id",referencedColumnName="id")
     */
    private $equipoEvento;
    


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
     * Set setUno
     *
     * @param integer $setUno
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetUno($setUno)
    {
        $this->setUno = $setUno;

        return $this;
    }

    /**
     * Get setUno
     *
     * @return int
     */
    public function getSetUno()
    {
        return $this->setUno;
    }

    /**
     * Set setDos
     *
     * @param integer $setDos
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetDos($setDos)
    {
        $this->setDos = $setDos;

        return $this;
    }

    /**
     * Get setDos
     *
     * @return int
     */
    public function getSetDos()
    {
        return $this->setDos;
    }

    /**
     * Set setTres
     *
     * @param integer $setTres
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetTres($setTres)
    {
        $this->setTres = $setTres;

        return $this;
    }

    /**
     * Get setTres
     *
     * @return int
     */
    public function getSetTres()
    {
        return $this->setTres;
    }

    /**
     * Set setCuatro
     *
     * @param integer $setCuatro
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetCuatro($setCuatro)
    {
        $this->setCuatro = $setCuatro;

        return $this;
    }

    /**
     * Get setCuatro
     *
     * @return int
     */
    public function getSetCuatro()
    {
        return $this->setCuatro;
    }

    /**
     * Set setCinco
     *
     * @param integer $setCinco
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetCinco($setCinco)
    {
        $this->setCinco = $setCinco;

        return $this;
    }

    /**
     * Get setCinco
     *
     * @return int
     */
    public function getSetCinco()
    {
        return $this->setCinco;
    }


    /**
     * Set encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
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
     * @return ResultadosEncuentrosSistemaDosVoleibol
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
     * Set setsAfavor
     *
     * @param integer $setsAfavor
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetsAfavor($setsAfavor)
    {
        $this->setsAfavor = $setsAfavor;

        return $this;
    }

    /**
     * Get setsAfavor
     *
     * @return int
     */
    public function getSetsAfavor()
    {
        return $this->setsAfavor;
    }


     /**
     * Set setsEnContra
     *
     * @param integer $setsEnContra
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
     */
    public function setSetsEnContra($setsEnContra)
    {
        $this->setsEnContra = $setsEnContra;

        return $this;
    }

    /**
     * Get setsEnContra
     *
     * @return int
     */
    public function getSetsEnContra()
    {
        return $this->setsEnContra;
    }



     /**
     * Set puntosJuegoLimpio
     *
     * @param integer $puntosJuegoLimpio
     *
     * @return ResultadosEncuentrosSistemaDosVoleibol
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
