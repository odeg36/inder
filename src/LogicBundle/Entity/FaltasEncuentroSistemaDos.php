<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaltasEncuentroSistemaDos
 *
 * @ORM\Table(name="faltas_encuentro_sistema_dos")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaltasEncuentroSistemaDosRepository")
 */
class FaltasEncuentroSistemaDos
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", inversedBy="faltasEncuentroSistemaDos")
     * @ORM\JoinColumn(name="encuentroSistemaDos_id",referencedColumnName="id")
     */
    
    private $encuentroSistemaDos;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="faltasEncuentroSistemaDos")
     * @ORM\JoinColumn(name="equipoEvento_id",referencedColumnName="id")
     */
    
    private $equipoEvento;


     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SancionEvento", inversedBy="faltasEncuentroSistemaDos")
     * @ORM\JoinColumn(name="sancionEvento_id",referencedColumnName="id")
     */
    
    private $sancionEvento;


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
     * Set encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos
     *
     * @return FaltasEncuentroSistemaDos
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
     * @return FaltasEncuentroSistemaDos
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
     * Set sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return FaltasEncuentroSistemaDos
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
     * Set identificadorSistemaJuego
     *
     * @param integer $identificadorSistemaJuego
     *
     * @return FaltasEncuentroSistemaDos
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
}
