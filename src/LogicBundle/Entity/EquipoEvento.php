<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipoEvento
 *
 * @ORM\Table(name="equipo_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EquipoEventoRepository")
 */
class EquipoEvento
{


    public function __toString() {
        //return $this->getNombre() ? ' ':'';
        return $this->getNombre() ? $this->getNombre(): '';
    
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
     * @ORM\Column(name="estado", type="integer", options={"default":0})
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="equipoEventos")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\JugadorEvento", mappedBy="equipoEvento")
     */
    private $jugadorEventos;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaDos", mappedBy="equipoEvento")
     */
    private $faltasEncuentroSistemaDos;


    private $numeroJugadores;  
    
    private $nombreEstado;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", mappedBy="competidorUno")
     */
    private $equipoUnoEncuentroSistemaTres;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", mappedBy="competidorDos")
     */
    private $equipoDosEncuentroSistemaTres;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaTres", mappedBy="equipoEvento")
     */
    private $faltasEquipoEncuentroSistemaTres;
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", mappedBy="competidorUno")
     */
   private $encuentroSistemaDosCompetidorUno;


   /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", mappedBy="competidorDos")
     */
    private $encuentroSistemaDosCompetidorDos;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros", mappedBy="equipoEvento")
     */
    private $resultadosEncuentroSistemaDosOtros;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaCuatro", mappedBy="equipoEvento")
     */
    private $faltasEquipoEncuentroSistemaCuatro;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol", mappedBy="equipoEvento")
     */
    private $resultadosEncuentrosSistemaDosVoleibol;





        /**
        * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", mappedBy="competidorUno")
        */
        private $encuentroSistemaCuatroCompetidorUno;
   
   
      /**
        * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", mappedBy="competidorDos")
        */
       private $encuentroSistemaCuatroCompetidorDos;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros", mappedBy="equipoEvento")
     */
    private $resultadosEncuentroSistemaCuatroOtros;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\GruposEncuentroSistemaCuatro", mappedBy="equipoEvento")
     */
    private $gruposEncuentroSistemaCuatro;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol", mappedBy="equipoEvento")
     */
    private $resultadosEncuentroSistemaCuatroVoleibol;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->jugadorEventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faltasEncuentroSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaDosCompetidorUno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaDosCompetidorDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentroSistemaDosOtros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentrosSistemaDosVoleibol = new \Doctrine\Common\Collections\ArrayCollection();

        $this->encuentroSistemaCuatroCompetidorUno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaCuatroCompetidorDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentroSistemaCuatroOtros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultadosEncuentroSistemaCuatroVoleibol = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposEncuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estado
     *
     * @param integer $estado
     *
     * @return EquipoEvento
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return EquipoEvento
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
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Proyecto
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento()
    {
        return $this->evento;
    }


    /**
     * Add jugadorEvento
     *
     * @param \LogicBundle\Entity\JugadorEvento $jugadorEvento
     *
     * @return EquipoEvento
     */
    public function addJugadorEvento(\LogicBundle\Entity\JugadorEvento $jugadorEvento) {
        $this->jugadorEventos[] = $jugadorEvento;

        return $this;
    }

    /**
     * Remove jugadorEvento
     *
     * @param \LogicBundle\Entity\JugadorEvento $jugadorEvento
     */
    public function removeJugadorEvento(\LogicBundle\Entity\JugadorEvento $jugadorEvento) {
        $this->jugadorEventos->removeElement($jugadorEvento);
    }

    /**
     * Get jugadorEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJugadorEventos() {
        return $this->jugadorEventos;
    }

    public function getNumeroJugadores()
    {
        if ($this->getId() != null) {            
            return count($this->getJugadorEventos());
        }else{
            return $this->numeroJugadores;
        }
    }

    public function getNombreEstado()
    {
        if ($this->getId() != null) {            
            $estado = $this->getEstado();
            if ($estado == 0) {return 'Pendiente';}
            else if ($estado == 1) {return 'Aprobado';} 
            else if ($estado == 2) {return 'Rechazado';}            
        }else{
            return $this->nombreEstado;
        }
    }


    /**
     * Add faltasEncuentroSistemaDos
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos
     *
     * @return EquipoEvento
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
     * Add encuentroSistemaDosCompetidorUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorUno
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaDosCompetidorUno(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorUno) {
        $this->encuentroSistemaDosCompetidorUno[] = $encuentroSistemaDosCompetidorUno;

        return $this;
    }

    /**
     * Remove encuentroSistemaDosCompetidorUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorUno
     */
    public function removeEncuentroSistemaDosCompetidorUno(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorUno) {
        $this->encuentroSistemaDosCompetidorUno->removeElement($encuentroSistemaDosCompetidorUno);
    }

