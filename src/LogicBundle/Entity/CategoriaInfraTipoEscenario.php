<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaInfraTipoEscenario
 *
 * @ORM\Table(name="categoria_infra_tipo_escenario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaInfraTipoEscenarioRepository")
 */
class CategoriaInfraTipoEscenario
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


     /**
     * @ORM\ManyToOne(targetEntity="CategoriaInfraestructura")
     * @ORM\JoinColumn(name="categoria_infraestructura", referencedColumnName="id")
     */
    private $categoriaInfraestructura;


    /**
     * @ORM\ManyToOne(targetEntity="TipoEscenario")
     * @ORM\JoinColumn(name="tipo_escenario", referencedColumnName="id")
     */
    private $tipoEscenario;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set categoriaInfraestructura
     *
     * @param \LogicBundle\Entity\CategoriaInfraestructura $categoriaInfraestructura
     *
     * @return CategoriaInfraTipoEscenario
     */
    public function setCategoriaInfraestructura(\LogicBundle\Entity\CategoriaInfraestructura $categoriaInfraestructura = null)
    {
        $this->categoriaInfraestructura = $categoriaInfraestructura;

        return $this;
    }

    /**
     * Get categoriaInfraestructura
     *
     * @return \LogicBundle\Entity\CategoriaInfraestructura
     */
    public function getCategoriaInfraestructura()
    {
        return $this->categoriaInfraestructura;
    }




    /**
     * Set tipoEscenario
     *
     * @param \LogicBundle\Entity\TipoEscenario $tipoEscenario
     *
     * @return CategoriaInfraTipoEscenario
     */
    public function setTipoEscenario(\LogicBundle\Entity\TipoEscenario $tipoEscenario = null)
    {
        $this->tipoEscenario = $tipoEscenario;

        return $this;
    }


    /**
     * Get tipoEscenario
     *
     * @return \LogicBundle\Entity\TipoEscenario
     */
    public function getTipoEscenario()
    {
        return $this->tipoEscenario;
    }

}
