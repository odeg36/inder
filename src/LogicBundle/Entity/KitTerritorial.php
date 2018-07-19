<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KitTerritorial
 *
 * @ORM\Table(name="kit_territorial")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\KitTerritorialRepository")
 */
class KitTerritorial
{
    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Comuna", inversedBy="kitTerritorial");
     * @ORM\JoinColumn(name="comuna_id", referencedColumnName="id")
     */
    private $comuna;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\KitTerritorialComponente", mappedBy="kitTerritorial");
     */
    private $componentes;

    private $component;


    /**
     * Constructor
     */
    public function __construct() {
        $this->componentes = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * Set comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return KitTerritorial
     */
    public function setComuna(\LogicBundle\Entity\Comuna $comuna = null)
    {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return \LogicBundle\Entity\Comuna
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * Add componente
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $componente
     *
     * @return KitTerritorial
     */
    public function addComponente(\LogicBundle\Entity\KitTerritorialComponente $componente) {
        $this->componente[] = $componente;

        return $this;
    }

    /**
     * Remove componente
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $componente
     */
    public function removeComponente(\LogicBundle\Entity\KitTerritorialComponente $componente) {
        $this->componente->removeElement($componente);
    }


    /**
     * Get componentes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponentes() {
        return $this->componentes;
    }

    /**
     * Get componente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponent() {
        $componente = "";
        $i = 0;
        if(count($this->getComponentes()) > 0){
            foreach($this->getComponentes() as $component){
                $i++;
                if($component->getComponente()){
                    $componente .= $component->getComponente()->getNombre();
                    if($i < count($this->getComponentes())){
                        $componente .= ", ";
                    }
                }
            }
        }
        return $componente;
    }
}
