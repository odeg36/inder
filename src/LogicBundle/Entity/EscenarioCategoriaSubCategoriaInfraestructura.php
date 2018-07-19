<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenarioCategoriaSubCategoriaInfraestructura
 *
 * @ORM\Table(name="escenario_categoria_sub_categoria_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioCategoriaSubCategoriaInfraestructuraRepository")
 */
class EscenarioCategoriaSubCategoriaInfraestructura
{
    
    const VALOR_UNO = 1;
    const VALOR_DOS = 2;
    const VALOR_TRES = 3;
    const VALOR_CUATRO = 4;
    const VALOR_CINCO = 5;


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
     * @ORM\Column(name="importancia_relativa", type="integer")
     */
    private $importanciaRelativa = 0;
    
    /**
     * @var int
     *
     * @ORM\Column(name="calificacion_general", type="integer")
     */
    private $calificacionGeneral = 0;

    /**
     * @ORM\ManyToOne(targetEntity="EscenarioCategoriaInfraestructura", inversedBy="escenarioCategoriaSubCategoriaInfraestructuras")
     * @ORM\JoinColumn(name="escenario_categoria_infraestructura_id", referencedColumnName="id")
     */
    private $escenarioCategoriaInfraestructura;
    
    /**
     * @ORM\ManyToOne(targetEntity="SubcategoriaInfraestructura", inversedBy="escenarioCategoriaSubcategorias")
     * @ORM\JoinColumn(name="subcategoria_infraestructura", referencedColumnName="id")
     */
    private $subcategoriaInfraestructura;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaInfraestructuraCampo", mappedBy="escenarioCategoriaSubCategoria")
     */
    private $escenarioSubCategoriaInfraestructuraCampos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->escenarioSubCategoriaInfraestructuraCampos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set escenarioCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura
     *
     * @return EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function setEscenarioCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura = null)
    {
        $this->escenarioCategoriaInfraestructura = $escenarioCategoriaInfraestructura;

        return $this;
    }

    /**
     * Get escenarioCategoriaInfraestructura
     *
     * @return \LogicBundle\Entity\EscenarioCategoriaInfraestructura
     */
    public function getEscenarioCategoriaInfraestructura()
    {
        return $this->escenarioCategoriaInfraestructura;
    }

    /**
     * Set subcategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subcategoriaInfraestructura
     *
     * @return EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function setSubcategoriaInfraestructura(\LogicBundle\Entity\SubcategoriaInfraestructura $subcategoriaInfraestructura = null)
    {
        $this->subcategoriaInfraestructura = $subcategoriaInfraestructura;

        return $this;
    }

    /**
     * Get subcategoriaInfraestructura
     *
     * @return \LogicBundle\Entity\SubcategoriaInfraestructura
     */
    public function getSubcategoriaInfraestructura()
    {
        return $this->subcategoriaInfraestructura;
    }

    /**
     * Add escenarioSubCategoriaInfraestructuraCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo $escenarioSubCategoriaInfraestructuraCampo
     *
     * @return EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function addEscenarioSubCategoriaInfraestructuraCampo(\LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo $escenarioSubCategoriaInfraestructuraCampo)
    {
        $this->escenarioSubCategoriaInfraestructuraCampos[] = $escenarioSubCategoriaInfraestructuraCampo;

        return $this;
    }

    /**
     * Remove escenarioSubCategoriaInfraestructuraCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo $escenarioSubCategoriaInfraestructuraCampo
     */
    public function removeEscenarioSubCategoriaInfraestructuraCampo(\LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo $escenarioSubCategoriaInfraestructuraCampo)
    {
        $this->escenarioSubCategoriaInfraestructuraCampos->removeElement($escenarioSubCategoriaInfraestructuraCampo);
    }

    /**
     * Get escenarioSubCategoriaInfraestructuraCampos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioSubCategoriaInfraestructuraCampos()
    {
        return $this->escenarioSubCategoriaInfraestructuraCampos;
    }

    /**
     * Set importanciaRelativa
     *
     * @param integer $importanciaRelativa
     *
     * @return EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function setImportanciaRelativa($importanciaRelativa)
    {
        $this->importanciaRelativa = $importanciaRelativa;

        return $this;
    }

    /**
     * Get importanciaRelativa
     *
     * @return integer
     */
    public function getImportanciaRelativa()
    {
        return $this->importanciaRelativa;
    }

    /**
     * Set calificacionGeneral
     *
     * @param integer $calificacionGeneral
     *
     * @return EscenarioCategoriaSubCategoriaInfraestructura
     */
    public function setCalificacionGeneral($calificacionGeneral)
    {
        $this->calificacionGeneral = $calificacionGeneral;

        return $this;
    }

    /**
     * Get calificacionGeneral
     *
     * @return integer
     */
    public function getCalificacionGeneral()
    {
        return $this->calificacionGeneral;
    }
}
