<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * NivelPlanMetodologicoComponente
 *
 * @ORM\Table(name="nivel_plan_metodologico_componente")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelPlanMetodologicoComponenteRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NivelPlanMetodologicoComponente
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
     * @ORM\ManyToOne(targetEntity="PrincipioPlanMetodologico", inversedBy="componentes")
     * @ORM\JoinColumn(name="principio_plan_metodologico_id", referencedColumnName="id")
     */
    private $principio;

    /**
     * @ORM\ManyToOne(targetEntity="NivelPlanMetodologico", inversedBy="nivelPlanMetodologicoComponentes", cascade={"persist"})
     * @ORM\JoinColumn(name="nivel_plan_metodologico_id", referencedColumnName="id")
     */
    private $nivelPlanMetodologico;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelComponenteContenido", mappedBy="componente", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $componenteContenidos;
    
    /**
     * Add componenteContenido.
     *
     * @param \LogicBundle\Entity\NivelComponenteContenido $componenteContenido
     *
     * @return NivelPlanMetodologicoComponente
     */
    public function addComponenteContenido(\LogicBundle\Entity\NivelComponenteContenido $componenteContenido)
    {
        $componenteContenido->setComponente($this);
        
        $this->componenteContenidos[] = $componenteContenido;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->componenteContenidos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return NivelPlanMetodologicoComponente
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
     * @return NivelPlanMetodologicoComponente
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
     * Set principio.
     *
     * @param \LogicBundle\Entity\PrincipioPlanMetodologico|null $principio
     *
     * @return NivelPlanMetodologicoComponente
     */
    public function setPrincipio(\LogicBundle\Entity\PrincipioPlanMetodologico $principio = null)
    {
        $this->principio = $principio;

        return $this;
    }

    /**
     * Get principio.
     *
     * @return \LogicBundle\Entity\PrincipioPlanMetodologico|null
     */
    public function getPrincipio()
    {
        return $this->principio;
    }

    /**
     * Set nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico|null $nivelPlanMetodologico
     *
     * @return NivelPlanMetodologicoComponente
     */
    public function setNivelPlanMetodologico(\LogicBundle\Entity\NivelPlanMetodologico $nivelPlanMetodologico = null)
    {
        $this->nivelPlanMetodologico = $nivelPlanMetodologico;

        return $this;
    }

    /**
     * Get nivelPlanMetodologico.
     *
     * @return \LogicBundle\Entity\NivelPlanMetodologico|null
     */
    public function getNivelPlanMetodologico()
    {
        return $this->nivelPlanMetodologico;
    }

    /**
     * Remove componenteContenido.
     *
     * @param \LogicBundle\Entity\NivelComponenteContenido $componenteContenido
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComponenteContenido(\LogicBundle\Entity\NivelComponenteContenido $componenteContenido)
    {
        return $this->componenteContenidos->removeElement($componenteContenido);
    }

    /**
     * Get componenteContenidos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponenteContenidos()
    {
        return $this->componenteContenidos;
    }
}
