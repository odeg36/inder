<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampoEvento
 *
 * @ORM\Table(name="campo_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoEventoRepository")
 */
class CampoEvento {

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
     * @ORM\Column(name="nombre_mapeado", type="string", length=255,nullable=true)
     */
    private $nombreMapeado;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255,nullable=true)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\CampoFormularioEvento", mappedBy="campoEvento")
     */
    private $campoFormulariosEventos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->campoFormulariosEventos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CampoEvento
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set nombreMapeado
     *
     * @param string $nombreMapeado
     *
     * @return CampoEvento
     */
    public function setNombreMapeado($nombreMapeado) {
        $this->nombreMapeado = $nombreMapeado;

        return $this;
    }

    /**
     * Get nombreMapeado
     *
     * @return string
     */
    public function getNombreMapeado() {
        return $this->nombreMapeado;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return CampoEvento
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Add campoFormulariosEvento
     *
     * @param \LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento
     *
     * @return Evento
     */
    public function addCampoFormulariosEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento) {
        $this->campoFormulariosEventos[] = $campoFormulariosEvento;

        return $this;
    }

    /**
     * Remove campoFormulariosEventos
     *
     * @param \LogicBundle\Entity\CampoFormularioEventos $campoFormulariosEventos
     */
    public function removeCampoFormulariosEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento) {
        $this->campoFormulariosEventos->removeElement($campoFormulariosEvento);
    }

    /**
     * Get campoFormulariosEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoFormulariosEventos() {
        return $this->campoFormulariosEventos;
    }

}
