<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\CampoAmbiental as CampoAmbiental;

/**
 * OpcionCampoAmbiental
 *
 * @ORM\Table(name="opcion_campo_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OpcionCampoAmbientalRepository")
 */
class OpcionCampoAmbiental
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
     * @var int
     */
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CampoAmbiental", inversedBy="opcionesCampo", cascade={ "persist"})
     * @ORM\JoinColumn(name="campo_id", referencedColumnName="id")
     */
    private $campo;

   


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
     * Set campo
     *
     * @param integer $campo
     *
     * @return OpcionCampo
     */
    public function setCampo(CampoAmbiental $campo)
    {
        $this->campo = $campo;

        return $this;
    }

    /**
     * Get campo
     *
     * @return int
     */
    public function getCampo()
    {
        return $this->campo;
    }
}
