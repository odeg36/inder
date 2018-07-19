<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenarioSubCategoriaAmbiental
 *
 * @ORM\Table(name="escenario_sub_categoria_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioSubCategoriaAmbientalRepository")
 */
class EscenarioSubCategoriaAmbiental
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
     * @ORM\ManyToOne(targetEntity="EscenarioCategoriaAmbiental", inversedBy="escenarioSubCategoriaAmbientales")
     * @ORM\JoinColumn(name="escenario_categoria_ambiental_id", referencedColumnName="id")
     */
    private $escenarioCategoriaAmbiental;

    /**
     * @ORM\ManyToOne(targetEntity="SubcategoriaAmbiental", inversedBy="escenarioSubcategoriaAmbientales")
     * @ORM\JoinColumn(name="subcategoria_ambiental_id", referencedColumnName="id")
     */
    private $subcategoriaAmbiental;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaAmbientalCampo", mappedBy="escenarioSubCategoriaAmbiental")
     */
    private $escenarioSubCategoriaAmbientalCampos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->escenarioSubCategoriaAmbientalCampos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set escenarioCategoriaAmbiental
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbiental
     *
     * @return EscenarioSubCategoriaAmbiental
     */
    public function setEscenarioCategoriaAmbiental(\LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbiental = null)
    {
        $this->escenarioCategoriaAmbiental = $escenarioCategoriaAmbiental;

        return $this;
    }

    /**
     * Get escenarioCategoriaAmbiental
     *
     * @return \LogicBundle\Entity\EscenarioCategoriaAmbiental
     */
    public function getEscenarioCategoriaAmbiental()
    {
        return $this->escenarioCategoriaAmbiental;
    }

    /**
     * Set subcategoriaAmbiental
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbiental
     *
     * @return EscenarioSubCategoriaAmbiental
     */
    public function setSubcategoriaAmbiental(\LogicBundle\Entity\SubcategoriaAmbiental $subcategoriaAmbiental = null)
    {
        $this->subcategoriaAmbiental = $subcategoriaAmbiental;

        return $this;
    }

    /**
     * Get subcategoriaAmbiental
     *
     * @return \LogicBundle\Entity\SubcategoriaAmbiental
     */
    public function getSubcategoriaAmbiental()
    {
        return $this->subcategoriaAmbiental;
    }

    /**
     * Add escenarioSubCategoriaAmbientalCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo $escenarioSubCategoriaAmbientalCampo
     *
     * @return EscenarioSubCategoriaAmbiental
     */
    public function addEscenarioSubCategoriaAmbientalCampo(\LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo $escenarioSubCategoriaAmbientalCampo)
    {
        $this->escenarioSubCategoriaAmbientalCampos[] = $escenarioSubCategoriaAmbientalCampo;

        return $this;
    }

    /**
     * Remove escenarioSubCategoriaAmbientalCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo $escenarioSubCategoriaAmbientalCampo
     */
    public function removeEscenarioSubCategoriaAmbientalCampo(\LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo $escenarioSubCategoriaAmbientalCampo)
    {
        $this->escenarioSubCategoriaAmbientalCampos->removeElement($escenarioSubCategoriaAmbientalCampo);
    }

    /**
     * Get escenarioSubCategoriaAmbientalCampos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioSubCategoriaAmbientalCampos()
    {
        return $this->escenarioSubCategoriaAmbientalCampos;
    }
}
