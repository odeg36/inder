<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contenido
 *
 * @ORM\Table(name="contenido")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ContenidoRepository")
 */
class Contenido
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
     * @ORM\Column(name="nombre", type="text")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="text")
     */
    private $objetivo;

    /**
     * @var int
     *
     * @ORM\Column(name="ponderacion", type="float", nullable=true)
     */
    private $ponderacion;

    /**
     * @var string
     *
     * @ORM\Column(name="tema", type="string", length=255, nullable=true)
     */
    private $tema;

    /**
     * @var string
     *
     * @ORM\Column(name="actores", type="string", length=255, nullable=true)
     */
    private $actores;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Actividad", mappedBy="contenido");
     */
    private $actividades;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Componente", inversedBy="contenidos")
     * @ORM\JoinColumn(name="componente_id",referencedColumnName="id")
     */
    private $componente;


    /**
     * Constructor
     */
    public function __construct() {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Contenido
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
     * Set objetivo
     *
     * @param string $objetivo
     *
     * @return Contenido
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo
     *
     * @return string
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Set ponderacion
     *
     * @param integer $ponderacion
     *
     * @return Contenido
     */
    public function setPonderacion($ponderacion)
    {
        $this->ponderacion = $ponderacion;

        return $this;
    }

    /**
     * Get ponderacion
     *
     * @return int
     */
    public function getPonderacion()
    {
        return $this->ponderacion;
    }

    /**
     * Set tema
     *
     * @param string $tema
     *
     * @return Contenido
     */
    public function setTema($tema)
    {
        $this->tema = $tema;

        return $this;
    }

    /**
     * Get tema
     *
     * @return string
     */
    public function getTema()
    {
        return $this->tema;
    }

    /**
     * Set actores
     *
     * @param string $actores
     *
     * @return Contenido
     */
    public function setActores($actores)
    {
        $this->actores = $actores;

        return $this;
    }

    /**
     * Get actores
     *
     * @return string
     */
    public function getActores()
    {
        return $this->actores;
    }

    /**
     * Set componente
     *
     * @param string $componente
     *
     * @return Contenido
     */
    public function setComponente($componente)
    {
        $this->componente = $componente;

        return $this;
    }

    /**
     * Get componente
     *
     * @return string
     */
    public function getComponente()
    {
        return $this->componente;
    }
    
    /**
     * Add actividad
     *
     * @param \LogicBundle\Entity\Actividad $actividad
     *
     * @return Contenido
     */
    public function addActividad(\LogicBundle\Entity\Actividad $actividad) {
        $this->actividades[] = $actividad;

        return $this;
    }

    /**
     * Remove actividad
     *
     * @param \LogicBundle\Entity\Actividad $actividad
     */
    public function removeActividad(\LogicBundle\Entity\Actividad $actividad) {
        $this->actividades->removeElement($actividad);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades() {
        return $this->actividades;
    }

    /**
     * Add actividade
     *
     * @param \LogicBundle\Entity\Actividad $actividade
     *
     * @return Contenido
     */
    public function addActividade(\LogicBundle\Entity\Actividad $actividade)
    {
        $this->actividades[] = $actividade;

        return $this;
    }

    /**
     * Remove actividade
     *
     * @param \LogicBundle\Entity\Actividad $actividade
     */
    public function removeActividade(\LogicBundle\Entity\Actividad $actividade)
    {
        $this->actividades->removeElement($actividade);
    }
}
