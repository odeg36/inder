<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * NivelSubPrincipioObjetivo
 *
 * @ORM\Table(name="nivel_sub_principio_objetivo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelSubPrincipioObjetivoRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NivelSubPrincipioObjetivo
{
    public function __toString() {
        return $this->getContenido() ? : '';
    }
    
    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("nombre")
     */
    public function nombrePrincipio() {
        return $this->getObjetivo() ? : '';
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="string", length=255)
     * @Serializer\Expose
     */
    private $objetivo;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     * @Serializer\Expose
     */
    private $contenido;

    /**
     * @ORM\ManyToOne(targetEntity="NivelPlanMetodologicoSubPrincipio", inversedBy="subPrincipioObjectivos", cascade={"persist"})
     * @ORM\JoinColumn(name="sub_principio_id", referencedColumnName="id")
     */
    private $subPrincipio;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelObjetivoActividadEspecifica", mappedBy="objetivo", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $objetivoActividadEspecificas;
    
    /**
     * @ORM\OneToMany(targetEntity="TareaActividad", mappedBy="nivelSubPrincipioObjetivo")
     */
    private $planMetodologicoTareaActividades;
    
    /**
     * Add objetivoActividadEspecifica.
     *
     * @param \LogicBundle\Entity\NivelObjetivoActividadEspecifica $objetivoActividadEspecifica
     *
     * @return NivelSubPrincipioObjetivo
     */
    public function addObjetivoActividadEspecifica(\LogicBundle\Entity\NivelObjetivoActividadEspecifica $objetivoActividadEspecifica)
    {
        $objetivoActividadEspecifica->setObjetivo($this);
        
        $this->objetivoActividadEspecificas[] = $objetivoActividadEspecifica;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objetivoActividadEspecificas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set objetivo.
     *
     * @param string $objetivo
     *
     * @return NivelSubPrincipioObjetivo
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo.
     *
     * @return string
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Set contenido.
     *
     * @param string $contenido
     *
     * @return NivelSubPrincipioObjetivo
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido.
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set subPrincipio.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio|null $subPrincipio
     *
     * @return NivelSubPrincipioObjetivo
     */
    public function setSubPrincipio(\LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $subPrincipio = null)
    {
        $this->subPrincipio = $subPrincipio;

        return $this;
    }

    /**
     * Get subPrincipio.
     *
     * @return \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio|null
     */
    public function getSubPrincipio()
    {
        return $this->subPrincipio;
    }

    /**
     * Remove objetivoActividadEspecifica.
     *
     * @param \LogicBundle\Entity\NivelObjetivoActividadEspecifica $objetivoActividadEspecifica
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeObjetivoActividadEspecifica(\LogicBundle\Entity\NivelObjetivoActividadEspecifica $objetivoActividadEspecifica)
    {
        return $this->objetivoActividadEspecificas->removeElement($objetivoActividadEspecifica);
    }

    /**
     * Get objetivoActividadEspecificas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjetivoActividadEspecificas()
    {
        return $this->objetivoActividadEspecificas;
    }

    /**
     * Add planMetodologicoTareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade
     *
     * @return NivelSubPrincipioObjetivo
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
