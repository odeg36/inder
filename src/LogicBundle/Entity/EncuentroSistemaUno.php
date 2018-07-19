<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * EncuentroSistemaUno
 *
 * @ORM\Table(name="encuentro_sistema_uno")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuentroSistemaUnoRepository")
 */
class EncuentroSistemaUno
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
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     *  @var string
     *
     * @ORM\Column(name="hora", type="text")
     */
    private $hora;

     
    /**
     * @var int
     *
     * @ORM\Column(name="competidorUno", type="integer" , nullable=true)
    */
    private $competidorUno;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="competidorDos", type="integer" , nullable=true)
    */
    private $competidorDos;


    private $competidorUnoObject;

    private $competidorDosObject;



    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SistemaJuegoUno", inversedBy="encuentrosSistemaUno")
     * @ORM\JoinColumn(name="sistema_juego_uno_id",referencedColumnName="id")
     */
    private $sistemaJuegoUno;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroJugador", mappedBy="encuentroSistemaUno")
     */
    private $faltasEncuentroJugador;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\puntoAtencion", inversedBy="encuentroSistemaUno")
     * @ORM\JoinColumn(name="puntoAtencion_id",referencedColumnName="id")
     */
    private $puntoAtencion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="encuentroSistemaUno")
     * @ORM\JoinColumn(name="escenarioDeportivo_id",referencedColumnName="id")
     */
    private $escenarioDeportivo;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id" )
     */
    private $division;

    
        /**
         * @var int
         *
         * @ORM\Column(name="puntos_competidor_1", type="integer" , nullable=true)
         */
        private $puntos_competidor_1;
    
    
    
        /**
         * @var int
         *
         * @ORM\Column(name="puntos_competidor_2", type="integer" , nullable=true)
         */
        private $puntos_competidor_2;

        
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
            $this->faltasEncuentroJugador = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set puntos_competidor_1
     *
     * @param integer $puntos_competidor_1
     *
     * @return EncuentrosSistemaUno
     */
    public function setPuntosCompetidorUno($puntos_competidor_1)
    {
        $this->puntos_competidor_1 = $puntos_competidor_1;

        return $this;
    }

    /**
     * Get puntos_competidor_1
     *
     * @return int
     */
    public function getPuntosCompetidorUno()
    {
        return $this->puntos_competidor_1;
    }


    /**
     * Set puntos_competidor_2
     *
     * @param integer $puntos_competidor_2
     *
     * @return EncuentrosSistemaUno
     */
    public function setPuntosCompetidorDos($puntos_competidor_2)
    {
        $this->puntos_competidor_2 = $puntos_competidor_2;

        return $this;
    }

    /**
     * Get puntos_competidor_2
     *
     * @return int
     */
    public function getPuntosCompetidorDos()
    {
        return $this->puntos_competidor_2;
    }
    


    ////////////////////////////////////////////
    /**
     * Set competidorUno
     *
     * @param integer $competidorUno
     *
     * @return EncuentrosSistemaUno
     */
    public function setCompetidorUno($competidorUno)
    {
        $this->competidorUno = $competidorUno;

        return $this;
    }

    /**
     * Get competidorUno
     *
     * @return int
     */
    public function getCompetidorUno()
    {
        return $this->competidorUno;
    }



    /**
     * Set competidorDos
     *
     * @param integer $competidorDos
     *
     * @return EncuentrosSistemaUno
     */
    public function setcompetidorDos($competidorDos)
    {
        $this->competidorDos = $competidorDos;

        return $this;
    }

    /**
     * Get competidorDos
     *
     * @return int
     */
    public function getCompetidorDos()
    {
        return $this->competidorDos;
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
     * Set sistemaJuegoUno
     *
     * @param \LogicBundle\Entity\SistemaJuegoUno $sistemaJuegoUno
     *
     * @return EncuentroSistemaUno
     */
    public function setSistemaJuegoUno(\LogicBundle\Entity\SistemaJuegoUno $sistemaJuegoUno = null) {
        $this->sistemaJuegoUno = $sistemaJuegoUno;

        return $this;
    }

    /**
     * Get SistemaJuegoUno
     *
     * @return \LogicBundle\Entity\SistemaJuegoUno
     */
    public function getSistemaJuegoUno() {
        return $this->sistemaJuegoUno;
    }


    /**
     * Add faltasEncuentroJugador
     *
     * @param \LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador
     *
     * @return EncuentroSistemaUno
     */
    public function addFaltasEncuentroJugador(\LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador) {
        $this->faltasEncuentroJugador[] = $faltasEncuentroJugador;

        return $this;
    }

    /**
     * Remove faltasEncuentroJugador
     *
     * @param \LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador
     */
    public function removeFaltasEncuentroJugador(\LogicBundle\Entity\FaltasEncuentroJugador $faltasEncuentroJugador) {
        $this->faltasEncuentroJugador->removeElement($faltasEncuentroJugador);
    }

    /**
     * Get faltasEncuentroJugador
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroJugador() {
        return $this->faltasEncuentroJugador;
    }


    
    /**
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return EncuentroSistemaUno
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
     * Set puntosCompetidor1
     *
     * @param integer $puntosCompetidor1
     *
     * @return EncuentroSistemaUno
     */
    public function setPuntosCompetidor1($puntosCompetidor1)
    {
        $this->puntos_competidor_1 = $puntosCompetidor1;

        return $this;
    }

    /**
     * Get puntosCompetidor1
     *
     * @return integer
     */
    public function getPuntosCompetidor1()
    {
        return $this->puntos_competidor_1;
    }

    /**
     * Set puntosCompetidor2
     *
     * @param integer $puntosCompetidor2
     *
     * @return EncuentroSistemaUno
     */
    public function setPuntosCompetidor2($puntosCompetidor2)
    {
        $this->puntos_competidor_2 = $puntosCompetidor2;

        return $this;
    }

    /**
     * Get puntosCompetidor2
     *
     * @return integer
     */
    public function getPuntosCompetidor2()
    {
        return $this->puntos_competidor_2;
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
     * @return EncuentrosSistemaUno
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