    /**
     * Get encuentroSistemaDosCompetidorUno
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaDosCompetidorUno() {
        return $this->encuentroSistemaDosCompetidorUno;
    }


     /**
     * Add encuentroSistemaDosCompetidorDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDos
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaDosCompetidorDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDos) {
        $this->encuentroSistemaDosCompetidorDos[] = $encuentroSistemaDosCompetidorDos;

        return $this;
    }

    /**
     * Remove encuentroSistemaDosCompetidorDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDos
     */
    public function removeEncuentroSistemaDosCompetidorDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDos) {
        $this->encuentroSistemaDosCompetidorDos->removeElement($encuentroSistemaDosCompetidorDos);
    }

    /**
     * Get encuentroSistemaDosCompetidorDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaDosCompetidorDos() {
        return $this->encuentroSistemaDosCompetidorDos;
    }


    /**
     * Add resultadosEncuentroSistemaDosOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtros
     *
     * @return EquipoEvento
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
     * Add ResultadosEncuentrosSistemaDosVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol
     *
     * @return EquipoEvento
     */
    public function addResultadosEncuentrosSistemaDosVoleibol(\LogicBundle\Entity\ResultadosEncuentrosSistemaDosVoleibol $resultadosEncuentrosSistemaDosVoleibol) {
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
     * Add faltasEquipoEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTres
     *
     * @return EquipoEvento
     */
    public function addFaltasEquipoEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTres) {
        $this->faltasEquipoEncuentroSistemaTres[] = $faltasEquipoEncuentroSistemaTres;

        return $this;
    }

    /**
     * Remove faltasEquipoEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTres
     */
    public function removeFaltasEquipoEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTres) {
        $this->faltasEquipoEncuentroSistemaTres->removeElement($faltasEquipoEncuentroSistemaTres);
    }

    /**
     * Get faltasEquipoEncuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEquipoEncuentroSistemaTres() {
        return $this->faltasEquipoEncuentroSistemaTres;
    }
    /**
     * Add faltasEquipoEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEquipoEncuentroSistemaCuatro
     *
     * @return EquipoEvento
     */
    public function addFaltasEquipoEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEquipoEncuentroSistemaCuatro) {
        $this->faltasEquipoEncuentroSistemaCuatro[] = $faltasEquipoEncuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove faltasEquipoEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEquipoEncuentroSistemaCuatro
     */
    public function removeFaltasEquipoEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEquipoEncuentroSistemaCuatro) {
        $this->faltasEquipoEncuentroSistemaCuatro->removeElement($faltasEquipoEncuentroSistemaCuatro);
    }

    /**
     * Get faltasEquipoEncuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEquipoEncuentroSistemaCuatro() {
        return $this->faltasEquipoEncuentroSistemaCuatro;
    }





     /**
     * Add encuentroSistemaCuatroCompetidorUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorUno
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaCuatroCompetidorUno(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorUno) {
        $this->encuentroSistemaCuatroCompetidorUno[] = $encuentroSistemaCuatroCompetidorUno;

        return $this;
    }

    /**
     * Remove encuentroSistemaCuatroCompetidorUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorUno
     */
    public function removeEncuentroSistemaCuatroCompetidorUno(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorUno) {
        $this->encuentroSistemaCuatroCompetidorUno->removeElement($encuentroSistemaDosCompetidorDos);
    }

    /**
     * Get encuentroSistemaCuatroCompetidorUno
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaCuatroCompetidorUno() {
        return $this->encuentroSistemaCuatroCompetidorUno;
    }



    /**
     * Add encuentroSistemaCuatroCompetidorDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDos
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaCuatroCompetidorDos(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDos) {
        $this->encuentroSistemaCuatroCompetidorDos[] = $encuentroSistemaCuatroCompetidorDos;

        return $this;
    }

    /**
     * Remove encuentroSistemaCuatroCompetidorDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDos
     */
    public function removeEncuentroSistemaCuatroCompetidorDos(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDos) {
        $this->encuentroSistemaCuatroCompetidorDos->removeElement($encuentroSistemaCuatroCompetidorDos);
    }

    /**
     * Get encuentroSistemaCuatroCompetidorDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaCuatroCompetidorDos() {
        return $this->encuentroSistemaCuatroCompetidorDos;
    }




    /**
     * Add resultadosEncuentroSistemaCuatroOtros
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtros
     *
     * @return EquipoEvento
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
     * Get resultadosEncuentroSistemaCuatroOtros
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultadosEncuentroSistemaCuatroOtros() {
        return $this->resultadosEncuentroSistemaCuatroOtros;
    }

    /**
     * Add gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\equipoEvento $equipoEvento
     *
     * @return equipoEvento
     */
    public function addequipoEvento(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro)
    {
        $this->gruposEncuentroSistemaCuatro[] = $gruposEncuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\GruposEncuentroSistemaCuatro $equipoEvento
     */
    public function removeGruposEncuentroSistemaCuatro(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $equipoEvento)
    {
        $this->gruposEncuentroSistemaCuatro->removeElement($equipoEvento);
    }

    /**
     * Get gruposEncuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGruposEncuentroSistemaCuatro()
    {
        return $this->gruposEncuentroSistemaCuatro;
    }

    /**
     * Add faltasEncuentroSistemaDo
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo
     *
     * @return EquipoEvento
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
     * Add equipoUnoEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $equipoUnoEncuentroSistemaTre
     *
     * @return EquipoEvento
     */
    public function addEquipoUnoEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $equipoUnoEncuentroSistemaTre)
    {
        $this->equipoUnoEncuentroSistemaTres[] = $equipoUnoEncuentroSistemaTre;

        return $this;
    }

    /**
     * Remove equipoUnoEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $equipoUnoEncuentroSistemaTre
     */
    public function removeEquipoUnoEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $equipoUnoEncuentroSistemaTre)
    {
        $this->equipoUnoEncuentroSistemaTres->removeElement($equipoUnoEncuentroSistemaTre);
    }

    /**
     * Get equipoUnoEncuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipoUnoEncuentroSistemaTres()
    {
        return $this->equipoUnoEncuentroSistemaTres;
    }

    /**
     * Add equipoDosEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $equipoDosEncuentroSistemaTre
     *
     * @return EquipoEvento
     */
    public function addEquipoDosEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $equipoDosEncuentroSistemaTre)
    {
        $this->equipoDosEncuentroSistemaTres[] = $equipoDosEncuentroSistemaTre;

        return $this;
    }

    /**
     * Remove equipoDosEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $equipoDosEncuentroSistemaTre
     */
    public function removeEquipoDosEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $equipoDosEncuentroSistemaTre)
    {
        $this->equipoDosEncuentroSistemaTres->removeElement($equipoDosEncuentroSistemaTre);
    }

    /**
     * Get equipoDosEncuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipoDosEncuentroSistemaTres()
    {
        return $this->equipoDosEncuentroSistemaTres;
    }

    /**
     * Add faltasEquipoEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTre
     *
     * @return EquipoEvento
     */
    public function addFaltasEquipoEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTre)
    {
        $this->faltasEquipoEncuentroSistemaTres[] = $faltasEquipoEncuentroSistemaTre;

        return $this;
    }

    /**
     * Remove faltasEquipoEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTre
     */
    public function removeFaltasEquipoEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEquipoEncuentroSistemaTre)
    {
        $this->faltasEquipoEncuentroSistemaTres->removeElement($faltasEquipoEncuentroSistemaTre);
    }

    /**
     * Add encuentroSistemaDosCompetidorDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDo
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaDosCompetidorDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDo)
    {
        $this->encuentroSistemaDosCompetidorDos[] = $encuentroSistemaDosCompetidorDo;

        return $this;
    }

    /**
     * Remove encuentroSistemaDosCompetidorDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDo
     */
    public function removeEncuentroSistemaDosCompetidorDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDosCompetidorDo)
    {
        $this->encuentroSistemaDosCompetidorDos->removeElement($encuentroSistemaDosCompetidorDo);
    }

    /**
     * Add resultadosEncuentroSistemaDosOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaDosOtros $resultadosEncuentroSistemaDosOtro
     *
     * @return EquipoEvento
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
     * Add encuentroSistemaCuatroCompetidorDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDo
     *
     * @return EquipoEvento
     */
    public function addEncuentroSistemaCuatroCompetidorDo(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDo)
    {
        $this->encuentroSistemaCuatroCompetidorDos[] = $encuentroSistemaCuatroCompetidorDo;

        return $this;
    }

    /**
     * Remove encuentroSistemaCuatroCompetidorDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDo
     */
    public function removeEncuentroSistemaCuatroCompetidorDo(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatroCompetidorDo)
    {
        $this->encuentroSistemaCuatroCompetidorDos->removeElement($encuentroSistemaCuatroCompetidorDo);
    }

    /**
     * Add resultadosEncuentroSistemaCuatroOtro
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroOtros $resultadosEncuentroSistemaCuatroOtro
     *
     * @return EquipoEvento
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
     * Add gruposEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro
     *
     * @return EquipoEvento
     */
    public function addGruposEncuentroSistemaCuatro(\LogicBundle\Entity\GruposEncuentroSistemaCuatro $gruposEncuentroSistemaCuatro)
    {
        $this->gruposEncuentroSistemaCuatro[] = $gruposEncuentroSistemaCuatro;

        return $this;
    }






     /**
     * Add resultadosEncuentroSistemaCuatroVoleibol
     *
     * @param \LogicBundle\Entity\ResultadosEncuentroSistemaCuatroVoleibol $resultadosEncuentroSistemaCuatroVoleibol
     *
     * @return EquipoEvento
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
}
