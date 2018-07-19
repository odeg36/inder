<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividad
 *
 * @ORM\Table(name="actividad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ActividadRepository")
 */
class Actividad
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
     * @var int
     *
     * @ORM\Column(name="duracion", type="integer")
     */
    private $duracion;

    /**
     * @var string
     *
     * @ORM\Column(name="indicador", type="text")
     */
    private $indicador;

    /**
     * @var string
     *
     * @ORM\Column(name="metodo_evaluacion", type="text")
     */
    private $metodoEvaluacion;

    /**
     * @var string
     *
     * @ORM\Column(name="logro", type="string", length=255, nullable=true)
     */
    private $logro;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoTiempoEjecucion", inversedBy="actividades")
     * @ORM\JoinColumn(name="tipo_tiempo_ejecucion_id",referencedColumnName="id")
     */
    private $tipoTiempoEjecucion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Contenido", inversedBy="actividades")
     * @ORM\JoinColumn(name="contenido_id",referencedColumnName="id")
     */
    private $contenido;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ActividadPlanClase", mappedBy="actividad")
     */
    private $planesClase;


    /**
     * Constructor
     */
    public function __construct() {
        $this->planesClase = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Actividad
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
     * Set duracion
     *
     * @param integer $duracion
     *
     * @return Actividad
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return int
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set indicador
     *
     * @param string $indicador
     *
     * @return Actividad
     */
    public function setIndicador($indicador)
    {
        $this->indicador = $indicador;

        return $this;
    }

    /**
     * Get indicador
     *
     * @return string
     */
    public function getIndicador()
    {
        return $this->indicador;
    }

    /**
     * Set metodoEvaluacion
     *
     * @param string $metodoEvaluacion
     *
     * @return Actividad
     */
    public function setMetodoEvaluacion($metodoEvaluacion)
    {
        $this->metodoEvaluacion = $metodoEvaluacion;

        return $this;
    }

    /**
     * Get metodoEvaluacion
     *
     * @return string
     */
    public function getMetodoEvaluacion()
    {
        return $this->metodoEvaluacion;
    }

    /**
     * Set logro
     *
     * @param string $logro
     *
     * @return Actividad
     */
    public function setLogro($logro)
    {
        $this->logro = $logro;

        return $this;
    }

    /**
     * Get logro
     *
     * @return string
     */
    public function getLogro()
    {
        return $this->logro;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return Actividad
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set tipoTiempoEjecucion
     *
     * @param \LogicBundle\Entity\TipoTiempoEjecucion $tipoTiempoEjecucion
     *
     * @return Actividad
     */
    public function setTipoTiempoEjecucion(\LogicBundle\Entity\TipoTiempoEjecucion $tipoTiempoEjecucion = null) {
        $this->tipoTiempoEjecucion = $tipoTiempoEjecucion;

        return $this;
    }

    /**
     * Get tipoTiempoEjecucion
     *
     * @return \LogicBundle\Entity\TipoTiempoEjecucion
     */
    public function getTipoTiempoEjecucion() {
        return $this->tipoTiempoEjecucion;
    }

    /**
     * Add planClase
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $planClase
     *
     * @return PlanClase
     */
    public function addPlanClase(\LogicBundle\Entity\ActividadPlanClase $planClase) {
        $this->planesClase[] = $planClase;

        return $this;
    }

    /**
     * Remove planClase
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $planClase
     */
    public function removePlanClase(\LogicBundle\Entity\ActividadPlanClase $planClase)
    {
        $this->planClase->removeElement($planClase);
    }

    /**
     * Get planesClase
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanesClase()
    {
        return $this->planesClase;
    }


    /**
     * Add planesClase
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $planesClase
     *
     * @return Actividad
     */
    public function addPlanesClase(\LogicBundle\Entity\ActividadPlanClase $planesClase)
    {
        $this->planesClase[] = $planesClase;

        return $this;
    }

    /**
     * Remove planesClase
     *
     * @param \LogicBundle\Entity\ActividadPlanClase $planesClase
     */
    public function removePlanesClase(\LogicBundle\Entity\ActividadPlanClase $planesClase)
    {
        $this->planesClase->removeElement($planesClase);
    }
}
