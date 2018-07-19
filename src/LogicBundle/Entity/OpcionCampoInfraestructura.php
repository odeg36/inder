<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\CampoInfraestructura as CampoInfraestructura;

/**
 * OpcionCampoInfraestructura
 *
 * @ORM\Table(name="opcion_campo_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OpcionCampoInfraestructuraRepository")
 */
class OpcionCampoInfraestructura
{
    public function __toString() {
        return $this->opcion ? $this->opcion : '';
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
     * @ORM\Column(name="opcion", type="string", length=255)
     */
    private $opcion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CampoInfraestructura", inversedBy="opcionCampoInfraestructuras", cascade={ "persist"})
     * @ORM\JoinColumn(name="campo_id", referencedColumnName="id")
     */
    private $campoInfraestructura;

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
     * Set opcion
     *
     * @param string $opcion
     *
     * @return OpcionCampo
     */
    public function setOpcion($opcion)
    {
        $this->opcion = $opcion;

        return $this;
    }

    /**
     * Get opcion
     *
     * @return string
     */
    public function getOpcion()
    {
        return $this->opcion;
    }

    /**
     * Set campoInfraestructura
     *
     * @param \LogicBundle\Entity\CampoInfraestructura $campoInfraestructura
     *
     * @return CampoInfraestructura
     */
    public function setCampoInfraestructura(CampoInfraestructura $campoInfraestructura)
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
}
