<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ergogenica
 *
 * @ORM\Table(name="ergogenica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ErgogenicaRepository")
 */
class Ergogenica
{
    const TIPO_SUPLEMENTACION = "Suplementacion de Macro y macronutrientes";
    const TIPO_AGUA = "Agua";
    const TIPO_ESTIMULANTES = "Estimulantes";
    const TIPO_NARCOTICO = "Narcoticos";
    const TIPO_ESTEROIDES = "Esteroides anabolicos";
    const TIPO_BETA = "Beta bloqueadores";
    const TIPO_DIURETICOS = "Diureticos";
    const TIPO_HORMONAS = "Hormonas";
    const TIPO_DOPAJE = "Dopaje Sanguineo";
    const TIPO_HIPNOSIS = "Hipnosis";
    const TIPO_TECNICAS = "Tecnicas de relajamiento";
    const TIPO_ROPA = "Ropa";
    const TIPO_AMBIENTE = "Ambiente";
    const TIPO_IMPLEMNTOS = "Implementos";
    const TIPO_OTROS = "otros";
    
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
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
     * @ORM\Column(name="tipo", type="string", length=50, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="ConsultaNutricion", inversedBy="ergogenicas")
     * @ORM\JoinColumn(name="nutiricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\ManyToOne(targetEntity="AyudaErgogenica", inversedBy="ergogenicas")
     * @ORM\JoinColumn(name="ayuda_ergogenica_id", referencedColumnName="id")
     */
    private $ayudaErgogenica;

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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Ergogenica
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Ergogenica
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return Ergogenica
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }

    /**
     * Set ayudaErgogenica
     *
     * @param \LogicBundle\Entity\AyudaErgogenica $ayudaErgogenica
     *
     * @return Ergogenica
     */
    public function setAyudaErgogenica(\LogicBundle\Entity\AyudaErgogenica $ayudaErgogenica = null)
    {
        $this->ayudaErgogenica = $ayudaErgogenica;

        return $this;
    }

    /**
     * Get ayudaErgogenica
     *
     * @return \LogicBundle\Entity\AyudaErgogenica
     */
    public function getAyudaErgogenica()
    {
        return $this->ayudaErgogenica;
    }
}
