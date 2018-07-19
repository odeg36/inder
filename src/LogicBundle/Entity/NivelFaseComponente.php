<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * NivelFaseComponente
 *
 * @ORM\Table(name="nivel_fase_componente")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelFaseComponenteRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NivelFaseComponente
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
     * @ORM\ManyToOne(targetEntity="NivelPlanMetodologico", inversedBy="nivelPlanMetodologicoFaseComponentes", cascade={"persist"})
     * @ORM\JoinColumn(name="nivel_plan_metodologico_id", referencedColumnName="id")
     */
    private $nivelPlanMetodologico;
    
    /**
     * @ORM\OneToMany(targetEntity="FaseComponentePrincipio", mappedBy="faseComponente", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $faseComponentePrincipios;

    /**
     * Add faseComponentePrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipio $faseComponentePrincipio
     *
     * @return NivelFaseComponente
     */
    public function addFaseComponentePrincipio(\LogicBundle\Entity\FaseComponentePrincipio $faseComponentePrincipio)
    {
        $faseComponentePrincipio->setFaseComponente($this);
        
        $this->faseComponentePrincipios[] = $faseComponentePrincipio;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->faseComponentePrincipios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return NivelFaseComponente
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
     * Set nivelPlanMetodologico.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologico|null $nivelPlanMetodologico
     *
     * @return NivelFaseComponente
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
     * Remove faseComponentePrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipio $faseComponentePrincipio
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFaseComponentePrincipio(\LogicBundle\Entity\FaseComponentePrincipio $faseComponentePrincipio)
    {
        return $this->faseComponentePrincipios->removeElement($faseComponentePrincipio);
    }

    /**
     * Get faseComponentePrincipios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaseComponentePrincipios()
    {
        return $this->faseComponentePrincipios;
    }
}
