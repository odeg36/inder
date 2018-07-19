<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * FaseComponentePrincipioSubPrincipio
 *
 * @ORM\Table(name="fase_componente_principio_sub_principio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaseComponentePrincipioSubPrincipioRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class FaseComponentePrincipioSubPrincipio
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
     * @ORM\ManyToOne(targetEntity="FaseComponentePrincipio", inversedBy="faseComponentePrincipioSubPrincipios", cascade={"persist"})
     * @ORM\JoinColumn(name="fase_componentes_principio_id", referencedColumnName="id")
     */
    private $faseComponentePrincipio;
    
    /**
     * @ORM\OneToMany(targetEntity="TareaActividad", mappedBy="faseComponentePrincipioSubPrincipio")
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
     * @return FaseComponentePrincipioSubPrincipio
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
     * Set faseComponentePrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipio|null $faseComponentePrincipio
     *
     * @return FaseComponentePrincipioSubPrincipio
     */
    public function setFaseComponentePrincipio(\LogicBundle\Entity\FaseComponentePrincipio $faseComponentePrincipio = null)
    {
        $this->faseComponentePrincipio = $faseComponentePrincipio;

        return $this;
    }

    /**
     * Get faseComponentePrincipio.
     *
     * @return \LogicBundle\Entity\FaseComponentePrincipio|null
     */
    public function getFaseComponentePrincipio()
    {
        return $this->faseComponentePrincipio;
    }

    /**
     * Add planMetodologicoTareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $planMetodologicoTareaActividade
     *
     * @return FaseComponentePrincipioSubPrincipio
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
