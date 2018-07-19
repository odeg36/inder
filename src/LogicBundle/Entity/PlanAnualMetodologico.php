<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanAnualMetodologico
 *
 * @ORM\Table(name="plan_anual_metodologico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PlanAnualMetodologicoRepository")
 */
class PlanAnualMetodologico
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ponderacionComponentes", type="boolean", nullable=true)
     */
    private $ponderacionComponentes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ponderacionContenidos", type="boolean", nullable=true)
     */
    private $ponderacionContenidos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Enfoque", inversedBy="planesAnualesMetodologicos")
     * @ORM\JoinColumn(name="enfoque_id",referencedColumnName="id")
     */
    private $enfoque;

    /**
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\Nivel", inversedBy="planesAnualesMetodologicos")
     * @ORM\JoinColumn(name="nivel_id",referencedColumnName="id")
     */
    private $niveles;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Componente", cascade={"persist"}, mappedBy="planAnualMetodologico")
     */
    private $componentes;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Clasificacion", inversedBy="planesAnualesMetodologicos")
     * @ORM\JoinColumn(name="clasificacion_id",referencedColumnName="id")
     */
    private $clasificacion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina", inversedBy="planesAnualesMetodologicos")
     * @ORM\JoinColumn(name="disciplina_id",referencedColumnName="id")
     */
    private $disciplina;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="planAnualMetodologico")
     */
    private $ofertas;

    private $estrategias;


    /**
     * Constructor
     */
    public function __construct() {
        $this->componentes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estrategias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->niveles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PlanAnualMetodologico
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
     * Set ponderacionComponentes
     *
     * @param boolean $ponderacionComponentes
     *
     * @return PlanAnualMetodologico
     */
    public function setPonderacionComponentes($ponderacionComponentes)
    {
        $this->ponderacionComponentes = $ponderacionComponentes;

        return $this;
    }

    /**
     * Get ponderacionComponentes
     *
     * @return boolean
     */
    public function getPonderacionComponentes()
    {
        return $this->ponderacionComponentes;
    }

    /**
     * Set ponderacionContenidos
     *
     * @param boolean $ponderacionContenidos
     *
     * @return PlanAnualMetodologico
     */
    public function setPonderacionContenidos($ponderacionContenidos)
    {
        $this->ponderacionContenidos = $ponderacionContenidos;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return PlanAnualMetodologico
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get ponderacionContenidos
     *
     * @return boolean
     */
    public function getPonderacionContenidos()
    {
        return $this->ponderacionContenidos;
    }

    /**
     * Set enfoque
     *
     * @param \LogicBundle\Entity\Enfoque $enfoque
     *
     * @return PlanAnualMetodologico
     */
    public function setEnfoque(\LogicBundle\Entity\Enfoque $enfoque = null) {
        $this->enfoque = $enfoque;

        return $this;
    }

    /**
     * Get enfoque
     *
     * @return \LogicBundle\Entity\Enfoque
     */
    public function getEnfoque() {
        return $this->enfoque;
    }

    /**
     * Add nivel
     *
     * @param \LogicBundle\Entity\Nivel $nivel
     *
     * @return PlanAnualMetodologico
     */
    public function addNivel(\LogicBundle\Entity\Nivel $nivel) {
        $this->niveles[] = $nivel;

        return $this;
    }

    /**
     * Remove nivel
     *
     * @param \LogicBundle\Entity\Nivel $nivel
     */
    public function removeNivel(\LogicBundle\Entity\Nivel $nivel) {
        $this->niveles->removeElement($nivel);
    }

    /**
     * Get niveles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNiveles() {
        return $this->niveles;
    }

    /**
     * Add componente
     *
     * @param \LogicBundle\Entity\Componente $componente
     *
     * @return PlanAnualMetodologico
     */
    public function addComponente(\LogicBundle\Entity\Componente $componente) {
        $componente->setPlanAnualMetodologico($this);
        $this->componentes[] = $componente;

        return $this;
    }

    /**
     * Remove componente
     *
     * @param \LogicBundle\Entity\Componente $componente
     */
    public function removeComponente(\LogicBundle\Entity\Componente $componente)
    {
        $this->componentes->removeElement($componente);
    }

    /**
     * Get componentes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponentes()
    {
        return $this->componentes;
    }

    /**
     * Set clasificacion
     *
     * @param \LogicBundle\Entity\Clasificacion $clasificacion
     *
     * @return PlanAnualMetodologico
     */
    public function setClasificacion(\LogicBundle\Entity\Clasificacion $clasificacion = null) {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return \LogicBundle\Entity\Clasificacion
     */
    public function getClasificacion() {
        return $this->clasificacion;
    }

    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return PlanAnualMetodologico
     */
    public function setDisciplina(\LogicBundle\Entity\Disciplina $disciplina = null) {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina
     *
     * @return \LogicBundle\Entity\Disciplina
     */
    public function getDisciplina() {
        return $this->disciplina;
    }

    /**
     * Add oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return PlanAnualMetodologico
     */
    public function addOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     */
    public function removeOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas->removeElement($oferta);
    }

    /**
     * Get ofertas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertas() {
        return $this->ofertas;
    }

    /**
     * Get estrategias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstrategias() {
        $estrategias = "";
        $i = 0;
        if(count($this->getOfertas()) > 0){
            foreach($this->getOfertas() as $oferta){
                $i++;
                if($oferta->getEstrategia()){
                    $estrategias .= $oferta->getEstrategia()->getNombre();
                    if($i < count($this->getOfertas())){
                        $estrategias .= ", ";
                    }
                }
            }
        }
        return $estrategias;
    }

    /**
     * Add nivele.
     *
     * @param \LogicBundle\Entity\Nivel $nivele
     *
     * @return PlanAnualMetodologico
     */
    public function addNivele(\LogicBundle\Entity\Nivel $nivele)
    {
        $this->niveles[] = $nivele;

        return $this;
    }

    /**
     * Remove nivele.
     *
     * @param \LogicBundle\Entity\Nivel $nivele
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNivele(\LogicBundle\Entity\Nivel $nivele)
    {
        return $this->niveles->removeElement($nivele);
    }
}
