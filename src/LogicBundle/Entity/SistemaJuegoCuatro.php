<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SistemaJuegoCuatro
 *
 * @ORM\Table(name="sistema_juego_cuatro")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SistemaJuegoCuatroRepository")
 */
class SistemaJuegoCuatro
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
     * @ORM\Column(name="puntos_w", type="integer")
     */
    private $puntosW;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_juego_limpio", type="integer")
     */
    private $puntosJuegoLimpio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="sistemaJuegoCuatro")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", mappedBy="sistemaJuegoCuatro")
     */
    private $encuentroSistemaCuatro;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\GruposEncuentroSistemaCuatro", mappedBy="sistemaJuegoCuatro")
     */
    private $gruposEncuentroSistemaCuatro;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->encuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposEncuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SistemaJuegoCuatro
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
     * @return SistemaJuegoCuatro
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
     * @return SistemaJuegoCuatro
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
     * @return SistemaJuegoCuatro
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
     * Set puntosW
     *
     * @param integer $puntosW
     *
     * @return SistemaJuegoCuatro
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
     * @return SistemaJuegoCuatro
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
     * @return SistemaJuegoCuatro
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
     * Add encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     *
     * @return SistemaJuegoCuatro
     */
    public function addEncuentroSistemaCuatro(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro)
    {
        $this->encuentroSistemaCuatro[] = $encuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     */
    public function removeEncuentroSistemaCuatro(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro)
    {
        $this->encuentroSistemaCuatro->removeElement($encuentroSistemaCuatro);
    }

    /**
     * Get encuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaCuatro()
    {
        return $this->encuentroSistemaCuatro;
    }




        /**
     * Add gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro
     *
     * @return SistemaJuegoCuatro
     */
    public function addGruposEncuentroSistemaCuatro(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro)
    {
        $this->gruposEncuentroSistemaCuatro[] = $gruposEncuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro
     */
    public function removeGruposEncuentroSistemaCuatro(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro)
    {
        $this->gruposEncuentroSistemaCuatro->removeElement($gruposEncuentroSistemaCuatro);
    }

    /**
     * Get gruposEncuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGruposEncuentroSistemaCuatro()
    {
        return $this->gruposEncuentroSistemaCuatro ;
    }
}
