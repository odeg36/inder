<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuentroSistemaCuatro
 *
 * @ORM\Table(name="encuentro_sistema_cuatro")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuentroSistemaCuatroRepository")
 */
class EncuentroSistemaCuatro
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
     * @var string
     *
     * @ORM\Column(name="hora", type="string", length=255 , nullable=true)
     */
    private $hora;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime" , nullable=true)
     */
    private $fecha;
   
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaCuatro", mappedBy="encuentroSistemaCuatro")
     */
    private $faltasEncuentroSistemaCuatro;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol", mappedBy="encuentroSistemaCuatro")
     */
    private $resultadosEncuentroSistemaCuatroVoleibol;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SistemaJuegoCuatro", inversedBy="encuentroSistemaCuatro")
     * @ORM\JoinColumn(name="sistemaJuegoCuatro_id",referencedColumnName="id")
     */
    private $sistemaJuegoCuatro;
    
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros", mappedBy="encuentroSistemaCuatro")
     */
    private $resultadosEncuentroSistemaCuatroOtros;
    
     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PuntoAtencion", inversedBy="encuentroSistemaCuatro")
     * @ORM\JoinColumn(name="puntoAtencion_id",referencedColumnName="id")
     */
    private $puntoAtencion;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="encuentroSistemaCuatro")
     * @ORM\JoinColumn(name="escenarioDeportivo_id",referencedColumnName="id")
     */
    private $escenarioDeportivo;



    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="encuentroSistemaCuatroCompetidorUno")
     * @ORM\JoinColumn(name="equipoUnoEvento_id",referencedColumnName="id")
     */
    private $competidorUno;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="encuentroSistemaCuatroCompetidorDos")
     * @ORM\JoinColumn(name="equipoDosEvento_id",referencedColumnName="id")
     */
    private $competidorDos;

    

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255)
     */
    private $grupo;


     /**
     * @var string
     *
     * @ORM\Column(name="fase", type="string", length=255)
     */
    private $fase;
    

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;


    /**
     * Constructor
     */
     public function __construct() {
            $this->faltasEncuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
            $this->resultadosEncuentroSistemaCuatroVoleibol = new \Doctrine\Common\Collections\ArrayCollection();
            $this->resultadosEncuentroSistemaCuatroOtros = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set hora
     *
     * @param string $hora
     *
     * @return EncuentroSistemaCuatro
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return string
     */
    public function getHora()
    {
        return $this->hora;
    }



    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return EncuentroSistemaCuatro
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }


    /**
     * Set fase
     *
     * @param string $fase
     *
     * @return EncuentroSistemaCuatro
     */
    public function setFase($fase)
    {
        $this->fase = $fase;

        return $this;
    }

    /**
     * Get fase
     *
     * @return string
     */
    public function getFase()
    {
        return $this->fase;
    }
    

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return EncuentroSistemaCuatro
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Add faltasEncuentroSistemacuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemacuatro
     *
     * @return EncuentroSistemacuatro
     */
    public function addFaltasEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemacuatro) {
        $this->faltasEncuentroSistemacuatro[] = $faltasEncuentroSistemacuatro;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemacuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemacuatro
     */
    public function removeFaltasEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemacuatro) {
        $this->faltasEncuentroSistemacuatro->removeElement($faltasEncuentroSistemacuatro);
    }

    /**
     * Get faltasEncuentroSistemacuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaCuatro() {
        return $this->faltasEncuentroSistemacuatro;
    }

    /**
     * Set gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro
     *
     * @return EncuentroSistemaCuatro
     */
    public function setGruposEncuentroSistemaCuatro(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro = null) {
        $this->gruposEncuentroSistemaCuatro = $gruposEncuentroSistemaCuatro;

        return $this;
    }

    /**
     * Get gruposEncuentroSistemaCuatro
     *
     * @return \LogicBundle\Entity\GruposEncuentroSistemaCuatro
     */
    public function getGruposEncuentroSistemaCuatro() {
        return $this->gruposEncuentroSistemaCuatro;
    }

    /**
     * Add resultadosEncuentroSistemaCuatroVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol $resultadosEncuentroSistemaCuatroVoleibol
     *
     * @return EncuentroSistemacuatro
     */
    public function addResultadosEncuentroSistemaCuatroVoleibol(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol $resultadosEncuentroSistemaCuatroVoleibol) {
        $this->resultadosEncuentroSistemaCuatroVoleibol[] = $resultadosEncuentroSistemaCuatroVoleibol;

        return $this;
    }

    /**
     * Remove resultadosEncuentroSistemaCuatroVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol $resultadosEncuentroSistemaCuatroVoleibol
     */
    public function removeResultadosEncuentroSistemaCuatroVoleibol(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol $resultadosEncuentroSistemaCuatroVoleibol) {
        $this->resultadosEncuentroSistemaCuatroVoleibol->removeElement($resultadosEncuentroSistemaCuatroVoleibol);
    }

    /**
     * Get resultadosEncuentroSistemaCuatroVoleibol
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadosEncuentroSistemaCuatroVoleibol() {
        return $this->resultadosEncuentroSistemaCuatroVoleibol;
    }
    
    
    
    /**
     * Add resultadosEncuentroSistemaCuatroOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtros
     *
     * @return EncuentroSistemaCuatro
     */
    public function addResultadosEncuentroSistemaCuatroOtros(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtros) {
        $this->resultadosEncuentroSistemaCuatroOtros[] = $resultadosEncuentroSistemaCuatroOtros;

        return $this;
    }

    /**
     * Remove resultadosEncuentroSistemaCuatroOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtros
     */
    public function removeResultadosEncuentroSistemaCuatroOtros(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtros) {
        $this->resultadosEncuentroSistemaCuatroOtros->removeElement($resultadosEncuentroSistemaCuatroOtros);
    }

    /**
     * Get resultadosEncuentroSistemaDosOtros
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadosEncuentroSistemaCuatroOtros() {
        return $this->resultadosEncuentroSistemaCuatroOtros;
    }
    
    
    /**
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return EncuentroSistemaCuatro
     */
    public function setPuntoAtencion(\LogicBundle\Entity\PuntoAtencion $puntoAtencion = null) {
        $this->puntoAtencion = $puntoAtencion;

        return $this;
    }


    /**
     * Get puntoAtencion
     *
     * @return \LogicBundle\Entity\PuntoAtencion
     */
    public function getPuntoAtencion() {
        return $this->puntoAtencion;
    }
    
    
    
    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return EncuentroSistemaCuatro
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }


    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
    * Set sistemaJuegoCuatro
    *
    * @param \LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro
    *
    * @return EncuentroSistemaCuatro
    */
   public function setSistemaJuegoCuatro(\LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro = null) {
       $this->sistemaJuegoCuatro = $sistemaJuegoCuatro;

       return $this;
   }

   /**
    * Get sistemaJuegoCuatro
    *
    * @return \LogicBundle\Entity\SistemaJuegoCuatro
    */
   public function getSistemaJuegoCuatro() {
       return $this->sistemaJuegoCuatro;
   }


    /**
     * Set competidorUno
     *
     * @param \LogicBundle\Entity\EquipoEvento $competidorUno
     *
     * @return EncuentroSistemaCuatro
     */
    public function setCompetidorUno(\LogicBundle\Entity\EquipoEvento $competidorUno = null) {
        $this->competidorUno = $competidorUno;

        return $this;
    }


    /**
     * Get competidorUno
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getCompetidorUno() {
        return $this->competidorUno;
    }



    /**
     * Set competidorDos
     *
     * @param \LogicBundle\Entity\EquipoEvento $competidorDos
     *
     * @return EncuentroSistemaCuatro
     */
    public function setCompetidorDos(\LogicBundle\Entity\EquipoEvento $competidorDos = null) {
        $this->competidorDos = $competidorDos;

        return $this;
    }


    /**
     * Get competidorDos
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getCompetidorDos() {
        return $this->competidorDos;
    }

    /**
     * Add resultadosEncuentroSistemaCuatroOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtro
     *
     * @return EncuentroSistemaCuatro
     */
    public function addResultadosEncuentroSistemaCuatroOtro(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtro)
    {
        $this->resultadosEncuentroSistemaCuatroOtros[] = $resultadosEncuentroSistemaCuatroOtro;

        return $this;
    }

    /**
     * Remove resultadosEncuentroSistemaCuatroOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtro
     */
    public function removeResultadosEncuentroSistemaCuatroOtro(\LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtro)
    {
        $this->resultadosEncuentroSistemaCuatroOtros->removeElement($resultadosEncuentroSistemaCuatroOtro);
    }

     /**
     * Set division
     *
     * @param \LogicBundle\Entity\Division $division
     *
     * @return Evento
     */
    public function setDivision(\LogicBundle\Entity\Division $division = null)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return \LogicBundle\Entity\Division
     */
    public function getDivision()
    {
        return $this->division;
    }
}
