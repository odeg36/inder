<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Variable
 *
 * @ORM\Table(name="variable")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\VariableRepository")
 */
class Variable
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
     * @ORM\ManyToOne(targetEntity="TestPractico", inversedBy="variables")
     * @ORM\JoinColumn(name="test_practico_id", referencedColumnName="id")
     */
    private $testPractico;
    

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
     * @return Variable
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
     * Set testPractico
     *
     * @param \LogicBundle\Entity\TestPractico $testPractico
     *
     * @return Variable
     */
    public function setTestPractico(\LogicBundle\Entity\TestPractico $testPractico = null)
    {
        $this->testPractico = $testPractico;

        return $this;
    }

    /**
     * Get testPractico
     *
     * @return \LogicBundle\Entity\TestPractico
     */
    public function getTestPractico()
    {
        return $this->testPractico;
    }
}
