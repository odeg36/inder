<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaltasEncuentroJugador
 *
 * @ORM\Table(name="faltas_encuentro_sistema_cuatro")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaltasEncuentroSistemaCuatroRepository")
 */
class FaltasEncuentroSistemaCuatro
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", inversedBy="faltasEncuentroSistemaCuatro")
     * @ORM\JoinColumn(name="encuentroSistemaCuatro_id",referencedColumnName="id")
     */

    private $encuentroSistemaCuatro;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="faltasEquipoEncuentroSistemaCuatro")
     * @ORM\JoinColumn(name="equipoEvento_id",referencedColumnName="id")
     */
    private $equipoEvento;

    /**
     * @var int
     *
     * @ORM\Column(name="identificador_sistema_juego", type="integer")
     */
    private $identificadorSistemaJuego;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SancionEvento", inversedBy="faltasEncuentroSistemaCuatro")
     * @ORM\JoinColumn(name="sancionEvento_id",referencedColumnName="id")
     */
    
    private $sancionEvento;



    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255 , nullable=true)
     */
    private $grupo;
    

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
     * Set tipoFalta
     *
     * @param \LogicBundle\Entity\TipoFalta $tipoFalta
     *
     * @return FaltasEncuentroJugador
     */
    public function setTipoFalta(\LogicBundle\Entity\TipoFalta $tipoFalta = null) {
        $this->tipoFalta = $tipoFalta;

        return $this;
    }

    /**
     * Get tipoFalta
     *
     * @return \LogicBundle\Entity\TipoFalta
     */
    public function getTipoFalta() {
        return $this->tipoFalta;
    }


    /**
     * Set encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     *
     * @return FaltasEncuentroJugador
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
     * @param \LogicBundle\Entity\EquipoEvento $encuentroSistemaCuatro
     *
     * @return EquipoEvento
     */
    public function setEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento = null)
    {
        $this->equipoEvento = $equipoEvento;

        return $this;
    }

    /**
     * Get equipoEvento
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getEquipoEvento()
    {
        return $this->equipoEvento;
    }
    
    /**
     * Set identificadorSistemaJuego
     *
     * @param integer $identificadorSistemaJuego
     *
     * @return FaltasEncuentroSistemaCuatro
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
     * Set sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return FaltasEncuentroSistemaCuatro
     */
    public function setSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento = null) {
        $this->sancionEvento = $sancionEvento;

        return $this;
    }

    /**
     * Get sancionEvento
     *
     * @return \LogicBundle\Entity\SancionEvento
     */
    public function getSancionEvento() {
        return $this->sancionEvento;
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
     * @return FaltasEncuentroSistemaCuatro
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
