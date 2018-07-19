<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenarioSubCategoriaAmbientalCampo
 *
 * @ORM\Table(name="escenario_sub_categoria_ambiental_campo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioSubCategoriaAmbientalCampoRepository")
 */
class EscenarioSubCategoriaAmbientalCampo
{
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
     * @ORM\Column(name="valor", type="text")
     */
    private $valor;
    
    /**
     * @ORM\ManyToOne(targetEntity="CampoAmbiental", inversedBy="escenarioSubCategoriaAmbientalCampos")
     * @ORM\JoinColumn(name="campo_Ambiental_id", referencedColumnName="id" )
     */
    private $campoAmbiental;

    /**
     * @ORM\ManyToOne(targetEntity="EscenarioSubCategoriaAmbiental", inversedBy="escenarioSubCategoriaAmbientalCampos")
     * @ORM\JoinColumn(name="escenario_subcategoria_ambiental_id", referencedColumnName="id")
     */
    private $escenarioSubCategoriaAmbiental;

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
     * @return EscenarioSubCategoriaAmbientalCampo
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
     * Set campoAmbiental
     *
     * @param \LogicBundle\Entity\CampoAmbiental $campoAmbiental
     *
     * @return EscenarioSubCategoriaAmbientalCampo
     */
    public function setCampoAmbiental(\LogicBundle\Entity\CampoAmbiental $campoAmbiental = null)
    {
        $this->campoAmbiental = $campoAmbiental;

        return $this;
    }

    /**
     * Get campoAmbiental
     *
     * @return \LogicBundle\Entity\CampoAmbiental
     */
    public function getCampoAmbiental()
    {
        return $this->campoAmbiental;
    }

    /**
     * Set escenarioSubCategoriaAmbiental
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbiental
     *
     * @return EscenarioSubCategoriaAmbientalCampo
     */
    public function setEscenarioSubCategoriaAmbiental(\LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbiental = null)
    {
        $this->escenarioSubCategoriaAmbiental = $escenarioSubCategoriaAmbiental;

        return $this;
    }

    /**
     * Get escenarioSubCategoriaAmbiental
     *
     * @return \LogicBundle\Entity\EscenarioSubCategoriaAmbiental
     */
    public function getEscenarioSubCategoriaAmbiental()
    {
        return $this->escenarioSubCategoriaAmbiental;
    }
}
