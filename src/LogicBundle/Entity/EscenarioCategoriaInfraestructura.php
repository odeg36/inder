<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenarioCategoriaInfraestructura
 *
 * @ORM\Table(name="escenario_categoria_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioCategoriaInfraestructuraRepository")
 */
class EscenarioCategoriaInfraestructura
{

    public function __toString() {
        return $this->categoriaInfraestructura->getNombre() ? $this->categoriaInfraestructura->getNombre() : '';
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
     * @var int
     *
     * @ORM\Column(name="importancia_relativa", type="integer", nullable=true)
     */
    private $importanciaRelativa;
    
    /**
     * @var int
     *
     * @ORM\Column(name="calificacion_general", type="integer", nullable=true)
     */
    private $calificacionGeneral;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="escenarioCategoriaInfraestructuras")
     * @ORM\JoinColumn(name="escenario_deportivo_id",referencedColumnName="id")
     */    
    private $escenarioDeportivo;    

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaInfraestructura", inversedBy="escenarioCategoriaInfraestructura")
     * @ORM\JoinColumn(name="categoria_infraestructura", referencedColumnName="id")
     */
    private $categoriaInfraestructura;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaSubCategoriaInfraestructura", mappedBy="escenarioCategoriaInfraestructura", cascade={ "persist"}, orphanRemoval=true)
     */
    private $escenarioCategoriaSubCategoriaInfraestructuras;
   
    /**
     * Add escenarioCategoriaSubCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoriaInfraestructura
     *
     * @return EscenarioCategoriaInfraestructura
     */
    public function addEscenarioCategoriaSubCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoriaInfraestructura = null)
    {
        if($escenarioCategoriaSubCategoriaInfraestructura){
            $escenarioCategoriaSubCategoriaInfraestructura->setEscenarioCategoriaInfraestructura($this);
        }
        
        $this->escenarioCategoriaSubCategoriaInfraestructuras[] = $escenarioCategoriaSubCategoriaInfraestructura;

        return $this;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->escenarioCategoriaSubCategoriaInfraestructuras = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set importanciaRelativa
     *
     * @param integer $importanciaRelativa
     *
     * @return EscenarioCategoriaInfraestructura
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
     * @return EscenarioCategoriaInfraestructura
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

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return EscenarioCategoriaInfraestructura
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
     * Set categoriaInfraestructura
     *
     * @param \LogicBundle\Entity\CategoriaInfraestructura $categoriaInfraestructura
     *
     * @return EscenarioCategoriaInfraestructura
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
     * Remove escenarioCategoriaSubCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoriaInfraestructura
     */
    public function removeEscenarioCategoriaSubCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaSubCategoriaInfraestructura $escenarioCategoriaSubCategoriaInfraestructura)
    {
        $this->escenarioCategoriaSubCategoriaInfraestructuras->removeElement($escenarioCategoriaSubCategoriaInfraestructura);
    }

    /**
     * Get escenarioCategoriaSubCategoriaInfraestructuras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaSubCategoriaInfraestructuras()
    {
        return $this->escenarioCategoriaSubCategoriaInfraestructuras;
    }
}
