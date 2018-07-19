<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parentezco
 *
 * @ORM\Table(name="parentezco")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ParentezcoRepository")
 */
class Parentezco {
    
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Familiar", mappedBy="parentezco", cascade={"persist"})
     */
    private $familiares;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Parentezco
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->familiares = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add familiare
     *
     * @param \LogicBundle\Entity\Familiar $familiare
     *
     * @return Parentezco
     */
    public function addFamiliare(\LogicBundle\Entity\Familiar $familiare)
    {
        $this->familiares[] = $familiare;

        return $this;
    }

    /**
     * Remove familiare
     *
     * @param \LogicBundle\Entity\Familiar $familiare
     */
    public function removeFamiliare(\LogicBundle\Entity\Familiar $familiare)
    {
        $this->familiares->removeElement($familiare);
    }

    /**
     * Get familiares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFamiliares()
    {
        return $this->familiares;
    }
}
