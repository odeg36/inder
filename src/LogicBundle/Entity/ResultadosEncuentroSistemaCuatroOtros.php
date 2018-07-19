<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultadosEncuentroSistemaCuatroOtros
 *
 * @ORM\Table(name="resultados_encuentro_sistema_cuatro_otros")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadosEncuentroSistemaCuatroOtrosRepository")
 */
class ResultadosEncuentroSistemaCuatroOtros
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
     * @ORM\Column(name="puntos_afavor", type="integer")
     */
    private $puntosAfavor;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_en_contra", type="integer")
     */
    private $puntosEnContra;

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
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255 , nullable=true)
     */
    private $grupo;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", inversedBy="resultadosEncuentroSistemaCuatroOtros")
     * @ORM\JoinColumn(name="encuentroSistemaCuatro_id",referencedColumnName="id")
     */
    private $encuentroSistemaCuatro;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="resultadosEncuentroSistemaCuatroOtros")
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
     * Set puntosAfavor
     *
     * @param integer $puntosAfavor
     *
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * Set puntosJuegoLimpio
     *
     * @param integer $puntosJuegoLimpio
     *
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return ResultadosEncuentroSistemaCuatroOtros
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
     * Set grupo
     *
     * @param string grupo
     *
     * @return ResultadosEncuentroSistemaCuatroOtros
     */
    public function setHora($grupo)
    {
        $this->$grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getHora()
    {
        return $this->$grupo;
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
}
