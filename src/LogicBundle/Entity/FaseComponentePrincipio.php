<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * FaseComponentePrincipio
 *
 * @ORM\Table(name="fase_componente_principio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaseComponentePrincipioRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class FaseComponentePrincipio
{
    public function __toString() {
        return $this->getPrincipio()->getNombre() ? : '';
    }
    
    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("nombre")
     */
    public function nombrePrincipio() {
        if($this->getPrincipio()){
            return $this->getPrincipio()->getNombre();
        }
        
        return '';
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
     * @ORM\Column(name="objetivo", type="text")
     * @Serializer\Expose
     */
    private $objetivo;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivoSubPrincipio", type="text")
     * @Serializer\Expose
     */
    private $objetivoSubPrincipio;

    /**
     * @ORM\ManyToOne(targetEntity="PrincipioPlanMetodologico", inversedBy="faseComponentes")
     * @ORM\JoinColumn(name="fase_componentes_id", referencedColumnName="id")
     */
    private $principio;
    
    /**
     * @ORM\ManyToOne(targetEntity="NivelFaseComponente", inversedBy="faseComponentePrincipios", cascade={"persist"})
     * @ORM\JoinColumn(name="fase_componente_id", referencedColumnName="id")
     */
    private $faseComponente;
    
    /**
     * @ORM\OneToMany(targetEntity="FaseComponentePrincipioSubPrincipio", mappedBy="faseComponentePrincipio", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $faseComponentePrincipioSubPrincipios;
    
    /**
     * Add faseComponentePrincipioSubPrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipioSubPrincipio $faseComponentePrincipioSubPrincipio
     *
     * @return FaseComponentePrincipio
     */
    public function addFaseComponentePrincipioSubPrincipio(\LogicBundle\Entity\FaseComponentePrincipioSubPrincipio $faseComponentePrincipioSubPrincipio = null)
    {
        if($faseComponentePrincipioSubPrincipio){
            $faseComponentePrincipioSubPrincipio->setFaseComponentePrincipio($this);
        }
        
        $this->faseComponentePrincipioSubPrincipios[] = $faseComponentePrincipioSubPrincipio;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->faseComponentePrincipioSubPrincipios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FaseComponentePrincipio
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
     * Set objetivoSubPrincipio.
     *
     * @param string $objetivoSubPrincipio
     *
     * @return FaseComponentePrincipio
     */
    public function setObjetivoSubPrincipio($objetivoSubPrincipio)
    {
        $this->objetivoSubPrincipio = $objetivoSubPrincipio;

        return $this;
    }

    /**
     * Get objetivoSubPrincipio.
     *
     * @return string
     */
    public function getObjetivoSubPrincipio()
    {
        return $this->objetivoSubPrincipio;
    }

    /**
     * Set principio.
     *
     * @param \LogicBundle\Entity\PrincipioPlanMetodologico|null $principio
     *
     * @return FaseComponentePrincipio
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
     * Set faseComponente.
     *
     * @param \LogicBundle\Entity\NivelFaseComponente|null $faseComponente
     *
     * @return FaseComponentePrincipio
     */
    public function setFaseComponente(\LogicBundle\Entity\NivelFaseComponente $faseComponente = null)
    {
        $this->faseComponente = $faseComponente;

        return $this;
    }

    /**
     * Get faseComponente.
     *
     * @return \LogicBundle\Entity\NivelFaseComponente|null
     */
    public function getFaseComponente()
    {
        return $this->faseComponente;
    }

    /**
     * Remove faseComponentePrincipioSubPrincipio.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipioSubPrincipio $faseComponentePrincipioSubPrincipio
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFaseComponentePrincipioSubPrincipio(\LogicBundle\Entity\FaseComponentePrincipioSubPrincipio $faseComponentePrincipioSubPrincipio)
    {
        return $this->faseComponentePrincipioSubPrincipios->removeElement($faseComponentePrincipioSubPrincipio);
    }

    /**
     * Get faseComponentePrincipioSubPrincipios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaseComponentePrincipioSubPrincipios()
    {
        return $this->faseComponentePrincipioSubPrincipios;
    }
}
