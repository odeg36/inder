<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestPractico
 *
 * @ORM\Table(name="test_practico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TestPracticoRepository")
 */
class TestPractico
{
    public function __toString() {
        return $this->getNombre() ? : '';
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
     * @ORM\ManyToOne(targetEntity="Entrevista", inversedBy="testPracticos")
     * @ORM\JoinColumn(name="entrevista_id", referencedColumnName="id")
     */
    private $entrevista;
    
    /**
     * @ORM\OneToMany(targetEntity="Variable", mappedBy="testPractico", cascade={"persist"})
     */
    private $variables;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->variables = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TestPractico
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
     * Set entrevista
     *
     * @param \LogicBundle\Entity\Entrevista $entrevista
     *
     * @return TestPractico
     */
    public function setEntrevista(\LogicBundle\Entity\Entrevista $entrevista = null)
    {
        $this->entrevista = $entrevista;

        return $this;
    }

    /**
     * Get entrevista
     *
     * @return \LogicBundle\Entity\Entrevista
     */
    public function getEntrevista()
    {
        return $this->entrevista;
    }

    /**
     * Add variable
     *
     * @param \LogicBundle\Entity\Variable $variable
     *
     * @return TestPractico
     */
    public function addVariable(\LogicBundle\Entity\Variable $variable)
    {
        $this->variables[] = $variable;

        return $this;
    }

    /**
     * Remove variable
     *
     * @param \LogicBundle\Entity\Variable $variable
     */
    public function removeVariable(\LogicBundle\Entity\Variable $variable)
    {
        $this->variables->removeElement($variable);
    }

    /**
     * Get variables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
