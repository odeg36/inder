<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * PrincipioPlanMetodologico
 *
 * @ORM\Table(name="principio_plan_metodologico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PrincipioPlanMetodologicoRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PrincipioPlanMetodologico {

    public function __toString() {
        return $this->getNombre() ?: '';
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
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologicoSubPrincipio", mappedBy="principio")
     */
    private $subPrincipios;
    
    /**
     * @ORM\OneToMany(targetEntity="NivelPlanMetodologicoComponente", mappedBy="principio")
     */
    private $componentes;
    
    /**
     * @ORM\OneToMany(targetEntity="FaseComponentePrincipio", mappedBy="principio")
     */
    private $faseComponentes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subPrincipios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->componentes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faseComponentes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PrincipioPlanMetodologico
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
     * Add subPrincipio.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $subPrincipio
     *
     * @return PrincipioPlanMetodologico
     */
    public function addSubPrincipio(\LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $subPrincipio)
    {
        $this->subPrincipios[] = $subPrincipio;

        return $this;
    }

    /**
     * Remove subPrincipio.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $subPrincipio
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubPrincipio(\LogicBundle\Entity\NivelPlanMetodologicoSubPrincipio $subPrincipio)
    {
        return $this->subPrincipios->removeElement($subPrincipio);
    }

    /**
     * Get subPrincipios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubPrincipios()
    {
        return $this->subPrincipios;
    }

    /**
     * Add componente.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoComponente $componente
     *
     * @return PrincipioPlanMetodologico
     */
    public function addComponente(\LogicBundle\Entity\NivelPlanMetodologicoComponente $componente)
    {
        $this->componentes[] = $componente;

        return $this;
    }

    /**
     * Remove componente.
     *
     * @param \LogicBundle\Entity\NivelPlanMetodologicoComponente $componente
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComponente(\LogicBundle\Entity\NivelPlanMetodologicoComponente $componente)
    {
        return $this->componentes->removeElement($componente);
    }

    /**
     * Get componentes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponentes()
    {
        return $this->componentes;
    }

    /**
     * Add faseComponente.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipio $faseComponente
     *
     * @return PrincipioPlanMetodologico
     */
    public function addFaseComponente(\LogicBundle\Entity\FaseComponentePrincipio $faseComponente)
    {
        $this->faseComponentes[] = $faseComponente;

        return $this;
    }

    /**
     * Remove faseComponente.
     *
     * @param \LogicBundle\Entity\FaseComponentePrincipio $faseComponente
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFaseComponente(\LogicBundle\Entity\FaseComponentePrincipio $faseComponente)
    {
        return $this->faseComponentes->removeElement($faseComponente);
    }

    /**
     * Get faseComponentes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaseComponentes()
    {
        return $this->faseComponentes;
    }
}
