<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EventoRepository")
 */
class Evento
{
    public function __toString() {
        return $this->getNombre() ? : "";
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
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicial", type="date")
     */
    private $fechaInicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFinal", type="date")
     */
    private $fechaFinal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicial_inscripcion", type="date")
     */
    private $fechaInicialInscripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_final_inscripcion", type="date")
     */
    private $fechaFinalInscripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial", type="string", length=255,nullable=true)
     */
    private $horaInicial;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final", type="string", length=255,nullable=true)
     */
    private $horaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="cupo", type="string", length=255)
     */
    private $cupo;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_cupos", type="integer", nullable=true)
     */
    private $numeroCupos;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_equipos", type="integer", nullable=true)
     */
    private $numeroEquipos;

    /**
     * @var int
     *
     * @ORM\Column(name="participantes_equipo_minimo", type="integer", nullable=true)
     */
    private $participantesEquipoMinimo;

    /**
     * @var int
     *
     * @ORM\Column(name="participantes_equipo_maximo", type="integer", nullable=true)
     */
    private $participantesEquipoMaximo;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_mujeres", type="integer", nullable=true)
     */
    private $numeroMujeres;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_hombres", type="integer", nullable=true)
     */
    private $numeroHombres;

    /**
     * @var int
     *
     * @ORM\Column(name="edad_mayor_que", type="integer", nullable=true)
     */
    private $edadMayorQue;

