<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultadosEncuentroSistemaCuatroVoleibol
 *
 * @ORM\Table(name="resultados_encuentro_sistema_cuatro_voleibol")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadosEncuentroSistemaCuatroVoleibolRepository")
 */
class ResultadosEncuentroSistemaCuatroVoleibol
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
     * @ORM\Column(name="set_uno", type="integer")
     */
    private $setUno;

    /**
     * @var int
     *
     * @ORM\Column(name="set_dos", type="integer")
     */
    private $setDos;

    /**
     * @var int
     *
     * @ORM\Column(name="set_tres", type="integer")
     */
    private $setTres;

    /**
     * @var int
     *
     * @ORM\Column(name="set_cuatro", type="integer")
     */
    private $setCuatro;

    /**
     * @var int
     *
     * @ORM\Column(name="set_cinco", type="integer")
     */
    private $setCinco;

    /**
     * @var int
     *
     * @ORM\Column(name="sets_afavor", type="integer")
     */
    private $setsAfavor;

    /**
     * @var int
     *
     * @ORM\Column(name="sets_en_contra", type="integer")
     */
    private $setsEnContra;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_juego_limpio", type="integer")
     */
    private $puntosJuegoLimpio;

    /**
     * @var int
     *
     * @ORM\Column(name="identificador_sistema_juego", type="integer")
     */
    private $identificadorSistemaJuego;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", inversedBy="resultadosEncuentroSistemaCuatroVoleibol")
     * @ORM\JoinColumn(name="encuentro_sistema_cuatro_id",referencedColumnName="id")
     */
    private $encuentroSistemaCuatro;
    

     /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255 , nullable=true)
     */
    private $grupo;



    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="resultadosEncuentroSistemaCuatroVoleibol")
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatrocVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * Set setsAfavor
     *
     * @param integer $setsAfavor
     *
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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
     * @return ResultadosEncuentroSistemaCuatroVoleibol
     */
    public function setIdentificadorSistemaJuego($identificadorSistemaJuego)
    {
        $this->identificadorSistemaJuego = $identificadorSistemaJuego;

        return $this;
    }

    /**
     * Get identificadorSistemaJuego
     *
     * @return int
     */
    public function getIdentificadorSistemaJuego()
    {
        return $this->identificadorSistemaJuego;
    }

    /**
     * Set encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     *
     * @return ResultadosEncuentroSistemaCuatroVoleibol
     */
    public function setEncuentroSistemaCuatro(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro = null) {
        $this->encuentroSistemaCuatro = $encuentroSistemaCuatro;

        return $this;
    }

    /**
     * Get encuentroSistemaCuatro
     *
     * @return \LogicBundle\Entity\EncuentroSistemaCuatro
     */
    public function getEncuentroSistemaCuatro() {
        return $this->encuentroSistemaCuatro;
    }
    


      /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return ResultadosEncuentroSistemaCuatroOtros
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }


    /**
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return ResultadosEncuentroSistemaCuatroVoleibol
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

    
    
    
}
