<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampoInfraestructuraEscenario
 *
 * @ORM\Table(name="escenario_subcategoria_campo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioSubCategoriaInfraestructuraCampoRepository")
 */
class EscenarioSubCategoriaInfraestructuraCampo
{

    public function __toString() {
        return $this->id ? $this->id : '';
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
     * @ORM\Column(name="valor", type="text", nullable=true)
     */
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="CampoInfraestructura", inversedBy="escenarioSubCategoriaInfraestructuraCampos")
     * @ORM\JoinColumn(name="campo_infraestructura_id", referencedColumnName="id" )
     */
    private $campoInfraestructura;
    
    /**
     * @ORM\ManyToOne(targetEntity="EscenarioCategoriaSubCategoriaInfraestructura", inversedBy="escenarioSubCategoriaInfraestructuraCampos")
     * @ORM\JoinColumn(name="escenario_categoria_subCategoria", referencedColumnName="id")
     */
    private $escenarioCategoriaSubCategoria;


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
     * Set valor
     *
     * @param string $valor
     *
     * @return EscenarioSubCategoriaInfraestructuraCampo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set campoInfraestructura
     *
     * @param \LogicBundle\Entity\CampoInfraestructura $campoInfraestructura
     *
     * @return EscenarioSubCategoriaInfraestructuraCampo
     */
    public function setCampoInfraestructura(\LogicBundle\Entity\CampoInfraestructura $campoInfraestructura = null)
    {
        $this->campoInfraestructura = $campoInfraestructura;

        return $this;
    }

    /**
     * Get campoInfraestructura
     *
     * @return \LogicBundle\Entity\CampoInfraestructura
     */
    public function getCampoInfraestructura()
    {
        return $this->campoInfraestructura;
    }

    /**
     * Set escenarioCategoriaSubCategoria
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoria
     *
     * @return EscenarioSubCategoriaInfraestructuraCampo
     */
    public function setEscenarioCategoriaSubCategoria(\LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoria = null)
    {
        $this->escenarioCategoriaSubCategoria = $escenarioCategoriaSubCategoria;

        return $this;
    }

    /**
     * Get escenarioCategoriaSubCategoria
     *
     * @return \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function getEscenarioCategoriaSubCategoria()
    {
        return $this->escenarioCategoriaSubCategoria;
    }
}
