<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenarioCategoriaAmbiental
 *
 * @ORM\Table(name="escenario_categoria_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioCategoriaAmbientalRepository")
 */
class EscenarioCategoriaAmbiental
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
     * @ORM\ManyToOne(targetEntity="EscenarioDeportivo", inversedBy="escenarioCategoriaAmbientales")
     * @ORM\JoinColumn(name="escenario_deportivo_id",referencedColumnName="id")
     */    
    private $escenarioDeportivo;
    
    /**
     * @ORM\ManyToOne(targetEntity="CategoriaAmbiental", inversedBy="escenarioCategoriaAmbientales")
     * @ORM\JoinColumn(name="categoria_ambiental_id", referencedColumnName="id")
     */
    private $categoriaAmbiental;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaAmbiental", mappedBy="escenarioCategoriaAmbiental", cascade={ "persist"}, orphanRemoval=true)
     */
    private $escenarioSubCategoriaAmbientales;
    
    /**
     * Add escenarioSubCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbientale
     *
     * @return EscenarioCategoriaAmbiental
     */
    public function addEscenarioSubCategoriaAmbientale(\LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbientale)
    {
        if($escenarioSubCategoriaAmbientale){
            $escenarioSubCategoriaAmbientale->setEscenarioCategoriaAmbiental($this);
        }
        
        $this->escenarioSubCategoriaAmbientales[] = $escenarioSubCategoriaAmbientale;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->escenarioSubCategoriaAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return EscenarioCategoriaAmbiental
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null)
    {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo()
    {
        return $this->escenarioDeportivo;
    }

    /**
     * Set categoriaAmbiental
     *
     * @param \LogicBundle\Entity\CategoriaAmbiental $categoriaAmbiental
     *
     * @return EscenarioCategoriaAmbiental
     */
    public function setCategoriaAmbiental(\LogicBundle\Entity\CategoriaAmbiental $categoriaAmbiental = null)
    {
        $this->categoriaAmbiental = $categoriaAmbiental;

        return $this;
    }

    /**
     * Get categoriaAmbiental
     *
     * @return \LogicBundle\Entity\CategoriaAmbiental
     */
    public function getCategoriaAmbiental()
    {
        return $this->categoriaAmbiental;
    }

    /**
     * Remove escenarioSubCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbientale
     */
    public function removeEscenarioSubCategoriaAmbientale(\LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubCategoriaAmbientale)
    {
        $this->escenarioSubCategoriaAmbientales->removeElement($escenarioSubCategoriaAmbientale);
    }

    /**
     * Get escenarioSubCategoriaAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioSubCategoriaAmbientales()
    {
        return $this->escenarioSubCategoriaAmbientales;
    }
}
