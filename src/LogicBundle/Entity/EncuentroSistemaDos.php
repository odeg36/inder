<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuentroSistemaDos
 *
 * @ORM\Table(name="encuentro_sistema_dos")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuentroSistemaDosRepository")
 */
class EncuentroSistemaDos
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="hora", type="string", length=191)
     */
    private $hora;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoDeEncuentro", type="string", length=191)
     */
    private $tipoDeEncuentro;

     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SistemaJuegoDos", inversedBy="encuentrosSistemaDos")
     * @ORM\JoinColumn(name="sistema_juego_dos_id",referencedColumnName="id")
     */
    private $sistemaJuegoDos;


     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PuntoAtencion", inversedBy="encuentroSistemaDos")
     * @ORM\JoinColumn(name="puntoAtencion_id",referencedColumnName="id")
     */
    private $puntoAtencion;
    
    /**
         * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="encuentroSistemaDos")
         * @ORM\JoinColumn(name="escenarioDeportivo_id",referencedColumnName="id")
         */
    private $escenarioDeportivo;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaDos", mappedBy="encuentroSistemaDos")
     */
    private $faltasEncuentroSistemaDos;



    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="encuentroSistemaDosCompetidorUno")
     * @ORM\JoinColumn(name="equipoUnoEvento_id",referencedColumnName="id")
     */
    private $competidorUno;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="encuentroSistemaDosCompetidorDos")
     * @ORM\JoinColumn(name="equipoDosEvento_id",referencedColumnName="id")
     */
    private $competidorDos;



    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros", mappedBy="encuentroSistemaDos")
     */
    private $resultadosEncuentroSistemaDosOtros;


     /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol", mappedBy="encuentroSistemaDos")
     */
    private $resultadosEncuentrosSistemaDosVoleibol;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;
    


    /**
     * Constructor
     */
    public function __construct() {
        $this->faltasEncuentroSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentroSistemaDosOtros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentrosSistemaDosVoleibol = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return EncuentroSistemaDos
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
     * Set hora
     *
     * @param string $hora
     *
     * @return EncuentroSistemaDos
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
     * Set tipoDeEncuentro
     *
     * @param string $tipoDeEncuentro
     *
     * @return EncuentroSistemaDos
     */
    public function setTipoDeEncuentro($tipoDeEncuentro)
    {
        $this->tipoDeEncuentro = $tipoDeEncuentro;

        return $this;
    }

    /**
     * Get tipoDeEncuentro
     *
     * @return string
     */
    public function getTipoDeEncuentro()
    {
        return $this->tipoDeEncuentro;
    }



    /**
     * Set sistemaJuegoDos
     *
     * @param \LogicBundle\Entity\SistemaJuegoDos $sistemaJuegoDos
     *
     * @return EncuentroSistemaDos
     */
    public function setSistemaJuegoDos(\LogicBundle\Entity\SistemaJuegoDos $sistemaJuegoDos = null) {
        $this->sistemaJuegoDos = $sistemaJuegoDos;

        return $this;
    }

    /**
     * Get sistemaJuegoDos
     *
     * @return \LogicBundle\Entity\SistemaJuegoDos
     */
    public function getSistemaJuegoDos() {
        return $this->sistemaJuegoDos;
    }




    /**
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return EncuentroSistemaDos
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
     * @return EncuentroSistemaDos
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
     * Add faltasEncuentroSistemaDos
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos
     *
     * @return EncuentroSistemaDos
     */
    public function addFaltasEncuentroSistemaDos(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos) {
        $this->faltasEncuentroSistemaDos[] = $faltasEncuentroSistemaDos;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaDos
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos
     */
    public function removeFaltasEncuentroSistemaDos(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos) {
        $this->faltasEncuentroSistemaDos->removeElement($faltasEncuentroSistemaDos);
    }

    /**
     * Get faltasEncuentroSistemaDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaDos() {
        return $this->faltasEncuentroSistemaDos;
    }




    /**
     * Set competidorUno
     *
     * @param \LogicBundle\Entity\EquipoEvento $competidorUno
     *
     * @return EncuentroSistemaDos
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
     * @return EncuentroSistemaDos
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
     * Add resultadosEncuentroSistemaDosOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtros
     *
     * @return EncuentroSistemaDos
     */
    public function addResultadosEncuentroSistemaDosOtros(\LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtros) {
        $this->resultadosEncuentroSistemaDosOtros[] = $resultadosEncuentroSistemaDosOtros;

        return $this;
    }

    /**
     * Remove resultadosEncuentroSistemaDosOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtros
     */
    public function removeResultadosEncuentroSistemaDosOtros(\LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtros) {
        $this->resultadosEncuentroSistemaDosOtros->removeElement($resultadosEncuentroSistemaDosOtros);
    }

    /**
     * Get resultadosEncuentroSistemaDosOtros
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadosEncuentroSistemaDosOtros() {
        return $this->resultadosEncuentroSistemaDosOtros;
    }




    /**
     * Add resultadosEncuentrosSistemaDosVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol
     *
     * @return EncuentroSistemaDos
     */
    public function addResultadosEncuentroSistemaDosVoleibol(\LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol) {
        $this->resultadosEncuentrosSistemaDosVoleibol[] = $resultadosEncuentrosSistemaDosVoleibol;

        return $this;
    }

    /**
     * Remove resultadosEncuentrosSistemaDosVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol
     */
    public function removeResultadosEncuentrosSistemaDosVoleibol(\LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol) {
        $this->resultadosEncuentrosSistemaDosVoleibol->removeElement($resultadosEncuentrosSistemaDosVoleibol);
    }

    /**
     * Get resultadosEncuentrosSistemaDosVoleibol
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadosEncuentrosSistemaDosVoleibol() {
        return $this->resultadosEncuentrosSistemaDosVoleibol;
    }

    /**
     * Add faltasEncuentroSistemaDo
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo
     *
     * @return EncuentroSistemaDos
     */
    public function addFaltasEncuentroSistemaDo(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo)
    {
        $this->faltasEncuentroSistemaDos[] = $faltasEncuentroSistemaDo;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaDo
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo
     */
    public function removeFaltasEncuentroSistemaDo(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo)
    {
        $this->faltasEncuentroSistemaDos->removeElement($faltasEncuentroSistemaDo);
    }

    /**
     * Add resultadosEncuentroSistemaDosOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtro
     *
     * @return EncuentroSistemaDos
     */
    public function addResultadosEncuentroSistemaDosOtro(\LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtro)
    {
        $this->resultadosEncuentroSistemaDosOtros[] = $resultadosEncuentroSistemaDosOtro;

        return $this;
    }

    /**
     * Remove resultadosEncuentroSistemaDosOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtro
     */
    public function removeResultadosEncuentroSistemaDosOtro(\LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtro)
    {
        $this->resultadosEncuentroSistemaDosOtros->removeElement($resultadosEncuentroSistemaDosOtro);
    }

    /**
     * Add resultadosEncuentrosSistemaDosVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol
     *
     * @return EncuentroSistemaDos
     */
    public function addResultadosEncuentrosSistemaDosVoleibol(\LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol)
    {
        $this->resultadosEncuentrosSistemaDosVoleibol[] = $resultadosEncuentrosSistemaDosVoleibol;

        return $this;
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
