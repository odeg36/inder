<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KitTerritorialComponente
 *
 * @ORM\Table(name="kit_territorial_componente")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\KitTerritorialComponenteRepository")
 */
class KitTerritorialComponente
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
     * @var int
     *
     * @ORM\Column(name="nivel", type="integer")
     */
    private $nivel;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\KitTerritorial", inversedBy="componentes");
     * @ORM\JoinColumn(name="kitTerritorial_id", referencedColumnName="id")
     */
    private $kitTerritorial;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Componente", inversedBy="kitsTerritoriales");
     * @ORM\JoinColumn(name="componente_id", referencedColumnName="id")
     */
    private $componente;

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
     * Set nivel
     *
     * @param integer $nivel
     *
     * @return KitTerritorialComponente
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return int
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set kitTerritorial
     *
     * @param \LogicBundle\Entity\KitTerritorial $kitTerritorial
     *
     * @return KitTerritorialComponente
     */
    public function setKitTerritorial(\LogicBundle\Entity\KitTerritorial $kitTerritorial = null)
    {
        $this->kitTerritorial = $kitTerritorial;

        return $this;
    }

    /**
     * Get kitTerritorial
     *
     * @return \LogicBundle\Entity\KitTerritorial
     */
    public function getKitTerritorial()
    {
        return $this->kitTerritorial;
    }
    
    /**
     * Set componente
     *
     * @param \LogicBundle\Entity\Componente $componente
     *
     * @return KitTerritorialComponente
     */
    public function setComponente(\LogicBundle\Entity\Componente $componente = null)
    {
        $this->componente = $componente;

        return $this;
    }

    /**
     * Get componente
     *
     * @return \LogicBundle\Entity\Componente
     */
    public function getComponente()
    {
        return $this->componente;
    }
}
