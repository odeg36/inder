<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

/**
 * EscenarioDeportivo
 * @Gedmo\Loggable
 * @ORM\Table(name="escenario_deportivo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenarioDeportivoRepository")
 */
class EscenarioDeportivo {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @var int
     * @Gedmo\Versioned
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="latitud", type="string", length=255, nullable=true)
     */
    private $latitud;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="longitud", type="string", length=255, nullable=true)
     */
    private $longitud;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="localizacion", type="string", length=255, nullable=true)
     */
    private $localizacion;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="normaEscenario", type="text",  nullable=true)
     */
    private $normaEscenario;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="informacionReserva", type="text",  nullable=true)
     */
    private $informacionReserva;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="ancho", type="string", length=255, nullable=true)
     */
    private $ancho;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="largo", type="string", length=255, nullable=true)
     */
    private $largo;

    /**
     * @var string
     *
     * @ORM\Column(name="profundidad", type="string", length=255, nullable=true)
     */
    private $profundidad;

    /**
     * @var string
     * 
     * @ORM\Column(name="imagen_escenario_dividido", type="string", length=255, nullable=true)
     */
    private $imagenEscenarioDividido;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="cobamaLote", type="string", length=255, nullable=true)
     */
    private $cobamaLote;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="codigoEscenario", type="string", length=255, nullable=true)
     */
    private $codigoEscenario;

    /**
     * @var \DateTime $fechaCreacion
     * @Gedmo\Versioned
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     * @Gedmo\Versioned
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="hora_inicial", type="string", length=255, nullable=true)
     */
    private $horaInicial;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="hora_final", type="string", length=255, nullable=true)
     */
    private $horaFinal;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="tiene_acueducto", nullable=true)
     */
    protected $tieneAcueducto;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_contadores_acueducto", type="integer", nullable=true)
     */
    private $cantidadContadoresAcueducto;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_contadores_acueducto", type="text", nullable=true)
     */
    private $codigoContadoresAcueducto;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones_acueducto", type="text", nullable=true)
     */
    private $observacionesAcueducto;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="tiene_energia", nullable=true)
     */
    protected $tieneEnergia;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_contadores_energia", type="integer", nullable=true)
     */
    private $cantidadContadoresEnergia;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_contadores_energia", type="text",nullable=true)
     */
    private $codigoContadoresEnergia;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones_energia", type="text", nullable=true)
     */
    private $observacionesEnergia;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="tiene_telefono", nullable=true)
     */
    protected $tieneTelefono;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones_telefono", type="text", nullable=true)
     */
    private $observacionesTelefono;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="tiene_internet", nullable=true)
     */
    protected $tieneInternet;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="tiene_iluminacion", nullable=true)
     */
    protected $tieneIluminacion;

    /**
     * @var string
     *
     * @ORM\Column(name="fuente", type="text", nullable=true)
     */
    private $fuente;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="escenarioDeportivo");
     */
    private $oferta;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Barrio")
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id", )
     */
    private $barrio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\UnidadDeportiva", inversedBy="escenariosDeportivo")
     * @ORM\JoinColumn(name="unidad_deportiva_id", referencedColumnName="id", )
     */
    private $unidadDeportiva;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoEscenario")
     * @ORM\JoinColumn(name="tipo_escenario_id", referencedColumnName="id", )
     */
    private $tipoEscenario;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ArchivoEscenario", mappedBy="escenarioDeportivo")
     */
    private $archivoEscenarios;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Division", cascade={"persist"}, mappedBy="escenarioDeportivo", orphanRemoval=true)
     */
    private $divisiones;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivo", mappedBy="escenarioDeportivo")
     */
    private $tipoReservaEscenarioDeportivos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TendenciaEscenarioDeportivo", mappedBy="escenarioDeportivo")
     */
    private $tendenciaEscenarioDeportivos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinasEscenarioDeportivo", mappedBy="escenarioDeportivo")
     */
    private $disciplinasEscenarioDeportivos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\UsuarioEscenarioDeportivo", mappedBy="escenarioDeportivo")
     */
    private $usuarioEscenarioDeportivos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="escenarioDeportivo");
     */
    private $eventos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaUno", mappedBy="escenarioDeportivo");
     */
    private $encuentroSistemaUno;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", mappedBy="escenarioDeportivo");
     */
    private $encuentroSistemaDos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", mappedBy="escenarioDeportivo");
     */
    private $encuentroSistemaCuatro;

    /**
     * @var string
     *
     * @ORM\Column(name="matricula", type="string", length=255, nullable=true)
     */
    private $matricula;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", mappedBy="escenarioDeportivo");
     */
    private $encuentroSistemaTres;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaInfraestructura", mappedBy="escenarioDeportivo", cascade={ "persist"}, orphanRemoval=true);
     */
    private $escenarioCategoriaInfraestructuras;

    /**
     * @ORM\OneToMany(targetEntity="EscenarioCategoriaAmbiental", cascade={"persist"}, mappedBy="escenarioDeportivo");
     */
    private $escenarioCategoriaAmbientales;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoDireccion")
     * @ORM\JoinColumn(name="tipo_direccion", referencedColumnName="id")     
     */
    private $tipoDireccion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="horario_division", type="boolean", nullable=true)
     */
    private $horarioDivision;


    /* horarios de la mañana */

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_lunes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_lunes", type="string", length=255, nullable=true)
     */
    private $hora_final_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_martes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_martes", type="string", length=255, nullable=true)
     */
    private $hora_final_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_inicial_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_final_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_jueves", type="string", length=255, nullable=true)
     */
    private $hora_inicial_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_jueves", type="string", length=255, nullable=true)
     */
    private $hora_final_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_viernes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_viernes", type="string", length=255, nullable=true)
     */
    private $hora_final_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_sabado", type="string", length=255, nullable=true)
     */
    private $hora_inicial_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_sabado", type="string", length=255, nullable=true)
     */
    private $hora_final_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_domingo", type="string", length=255, nullable=true)
     */
    private $hora_inicial_domingo;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_domingo", type="string", length=255, nullable=true)
     */
    private $hora_final_domingo;

    /* horarios para la  tarde */

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_lunes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_lunes", type="string", length=255, nullable=true)
     */
    private $hora_final2_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_martes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_martes", type="string", length=255, nullable=true)
     */
    private $hora_final2_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_final2_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_jueves", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_jueves", type="string", length=255, nullable=true)
     */
    private $hora_final2_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_viernes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_viernes", type="string", length=255, nullable=true)
     */
    private $hora_final2_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_sabado", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_sabado", type="string", length=255, nullable=true)
     */
    private $hora_final2_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_domingo", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_domingo;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_domingo", type="string", length=255, nullable=true)
     */
    private $hora_final2_domingo;

    /**
     * Add escenarioCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura
     *
     * @return EscenarioDeportivo
     */
    public function addEscenarioCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura) {
        if ($escenarioCategoriaInfraestructura) {
            $escenarioCategoriaInfraestructura->setEscenarioDeportivo($this);
        }

        $this->escenarioCategoriaInfraestructuras[] = $escenarioCategoriaInfraestructura;

        return $this;
    }

    /**
     * Add escenarioCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale
     *
     * @return EscenarioDeportivo
     */
    public function addEscenarioCategoriaAmbientale(\LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale) {
        if ($escenarioCategoriaAmbientale) {
            $escenarioCategoriaAmbientale->setEscenarioDeportivo($this);
        }
        $this->escenarioCategoriaAmbientales[] = $escenarioCategoriaAmbientale;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->oferta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->archivoEscenarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->divisiones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoReservaEscenarioDeportivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendenciaEscenarioDeportivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinasEscenarioDeportivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarioEscenarioDeportivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaUno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaTres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioCategoriaInfraestructuras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioCategoriaAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return EscenarioDeportivo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return EscenarioDeportivo
     */
    public function setDireccion($direccion) {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion() {
        return $this->direccion;
    }

    /**
     * Set latitud
     *
     * @param string $latitud
     *
     * @return EscenarioDeportivo
     */
    public function setLatitud($latitud) {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return string
     */
    public function getLatitud() {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     *
     * @return EscenarioDeportivo
     */
    public function setLongitud($longitud) {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return string
     */
    public function getLongitud() {
        return $this->longitud;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return EscenarioDeportivo
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return EscenarioDeportivo
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set normaEscenario
     *
     * @param string $normaEscenario
     *
     * @return EscenarioDeportivo
     */
    public function setNormaEscenario($normaEscenario) {
        $this->normaEscenario = $normaEscenario;

        return $this;
    }

    /**
     * Get normaEscenario
     *
     * @return string
     */
    public function getNormaEscenario() {
        return $this->normaEscenario;
    }

    /**
     * Set informacionReserva
     *
     * @param string $informacionReserva
     *
     * @return EscenarioDeportivo
     */
    public function setInformacionReserva($informacionReserva) {
        $this->informacionReserva = $informacionReserva;

        return $this;
    }

    /**
     * Get informacionReserva
     *
     * @return string
     */
    public function getInformacionReserva() {
        return $this->informacionReserva;
    }

    /**
     * Set ancho
     *
     * @param string $ancho
     *
     * @return EscenarioDeportivo
     */
    public function setAncho($ancho) {
        $this->ancho = $ancho;

        return $this;
    }

    /**
     * Get ancho
     *
     * @return string
     */
    public function getAncho() {
        return $this->ancho;
    }

    /**
     * Set largo
     *
     * @param string $largo
     *
     * @return EscenarioDeportivo
     */
    public function setLargo($largo) {
        $this->largo = $largo;

        return $this;
    }

    /**
     * Get largo
     *
     * @return string
     */
    public function getLargo() {
        return $this->largo;
    }

    /**
     * Set profundidad
     *
     * @param string $profundidad
     *
     * @return EscenarioDeportivo
     */
    public function setProfundidad($profundidad) {
        $this->profundidad = $profundidad;

        return $this;
    }

    /**
     * Get profundidad
     *
     * @return string
     */
    public function getProfundidad() {
        return $this->profundidad;
    }

    /**
     * Set imagenEscenarioDividido
     *
     * @param string $imagenEscenarioDividido
     *
     * @return EscenarioDeportivo
     */
    public function setImagenEscenarioDividido($imagenEscenarioDividido) {
        $this->imagenEscenarioDividido = $imagenEscenarioDividido;

        return $this;
    }

    /**
     * Get imagenEscenarioDividido
     *
     * @return string
     */
    public function getImagenEscenarioDividido() {
        return $this->imagenEscenarioDividido;
    }

    /**
     * Set cobamaLote
     *
     * @param string $cobamaLote
     *
     * @return EscenarioDeportivo
     */
    public function setCobamaLote($cobamaLote) {
        $this->cobamaLote = $cobamaLote;

        return $this;
    }

    /**
     * Get cobamaLote
     *
     * @return string
     */
    public function getCobamaLote() {
        return $this->cobamaLote;
    }

    /**
     * Set codigoEscenario
     *
     * @param string $codigoEscenario
     *
     * @return EscenarioDeportivo
     */
    public function setCodigoEscenario($codigoEscenario) {
        $this->codigoEscenario = $codigoEscenario;

        return $this;
    }

    /**
     * Get codigoEscenario
     *
     * @return string
     */
    public function getCodigoEscenario() {
        return $this->codigoEscenario;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return EscenarioDeportivo
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return EscenarioDeportivo
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Set horaInicial
     *
     * @param string $horaInicial
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial($horaInicial) {
        $this->horaInicial = $horaInicial;

        return $this;
    }

    /**
     * Get horaInicial
     *
     * @return string
     */
    public function getHoraInicial() {
        return $this->horaInicial;
    }

    /**
     * Set horaFinal
     *
     * @param string $horaFinal
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal($horaFinal) {
        $this->horaFinal = $horaFinal;

        return $this;
    }

    /**
     * Get horaFinal
     *
     * @return string
     */
    public function getHoraFinal() {
        return $this->horaFinal;
    }

    /**
     * Set tieneAcueducto
     *
     * @param boolean $tieneAcueducto
     *
     * @return EscenarioDeportivo
     */
    public function setTieneAcueducto($tieneAcueducto) {
        $this->tieneAcueducto = $tieneAcueducto;

        return $this;
    }

    /**
     * Get tieneAcueducto
     *
     * @return boolean
     */
    public function getTieneAcueducto() {
        return $this->tieneAcueducto;
    }

    /**
     * Set cantidadContadoresAcueducto
     *
     * @param integer $cantidadContadoresAcueducto
     *
     * @return EscenarioDeportivo
     */
    public function setCantidadContadoresAcueducto($cantidadContadoresAcueducto) {
        $this->cantidadContadoresAcueducto = $cantidadContadoresAcueducto;

        return $this;
    }

    /**
     * Get cantidadContadoresAcueducto
     *
     * @return integer
     */
    public function getCantidadContadoresAcueducto() {
        return $this->cantidadContadoresAcueducto;
    }

    /**
     * Set codigoContadoresAcueducto
     *
     * @param string $codigoContadoresAcueducto
     *
     * @return EscenarioDeportivo
     */
    public function setCodigoContadoresAcueducto($codigoContadoresAcueducto) {
        $this->codigoContadoresAcueducto = $codigoContadoresAcueducto;

        return $this;
    }

    /**
     * Get codigoContadoresAcueducto
     *
     * @return string
     */
    public function getCodigoContadoresAcueducto() {
        return $this->codigoContadoresAcueducto;
    }

    /**
     * Set observacionesAcueducto
     *
     * @param string $observacionesAcueducto
     *
     * @return EscenarioDeportivo
     */
    public function setObservacionesAcueducto($observacionesAcueducto) {
        $this->observacionesAcueducto = $observacionesAcueducto;

        return $this;
    }

    /**
     * Get observacionesAcueducto
     *
     * @return string
     */
    public function getObservacionesAcueducto() {
        return $this->observacionesAcueducto;
    }

    /**
     * Set tieneEnergia
     *
     * @param boolean $tieneEnergia
     *
     * @return EscenarioDeportivo
     */
    public function setTieneEnergia($tieneEnergia) {
        $this->tieneEnergia = $tieneEnergia;

        return $this;
    }

    /**
     * Get tieneEnergia
     *
     * @return boolean
     */
    public function getTieneEnergia() {
        return $this->tieneEnergia;
    }

    /**
     * Set cantidadContadoresEnergia
     *
     * @param integer $cantidadContadoresEnergia
     *
     * @return EscenarioDeportivo
     */
    public function setCantidadContadoresEnergia($cantidadContadoresEnergia) {
        $this->cantidadContadoresEnergia = $cantidadContadoresEnergia;

        return $this;
    }

    /**
     * Get cantidadContadoresEnergia
     *
     * @return integer
     */
    public function getCantidadContadoresEnergia() {
        return $this->cantidadContadoresEnergia;
    }

    /**
     * Set codigoContadoresEnergia
     *
     * @param string $codigoContadoresEnergia
     *
     * @return EscenarioDeportivo
     */
    public function setCodigoContadoresEnergia($codigoContadoresEnergia) {
        $this->codigoContadoresEnergia = $codigoContadoresEnergia;

        return $this;
    }

    /**
     * Get codigoContadoresEnergia
     *
     * @return string
     */
    public function getCodigoContadoresEnergia() {
        return $this->codigoContadoresEnergia;
    }

    /**
     * Set observacionesEnergia
     *
     * @param string $observacionesEnergia
     *
     * @return EscenarioDeportivo
     */
    public function setObservacionesEnergia($observacionesEnergia) {
        $this->observacionesEnergia = $observacionesEnergia;

        return $this;
    }

    /**
     * Get observacionesEnergia
     *
     * @return string
     */
    public function getObservacionesEnergia() {
        return $this->observacionesEnergia;
    }

    /**
     * Set tieneTelefono
     *
     * @param boolean $tieneTelefono
     *
     * @return EscenarioDeportivo
     */
    public function setTieneTelefono($tieneTelefono) {
        $this->tieneTelefono = $tieneTelefono;

        return $this;
    }

    /**
     * Get tieneTelefono
     *
     * @return boolean
     */
    public function getTieneTelefono() {
        return $this->tieneTelefono;
    }

    /**
     * Set observacionesTelefono
     *
     * @param string $observacionesTelefono
     *
     * @return EscenarioDeportivo
     */
    public function setObservacionesTelefono($observacionesTelefono) {
        $this->observacionesTelefono = $observacionesTelefono;

        return $this;
    }

    /**
     * Get observacionesTelefono
     *
     * @return string
     */
    public function getObservacionesTelefono() {
        return $this->observacionesTelefono;
    }

    /**
     * Set tieneInternet
     *
     * @param boolean $tieneInternet
     *
     * @return EscenarioDeportivo
     */
    public function setTieneInternet($tieneInternet) {
        $this->tieneInternet = $tieneInternet;

        return $this;
    }

    /**
     * Get tieneInternet
     *
     * @return boolean
     */
    public function getTieneInternet() {
        return $this->tieneInternet;
    }

    /**
     * Set tieneIluminacion
     *
     * @param boolean $tieneIluminacion
     *
     * @return EscenarioDeportivo
     */
    public function setTieneIluminacion($tieneIluminacion) {
        $this->tieneIluminacion = $tieneIluminacion;

        return $this;
    }

    /**
     * Get tieneIluminacion
     *
     * @return boolean
     */
    public function getTieneIluminacion() {
        return $this->tieneIluminacion;
    }

    /**
     * Set fuente
     *
     * @param string $fuente
     *
     * @return EscenarioDeportivo
     */
    public function setFuente($fuente) {
        $this->fuente = $fuente;

        return $this;
    }

    /**
     * Get fuente
     *
     * @return string
     */
    public function getFuente() {
        return $this->fuente;
    }

    /**
     * Add ofertum
     *
     * @param \LogicBundle\Entity\Oferta $ofertum
     *
     * @return EscenarioDeportivo
     */
    public function addOfertum(\LogicBundle\Entity\Oferta $ofertum) {
        $this->oferta[] = $ofertum;

        return $this;
    }

    /**
     * Remove ofertum
     *
     * @param \LogicBundle\Entity\Oferta $ofertum
     */
    public function removeOfertum(\LogicBundle\Entity\Oferta $ofertum) {
        $this->oferta->removeElement($ofertum);
    }

    /**
     * Get oferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOferta() {
        return $this->oferta;
    }

    /**
     * Set barrio
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return EscenarioDeportivo
     */
    public function setBarrio(\LogicBundle\Entity\Barrio $barrio = null) {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return \LogicBundle\Entity\Barrio
     */
    public function getBarrio() {
        return $this->barrio;
    }

    /**
     * Set unidadDeportiva
     *
     * @param \LogicBundle\Entity\UnidadDeportiva $unidadDeportiva
     *
     * @return EscenarioDeportivo
     */
    public function setUnidadDeportiva(\LogicBundle\Entity\UnidadDeportiva $unidadDeportiva = null) {
        $this->unidadDeportiva = $unidadDeportiva;

        return $this;
    }

    /**
     * Get unidadDeportiva
     *
     * @return \LogicBundle\Entity\UnidadDeportiva
     */
    public function getUnidadDeportiva() {
        return $this->unidadDeportiva;
    }

    /**
     * Set tipoEscenario
     *
     * @param \LogicBundle\Entity\TipoEscenario $tipoEscenario
     *
     * @return EscenarioDeportivo
     */
    public function setTipoEscenario(\LogicBundle\Entity\TipoEscenario $tipoEscenario = null) {
        $this->tipoEscenario = $tipoEscenario;

        return $this;
    }

    /**
     * Get tipoEscenario
     *
     * @return \LogicBundle\Entity\TipoEscenario
     */
    public function getTipoEscenario() {
        return $this->tipoEscenario;
    }

    /**
     * Add archivoEscenario
     *
     * @param \LogicBundle\Entity\ArchivoEscenario $archivoEscenario
     *
     * @return EscenarioDeportivo
     */
    public function addArchivoEscenario(\LogicBundle\Entity\ArchivoEscenario $archivoEscenario) {
        $this->archivoEscenarios[] = $archivoEscenario;

        return $this;
    }

    /**
     * Remove archivoEscenario
     *
     * @param \LogicBundle\Entity\ArchivoEscenario $archivoEscenario
     */
    public function removeArchivoEscenario(\LogicBundle\Entity\ArchivoEscenario $archivoEscenario) {
        $this->archivoEscenarios->removeElement($archivoEscenario);
    }

    /**
     * Get archivoEscenarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivoEscenarios() {
        return $this->archivoEscenarios;
    }

    /**
     * Add divisione
     *
     * @param \LogicBundle\Entity\Division $divisione
     *
     * @return EscenarioDeportivo
     */
    public function addDivisione(\LogicBundle\Entity\Division $divisione) {
        $this->divisiones[] = $divisione;

        return $this;
    }

    /**
     * Remove divisione
     *
     * @param \LogicBundle\Entity\Division $divisione
     */
    public function removeDivisione(\LogicBundle\Entity\Division $divisione) {
        $this->divisiones->removeElement($divisione);
    }

    /**
     * Get divisiones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisiones() {
        return $this->divisiones;
    }

    /**
     * Add tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     *
     * @return EscenarioDeportivo
     */
    public function addTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tipoReservaEscenarioDeportivos[] = $tipoReservaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     */
    public function removeTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tipoReservaEscenarioDeportivos->removeElement($tipoReservaEscenarioDeportivo);
    }

    /**
     * Get tipoReservaEscenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoReservaEscenarioDeportivos() {
        return $this->tipoReservaEscenarioDeportivos;
    }

    /**
     * Add tendenciaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo
     *
     * @return EscenarioDeportivo
     */
    public function addTendenciaEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo) {
        $this->tendenciaEscenarioDeportivos[] = $tendenciaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tendenciaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo
     */
    public function removeTendenciaEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo) {
        $this->tendenciaEscenarioDeportivos->removeElement($tendenciaEscenarioDeportivo);
    }

    /**
     * Get tendenciaEscenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTendenciaEscenarioDeportivos() {
        return $this->tendenciaEscenarioDeportivos;
    }

    /**
     * Add disciplinasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinasEscenarioDeportivo
     *
     * @return EscenarioDeportivo
     */
    public function addDisciplinasEscenarioDeportivo(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinasEscenarioDeportivo) {
        $this->disciplinasEscenarioDeportivos[] = $disciplinasEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove disciplinasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinasEscenarioDeportivo
     */
    public function removeDisciplinasEscenarioDeportivo(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinasEscenarioDeportivo) {
        $this->disciplinasEscenarioDeportivos->removeElement($disciplinasEscenarioDeportivo);
    }

    /**
     * Get disciplinasEscenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinasEscenarioDeportivos() {
        return $this->disciplinasEscenarioDeportivos;
    }

    /**
     * Add usuarioEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\UsuarioEscenarioDeportivo $usuarioEscenarioDeportivo
     *
     * @return EscenarioDeportivo
     */
    public function addUsuarioEscenarioDeportivo(\LogicBundle\Entity\UsuarioEscenarioDeportivo $usuarioEscenarioDeportivo) {
        $this->usuarioEscenarioDeportivos[] = $usuarioEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove usuarioEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\UsuarioEscenarioDeportivo $usuarioEscenarioDeportivo
     */
    public function removeUsuarioEscenarioDeportivo(\LogicBundle\Entity\UsuarioEscenarioDeportivo $usuarioEscenarioDeportivo) {
        $this->usuarioEscenarioDeportivos->removeElement($usuarioEscenarioDeportivo);
    }

    /**
     * Get usuarioEscenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarioEscenarioDeportivos() {
        return $this->usuarioEscenarioDeportivos;
    }

    /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return EscenarioDeportivo
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Add encuentroSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno
     *
     * @return EscenarioDeportivo
     */
    public function addEncuentroSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno) {
        $this->encuentroSistemaUno[] = $encuentroSistemaUno;

        return $this;
    }

    /**
     * Remove encuentroSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno
     */
    public function removeEncuentroSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno) {
        $this->encuentroSistemaUno->removeElement($encuentroSistemaUno);
    }

    /**
     * Get encuentroSistemaUno
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaUno() {
        return $this->encuentroSistemaUno;
    }

    /**
     * Add encuentroSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo
     *
     * @return EscenarioDeportivo
     */
    public function addEncuentroSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo) {
        $this->encuentroSistemaDos[] = $encuentroSistemaDo;

        return $this;
    }

    /**
     * Remove encuentroSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo
     */
    public function removeEncuentroSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo) {
        $this->encuentroSistemaDos->removeElement($encuentroSistemaDo);
    }

    /**
     * Get encuentroSistemaDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaDos() {
        return $this->encuentroSistemaDos;
    }

    /**
     * Add encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     *
     * @return EscenarioDeportivo
     */
    public function addEncuentroSistemaCuatro(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro) {
        $this->encuentroSistemaCuatro[] = $encuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove encuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro
     */
    public function removeEncuentroSistemaCuatro(\LogicBundle\Entity\EncuentroSistemaCuatro $encuentroSistemaCuatro) {
        $this->encuentroSistemaCuatro->removeElement($encuentroSistemaCuatro);
    }

    /**
     * Get encuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaCuatro() {
        return $this->encuentroSistemaCuatro;
    }

    /**
     * Add encuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre
     *
     * @return EscenarioDeportivo
     */
    public function addEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre) {
        $this->encuentroSistemaTres[] = $encuentroSistemaTre;

        return $this;
    }

    /**
     * Set matricula
     *
     * @param string $matricula
     *
     * @return EscenarioDeportivo
     */
    public function setMatricula($matricula) {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * Remove encuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre
     */
    public function removeEncuentroSistemaTre(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre) {
        $this->encuentroSistemaTres->removeElement($encuentroSistemaTre);
    }

    /**
     * Get encuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaTres() {
        return $this->encuentroSistemaTres;
    }

    /**
     * Remove escenarioCategoriaInfraestructura
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura
     */
    public function removeEscenarioCategoriaInfraestructura(\LogicBundle\Entity\EscenarioCategoriaInfraestructura $escenarioCategoriaInfraestructura) {
        $this->escenarioCategoriaInfraestructuras->removeElement($escenarioCategoriaInfraestructura);
    }

    /**
     * Get escenarioCategoriaInfraestructuras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaInfraestructuras() {
        return $this->escenarioCategoriaInfraestructuras;
    }

    /**
     * Remove escenarioCategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale
     */
    public function removeEscenarioCategoriaAmbientale(\LogicBundle\Entity\EscenarioCategoriaAmbiental $escenarioCategoriaAmbientale) {
        $this->escenarioCategoriaAmbientales->removeElement($escenarioCategoriaAmbientale);
    }

    /**
     * Get escenarioCategoriaAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioCategoriaAmbientales() {
        return $this->escenarioCategoriaAmbientales;
    }

    /**
     * Set tipoDireccion
     *
     * @param \LogicBundle\Entity\TipoDireccion $tipoDireccion
     *
     * @return EscenarioDeportivo
     */
    public function setTipoDireccion(\LogicBundle\Entity\TipoDireccion $tipoDireccion = null) {
        $this->tipoDireccion = $tipoDireccion;

        return $this;
    }

    /**
     * Get tipoDireccion
     *
     * @return \LogicBundle\Entity\TipoDireccion
     */
    public function getTipoDireccion() {
        return $this->tipoDireccion;
    }

    /**
     * Set hora_inicial_lunes
     *
     * @param string $hora_inicial_lunes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialLunes($hora_inicial_lunes) {
        $this->hora_inicial_lunes = $hora_inicial_lunes;

        return $this;
    }

    /**
     * Get hora_inicial_lunes
     *
     * @return string
     */
    public function getHoraInicialLunes() {
        return $this->hora_inicial_lunes;
    }

    /**
     * Set hora_final_lunes
     *
     * @param string $hora_final_lunes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalLunes($hora_final_lunes) {
        $this->hora_final_lunes = $hora_final_lunes;

        return $this;
    }

    /**
     * Get hora_final_lunes
     *
     * @return string
     */
    public function getHoraFinalLunes() {
        return $this->hora_final_lunes;
    }

    /**
     * Set hora_inicial_martes
     *
     * @param string $hora_inicial_martes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialMartes($hora_inicial_martes) {
        $this->hora_inicial_martes = $hora_inicial_martes;

        return $this;
    }

    /**
     * Get hora_inicial_martes
     *
     * @return string
     */
    public function getHoraInicialMartes() {
        return $this->hora_inicial_martes;
    }

    /**
     * Set hora_final_martes
     *
     * @param string $hora_final_martes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalMartes($hora_final_martes) {
        $this->hora_final_martes = $hora_final_martes;

        return $this;
    }

    /**
     * Get hora_final_martes
     *
     * @return string
     */
    public function getHoraFinalMartes() {
        return $this->hora_final_martes;
    }

    /**
     * Set hora_inicial_miercoles
     *
     * @param string $hora_inicial_miercoles
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialMiercoles($hora_inicial_miercoles) {
        $this->hora_inicial_miercoles = $hora_inicial_miercoles;

        return $this;
    }

    /**
     * Get hora_inicial_miercoles
     *
     * @return string
     */
    public function getHoraInicialMiercoles() {
        return $this->hora_inicial_miercoles;
    }

    /**
     * Set hora_final_miercoles
     *
     * @param string $hora_final_miercoles
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalMiercoles($hora_final_miercoles) {
        $this->hora_final_miercoles = $hora_final_miercoles;

        return $this;
    }

    /**
     * Get hora_final_miercoles
     *
     * @return string
     */
    public function getHoraFinalMiercoles() {
        return $this->hora_final_miercoles;
    }

    /**
     * Set hora_inicial_jueves
     *
     * @param string $hora_inicial_jueves
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialJueves($hora_inicial_jueves) {
        $this->hora_inicial_jueves = $hora_inicial_jueves;

        return $this;
    }

    /**
     * Get hora_inicial_jueves
     *
     * @return string
     */
    public function getHoraInicialJueves() {
        return $this->hora_inicial_jueves;
    }

    /**
     * Set hora_final_jueves
     *
     * @param string $hora_final_jueves
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalJueves($hora_final_jueves) {
        $this->hora_final_jueves = $hora_final_jueves;

        return $this;
    }

    /**
     * Get hora_final_jueves
     *
     * @return string
     */
    public function getHoraFinalJueves() {
        return $this->hora_final_jueves;
    }

    /**
     * Set hora_inicial_viernes
     *
     * @param string $hora_inicial_viernes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialViernes($hora_inicial_viernes) {
        $this->hora_inicial_viernes = $hora_inicial_viernes;

        return $this;
    }

    /**
     * Get hora_inicial_viernes
     *
     * @return string
     */
    public function getHoraInicialViernes() {
        return $this->hora_inicial_viernes;
    }

    /**
     * Set hora_final_viernes
     *
     * @param string $hora_final_viernes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalViernes($hora_final_viernes) {
        $this->hora_final_viernes = $hora_final_viernes;

        return $this;
    }

    /**
     * Get hora_final_viernes
     *
     * @return string
     */
    public function getHoraFinalViernes() {
        return $this->hora_final_viernes;
    }

    /**
     * Set hora_inicial_sabado
     *
     * @param string $hora_inicial_sabado
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialSabado($hora_inicial_sabado) {
        $this->hora_inicial_sabado = $hora_inicial_sabado;

        return $this;
    }

    /**
     * Get hora_inicial_sabado
     *
     * @return string
     */
    public function getHoraInicialSabado() {
        return $this->hora_inicial_sabado;
    }

    /**
     * Set hora_final_sabado
     *
     * @param string $hora_final_sabado
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalSabado($hora_final_sabado) {
        $this->hora_final_sabado = $hora_final_sabado;

        return $this;
    }

    /**
     * Get hora_final_sabado
     *
     * @return string
     */
    public function getHoraFinalSabado() {
        return $this->hora_final_sabado;
    }

    /**
     * Set hora_inicial_domingo
     *
     * @param string $hora_inicial_domingo
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicialDomingo($hora_inicial_domingo) {
        $this->hora_inicial_domingo = $hora_inicial_domingo;

        return $this;
    }

    /**
     * Get hora_inicial_domingo
     *
     * @return string
     */
    public function getHoraInicialDomingo() {
        return $this->hora_inicial_domingo;
    }

    /**
     * Set hora_final_domingo
     *
     * @param string $hora_final_domingo
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinalDomingo($hora_final_domingo) {
        $this->hora_final_domingo = $hora_final_domingo;

        return $this;
    }

    /**
     * Get hora_final_domingo
     *
     * @return string
     */
    public function getHoraFinalDomingo() {
        return $this->hora_final_domingo;
    }

    /* horario para la tarde */

    /**
     * Set hora_inicial2_lunes
     *
     * @param string $hora_inicial2_lunes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Lunes($hora_inicial2_lunes) {
        $this->hora_inicial2_lunes = $hora_inicial2_lunes;

        return $this;
    }

    /**
     * Get hora_inicial2_lunes
     *
     * @return string
     */
    public function getHoraInicial2Lunes() {
        return $this->hora_inicial2_lunes;
    }

    /**
     * Set hora_final2_lunes
     *
     * @param string $hora_final2_lunes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Lunes($hora_final2_lunes) {
        $this->hora_final2_lunes = $hora_final2_lunes;

        return $this;
    }

    /**
     * Get hora_final2_lunes
     *
     * @return string
     */
    public function getHoraFinal2Lunes() {
        return $this->hora_final2_lunes;
    }

    /**
     * Set hora_inicial2_martes
     *
     * @param string $hora_inicial_martes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Martes($hora_inicial2_martes) {
        $this->hora_inicial2_martes = $hora_inicial2_martes;

        return $this;
    }

    /**
     * Get hora_inicial2_martes
     *
     * @return string
     */
    public function getHoraInicial2Martes() {
        return $this->hora_inicial2_martes;
    }

    /**
     * Set hora_final2_martes
     *
     * @param string $hora_final2_martes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Martes($hora_final2_martes) {
        $this->hora_final2_martes = $hora_final2_martes;

        return $this;
    }

    /**
     * Get hora_final2_martes
     *
     * @return string
     */
    public function getHoraFinal2Martes() {
        return $this->hora_final2_martes;
    }

    /**
     * Set hora_inicial2_miercoles
     *
     * @param string $hora_inicial2_miercoles
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Miercoles($hora_inicial2_miercoles) {
        $this->hora_inicial2_miercoles = $hora_inicial2_miercoles;

        return $this;
    }

    /**
     * Get hora_inicial2_miercoles
     *
     * @return string
     */
    public function getHoraInicial2Miercoles() {
        return $this->hora_inicial2_miercoles;
    }

    /**
     * Set hora_final2_miercoles
     *
     * @param string $hora_final2_miercoles
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Miercoles($hora_final2_miercoles) {
        $this->hora_final2_miercoles = $hora_final2_miercoles;

        return $this;
    }

    /**
     * Get hora_final_miercoles
     *
     * @return string
     */
    public function getHoraFinal2Miercoles() {
        return $this->hora_final2_miercoles;
    }

    /**
     * Set hora_inicial2_jueves
     *
     * @param string $hora_inicial2_jueves
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Jueves($hora_inicial2_jueves) {
        $this->hora_inicial2_jueves = $hora_inicial2_jueves;

        return $this;
    }

    /**
     * Get hora_inicial2_miercoles
     *
     * @return string
     */
    public function getHoraInicial2Jueves() {
        return $this->hora_inicial2_jueves;
    }

    /**
     * Set hora_final2_jueves
     *
     * @param string $hora_final2_jueves
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Jueves($hora_final2_jueves) {
        $this->hora_final2_jueves = $hora_final2_jueves;

        return $this;
    }

    /**
     * Get hora_final2_jueves
     *
     * @return string
     */
    public function getHoraFinal2Jueves() {
        return $this->hora_final2_jueves;
    }

    /**
     * Set hora_inicial2_viernes
     *
     * @param string $hora_inicial2_viernes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Viernes($hora_inicial2_viernes) {
        $this->hora_inicial2_viernes = $hora_inicial2_viernes;

        return $this;
    }

    /**
     * Get hora_inicial2_viernes
     *
     * @return string
     */
    public function getHoraInicial2Viernes() {
        return $this->hora_inicial2_viernes;
    }

    /**
     * Set hora_final2_viernes
     *
     * @param string $hora_final2_viernes
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Viernes($hora_final2_viernes) {
        $this->hora_final2_viernes = $hora_final2_viernes;

        return $this;
    }

    /**
     * Get hora_final2_viernes
     *
     * @return string
     */
    public function getHoraFinal2Viernes() {
        return $this->hora_final2_viernes;
    }

    /**
     * Set hora_inicial2_sabado
     *
     * @param string $hora_inicial2_sabado
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Sabado($hora_inicial2_sabado) {
        $this->hora_inicial2_sabado = $hora_inicial2_sabado;

        return $this;
    }

    /**
     * Get hora_inicial2_sabado
     *
     * @return string
     */
    public function getHoraInicial2Sabado() {
        return $this->hora_inicial2_sabado;
    }

    /**
     * Set hora_final2_sabado
     *
     * @param string $hora_final2_sabado
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Sabado($hora_final2_sabado) {
        $this->hora_final2_sabado = $hora_final2_sabado;

        return $this;
    }

    /**
     * Get hora_final2_sabado
     *
     * @return string
     */
    public function getHoraFinal2Sabado() {
        return $this->hora_final2_sabado;
    }

    /**
     * Set hora_inicial2_domingo
     *
     * @param string $hora_inicial2_domingo
     *
     * @return EscenarioDeportivo
     */
    public function setHoraInicial2Domingo($hora_inicial2_domingo) {
        $this->hora_inicial2_domingo = $hora_inicial2_domingo;

        return $this;
    }

    /**
     * Get hora_inicial2_domingo
     *
     * @return string
     */
    public function getHoraInicial2Domingo() {
        return $this->hora_inicial2_domingo;
    }

    /**
     * Set hora_final2_domingo
     *
     * @param string $hora_final2_domingo
     *
     * @return EscenarioDeportivo
     */
    public function setHoraFinal2Domingo($hora_final2_domingo) {
        $this->hora_final2_domingo = $hora_final2_domingo;

        return $this;
    }

    /**
     * Get hora_final2_sabado
     *
     * @return string
     */
    public function getHoraFinal2Domingo() {
        return $this->hora_final2_domingo;
    }

    /**
     * Set horarioDivision
     *
     * @param boolean $horarioDivision
     *
     * @return EscenarioDeportivo
     */
    public function setHorarioDivision($horarioDivision) {
        $this->horarioDivision = $horarioDivision;

        return $this;
    }

    /**
     * Get horarioDivision
     *
     * @return boolean
     */
    public function getHorarioDivision() {
        return $this->horarioDivision;
    }

    /**
     * Set localizacion
     *
     * @param string $localizacion
     *
     * @return EscenarioDeportivo
     */
    public function setLocalizacion($localizacion) {
        $this->setLongitud($localizacion['longitud']);
        $this->setLatitud($localizacion['latitud']);
        return $this;
    }

    /**
     * @Assert\NotBlank()
     */
    public function getLocalizacion() {
        return array('latitud' => $this->getLatitud(), 'longitud' => $this->getLongitud());
    }

    /**
     * Add programacione.
     *
     * @param \LogicBundle\Entity\ProgramacionReserva $programacione
     *
     * @return EscenarioDeportivo
     */
    public function addProgramacione(\LogicBundle\Entity\ProgramacionReserva $programacione) {
        $this->programaciones[] = $programacione;

        return $this;
    }

    /**
     * Remove programacione.
     *
     * @param \LogicBundle\Entity\ProgramacionReserva $programacione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProgramacione(\LogicBundle\Entity\ProgramacionReserva $programacione) {
        return $this->programaciones->removeElement($programacione);
    }

    /**
     * Get programaciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramaciones() {
        return $this->programaciones;
    }

}
