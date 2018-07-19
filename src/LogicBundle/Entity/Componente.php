<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Componente
 *
 * @ORM\Table(name="componente")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ComponenteRepository")
 */
class Componente
{
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
     * @ORM\Column(name="nombre", type="text")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="string", length=191)
     */
    private $objetivo;

    /**
     * @var int
     *
     * @ORM\Column(name="ponderacion", type="float", nullable=true)
     */
    private $ponderacion;

    /**
     * @var string
     *
     * @ORM\Column(name="logro", type="string", length=191, nullable=true)
     */
    private $logro;


    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=191, nullable=true)
     */
    private $imagen;

    /// relacion con modelo

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Modelo", inversedBy="componente");
     * @ORM\JoinColumn(name="modelo_id", referencedColumnName="id")
     */
    private $modelo;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\KitTerritorialComponente", mappedBy="componente");
     */
    private $kitsTerritoriales;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", inversedBy="componentes")
     * @ORM\JoinColumn(name="planAnualMetodologico_id",referencedColumnName="id")
     */
    private $planAnualMetodologico;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Contenido", mappedBy="componente");
     */
    private $contenidos;
    

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Componente
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
     * Set objetivo
     *
     * @param string $objetivo
     *
     * @return Componente
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo
     *
     * @return string
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Set ponderacion
     *
     * @param integer $ponderacion
     *
     * @return Componente
     */
    public function setPonderacion($ponderacion)
    {
        $this->ponderacion = $ponderacion;

        return $this;
    }

    /**
     * Get ponderacion
     *
     * @return int
     */
    public function getPonderacion()
    {
        return $this->ponderacion;
    }

    /**
     * Set logro
     *
     * @param string $logro
     *
     * @return Componente
     */
    public function setLogro($logro)
    {
        $this->logro = $logro;

        return $this;
    }

    /**
     * Get logro
     *
     * @return string
     */
    public function getLogro()
    {
        return $this->logro;
    }

    /**
     * Set planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     *
     * @return Componente
     */
    public function setPlanAnualMetodologico($planAnualMetodologico)
    {
        $this->planAnualMetodologico = $planAnualMetodologico;

        return $this;
    }

    /**
     * Get planAnualMetodologico
     *
     * @return \LogicBundle\Entity\PlanAnualMetodologico
     */
    public function getPlanAnualMetodologico()
    {
        return $this->planAnualMetodologico;
    }


    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Componente
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }


    /**
     * Constructor
     */
    public function __construct() {
        $this->kitsTerritoriales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contenidos = new \Doctrine\Common\Collections\ArrayCollection();
        
    }

    ///************ relacion con modelo ********////////////

    /**
     * Set modelo
     *
     * @param \LogicBundle\Entity\Modelo $modelo
     *
     * @return Componente
     */
    public function setModelo(\LogicBundle\Entity\Modelo $modelo = null)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return \LogicBundle\Entity\Modelo
     */
    public function getModelo()
    {
        return $this->modelo;
    }


    //////************* relacion con kitTerritorial *******//////////


     /**
     * Add kitTerritorial
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $kitTerritorial
     *
     * @return Componente
     */
    public function addKitTerritorial(\LogicBundle\Entity\KitTerritorialComponente $kitTerritorial) {
        $this->kitTerritorial[] = $kitTerritorial;

        return $this;
    }

    /**
     * Remove kitTerritorial
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $kitTerritorial
     */
    public function removeKitTerritorial(\LogicBundle\Entity\KitTerritorialComponente $kitTerritorial) {
        $this->kitTerritorial->removeElement($kitTerritorial);
    }


    /**
     * Get kitsTerritoriales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKitsTerritoriales() {
        return $this->kitsTerritoriales;
    }

    

    /**
     * Add contenido
     *
     * @param \LogicBundle\Entity\Contenido $contenido
     *
     * @return Componente
     */
    public function addContenido(\LogicBundle\Entity\Contenido $contenido) {
        $this->contenidos[] = $contenido;

        return $this;
    }

    /**
     * Remove contenido
     *
     * @param \LogicBundle\Entity\Contenido $contenido
     */
    public function removeContenido(\LogicBundle\Entity\Contenido $contenido) {
        $this->contenidos->removeElement($contenido);
    }

    /**
     * Get contenidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContenidos() {
        return $this->contenidos;
    }


    /**
     * Add kitsTerritoriale
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $kitsTerritoriale
     *
     * @return Componente
     */
    public function addKitsTerritoriale(\LogicBundle\Entity\KitTerritorialComponente $kitsTerritoriale)
    {
        $this->kitsTerritoriales[] = $kitsTerritoriale;

        return $this;
    }

    /**
     * Remove kitsTerritoriale
     *
     * @param \LogicBundle\Entity\KitTerritorialComponente $kitsTerritoriale
     */
    public function removeKitsTerritoriale(\LogicBundle\Entity\KitTerritorialComponente $kitsTerritoriale)
    {
        $this->kitsTerritoriales->removeElement($kitsTerritoriale);
    }
}
