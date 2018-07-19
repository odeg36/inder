<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaseEntrenamiento
 *
 * @ORM\Table(name="nivel_independencia_funcional")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelIndependenciaFuncionalRepository")
 */
class NivelIndependenciaFuncional {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Anamnesis", mappedBy="nivelIndependenciaFuncional")
     */
    private $anamnesis

    ;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anamnesis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return FaseEntrenamiento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add anamnesi
     *
     * @param \LogicBundle\Entity\Anamnesis $anamnesi
     *
     * @return FaseEntrenamiento
     */
    public function addAnamnesi(\LogicBundle\Entity\Anamnesis $anamnesi)
    {
        $this->anamnesis[] = $anamnesi;

        return $this;
    }

    /**
     * Remove anamnesi
     *
     * @param \LogicBundle\Entity\Anamnesis $anamnesi
     */
    public function removeAnamnesi(\LogicBundle\Entity\Anamnesis $anamnesi)
    {
        $this->anamnesis->removeElement($anamnesi);
    }

    /**
     * Get anamnesis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnamnesis()
    {
        return $this->anamnesis;
    }
}
