<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PruebaControlFisioterapia
 *
 * @ORM\Table(name="prueba_control_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaControlFisioterapiaRepository")
 */
class PruebaControlFisioterapia {
    
    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @ORM\ManyToOne(targetEntity="CategoriaTestControlFisioterapia", inversedBy="pruebaControlFisioterapias")
     * @ORM\JoinColumn(name="categoria_test_control_id", referencedColumnName="id")
     */
    private $categoriaTestControlFisioterapia;

    /**
     * @ORM\ManyToOne(targetEntity="TestControlFisioterapia", inversedBy="pruebaTestControlFisioterapias")
     * @ORM\JoinColumn(name="test_control_fisioterapia_id", referencedColumnName="id")
     */
    private $testControlFisioterapia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="primer_test", type="string", length=255, nullable=true)
     */
    private $primerTest;
    
    /**
     * @var string
     *
     * @ORM\Column(name="segundo_test", type="string", length=255, nullable=true)
     */
    private $segundoTest;
    

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
     * Set primerTest
     *
     * @param string $primerTest
     *
     * @return PruebaControlFisioterapia
     */
    public function setPrimerTest($primerTest)
    {
        $this->primerTest = $primerTest;

        return $this;
    }

    /**
     * Get primerTest
     *
     * @return string
     */
    public function getPrimerTest()
    {
        return $this->primerTest;
    }

    /**
     * Set segundoTest
     *
     * @param string $segundoTest
     *
     * @return PruebaControlFisioterapia
     */
    public function setSegundoTest($segundoTest)
    {
        $this->segundoTest = $segundoTest;

        return $this;
    }

    /**
     * Get segundoTest
     *
     * @return string
     */
    public function getSegundoTest()
    {
        return $this->segundoTest;
    }

    /**
     * Set categoriaTestControlFisioterapia
     *
     * @param \LogicBundle\Entity\CategoriaTestControlFisioterapia $categoriaTestControlFisioterapia
     *
     * @return PruebaControlFisioterapia
     */
    public function setCategoriaTestControlFisioterapia(\LogicBundle\Entity\CategoriaTestControlFisioterapia $categoriaTestControlFisioterapia = null)
    {
        $this->categoriaTestControlFisioterapia = $categoriaTestControlFisioterapia;

        return $this;
    }

    /**
     * Get categoriaTestControlFisioterapia
     *
     * @return \LogicBundle\Entity\CategoriaTestControlFisioterapia
     */
    public function getCategoriaTestControlFisioterapia()
    {
        return $this->categoriaTestControlFisioterapia;
    }

    /**
     * Set testControlFisioterapia
     *
     * @param \LogicBundle\Entity\TestControlFisioterapia $testControlFisioterapia
     *
     * @return PruebaControlFisioterapia
     */
    public function setTestControlFisioterapia(\LogicBundle\Entity\TestControlFisioterapia $testControlFisioterapia = null)
    {
        $this->testControlFisioterapia = $testControlFisioterapia;

        return $this;
    }

    /**
     * Get testControlFisioterapia
     *
     * @return \LogicBundle\Entity\TestControlFisioterapia
     */
    public function getTestControlFisioterapia()
    {
        return $this->testControlFisioterapia;
    }
}
