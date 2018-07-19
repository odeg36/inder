<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\OpcionCampoAmbiental as OpcionCampoAmbiental;
use LogicBundle\Entity\subcategoriaAmbiental;


/**
 * CampoAmbiental
 *
 * @ORM\Table(name="campo_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoAmbientalRepository")
 */
class CampoAmbiental
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\OpcionCampoAmbiental", cascade={ "persist"}, mappedBy="campo", orphanRemoval=true)
     */
    private $opcionesCampo;

    /**
     * Many campos have Many subcategorias.
     * @ORM\ManyToMany(targetEntity="SubcategoriaAmbiental",cascade={ "persist"}, mappedBy="campoAmbientales", orphanRemoval=true)
     */
    private $subAmbientalCampos;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaAmbientalCampo", mappedBy="campoAmbiental")
     */
    private $escenarioSubCategoriaAmbientalCampos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opcionesCampo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subAmbientalCampos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CampoAmbiental
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
     * @return CampoAmbiental
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
     * Add opcionesCampo
     *
     * @param \LogicBundle\Entity\OpcionCampoAmbiental $opcionesCampo
     *
     * @return CampoAmbiental
     */
    public function addOpcionesCampo(\LogicBundle\Entity\OpcionCampoAmbiental $opcionesCampo)
    {
        $this->opcionesCampo[] = $opcionesCampo;

        return $this;
    }

    /**
     * Remove opcionesCampo
     *
     * @param \LogicBundle\Entity\OpcionCampoAmbiental $opcionesCampo
     */
    public function removeOpcionesCampo(\LogicBundle\Entity\OpcionCampoAmbiental $opcionesCampo)
    {
        $this->opcionesCampo->removeElement($opcionesCampo);
    }

    /**
     * Get opcionesCampo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpcionesCampo()
    {
        return $this->opcionesCampo;
    }

    /**
     * Add subAmbientalCampo
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subAmbientalCampo
     *
     * @return CampoAmbiental
     */
    public function addSubAmbientalCampo(\LogicBundle\Entity\SubcategoriaAmbiental $subAmbientalCampo)
    {
        $this->subAmbientalCampos[] = $subAmbientalCampo;

        return $this;
    }

    /**
     * Remove subAmbientalCampo
     *
     * @param \LogicBundle\Entity\SubcategoriaAmbiental $subAmbientalCampo
     */
    public function removeSubAmbientalCampo(\LogicBundle\Entity\SubcategoriaAmbiental $subAmbientalCampo)
    {
        $this->subAmbientalCampos->removeElement($subAmbientalCampo);
    }

    /**
     * Get subAmbientalCampos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubAmbientalCampos()
    {
        return $this->subAmbientalCampos;
    }

    /**
     * Add escenarioSubCategoriaAmbientalCampo
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbientalCampo $escenarioSubCategoriaAmbientalCampo
     *
     * @return CampoAmbiental
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
