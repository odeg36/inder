<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TendenciaEscenarioDeportivo
 *
 * @ORM\Table(name="tendencia_escenario_deportivo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TendenciaEscenarioDeportivoRepository")
 */
class TendenciaEscenarioDeportivo
{

    public function __toString() {
        return $this->tendencia->getNombre() ? $this->tendencia->getNombre() : '';
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="tendenciaEscenarioDeportivos")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Tendencia", inversedBy="tendencias")
     * @ORM\JoinColumn(name="tendencia_id", referencedColumnName="id", )
     */
    private $tendencia;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendencia = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return UsuarioEscenarioDeportivo
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;
        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }


    /**
     * Set tendencia
     *
     * @param \LogicBundle\Entity\Tendencia $tendencia
     *
     * @return TendenciaEscenarioDeportivo
     */
    public function setTendencia(\LogicBundle\Entity\Tendencia $tendencia = null) {
        $this->tendencia = $tendencia;
        return $this;
    }

    /**
     * Get tendencia
     *
     * @return \LogicBundle\Entity\Tendencia
     */
    public function getTendencia() {
        return $this->tendencia;
    }
}
