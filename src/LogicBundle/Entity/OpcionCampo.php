<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\Campo as Campo;

/**
 * OpcionCampo
 *
 * @ORM\Table(name="opcion_campo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OpcionCampoRepository")
 */
class OpcionCampo
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
     * @ORM\Column(name="opcion", type="string", length=255)
     */
    private $opcion;

    /**
     * @var int
     */
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Campo", inversedBy="opcionesCampo")
     * @ORM\JoinColumn(name="campo_id", referencedColumnName="id")
     */
    private $campo;

    

    public function __construct() {
        $this->campo = new \Doctrine\Common\Collections\ArrayCollection();
        
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
    public function setCampo(Campo $campo)
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
