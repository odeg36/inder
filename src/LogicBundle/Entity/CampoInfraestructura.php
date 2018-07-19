<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\OpcionCampoInfraestructura as OpcionCampoInfraestructura;
use LogicBundle\Entity\SubcategoriaInfraestructura;


/**
 * CampoInfraestructura
 *
 * @ORM\Table(name="campo_infraestructura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoInfraestructuraRepository")
 */
class CampoInfraestructura
{
    const TIPO_TEXTO = "Texto";
    const TIPO_AREA_TEXTO = "Area de texto";
    const TIPO_FECHA = "Fecha";
    const TIPO_NUMERO = "Numero";
    const TIPO_SELECCION = "Seleccion";
    const TIPO_SELECCION_MULTIPLE = "Seleccion Multiple";
    const TIPO_RADIO_BUTTON = "Radio Button";
    const TIPO_CHECKBOX = "Checkbox";

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_entrada", type="string", length=255)
     */
    private $tipoEntrada;
    
    /**
     * @ORM\OneToMany(targetEntity="OpcionCampoInfraestructura", cascade={ "persist"}, mappedBy="campoInfraestructura", orphanRemoval=true)
     */
    private $opcionCampoInfraestructuras;
    
    /**
     * Many campos have Many subcategorias.
     * @ORM\ManyToMany(targetEntity="SubcategoriaInfraestructura",cascade={ "persist"}, mappedBy="campoInfraestructuras", orphanRemoval=true)
     */
    private $subInfraestructuraCampos;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaInfraestructuraCampo", mappedBy="campoInfraestructura")
     */
    private $escenarioSubCategoriaInfraestructuraCampos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opcionCampoInfraestructuras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subInfraestructuraCampos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CampoInfraestructura
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipoEntrada
     *
     * @param string $tipoEntrada
     *
     * @return CampoInfraestructura
     */
    public function setTipoEntrada($tipoEntrada)
    {
        $this->tipoEntrada = $tipoEntrada;

        return $this;
    }

    /**
     * Get tipoEntrada
     *
     * @return string
     */
    public function getTipoEntrada()
    {
        return $this->tipoEntrada;
    }

    /**
     * Add opcionCampoInfraestructura
     *
     * @param \LogicBundle\Entity\OpcionCampoInfraestructura $opcionCampoInfraestructura
     *
     * @return CampoInfraestructura
     */
    public function addOpcionCampoInfraestructura(\LogicBundle\Entity\OpcionCampoInfraestructura $opcionCampoInfraestructura)
    {
        $this->opcionCampoInfraestructuras[] = $opcionCampoInfraestructura;

        return $this;
    }

    /**
     * Remove opcionCampoInfraestructura
     *
     * @param \LogicBundle\Entity\OpcionCampoInfraestructura $opcionCampoInfraestructura
     */
    public function removeOpcionCampoInfraestructura(\LogicBundle\Entity\OpcionCampoInfraestructura $opcionCampoInfraestructura)
    {
        $this->opcionCampoInfraestructuras->removeElement($opcionCampoInfraestructura);
    }

    /**
     * Get opcionCampoInfraestructuras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpcionCampoInfraestructuras()
    {
        return $this->opcionCampoInfraestructuras;
    }

    /**
     * Add subInfraestructuraCampo
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructuraCampo
     *
     * @return CampoInfraestructura
     */
    public function addSubInfraestructuraCampo(\LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructuraCampo)
    {
        $this->subInfraestructuraCampos[] = $subInfraestructuraCampo;

        return $this;
    }

    /**
     * Remove subInfraestructuraCampo
     *
     * @param \LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructuraCampo
     */
    public function removeSubInfraestructuraCampo(\LogicBundle\Entity\SubcategoriaInfraestructura $subInfraestructuraCampo)
    {
        $this->subInfraestructuraCampos->removeElement($subInfraestructuraCampo);
    }

    /**
     * Get subInfraestructuraCampos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubInfraestructuraCampos()
    {
        return $this->subInfraestructuraCampos;
    }

    /**
     * Add escenarioSubCategoriaInfraestructuraCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaInfraestructuraCampo $escenarioSubCategoriaInfraestructuraCampo
     *
     * @return CampoInfraestructura
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
}
