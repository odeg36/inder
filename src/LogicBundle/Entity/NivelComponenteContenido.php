<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * NivelComponenteContenido
 *
 * @ORM\Table(name="nivel_componente_contenido")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelComponenteContenidoRepository")
 * @Serializer\ExclusionPolicy("all") 
 */
class NivelComponenteContenido
{
    
    public function __toString() {
        return $this->getNombre() ? : '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Serializer\Expose
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="text")
     * @Serializer\Expose
     */
    private $objetivo;
    
    /**
     * @ORM\ManyToOne(targetEntity="NivelPlanMetodologicoComponente", inversedBy="componenteContenidos", cascade={"persist"})
     * @ORM\JoinColumn(name="sub_principio_id", referencedColumnName="id")
     */
    private $componente;

    /**
     * @ORM\OneToMany(targetEntity="TareaActividad", mappedBy="nivelComponenteContenido")
     */
    private $planMetodologicoTareaActividades;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return NivelComponenteContenido
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
     * Set objetivo.
     *
     * @param string $objetivo
     *
     * @return NivelComponenteContenido
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
     * Set componente.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoComponente|null $componente
     *
     * @return NivelComponenteContenido
     */
    public function setComponente(\LogicBundle\Entity\NivelPlanMetodologicoComponente $componente = null)
    {
        $this->componente = $componente;

        return $this;
    }

    /**
     * Get componente.
     *
     * @return \LogicBundle\Entity\NivelPlanMetodologicoComponente|null
     */
    public function getComponente()
    {
        return $this->componente;
    }

    /**
     * Add planMetodologicoTareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade
     *
     * @return NivelComponenteContenido
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
