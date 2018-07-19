<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TareaActividad
 *
 * @ORM\Table(name="tarea_actividad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TareaActividadRepository")
 */
class TareaActividad
{
    public function __toString() {
        return (string)$this->getId() ? : '';
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="modeloPalificacion", type="string", length=255)
     */
    private $modeloPalificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="modeloEnsenanza", type="string", length=255)
     */
    private $modeloEnsenanza;

    /**
     * @var string
     *
     * @ORM\Column(name="contenidoTecnico", type="text")
     */
    private $contenidoTecnico;

    /**
     * @var string
     *
     * @ORM\Column(name="testAplicativo", type="string", length=255)
     */
    private $testAplicativo;
    
    /**
     * @ORM\ManyToOne(targetEntity="PlanMetodologico", inversedBy="planMetodologicoTareaActividades")
     * @ORM\JoinColumn(name="plan_metodologico_id", referencedColumnName="id")
     */
    private $planMetodologico;
    
    /**
     * @ORM\ManyToOne(targetEntity="ResponsableTareaActividad", inversedBy="tareaActividades")
     * @ORM\JoinColumn(name="tarea_actividad_id", referencedColumnName="id")
     */
    private $responsableTareaActividad;
    
    /**
     * @ORM\ManyToMany(targetEntity="TipoTarea", inversedBy="tareaActividades", cascade={"persist"})
     * @ORM\JoinTable(name="tipo_tarea_actividad")
     */
    private $tipoTareas;
    
    /**
     * @ORM\ManyToOne(targetEntity="NivelSubPrincipioObjetivo", inversedBy="planMetodologicoTareaActividades")
     * @ORM\JoinColumn(name="nivel_sub_principio_objetivo_id", referencedColumnName="id")
     */
    private $nivelSubPrincipioObjetivo;
    
    /**
     * @ORM\ManyToOne(targetEntity="NivelComponenteContenido", inversedBy="planMetodologicoTareaActividades")
     * @ORM\JoinColumn(name="nivel_componente_contenido_id", referencedColumnName="id")
     */
    private $nivelComponenteContenido;
    
    /**
     * @ORM\ManyToOne(targetEntity="FaseComponentePrincipioSubPrincipio", inversedBy="planMetodologicoTareaActividades")
     * @ORM\JoinColumn(name="fase_componente_principio_subprincipio_id", referencedColumnName="id")
     */
    private $faseComponentePrincipioSubPrincipio;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoTareas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set modeloPalificacion.
     *
     * @param string $modeloPalificacion
     *
     * @return TareaActividad
     */
    public function setModeloPalificacion($modeloPalificacion)
    {
        $this->modeloPalificacion = $modeloPalificacion;

        return $this;
    }

    /**
     * Get modeloPalificacion.
     *
     * @return string
     */
    public function getModeloPalificacion()
    {
        return $this->modeloPalificacion;
    }

    /**
     * Set modeloEnsenanza.
     *
     * @param string $modeloEnsenanza
     *
     * @return TareaActividad
     */
    public function setModeloEnsenanza($modeloEnsenanza)
    {
        $this->modeloEnsenanza = $modeloEnsenanza;

        return $this;
    }

    /**
     * Get modeloEnsenanza.
     *
     * @return string
     */
    public function getModeloEnsenanza()
    {
        return $this->modeloEnsenanza;
    }

    /**
     * Set contenidoTecnico.
     *
     * @param string $contenidoTecnico
     *
     * @return TareaActividad
     */
    public function setContenidoTecnico($contenidoTecnico)
    {
        $this->contenidoTecnico = $contenidoTecnico;

        return $this;
    }

    /**
     * Get contenidoTecnico.
     *
     * @return string
     */
    public function getContenidoTecnico()
    {
        return $this->contenidoTecnico;
    }

    /**
     * Set testAplicativo.
     *
     * @param string $testAplicativo
     *
     * @return TareaActividad
     */
    public function setTestAplicativo($testAplicativo)
    {
        $this->testAplicativo = $testAplicativo;

        return $this;
    }

    /**
     * Get testAplicativo.
     *
     * @return string
     */
    public function getTestAplicativo()
    {
        return $this->testAplicativo;
    }

    /**
     * Set planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico|null $planMetodologico
     *
     * @return TareaActividad
     */
    public function setPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico = null)
    {
        $this->planMetodologico = $planMetodologico;

        return $this;
    }

    /**
     * Get planMetodologico.
     *
     * @return \LogicBundle\Entity\PlanMetodologico|null
     */
    public function getPlanMetodologico()
    {
        return $this->planMetodologico;
    }

    /**
     * Set responsableTareaActividad.
     *
     * @param \LogicBundle\Entity\ResponsableTareaActividad|null $responsableTareaActividad
     *
     * @return TareaActividad
     */
    public function setResponsableTareaActividad(\LogicBundle\Entity\ResponsableTareaActividad $responsableTareaActividad = null)
    {
        $this->responsableTareaActividad = $responsableTareaActividad;

        return $this;
    }

    /**
     * Get responsableTareaActividad.
     *
     * @return \LogicBundle\Entity\ResponsableTareaActividad|null
     */
    public function getResponsableTareaActividad()
    {
        return $this->responsableTareaActividad;
    }

    /**
     * Add tipoTarea.
     *
     * @param \LogicBundle\Entity\TipoTarea $tipoTarea
     *
     * @return TareaActividad
     */
    public function addTipoTarea(\LogicBundle\Entity\TipoTarea $tipoTarea)
    {
        $this->tipoTareas[] = $tipoTarea;

        return $this;
    }

    /**
     * Remove tipoTarea.
     *
     * @param \LogicBundle\Entity\TipoTarea $tipoTarea
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTipoTarea(\LogicBundle\Entity\TipoTarea $tipoTarea)
    {
        return $this->tipoTareas->removeElement($tipoTarea);
    }

    /**
     * Get tipoTareas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoTareas()
    {
        return $this->tipoTareas;
    }

    /**
     * Set nivelSubPrincipioObjetivo.
     *
     * @param \LogicBundle\Entity\NivelSubPrincipioObjetivo|null $nivelSubPrincipioObjetivo
     *
     * @return TareaActividad
     */
    public function setNivelSubPrincipioObjetivo(\LogicBundle\Entity\NivelSubPrincipioObjetivo $nivelSubPrincipioObjetivo = null)
    {
        $this->nivelSubPrincipioObjetivo = $nivelSubPrincipioObjetivo;

        return $this;
    }

    /**
     * Get nivelSubPrincipioObjetivo.
     *
     * @return \LogicBundle\Entity\NivelSubPrincipioObjetivo|null
     */
    public function getNivelSubPrincipioObjetivo()
    {
        return $this->nivelSubPrincipioObjetivo;
    }

    /**
     * Set nivelComponenteContenido.
     *
     * @param \LogicBundle\Entity\NivelComponenteContenido|null $nivelComponenteContenido
     *
     * @return TareaActividad
     */
    public function setNivelComponenteContenido(\LogicBundle\Entity\NivelComponenteContenido $nivelComponenteContenido = null)
    {
        $this->nivelComponenteContenido = $nivelComponenteContenido;

        return $this;
    }

    /**
     * Get nivelComponenteContenido.
     *
     * @return \LogicBundle\Entity\NivelComponenteContenido|null
     */
    public function getNivelComponenteContenido()
    {
        return $this->nivelComponenteContenido;
    }

    /**
     * Set faseComponentePrincipioSubPrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipioSubPrincipio|null $faseComponentePrincipioSubPrincipio
     *
     * @return TareaActividad
     */
    public function setFaseComponentePrincipioSubPrincipio(\LogicBundle\Entity\FaseComponentePrincipioSubPrincipio $faseComponentePrincipioSubPrincipio = null)
    {
        $this->faseComponentePrincipioSubPrincipio = $faseComponentePrincipioSubPrincipio;

        return $this;
    }

    /**
     * Get faseComponentePrincipioSubPrincipio.
     *
     * @return \LogicBundle\Entity\FaseComponentePrincipioSubPrincipio|null
     */
    public function getFaseComponentePrincipioSubPrincipio()
    {
        return $this->faseComponentePrincipioSubPrincipio;
    }
}
