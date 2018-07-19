<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanMetodologico
 *
 * @ORM\Table(name="plan_metodologico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PlanMetodologicoRepository")
 */
class PlanMetodologico {

    public function __toString() {
        return $this->getNombre() ?: '';
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="Rama", inversedBy="planMetodologicos")
     * @ORM\JoinColumn(name="rama_id", referencedColumnName="id")
     */
    private $rama;

    /**
     * @ORM\ManyToOne(targetEntity="ClasificacionDeporte", inversedBy="planMetodologicos")
     * @ORM\JoinColumn(name="clasificacion_deporte_id", referencedColumnName="id")
     */
    private $clasificacion;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="planMetodologicos")
     * @ORM\JoinColumn(name="disicplina_id", referencedColumnName="id")
     */
    private $disciplina;

    /**
     * @ORM\OneToMany(targetEntity="ActividadPlan", mappedBy="planMetodologico")
     */
    private $actividades;

    /**
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologico", mappedBy="planMetodologico")
     */
    private $nivelPlanMetodologicos;
    
    /**
     * @ORM\OneToMany(targetEntity="PlanMetodologicoActividad", mappedBy="planMetodologico", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $planMetodologicoActividades;
    
    /**
     * @ORM\OneToMany(targetEntity="TareaActividad", mappedBy="planMetodologico")
     */
    private $planMetodologicoTareaActividades;
    
    protected $nivelPlanes;
    
    function getNivelPlanes() {
        return $this->nivelPlanes;
    }

    function setNivelPlanes($nivelPlanes) {
        $this->nivelPlanes = $nivelPlanes;

        return $this;
    }
    
    function addNivelPlanes(\LogicBundle\Entity\NivelPlan $nivelPlan) {
        $this->nivelPlanes[] = $nivelPlan;

        return $this;
    }
    
    /**
     * Add planMetodologicoActividade.
     *
     * @param \LogicBundle\Entity\PlanMetodologicoActividad $planMetodologicoActividade
     *
     * @return PlanMetodologico
     */
    public function addPlanMetodologicoActividade(\LogicBundle\Entity\PlanMetodologicoActividad $planMetodologicoActividade)
    {
        $planMetodologicoActividade->setPlanMetodologico($this);
        
        $this->planMetodologicoActividades[] = $planMetodologicoActividade;

        return $this;
    }
    
    /**
     * Remove planMetodologicoActividade.
     *
     * @param \LogicBundle\Entity\PlanMetodologicoActividad $planMetodologicoActividade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanMetodologicoActividade(\LogicBundle\Entity\PlanMetodologicoActividad $planMetodologicoActividade)
    {
        return $this->planMetodologicoActividades->removeElement($planMetodologicoActividade);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nivelPlanMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planMetodologicoActividades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planMetodologicoTareaActividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return PlanMetodologico
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set estado.
     *
     * @param bool|null $estado
     *
     * @return PlanMetodologico
     */
    public function setEstado($estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return bool|null
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set rama.
     *
     * @param \LogicBundle\Entity\Rama|null $rama
     *
     * @return PlanMetodologico
     */
    public function setRama(\LogicBundle\Entity\Rama $rama = null)
    {
        $this->rama = $rama;

        return $this;
    }

    /**
     * Get rama.
     *
     * @return \LogicBundle\Entity\Rama|null
     */
    public function getRama()
    {
        return $this->rama;
    }

    /**
     * Set clasificacion.
     *
     * @param \LogicBundle\Entity\ClasificacionDeporte|null $clasificacion
     *
     * @return PlanMetodologico
     */
    public function setClasificacion(\LogicBundle\Entity\ClasificacionDeporte $clasificacion = null)
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion.
     *
     * @return \LogicBundle\Entity\ClasificacionDeporte|null
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set disciplina.
     *
     * @param \LogicBundle\Entity\Disciplina|null $disciplina
     *
     * @return PlanMetodologico
     */
    public function setDisciplina(\LogicBundle\Entity\Disciplina $disciplina = null)
    {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina.
     *
     * @return \LogicBundle\Entity\Disciplina|null
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * Add actividade.
     *
     * @param \LogicBundle\Entity\ActividadPlan $actividade
     *
     * @return PlanMetodologico
     */
    public function addActividade(\LogicBundle\Entity\ActividadPlan $actividade)
    {
        $this->actividades[] = $actividade;

        return $this;
    }

    /**
     * Remove actividade.
     *
     * @param \LogicBundle\Entity\ActividadPlan $actividade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeActividade(\LogicBundle\Entity\ActividadPlan $actividade)
    {
        return $this->actividades->removeElement($actividade);
    }

    /**
     * Get actividades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades()
    {
        return $this->actividades;
    }

    /**
     * Add nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico
     *
     * @return PlanMetodologico
     */
    public function addNivelPlanMetodologico(\LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico)
    {
        $this->nivelPlanMetodologicos[] = $nivelPlanMetodologico;

        return $this;
    }

    /**
     * Remove nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivelPlanMetodologico(\LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico)
    {
        return $this->nivelPlanMetodologicos->removeElement($nivelPlanMetodologico);
    }

    /**
     * Get nivelPlanMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNivelPlanMetodologicos()
    {
        return $this->nivelPlanMetodologicos;
    }

    /**
     * Get planMetodologicoActividades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanMetodologicoActividades()
    {
        return $this->planMetodologicoActividades;
    }

    /**
     * Add planMetodologicoTareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade
     *
     * @return PlanMetodologico
     */
    public function addPlanMetodologicoTareaActividade(\LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade)
    {
        $this->planMetodologicoTareaActividades[] = $planMetodologicoTareaActividade;

        return $this;
    }

    /**
     * Remove planMetodologicoTareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanMetodologicoTareaActividade(\LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade)
    {
        return $this->planMetodologicoTareaActividades->removeElement($planMetodologicoTareaActividade);
    }

    /**
     * Get planMetodologicoTareaActividades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanMetodologicoTareaActividades()
    {
        return $this->planMetodologicoTareaActividades;
    }
}