    /**
     * @var int
     *
     * @ORM\Column(name="edad_menor_que", type="integer", nullable=true)
     */
    private $edadMenorQue;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="terminos_condiciones", type="text", nullable=true)
     */
    private $terminosCondiciones;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_inscripcion_publica", type="boolean", nullable=true)
     */
    private $tieneInscripcionPublica;

    /**
     * @var bool
     *
     * @ORM\Column(name="habilitar_recambios", type="boolean", nullable=true)
     */
    private $habilitarRecambios;

    /**
     * @var bool
     *
     * @ORM\Column(name="habilitar_lista_larga", type="boolean", nullable=true)
     */
    private $habilitarListaLarga;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_preinscripcion_publica", type="boolean", nullable=true)
     */
    private $tienePreinscripcionPublica;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_formulario_gandador", type="boolean", nullable=true)
     */
    private $tieneFormularioGanador;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_formulario_recambios", type="boolean", nullable=true)
     */
    private $tieneFormularioRecambios;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EventoRol", mappedBy="evento")
     */
    private $eventoRoles;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\CampoFormularioEvento", mappedBy="evento")
     */
    private $campoFormulariosEventos;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EquipoEvento", mappedBy="evento")
     */
    private $equipoEventos;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\JugadorEvento", mappedBy="evento")
     */
    private $jugadorEventos;
    
    /**
     * @ORM\OneToOne(targetEntity="CarneEvento", mappedBy="evento")
     */
    private $carne;

    /**
     * Many Evento have Many CategoriaSubCategoriaEvento.
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\CategoriaSubcategoria", inversedBy="evento", cascade={"persist"})
     * @ORM\JoinTable(name="categoria_subcategorias_eventos",
     *      inverseJoinColumns={@ORM\JoinColumn(name="categoria_subcategoria_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="evento_id", referencedColumnName="id")}
     *      )
     */
    private $categoriaSubcategorias;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SistemaJuegoUno", mappedBy="evento")
     */
    private $sistemaJuegosUno;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SistemaJuegoDos", mappedBy="evento")
     */
    private $sistemaJuegosDos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SistemaJuegoLiga", mappedBy="evento")
     */
    private $sistemaJuegosLiga;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", mappedBy="evento")
     */
    private $encuentroSistemaTres;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SistemaJuegoCuatro", mappedBy="evento")
     */
    private $sistemaJuegoCuatro;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Rama", inversedBy="eventos")
     * @ORM\JoinColumn(name="rama_id",referencedColumnName="id")
     */
    private $rama;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Enfoque", inversedBy="eventos")
     * @ORM\JoinColumn(name="enfoque_id",referencedColumnName="id")
     */
    private $enfoque;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoEnfoque", inversedBy="eventos")
     * @ORM\JoinColumn(name="tipo_enfoque_id",referencedColumnName="id")
     */
    private $tipoEnfoque;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Estrategia", inversedBy="eventos")
     * @ORM\JoinColumn(name="estrategia_id",referencedColumnName="id")
     */
    private $estrategia;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina", inversedBy="eventos")
     * @ORM\JoinColumn(name="disciplina_id",referencedColumnName="id")
     */
    private $disciplina;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\FuenteFinanciacion", inversedBy="eventos")
     * @ORM\JoinColumn(name="fuente_financiacion_id",referencedColumnName="id")
     */
    private $fuenteFinanciacion;


    ////relacion con sancionEvento 
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\SancionEvento", mappedBy="evento")
     */
    private $sancionEvento;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="eventos");
     * @ORM\JoinColumn(name="escenariodeportivo_id", referencedColumnName="id")
     */
    private $escenarioDeportivo;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Division", inversedBy="eventos");
     * @ORM\JoinColumn(name="divisiones_id", referencedColumnName="id")
     */
    private $division;


    /**
     * @ORM\ManyToOne(targetEntity="PuntoAtencion", inversedBy="eventos");
     * @ORM\JoinColumn(name="puntoatencion_id", referencedColumnName="id")
     */
    private $puntoAtencion;

     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventoRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->campoFormulariosEventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->equipoEventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->jugadorEventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sancionEvento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sistemaJuegosUno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sistemaJuegosDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sistemaJuegoLiga = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaTres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sistemaJuegoCuatro = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoriaSubcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Evento
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
     * Set estado
     *
     * @param string $estado
     *
     * @return Evento
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaInicial
     *
     * @param \DateTime $fechaInicial
     *
     * @return Evento
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * Get fechaInicial
     *
     * @return \DateTime
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     *
     * @return Evento
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Set fechaInicialInscripcion
     *
     * @param \DateTime $fechaInicialInscripcion
     *
     * @return Evento
     */
    public function setFechaInicialInscripcion($fechaInicialInscripcion)
    {
        $this->fechaInicialInscripcion = $fechaInicialInscripcion;

        return $this;
    }

    /**
     * Get fechaInicialInscripcion
     *
     * @return \DateTime
     */
    public function getFechaInicialInscripcion()
    {
        return $this->fechaInicialInscripcion;
    }

    /**
     * Set fechaFinalInscripcion
     *
     * @param \DateTime $fechaFinalInscripcion
     *
     * @return Evento
     */
    public function setFechaFinalInscripcion($fechaFinalInscripcion)
    {
        $this->fechaFinalInscripcion = $fechaFinalInscripcion;

        return $this;
    }

    /**
     * Get fechaFinalInscripcion
     *
     * @return \DateTime
     */
    public function getFechaFinalInscripcion()
    {
        return $this->fechaFinalInscripcion;
    }

    /**
     * Set cupo
     *
     * @param string $cupo
     *
     * @return Evento
     */
    public function setCupo($cupo)
    {
        $this->cupo = $cupo;

        return $this;
    }

    /**
     * Get cupo
     *
     * @return string
     */
    public function getCupo()
    {
        return $this->cupo;
    }

    /**
     * Set numeroEquipos
     *
     * @param integer $numeroEquipos
     *
     * @return Evento
     */
    public function setNumeroEquipos($numeroEquipos)
    {
        $this->numeroEquipos = $numeroEquipos;

        return $this;
    }

    /**
     * Get numeroEquipos
     *
     * @return int
     */
    public function getNumeroEquipos()
    {
        return $this->numeroEquipos;
    }

    /**
     * Set numeroCupos
     *
     * @param integer $numeroCupos
     *
     * @return Evento
     */
    public function setNumeroCupos($numeroCupos)
    {
        $this->numeroCupos = $numeroCupos;

        return $this;
    }

    /**
     * Get numeroCupos
     *
     * @return int
     */
    public function getNumeroCupos()
    {
        return $this->numeroCupos;
    }

    /**
     * Set participantesEquipoMinimo
     *
     * @param integer $participantesEquipoMinimo
     *
     * @return Evento
     */
    public function setParticipantesEquipoMinimo($participantesEquipoMinimo)
    {
        $this->participantesEquipoMinimo = $participantesEquipoMinimo;

        return $this;
    }

    /**
     * Get participantesEquipoMinimo
     *
     * @return int
     */
    public function getParticipantesEquipoMinimo()
    {
        return $this->participantesEquipoMinimo;
    }

    /**
     * Set participantesEquipoMaximo
     *
     * @param integer $participantesEquipoMaximo
     *
     * @return Evento
     */
    public function setParticipantesEquipoMaximo($participantesEquipoMaximo)
    {
        $this->participantesEquipoMaximo = $participantesEquipoMaximo;

        return $this;
    }

    /**
     * Get participantesEquipoMaximo
     *
     * @return int
     */
    public function getParticipantesEquipoMaximo()
    {
        return $this->participantesEquipoMaximo;
    }

    /**
     * Set numeroMujeres
     *
     * @param integer $numeroMujeres
     *
     * @return Evento
     */
    public function setNumeroMujeres($numeroMujeres)
    {
        $this->numeroMujeres = $numeroMujeres;

        return $this;
    }

    /**
     * Get numeroMujeres
     *
     * @return int
     */
    public function getNumeroMujeres()
    {
        return $this->numeroMujeres;
    }

    /**
     * Set numeroHombres
     *
     * @param integer $numeroHombres
     *
     * @return Evento
     */
    public function setNumeroHombres($numeroHombres)
    {
        $this->numeroHombres = $numeroHombres;

        return $this;
    }

    /**
     * Get numeroHombres
     *
     * @return int
     */
    public function getNumeroHombres()
    {
        return $this->numeroHombres;
    }

    /**
     * Set edadMayorQue
     *
     * @param integer $edadMayorQue
     *
     * @return Evento
     */
    public function setEdadMayorQue($edadMayorQue)
    {
        $this->edadMayorQue = $edadMayorQue;

        return $this;
    }

    /**
     * Get edadMayorQue
     *
     * @return int
     */
    public function getEdadMayorQue()
    {
        return $this->edadMayorQue;
    }

    /**
     * Set edadMenorQue
     *
     * @param integer $edadMenorQue
     *
     * @return Evento
     */
    public function setEdadMenorQue($edadMenorQue)
    {
        $this->edadMenorQue = $edadMenorQue;

        return $this;
    }

    /**
     * Get edadMenorQue
     *
     * @return int
     */
    public function getEdadMenorQue()
    {
        return $this->edadMenorQue;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Evento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set terminosCondiciones
     *
     * @param string $terminosCondiciones
     *
     * @return Evento
     */
    public function setTerminosCondiciones($terminosCondiciones)
    {
        $this->terminosCondiciones = $terminosCondiciones;

        return $this;
    }

    /**
     * Get terminosCondiciones
     *
     * @return string
     */
    public function getTerminosCondiciones()
    {
        return $this->terminosCondiciones;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Evento
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
        if (!$this->imagen) {
            $this->imagen = "img-perfil.png";
        }
        return $this->imagen;
    }


    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
        }

        $filename = $file->getClientOriginalName();

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = uniqid(date('YmdHis')) . '.' . $ext;
        $file->move(
                $path, $filename
        );

        return $filename;
    }


    /**
     * Set tieneInscripcionPublica
     *
     * @param boolean $tieneInscripcionPublica
     *
     * @return Evento
     */
    public function setTieneInscripcionPublica($tieneInscripcionPublica)
    {
        $this->tieneInscripcionPublica = $tieneInscripcionPublica;

        return $this;
    }

    /**
     * Get tieneInscripcionPublica
     *
     * @return bool
     */
    public function getTieneInscripcionPublica()
    {
        return $this->tieneInscripcionPublica;
    }

    /**
     * Set tienePreinscripcionPublica
     *
     * @param boolean $tienePreinscripcionPublica
     *
     * @return Evento
     */
    public function setTienePreinscripcionPublica($tienePreinscripcionPublica)
    {
        $this->tienePreinscripcionPublica = $tienePreinscripcionPublica;

        return $this;
    }

    /**
     * Get tienePreinscripcionPublica
     *
     * @return bool
     */
    public function getTienePreinscripcionPublica()
    {
        return $this->tienePreinscripcionPublica;
    }

    /**
     * Set tieneFormularioGanador
     *
     * @param boolean $tieneFormularioGanador
     *
     * @return Evento
     */
    public function setTieneFormularioGanador($tieneFormularioGanador)
    {
        $this->tieneFormularioGanador = $tieneFormularioGanador;

        return $this;
    }

    /**
     * Get tieneFormularioGanador
     *
     * @return bool
     */
    public function getTieneFormularioGanador()
    {
        return $this->tieneFormularioGanador;
    }

    /**
     * Set tieneFormularioRecambios
     *
     * @param boolean $tieneFormularioRecambios
     *
     * @return Evento
     */
    public function setTieneFormularioRecambios($tieneFormularioRecambios)
    {
        $this->tieneFormularioRecambios = $tieneFormularioRecambios;

        return $this;
    }

    /**
     * Get tieneFormularioRecambios
     *
     * @return bool
     */
    public function getTieneFormularioRecambios()
    {
        return $this->tieneFormularioRecambios;
    }

    /**
     * Add eventoRol
     *
     * @param \LogicBundle\Entity\EventoRol $eventoRol
     *
     * @return Evento
     */
    public function addEventoRoles(\LogicBundle\Entity\EventoRol $eventoRol)
    {
        $this->eventoRoles[] = $eventoRol;

        return $this;
    }

    /**
     * Remove eventoRol
     *
     * @param \LogicBundle\Entity\EventoRol $eventoRol
     */
    public function removeEventoRoles(\LogicBundle\Entity\EventoRol $eventoRol)
    {
        $this->eventoRoles->removeElement($eventoRol);
    }

    /**
     * Get eventoRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventoRoles()
    {
        return $this->eventoRoles;
    }
    
    /**
     * Add campoFormulariosEvento
     *
     * @param \LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento
     *
     * @return Evento
     */
    public function addCampoFormularioEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento)
    {
        $this->campoFormulariosEventos[] = $campoFormulariosEvento;

        return $this;
    }

    /**
     * Remove campoFormulariosEventos
     *
     * @param \LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEventos
     */
    public function removeCampoFormularioEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento)
    {
        $this->campoFormulariosEventos->removeElement($campoFormulariosEvento);
    }

    /**
     * Get campoFormulariosEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoFormularioEventos()
    {
        return $this->campoFormulariosEventos;
    }

    /**
     * Add equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return Evento
     */
    public function addEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento)
    {
        $this->equipoEventos[] = $equipoEvento;

        return $this;
    }

    /**
     * Remove equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     */
    public function removeEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento)
    {
        $this->equipoEventos->removeElement($equipoEvento);
    }

    /**
     * Get equipoEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipoEventos()
    {
        return $this->equipoEventos;
    }

     /**
     * Add jugadorEvento
     *
     * @param \LogicBundle\Entity\JugadorEvento $jugadorEvento
     *
     * @return Evento
     */
    public function addJugadorEvento(\LogicBundle\Entity\JugadorEvento $jugadorEvento)
    {
        $this->jugadorEventos[] = $jugadorEvento;

        return $this;
    }

    /**
     * Remove JugadorEvento
     *
     * @param \LogicBundle\Entity\JugadorEvento $jugadorEvento
     */
    public function removeJugadorEvento(\LogicBundle\Entity\JugadorEvento $jugadorEvento)
    {
        $this->jugadorEventos->removeElement($jugadorEvento);
    }

    /**
     * Get JugadorEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJugadorEventos()
    {
        return $this->jugadorEventos;
    }

    

    /**
     * Add sistemaJuegoUno
     *
     * @param \LogicBundle\Entity\SistemaJuegoUno $sistemaJuego
     *
     * @return Evento
     */
    public function addSistemaJuegoUno(\LogicBundle\Entity\SistemaJuegoUno $sistemaJuegoUno)
    {
        $this->sistemaJuegosUno[] = $sistemaJuego;

        return $this;
    }

    /**
     * Remove sistemaJuegoUno
     *
     * @param \LogicBundle\Entity\SistemaJuegoUno $sistemaJuego
     */
    public function removeSistemaJuegoUno(\LogicBundle\Entity\SistemaJuegoUno $sistemaJuegoUno)
    {
        $this->sistemaJuegosUno->removeElement($sistemaJuegoUno);
    }

    /**
     * Get sistemaJuegosUno
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSistemaJuegosUno()
    {
        return $this->sistemaJuegosUno;
    }

    /**
     * Add sistemaJuegosDos
     *
     * @param \LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDos
     *
     * @return Evento
     */
    public function addSistemaJuegosDos(\LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDos)
    {
        $this->sistemaJuegosDos[] = $sistemaJuegosDos;

        return $this;
    }

    /**
     * Remove sistemaJuegosDos
     *
     * @param \LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDos
     */
    public function removeSistemaJuegosDos(\LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDos)
    {
        $this->sistemaJuegosDos->removeElement($sistemaJuegosDos);
    }

    /**
     * Get sistemaJuegosDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSistemaJuegosDos()
    {
        return $this->sistemaJuegosDos;
    }

     
    /**
     * Add sistemaJuegoLiga
     *
     * @param \LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga
     *
     * @return Evento
     */
    public function addSistemaJuegoLiga(\LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga)
    {
        $this->sistemaJuegosLiga[] = $sistemaJuegoLiga;

        return $this;
    }

    /**
     * Remove sistemaJuegoLiga
     *
     * @param \LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga
     */
    public function removeSistemaJuegoLiga(\LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegoLiga)
    {
        $this->sistemaJuegosLiga->removeElement($sistemaJuegoLiga);
    }

    /**
     * Get sistemaJuegosLiga
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSistemasJuegoLiga()
    {
        return $this->sistemaJuegosLiga;
    }
    
    /**
     * Set enfoque
     *
     * @param \LogicBundle\Entity\Enfoque $enfoque
     *
     * @return Evento
     */
    public function setEnfoque(\LogicBundle\Entity\Enfoque $enfoque = null)
    {
        $this->enfoque = $enfoque;

        return $this;
    }

    /**
     * Get enfoque
     *
     * @return \LogicBundle\Entity\Enfoque
     */
    public function getEnfoque()
    {
        return $this->enfoque;
    }

    /**
     * Set tipoEnfoque
     *
     * @param \LogicBundle\Entity\TipoEnfoque $tipoEnfoque
     *
     * @return Evento
     */
    public function setTipoEnfoque(\LogicBundle\Entity\TipoEnfoque $tipoEnfoque = null)
    {
        $this->tipoEnfoque = $tipoEnfoque;

        return $this;
    }

    /**
     * Get tipoEnfoque
     *
     * @return \LogicBundle\Entity\TipoEnfoque
     */
    public function getTipoEnfoque()
    {
        return $this->tipoEnfoque;
    }

    
    /**
     * Set rama
     *
     * @param \LogicBundle\Entity\Rama $rama
     *
     * @return Evento
     */
    public function setRama(\LogicBundle\Entity\Rama $rama = null)
    {
        $this->rama = $rama;

        return $this;
    }

    /**
     * Get rama
     *
     * @return \LogicBundle\Entity\Rama
     */
    public function getRama()
    {
        return $this->rama;
    }

    /**
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return Evento
     */
    public function setEstrategia(\LogicBundle\Entity\Estrategia $estrategia = null)
    {
        $this->estrategia = $estrategia;

        return $this;
    }

    /**
     * Get estrategia
     *
     * @return \LogicBundle\Entity\Estrategia
     */
    public function getEstrategia()
    {
        return $this->estrategia;
    }
    
    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return Evento
     */
    public function setDisciplina(\LogicBundle\Entity\Disciplina $disciplina = null)
    {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina
     *
     * @return \LogicBundle\Entity\Disciplina
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * Set fuenteFinanciacion
     *
     * @param \LogicBundle\Entity\FuenteFinanciacion $fuenteFinanciacion
     *
     * @return Evento
     */
    public function setFuenteFinanciacion(\LogicBundle\Entity\FuenteFinanciacion $fuenteFinanciacion = null)
    {
        $this->fuenteFinanciacion = $fuenteFinanciacion;

        return $this;
    }

    /**
     * Get fuenteFinanciacion
     *
     * @return \LogicBundle\Entity\FuenteFinanciacion
     */
    public function getFuenteFinanciacion()
    {
        return $this->fuenteFinanciacion;
    }

    
    /**
     * Set habilitarRecambios
     *
     * @param boolean $habilitarRecambios
     *
     * @return Evento
     */
    public function setHabilitarRecambios($habilitarRecambios)
    {
        $this->habilitarRecambios = $habilitarRecambios;

        return $this;
    }

    /**
     * Get habilitarRecambios
     *
     * @return bool
     */
    public function getHabilitarRecambios()
    {
        return $this->habilitarRecambios;
    }

      
    /**
     * Set habilitarListaLarga
     *
     * @param boolean $habilitarListaLarga
     *
     * @return Evento
     */
    public function setHabilitarListaLarga($habilitarListaLarga)
    {
        $this->habilitarListaLarga = $habilitarListaLarga;

        return $this;
    }

    /**
     * Get habilitarListaLarga
     *
     * @return bool
     */
    public function getHabilitarListaLarga()
    {
        return $this->habilitarListaLarga;
    }


    ////// sancionEvento

    /**
     * Add sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return Evento
     */
    public function addSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento)
    {
        $this->sancionEvento[] = $sancionEvento;

        return $this;
    }

    /**
     * Remove sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     */
    public function removeSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento)
    {
        $this->sancionEvento->removeElement($sancionEvento);
    }

    /**
     * Get sancionEvento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSancionEvento()
    {
        return $this->sancionEvento;
    }    

      /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return Evento
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
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return Evento
     */
    public function setPuntoAtencion(\LogicBundle\Entity\PuntoAtencion $puntoAtencion = null)
    {
        $this->puntoAtencion = $puntoAtencion;

        return $this;
    }

    /**
     * Get puntoAtencion
     *
     * @return \LogicBundle\Entity\PuntoAtencion
     */
    public function getPuntoAtencion()
    {
        return $this->puntoAtencion;
    }

    /**
     * Add encuentroSistemaTres
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres
     *
     * @return encuentroSistemaTres
     */
    public function addEncuentroSistemaTres(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres)
    {
        $this->encuentroSistemaTres[] = $encuentroSistemaTres;

        return $this;
    }

    /**
     * Remove encuentroSistemaTres
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres
     */
    public function removeEncuentroSistemaTres(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres)
    {
        $this->encuentroSistemaTres->removeElement($encuentroSistemaTres);
    }

    /**
     * Get encuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaTres()
    {
        return $this->encuentroSistemaTres;
    }

    /**
     * Add sistemaJuegoCuatro
     *
     * @param \LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro
     *
     * @return sistemaJuegoCuatro
     */
    public function addSistemaJuegoCuatro(\LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro)
    {
        $this->sistemaJuegoCuatro[] = $sistemaJuegoCuatro;

        return $this;
    }

    /**
     * Remove sistemaJuegoCuatro
     *
     * @param \LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro
     */
    public function removeSistemaJuegoCuatro(\LogicBundle\Entity\SistemaJuegoCuatro $sistemaJuegoCuatro)
    {
        $this->sistemaJuegoCuatro->removeElement($sistemaJuegoCuatro);
    }

    /**
     * Get sistemaJuegoCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSistemaJuegoCuatro()
    {
        return $this->sistemaJuegoCuatro;
    }

    /**
     * Add eventoRole
     *
     * @param \LogicBundle\Entity\EventoRol $eventoRole
     *
     * @return Evento
     */
    public function addEventoRole(\LogicBundle\Entity\EventoRol $eventoRole)
    {
        $this->eventoRoles[] = $eventoRole;

        return $this;
    }

    /**
     * Remove eventoRole
     *
     * @param \LogicBundle\Entity\EventoRol $eventoRole
     */
    public function removeEventoRole(\LogicBundle\Entity\EventoRol $eventoRole)
    {
        $this->eventoRoles->removeElement($eventoRole);
    }

    /**
     * Add campoFormulariosEvento
     *
     * @param \LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento
     *
     * @return Evento
     */
    public function addCampoFormulariosEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento)
    {
        $this->campoFormulariosEventos[] = $campoFormulariosEvento;

        return $this;
    }

    /**
     * Remove campoFormulariosEvento
     *
     * @param \LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento
     */
    public function removeCampoFormulariosEvento(\LogicBundle\Entity\CampoFormularioEvento $campoFormulariosEvento)
    {
        $this->campoFormulariosEventos->removeElement($campoFormulariosEvento);
    }

    /**
     * Get campoFormulariosEventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoFormulariosEventos()
    {
        return $this->campoFormulariosEventos;
    }

    /**
     * Add sistemaJuegosUno
     *
     * @param \LogicBundle\Entity\SistemaJuegoUno $sistemaJuegosUno
     *
     * @return Evento
     */
    public function addSistemaJuegosUno(\LogicBundle\Entity\SistemaJuegoUno $sistemaJuegosUno)
    {
        $this->sistemaJuegosUno[] = $sistemaJuegosUno;

        return $this;
    }

    /**
     * Remove sistemaJuegosUno
     *
     * @param \LogicBundle\Entity\SistemaJuegoUno $sistemaJuegosUno
     */
    public function removeSistemaJuegosUno(\LogicBundle\Entity\SistemaJuegoUno $sistemaJuegosUno)
    {
        $this->sistemaJuegosUno->removeElement($sistemaJuegosUno);
    }

    /**
     * Add sistemaJuegosDo
     *
     * @param \LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDo
     *
     * @return Evento
     */
    public function addSistemaJuegosDo(\LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDo)
    {
        $this->sistemaJuegosDos[] = $sistemaJuegosDo;

        return $this;
    }

    /**
     * Remove sistemaJuegosDo
     *
     * @param \LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDo
     */
    public function removeSistemaJuegosDo(\LogicBundle\Entity\SistemaJuegoDos $sistemaJuegosDo)
    {
        $this->sistemaJuegosDos->removeElement($sistemaJuegosDo);
    }

    /**
     * Add sistemaJuegosLiga
     *
     * @param \LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegosLiga
     *
     * @return Evento
     */
    public function addSistemaJuegosLiga(\LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegosLiga)
    {
        $this->sistemaJuegosLiga[] = $sistemaJuegosLiga;

        return $this;
    }

    /**
     * Remove sistemaJuegosLiga
     *
     * @param \LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegosLiga
     */
    public function removeSistemaJuegosLiga(\LogicBundle\Entity\SistemaJuegoLiga $sistemaJuegosLiga)
    {
        $this->sistemaJuegosLiga->removeElement($sistemaJuegosLiga);
    }

    /**
     * Get sistemaJuegosLiga
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSistemaJuegosLiga()
    {
        return $this->sistemaJuegosLiga;
    }

    /**
     * Add encuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre
     *
     * @return Evento
     */
    public function addEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre)
    {
        $this->encuentroSistemaTres[] = $encuentroSistemaTre;

        return $this;
    }

    /**
     * Remove encuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre
     */
    public function removeEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre)
    {
        $this->encuentroSistemaTres->removeElement($encuentroSistemaTre);
    }

    /**
     * Add categoriaSubcategoria
     *
     * @param \LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria
     *
     * @return Evento
     */
    public function addCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria)
    {
        $this->categoriaSubcategorias[] = $categoriaSubcategoria;

        return $this;
    }

    /**
     * Remove categoriaSubcategoria
     *
     * @param \LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria
     */
    public function removeCategoriaSubcategoria(\LogicBundle\Entity\CategoriaSubcategoria $categoriaSubcategoria)
    {
        $this->categoriaSubcategorias->removeElement($categoriaSubcategoria);
    }

    /**
     * Get categoriaSubcategorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriaSubcategorias()
    {
        return $this->categoriaSubcategorias;
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
        $this->$division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return \LogicBundle\Entity\Division
     */
    public function getDivisiones()
    {
        return $this->$division;
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

    /**
     * Set horaInicial
     *
     * @param string $horaInicial
     *
     * @return Evento
     */
    public function setHoraInicial($horaInicial)
    {
        $this->horaInicial = $horaInicial;

        return $this;
    }

    /**
     * Get horaInicial
     *
     * @return string
     */
    public function getHoraInicial()
    {
        return $this->horaInicial;
    }

    /**
     * Set horaFinal
     *
     * @param string $horaFinal
     *
     * @return Evento
     */
    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $horaFinal;

        return $this;
    }

    /**
     * Get horaFinal
     *
     * @return string
     */
    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    /**
     * Set carne.
     *
     * @param \LogicBundle\Entity\CarneEvento|null $carne
     *
     * @return Evento
     */
    public function setCarne(\LogicBundle\Entity\CarneEvento $carne = null)
    {
        $this->carne = $carne;

        return $this;
    }

    /**
     * Get carne.
     *
     * @return \LogicBundle\Entity\CarneEvento|null
     */
    public function getCarne()
    {
        return $this->carne;
    }
}
