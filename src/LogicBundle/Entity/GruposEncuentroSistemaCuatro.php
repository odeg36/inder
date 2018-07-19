<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GruposEncuentroSistemaCuatro
 *
 * @ORM\Table(name="grupos_encuentro_sistema_cuatro")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\GruposEncuentroSistemaCuatroRepository")
 */
class GruposEncuentroSistemaCuatro
{
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


    /**
     * @var string
     *
     * @ORM\Column(name="identificador", type="string", length=255)
     */
    private $identificador;
 
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SistemaJuegoCuatro", inversedBy="gruposEncuentroSistemaCuatro")
     * @ORM\JoinColumn(name="sistemaJuegoCuatro_id",referencedColumnName="id")
     */
    private $sistemaJuegoCuatro;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="gruposEncuentroSistemaCuatro")
     * @ORM\JoinColumn(name="equipo_evento_id",referencedColumnName="id")
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GruposEncuentroSistemaCuatro
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }


    /**
     * Set identificador
     *
     * @param string $identificador
     *
     * @return GruposEncuentroSistemaCuatro
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;

        return $this;
    }

    /**
     * Get identificador
     *
     * @return string
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set sistemaJuegoCuatro
     *
     * @param \LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro
     *
     * @return GruposEncuentroSistemaCuatro
     */
    public function setSistemaJuegoCuatro(\LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro = null) {
        $this->sistemaJuegoCuatro = $sistemaJuegoCuatro;

        return $this;
    }

    /**
     * Get sistemaJuegoCuatro
     *
     * @return \LogicBundle\Entity\SistemaJuegoCuatro
     */
    public function getSistemaJuegoCuatro() {
        return $this->sistemaJuegoCuatro;
    }

    

    /**
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return GruposEncuentroSistemaCuatro
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
