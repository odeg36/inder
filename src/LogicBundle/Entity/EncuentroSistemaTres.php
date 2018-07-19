<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * EncuentroSistemaUno
 *
 * @ORM\Table(name="encuentro_sistema_tres")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuentroSistemaTresRepository")
 */
class EncuentroSistemaTres
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
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     *  @var string
     *
     * @ORM\Column(name="hora", type="text", nullable=true)
     */
    private $hora;

     
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="equipoUnoEncuentroSistemaTres")
     * @ORM\JoinColumn(name="equipoUnoEvento_id",referencedColumnName="id")
     */
    private $competidorUno;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="equipoDosEncuentroSistemaTres")
     * @ORM\JoinColumn(name="equipoDosEvento_id",referencedColumnName="id")
     */
    private $competidorDos;


    /**
     * @var string
     *
     * @ORM\Column(name="tipo_sistema", type="string", length=255)
     */
    private $tipoSistema;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaTres", mappedBy="encuentroSistemaTres")
     */
    private $faltasEncuentroSistemaTres;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PuntoAtencion", inversedBy="encuentroSistemaTres")
     * @ORM\JoinColumn(name="puntoAtencion_id",referencedColumnName="id")
     */
    private $puntoAtencion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="encuentroSistemaTres")
     * @ORM\JoinColumn(name="escenarioDeportivo_id",referencedColumnName="id")
     */
    private $escenarioDeportivo;

    
    /**
     * @var int
     *
     * @ORM\Column(name="puntos_competidor_1", type="integer" , nullable=true)
     */
    private $puntos_competidor_uno;



    /**
     * @var int
     *
     * @ORM\Column(name="puntos_competidor_2", type="integer" , nullable=true)
     */
    private $puntos_competidor_dos;


    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo_juego", type="boolean" , nullable=true)
     */
    private $tipo_juego;

    /**
     * @var int
     *
     * @ORM\Column(name="llave", type="integer" , nullable=true)
     */
    private $llave;
    /**
     * @var int
     *
     * @ORM\Column(name="ronda_llave", type="integer")
     */
    private $ronda;
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="encuentroSistemaTres")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;



            ////// voleibol para el usuario 1

            /**
             * @var int
             *
             * @ORM\Column(name="setUno", type="integer", nullable=true)
             */
            private $setUno;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setDos", type="integer", nullable=true)
             */
            private $setDos;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setTres", type="integer", nullable=true)
             */
            private $setTres;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setCuatro", type="integer", nullable=true)
             */
            private $setCuatro;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setCinco", type="integer", nullable=true)
             */
            private $setCinco;
        
        
            /**
             * @var int
             *
             * @ORM\Column(name="setAfavor", type="integer", nullable=true)
             */
            private $setsAfavor;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setEnContra", type="integer", nullable=true)
             */
            private $setsEnContra;
    
    
            ////// voleibol para el usuario 2
    
            /**
             * @var int
             *
             * @ORM\Column(name="setUno2", type="integer", nullable=true)
             */
            private $setUno2;
            
            /**
             * @var int
             *
             * @ORM\Column(name="setDos2", type="integer", nullable=true)
             */
            private $setDos2;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setTres2", type="integer", nullable=true)
             */
            private $setTres2;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setCuatro2", type="integer", nullable=true)
             */
            private $setCuatro2;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setCinco2", type="integer", nullable=true)
             */
            private $setCinco2;
        
        
            /**
             * @var int
             *
             * @ORM\Column(name="setAfavor2", type="integer", nullable=true)
             */
            private $setsAfavor2;
        
            /**
             * @var int
             *
             * @ORM\Column(name="setEnContra2", type="integer", nullable=true)
             */
            private $setsEnContra2;

    
    /**
     * Constructor
     */
     public function __construct() {
            $this->faltasEncuentroSistemaTres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set puntos_competidor_uno
     *
     * @param integer $puntos_competidor_uno
     *
     * @return EncuentrosSistemaUno
     */
    public function setPuntosCompetidorUno($puntos_competidor_uno)
    {
        $this->puntos_competidor_uno = $puntos_competidor_uno;

        return $this;
    }

    /**
     * Get puntos_competidor_uno
     *
     * @return int
     */
    public function getPuntosCompetidorUno()
    {
        return $this->puntos_competidor_uno;
    }


    /**
     * Set puntos_competidor_dos
     *
     * @param integer $puntos_competidor_dos
     *
     * @return EncuentrosSistemaUno
     */
    public function setPuntosCompetidorDos($puntos_competidor_dos)
    {
        $this->puntos_competidor_dos = $puntos_competidor_dos;

        return $this;
    }

    /**
     * Get puntos_competidor_dos
     *
     * @return int
     */
    public function getPuntosCompetidorDos()
    {
        return $this->puntos_competidor_dos;
    }
     


    ////////////////////////////////////////////
    /**
     * Set competidorUno
     *
     * @param \LogicBundle\Entity\EquipoEvento
     *
     * @return competidorUno
     */
    public function setCompetidorUno(\LogicBundle\Entity\EquipoEvento $competidorUno= null)
    {
        $this->competidorUno = $competidorUno;

        return $this;
    }

    /**
     * Get competidorUno
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getCompetidorUno()
    {
        return $this->competidorUno;
    }



    /**
     * Set competidorDos
     *
     * @param \LogicBundle\Entity\EquipoEvento
     *
     * @return competidorDos
     */
    public function setcompetidorDos(\LogicBundle\Entity\EquipoEvento $competidorDos=null)
    {
        $this->competidorDos = $competidorDos;

        return $this;
    }

    /**
     * Get competidorDos
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getCompetidorDos()
    {
        return $this->competidorDos;
    }

    /**
     * Set llave
     *
     * @param int $llave
     *
     * @return llave
     */
    public function setLlave($llave)
    {
        $this->llave = $llave;

        return $this;
    }

    /**
     * Get llave
     *
     * @return int
     */
    public function getLlave()
    {
        return $this->llave;
    }
    /**
     * Set ronda
     *
     * @param integer $ronda
     *
     * @return EncuentrosSistemaUno
     */
    public function setRonda($ronda)
    {
        $this->ronda = $ronda;

        return $this;
    }

    /**
     * Get ronda
     *
     * @return int
     */
    public function getRonda()
    {
        return $this->ronda;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return EncuentrosSistemaUno
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
     * Set tipoSistema
     *
     * @param string $tipoSistema
     */
    public function setTipoSistema($tipoSistema)
    {
        $this->tipoSistema = $tipoSistema;

        return $this;
    }

    /**
     * Get tipoSistema
     *
     * @return string
     */
    public function getTipoSistema()
    {
        return $this->tipoSistema;
    }

    /**
     * Set tipo_juego
     *
     * @param boolean $tipo_encuentro
     */
    public function setTipoJuego($tipo_juego)
    {
        $this->tipo_juego = $tipo_juego;

        return $this;
    }

    /**
     * Get tipo_juego
     *
     * @return boolean
     */
    public function getTipoJuego()
    {
        return $this->tipo_juego;
    }
     /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return SistemaJuegoDos
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null) {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento() {
        return $this->evento;
    }
    
    /**
     * Add faltasEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres
     *
     * @return EncuentroSistemaTres
     */
    public function addFaltasEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres) {
        $this->faltasEncuentroSistemaTres[] = $faltasEncuentroSistemaTres;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaTres
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres
     */
    public function removeFaltasEncuentroSistemaTres(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTres) {
        $this->faltasEncuentroSistemaTres->removeElement($faltasEncuentroSistemaTres);
    }

    /**
     * Get faltasEncuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaTres() {
        return $this->faltasEncuentroSistemaTres;
    }


    
    /**
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return EncuentroSistemaTres
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
     * @return EncuentroSistemaUno
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }


    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\PuntoAtencion
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }
        

    /**
     * Add faltasEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre
     *
     * @return EncuentroSistemaTres
     */
    public function addFaltasEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre)
    {
        $this->faltasEncuentroSistemaTres[] = $faltasEncuentroSistemaTre;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaTre
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre
     */
    public function removeFaltasEncuentroSistemaTre(\LogicBundle\Entity\FaltasEncuentroSistemaTres $faltasEncuentroSistemaTre)
    {
        $this->faltasEncuentroSistemaTres->removeElement($faltasEncuentroSistemaTre);
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


    /// voleibol usuario 1

     /**
     * Set setUno
     *
     * @param integer $setUno
     *
     * @return EncuentroSistemaTres
     */
    public function setSetUno($setUno)
    {
        $this->setUno = $setUno;

        return $this;
    }

    /**
     * Get setUno
     *
     * @return int
     */
    public function getSetUno()
    {
        return $this->setUno;
    }

    /**
     * Set setDos
     *
     * @param integer $setDos
     *
     * @return EncuentroSistemaTres
     */
    public function setSetDos($setDos)
    {
        $this->setDos = $setDos;

        return $this;
    }

    /**
     * Get setDos
     *
     * @return int
     */
    public function getSetDos()
    {
        return $this->setDos;
    }

    /**
     * Set setTres
     *
     * @param integer $setTres
     *
     * @return EncuentroSistemaTres
     */
    public function setSetTres($setTres)
    {
        $this->setTres = $setTres;

        return $this;
    }

    /**
     * Get setTres
     *
     * @return int
     */
    public function getSetTres()
    {
        return $this->setTres;
    }

    /**
     * Set setCuatro
     *
     * @param integer $setCuatro
     *
     * @return EncuentroSistemaTres
     */
    public function setSetCuatro($setCuatro)
    {
        $this->setCuatro = $setCuatro;

        return $this;
    }

    /**
     * Get setCuatro
     *
     * @return int
     */
    public function getSetCuatro()
    {
        return $this->setCuatro;
    }

    /**
     * Set setCinco
     *
     * @param integer $setCinco
     *
     * @return EncuentroSistemaTres
     */
    public function setSetCinco($setCinco)
    {
        $this->setCinco = $setCinco;

        return $this;
    }

    /**
     * Get setCinco
     *
     * @return int
     */
    public function getSetCinco()
    {
        return $this->setCinco;
    }


    //voleibol usuario 2


    /**
     * Set setUno2
     *
     * @param integer $setUno2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetUno2($setUno2)
    {
        $this->setUno2 = $setUno2;

        return $this;
    }

    /**
     * Get setUno2
     *
     * @return int
     */
    public function getSetUno2()
    {
        return $this->setUno2;
    }

    /**
     * Set setDos2
     *
     * @param integer $setDos2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetDos2($setDos2)
    {
        $this->setDos2 = $setDos2;

        return $this;
    }

    /**
     * Get setDos2
     *
     * @return int
     */
    public function getSetDos2()
    {
        return $this->setDos2;
    }

    /**
     * Set setTres2
     *
     * @param integer $setTres2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetTres2($setTres2)
    {
        $this->setTres2 = $setTres2;

        return $this;
    }

    /**
     * Get setTres2
     *
     * @return int
     */
    public function getSetTres2()
    {
        return $this->setTres2;
    }

    /**
     * Set setCuatro2
     *
     * @param integer $setCuatro2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetCuatro2($setCuatro2)
    {
        $this->setCuatro2 = $setCuatro2;

        return $this;
    }

    /**
     * Get setCuatro2
     *
     * @return int
     */
    public function getSetCuatro2()
    {
        return $this->setCuatro2;
    }

    /**
     * Set setCinco2
     *
     * @param integer $setCinco2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetCinco2($setCinco2)
    {
        $this->setCinco2 = $setCinco2;

        return $this;
    }

    /**
     * Get setCinco
     *
     * @return int
     */
    public function getSetCinco2()
    {
        return $this->setCinco2;
    }



     /**
     * Set setsAfavor2
     *
     * @param integer $setsAfavor2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetsAfavor2($setsAfavor2)
    {
        $this->setsAfavor2 = $setsAfavor2;

        return $this;
    }

    /**
     * Get setsAfavor2
     *
     * @return int
     */
    public function getSetsAfavor2()
    {
        return $this->setsAfavor2;
    }


     /**
     * Set setsEnContra2
     *
     * @param integer $setsEnContra2
     *
     * @return EncuentroSistemaTres
     */
    public function setSetsEnContra2($setsEnContra2)
    {
        $this->setsEnContra2 = $setsEnContra2;

        return $this;
    }

    /**
     * Get setsEnContra2
     *
     * @return int
     */
    public function getSetsEnContra2()
    {
        return $this->setsEnContra2;
    }


    /**
     * Set setsAfavor
     *
     * @param integer $setsAfavor
     *
     * @return EncuentroSistemaTres
     */
    public function setSetsAfavor($setsAfavor)
    {
        $this->setsAfavor = $setsAfavor;

        return $this;
    }

    /**
     * Get setsAfavor
     *
     * @return int
     */
    public function getSetsAfavor()
    {
        return $this->setsAfavor;
    }


     /**
     * Set setsEnContra
     *
     * @param integer $setsEnContra
     *
     * @return EncuentroSistemaTres
     */
    public function setSetsEnContra($setsEnContra)
    {
        $this->setsEnContra = $setsEnContra;

        return $this;
    }

    /**
     * Get setsEnContra
     *
     * @return int
     */
    public function getSetsEnContra()
    {
        return $this->setsEnContra;
    }
}
