<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * NivelPlanMetodologicoSubPrincipio
 *
 * @ORM\Table(name="nivel_plan_metodologico_sub_principio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelPlanMetodologicoSubPrincipioRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NivelPlanMetodologicoSubPrincipio
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
     * @ORM\ManyToOne(targetEntity="PrincipioPlanMetodologico", inversedBy="subPrincipios")
     * @ORM\JoinColumn(name="principio_plan_metodologico_id", referencedColumnName="id")
     */
    private $principio;

    /**
     * @ORM\ManyToOne(targetEntity="NivelPlanMetodologico", inversedBy="nivelPlanMetodologicoSubPrincipios", cascade={"persist"})
     * @ORM\JoinColumn(name="nivel_plan_metodologico_id", referencedColumnName="id")
     */
    private $nivelPlanMetodologico;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelSubPrincipioObjetivo", mappedBy="subPrincipio", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $subPrincipioObjectivos;
    
    /**
     * Add subPrincipioObjectivo.
     *
     * @param \LogicBundle\Entity\NivelSubPrincipioObjetivo $subPrincipioObjectivo
     *
     * @return NivelPlanMetodologicoSubPrincipio
     */
    public function addSubPrincipioObjectivo(\LogicBundle\Entity\NivelSubPrincipioObjetivo $subPrincipioObjectivo)
    {
        $subPrincipioObjectivo->setSubPrincipio($this);
        
        $this->subPrincipioObjectivos[] = $subPrincipioObjectivo;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subPrincipioObjectivos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return NivelPlanMetodologicoSubPrincipio
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
     * Set principio.
     *
     * @param \LogicBundle\Entity\PrincipioPlanMetodologico|null $principio
     *
     * @return NivelPlanMetodologicoSubPrincipio
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
     * @return NivelPlanMetodologicoSubPrincipio
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
     * Remove subPrincipioObjectivo.
     *
     * @param \LogicBundle\Entity\NivelSubPrincipioObjetivo $subPrincipioObjectivo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubPrincipioObjectivo(\LogicBundle\Entity\NivelSubPrincipioObjetivo $subPrincipioObjectivo)
    {
        return $this->subPrincipioObjectivos->removeElement($subPrincipioObjectivo);
    }

    /**
     * Get subPrincipioObjectivos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubPrincipioObjectivos()
    {
        return $this->subPrincipioObjectivos;
    }
}
