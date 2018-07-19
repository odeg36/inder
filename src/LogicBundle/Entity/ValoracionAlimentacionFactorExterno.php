<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValoracionAlimentacionFactorExterno
 *
 * @ORM\Table(name="valoracion_alimentacion_factor_externo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ValoracionAlimentacionFactorExternoRepository")
 */
class ValoracionAlimentacionFactorExterno
{
    
    const REGULARIDAD_AUMENTA = "Aumenta";
    const REGULARIDAD_DISMINUYE = "Disminuye";
    
    public function __toString() {
        return (string)$this->getId()? : '';
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
     * @var bool
     *
     * @ORM\Column(name="existe", type="boolean")
     */
    private $existe;

    /**
     * @var string
     *
     * @ORM\Column(name="regularidad", type="string", length=30, nullable=true)
     */
    private $regularidad;

    /**
     * @ORM\ManyToOne(targetEntity="ValoracionAlimentaria", inversedBy="valoracionAlimentariaFactorExternos")
     * @ORM\JoinColumn(name="valoracion_alimentaria_id", referencedColumnName="id")
     */
    private $valoracionAlimentaria;
    
    /**
     * @ORM\ManyToOne(targetEntity="FactorExterno", inversedBy="factorExternos")
     * @ORM\JoinColumn(name="factor_externo_id", referencedColumnName="id")
     */
    private $factorExterno;
    

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
     * Set existe
     *
     * @param boolean $existe
     *
     * @return ValoracionAlimentacionFactorExterno
     */
    public function setExiste($existe)
    {
        $this->existe = $existe;

        return $this;
    }

    /**
     * Get existe
     *
     * @return boolean
     */
    public function getExiste()
    {
        return $this->existe;
    }

    /**
     * Set regularidad
     *
     * @param string $regularidad
     *
     * @return ValoracionAlimentacionFactorExterno
     */
    public function setRegularidad($regularidad)
    {
        $this->regularidad = $regularidad;

        return $this;
    }

    /**
     * Get regularidad
     *
     * @return string
     */
    public function getRegularidad()
    {
        return $this->regularidad;
    }

    /**
     * Set valoracionAlimentaria
     *
     * @param \LogicBundle\Entity\ValoracionAlimentaria $valoracionAlimentaria
     *
     * @return ValoracionAlimentacionFactorExterno
     */
    public function setValoracionAlimentaria(\LogicBundle\Entity\ValoracionAlimentaria $valoracionAlimentaria = null)
    {
        $this->valoracionAlimentaria = $valoracionAlimentaria;

        return $this;
    }

    /**
     * Get valoracionAlimentaria
     *
     * @return \LogicBundle\Entity\ValoracionAlimentaria
     */
    public function getValoracionAlimentaria()
    {
        return $this->valoracionAlimentaria;
    }

    /**
     * Set factorExterno
     *
     * @param \LogicBundle\Entity\FactorExterno $factorExterno
     *
     * @return ValoracionAlimentacionFactorExterno
     */
    public function setFactorExterno(\LogicBundle\Entity\FactorExterno $factorExterno = null)
    {
        $this->factorExterno = $factorExterno;

        return $this;
    }

    /**
     * Get factorExterno
     *
     * @return \LogicBundle\Entity\FactorExterno
     */
    public function getFactorExterno()
    {
        return $this->factorExterno;
    }
}
