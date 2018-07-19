<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Accion
 *
 * @ORM\Table(name="informacion_extra_usuario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\InformacionExtraUsuarioRepository")
 */
class InformacionExtraUsuario {

    public function __toString() {
        return $this->getUsuario() ? $this->getUsuario()->getFullName() : '';
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
     * @ORM\OneToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="informacionExtraUsuario")
     * @ORM\JoinColumn(referencedColumnName="id", name="usuario_id", nullable=false)
     */
    protected $usuario;

    /**
     * @var float
     *
     * @ORM\Column(name="estatura", type="float", nullable=true)
     */
    private $estatura;

    /**
     * @var float
     *
     * @ORM\Column(name="peso", type="float", nullable=true)
     */
    private $peso;

    /**
     * @var float
     *
     * @ORM\Column(name="indiceMasaCorporal", type="float", nullable=true)
     */
    private $indiceMasaCorporal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fuma", type="boolean", nullable=true)
     */
    private $fuma;

    /**
     * @var boolean
     *
     * @ORM\Column(name="consumeBebidasAlcoholicas", type="boolean", nullable=true)
     */
    private $consumeBebidasAlcoholicas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="padeceEnfermedadesCronicas", type="boolean", nullable=true)
     */
    private $padeceEnfermedadesCronicas;

    /**
     * @var enfermedades
     *
     * @ORM\Column(name="enfermedades", type="text", nullable=true)
     */
    private $enfermedades;

    /**
     * @var boolean
     *
     * @ORM\Column(name="consumeMedicamentos", type="boolean", nullable=true)
     */
    private $consumeMedicamentos;

    /**
     * @var medicamentos
     *
     * @ORM\Column(name="medicamentos", type="text", nullable=true)
     */
    private $medicamentos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tiene_contacto", type="boolean", nullable=true)
     */
    private $tieneContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_contacto",type="string", length=255, nullable=true)
     */
    private $nombreContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_contacto",type="string", length=255, nullable=true)
     */
    private $telefonoContacto;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoSangre", inversedBy="informacionExtraUsuarios")
     * @ORM\JoinColumn(name="tipo_sangre_id", referencedColumnName="id", nullable=true)
     */
    private $tipoSangre;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoDesplazado")
     * @ORM\JoinColumn(name="tipo_desplazado_id", referencedColumnName="id", nullable=true)
     */
    private $tipoDesplazado;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Barrio")
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id", nullable=true)
     */
    private $barrio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EstablecimientoEducativo")
     * @ORM\JoinColumn(name="establecimiento_educativo_id", referencedColumnName="id", nullable=true)
     */
    private $establecimientoEducativo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\NivelEscolaridad")
     * @ORM\JoinColumn(name="nivel_escolaridad_id", referencedColumnName="id", nullable=true)
     */
    private $nivelEscolaridad;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Estrato")
     * @ORM\JoinColumn(name="estrato_id", referencedColumnName="id", nullable=true)
     */
    private $estrato;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Ocupacion")
     * @ORM\JoinColumn(name="ocupacion_id", referencedColumnName="id", nullable=true)
     */
    private $ocupacion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", nullable=true)
     */
    private $municipio;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;
    //***************************//
    /**
     * @var boolean
     *
     * @ORM\Column(name="desplazado", type="boolean", nullable=true)
     */
    private $desplazado;

    /**
     * @var string
     * @ORM\Column(type="string", name="medio_transporte", length=255, nullable=true)
     */
    private $medioTransporte;

    /**
     * @var string
     * @ORM\Column(type="string", name="punto_recoleccion", length=255, nullable=true)
     */
    private $puntoRecoleccion;

    /**
     * @var string
     * @ORM\Column(type="string", name="nombre_club_equipo", length=255, nullable=true)
     */
    private $nombreClubEquipo;

    /**
     * @var string
     * @ORM\Column(type="string", name="nombre_carro", length=255, nullable=true)
     */
    private $nombreCarro;

    /**
     * @var string
     * @ORM\Column(type="string", name="rama", length=255, nullable=true)
     */
    private $rama;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaEvento")
     * @ORM\JoinColumn(name="categoria_evento_id", referencedColumnName="id", nullable=true)
     */
    private $categoria;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SubCategoriaEvento")
     * @ORM\JoinColumn(name="sub_categoria_evento_id", referencedColumnName="id", nullable=true)
     */
    private $subCategoria;
    
    /**
     * @var string
     * @ORM\Column(type="string", name="grado_cursa", length=255, nullable=true)
     */
    private $gradoCursa;

    /**
     * @var string
     * @ORM\Column(type="string", name="talla_pantalon", length=255, nullable=true)
     */
    private $tallaPantalon;

    /**
     * @var string
     * @ORM\Column(type="string", name="talla_camisa", length=255, nullable=true)
     */
    private $tallaCamisa;

    /**
     * @var string
     * @ORM\Column(type="string", name="talla_zapatos", length=255, nullable=true)
     */
    private $tallaZapatos;

    /**
     * @var string
     * @ORM\Column(type="string", name="numero_matricula", length=255, nullable=true)
     */
    private $numeroMatricula;

    /**
     * @var string
     * @ORM\Column(type="string", name="tipo_discapacidad", length=255, nullable=true)
     */
    private $tipoDiscapacidad;

    /**
     * @var string
     * @ORM\Column(type="string", name="adjuntar_documentos", length=255, nullable=true)
     */
    private $adjuntarDocumentos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="licencia_ciclismo", type="boolean", nullable=true)
     */
    private $licenciaCiclismo;

    ////////////////////////////////////////////

    /**
     * @var boolean
     *
     * @ORM\Column(name="pertenece_lgbti", type="boolean", nullable=true)
     */
    private $perteneceLGBTI;

    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=255, nullable=true)
     */
    private $rol;

    /**
     * @var boolean
     *
     * @ORM\Column(name="jefe_cabeza_hogar", type="boolean", nullable=true)
     */
    private $jefeCabezaHogar;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=255, nullable=true)
     */
    private $sexo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="discapacitado", type="boolean", nullable=true)
     */
    private $discapacitado;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_discapacidad", type="string", length=255, nullable=true)
     */
    private $subDiscapacidad;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_electronico", type="string", length=255, nullable=true)
     */
    private $correoElectronico;

    /**
     * @var \DateTime $fechaNacimiento
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    protected $fechaNacimiento;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set estatura
     *
     * @param string $estatura
     *
     * @return InformacionExtraUsuario
     */
    public function setEstatura($estatura) {
        $this->estatura = $estatura;

        return $this;
    }

    /**
     * Get estatura
     *
     * @return string
     */
    public function getEstatura() {
        return $this->estatura;
    }

    /**
     * Set peso
     *
     * @param string $peso
     *
     * @return InformacionExtraUsuario
     */
    public function setPeso($peso) {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string
     */
    public function getPeso() {
        return $this->peso;
    }

    /**
     * Set indiceMasaCorporal
     *
     * @param string $indiceMasaCorporal
     *
     * @return InformacionExtraUsuario
     */
    public function setIndiceMasaCorporal($indiceMasaCorporal) {
        $this->indiceMasaCorporal = $indiceMasaCorporal;

        return $this;
    }

    /**
     * Get indiceMasaCorporal
     *
     * @return string
     */
    public function getIndiceMasaCorporal() {
        return $this->indiceMasaCorporal;
    }

    /**
     * Set fuma
     *
     * @param boolean $fuma
     *
     * @return InformacionExtraUsuario
     */
    public function setFuma($fuma) {
        $this->fuma = $fuma;

        return $this;
    }

    /**
     * Get fuma
     *
     * @return boolean
     */
    public function getFuma() {
        return $this->fuma;
    }

    /**
     * Set consumeBebidasAlcoholicas
     *
     * @param boolean $consumeBebidasAlcoholicas
     *
     * @return InformacionExtraUsuario
     */
    public function setConsumeBebidasAlcoholicas($consumeBebidasAlcoholicas) {
        $this->consumeBebidasAlcoholicas = $consumeBebidasAlcoholicas;

        return $this;
    }

    /**
     * Get consumeBebidasAlcoholicas
     *
     * @return boolean
     */
    public function getConsumeBebidasAlcoholicas() {
        return $this->consumeBebidasAlcoholicas;
    }

    /**
     * Set padeceEnfermedadesCronicas
     *
     * @param boolean $padeceEnfermedadesCronicas
     *
     * @return InformacionExtraUsuario
     */
    public function setPadeceEnfermedadesCronicas($padeceEnfermedadesCronicas) {
        $this->padeceEnfermedadesCronicas = $padeceEnfermedadesCronicas;

        return $this;
    }

    /**
     * Get padeceEnfermedadesCronicas
     *
     * @return boolean
     */
    public function getPadeceEnfermedadesCronicas() {
        return $this->padeceEnfermedadesCronicas;
    }

    /**
     * Set consumeMedicamentos
     *
     * @param boolean $consumeMedicamentos
     *
     * @return InformacionExtraUsuario
     */
    public function setConsumeMedicamentos($consumeMedicamentos) {
        $this->consumeMedicamentos = $consumeMedicamentos;

        return $this;
    }

    /**
     * Get consumeMedicamentos
     *
     * @return boolean
     */
    public function getConsumeMedicamentos() {
        return $this->consumeMedicamentos;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return InformacionExtraUsuario
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
     * @return InformacionExtraUsuario
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
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return InformacionExtraUsuario
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set tipoSangre
     *
     * @param \LogicBundle\Entity\TipoSangre $tipoSangre
     *
     * @return InformacionExtraUsuario
     */
    public function setTipoSangre(\LogicBundle\Entity\TipoSangre $tipoSangre = null) {
        $this->tipoSangre = $tipoSangre;

        return $this;
    }

    /**
     * Get tipoSangre
     *
     * @return \LogicBundle\Entity\TipoSangre
     */
    public function getTipoSangre() {
        return $this->tipoSangre;
    }
    
    /**
     * Set tipoDesplazado
     *
     * @param \LogicBundle\Entity\TipoDesplazado $tipoDesplazado
     *
     * @return InformacionExtraUsuario
     */
    public function setTipoDesplazado(\LogicBundle\Entity\TipoDesplazado $tipoDesplazado = null) {
        $this->tipoDesplazado = $tipoDesplazado;

        return $this;
    }

    /**
     * Get tipoDesplazado
     *
     * @return \LogicBundle\Entity\TipoDesplazado
     */
    public function getTipoDesplazado() {
        return $this->tipoDesplazado;
    }

    /**
     * Set barrio
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return InformacionExtraUsuario
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
     * Set establecimientoEducativo
     *
     * @param \LogicBundle\Entity\EstablecimientoEducativo $establecimientoEducativo
     *
     * @return InformacionExtraUsuario
     */
    public function setEstablecimientoEducativo(\LogicBundle\Entity\EstablecimientoEducativo $establecimientoEducativo = null) {
        $this->establecimientoEducativo = $establecimientoEducativo;

        return $this;
    }

    /**
     * Get establecimientoEducativo
     *
     * @return \LogicBundle\Entity\EstablecimientoEducativo
     */
    public function getEstablecimientoEducativo() {
        return $this->establecimientoEducativo;
    }

    /**
     * Set nivelEscolaridad
     *
     * @param \LogicBundle\Entity\NivelEscolaridad $nivelEscolaridad
     *
     * @return InformacionExtraUsuario
     */
    public function setNivelEscolaridad(\LogicBundle\Entity\NivelEscolaridad $nivelEscolaridad = null) {
        $this->nivelEscolaridad = $nivelEscolaridad;

        return $this;
    }

    /**
     * Get nivelEscolaridad
     *
     * @return \LogicBundle\Entity\NivelEscolaridad
     */
    public function getNivelEscolaridad() {
        return $this->nivelEscolaridad;
    }

    /**
     * Set estrato
     *
     * @param \LogicBundle\Entity\Estrato $estrato
     *
     * @return InformacionExtraUsuario
     */
    public function setEstrato(\LogicBundle\Entity\Estrato $estrato = null) {
        $this->estrato = $estrato;

        return $this;
    }

    /**
     * Get estrato
     *
     * @return \LogicBundle\Entity\Estrato
     */
    public function getEstrato() {
        return $this->estrato;
    }

    /**
     * Set ocupacion
     *
     * @param \LogicBundle\Entity\Ocupacion $ocupacion
     *
     * @return InformacionExtraUsuario
     */
    public function setOcupacion(\LogicBundle\Entity\Ocupacion $ocupacion = null) {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return \LogicBundle\Entity\Ocupacion
     */
    public function getOcupacion() {
        return $this->ocupacion;
    }

    /**
     * Set municipio
     *
     * @param \LogicBundle\Entity\Municipio $municipio
     *
     * @return InformacionExtraUsuario
     */
    public function setMunicipio(\LogicBundle\Entity\Municipio $municipio = null) {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \LogicBundle\Entity\Municipio
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    //***************************//
    /**
     * Set desplazado
     *
     * @param boolean $desplazado
     *
     * @return InformacionExtraUsuario
     */
    public function setDesplazado($desplazado) {
        $this->desplazado = $desplazado;

        return $this;
    }

    /**
     * Get desplazado
     *
     * @return boolean
     */
    public function getDesplazado() {
        return $this->desplazado;
    }

    /**
     * Set medioTransporte
     *
     * @param boolean $medioTransporte
     *
     * @return InformacionExtraUsuario
     */
    public function setMedioTransporte($medioTransporte) {
        $this->medioTransporte = $medioTransporte;

        return $this;
    }

    /**
     * Get medioTransporte
     *
     * @return string
     */
    public function getMedioTransporte() {
        return $this->medioTransporte;
    }

    /**
     * Set puntoRecoleccion
     *
     * @param string $puntoRecoleccion
     *
     * @return InformacionExtraUsuario
     */
    public function setPuntoRecoleccion($puntoRecoleccion) {
        $this->puntoRecoleccion = $puntoRecoleccion;

        return $this;
    }

    /**
     * Get puntoRecoleccion
     *
     * @return string
     */
    public function getPuntoRecoleccion() {
        return $this->puntoRecoleccion;
    }

    /**
     * Set nombreClubEquipo
     *
     * @param string $nombreClubEquipo
     *
     * @return InformacionExtraUsuario
     */
    public function setNombreClubEquipo($nombreClubEquipo) {
        $this->nombreClubEquipo = $nombreClubEquipo;

        return $this;
    }

    /**
     * Get nombreClubEquipo
     *
     * @return string
     */
    public function getNombreClubEquipo() {
        return $this->nombreClubEquipo;
    }

    /**
     * Set nombreCarro
     *
     * @param string $nombreCarro
     *
     * @return InformacionExtraUsuario
     */
    public function setNombreCarro($nombreCarro) {
        $this->nombreCarro = $nombreCarro;

        return $this;
    }

    /**
     * Get nombreCarro
     *
     * @return string
     */
    public function getNombreCarro() {
        return $this->nombreCarro;
    }

    /**
     * Set rama
     *
     * @param string $rama
     *
     * @return InformacionExtraUsuario
     */
    public function setRama($rama) {
        $this->rama = $rama;

        return $this;
    }

    /**
     * Get rama
     *
     * @return string
     */
    public function getRama() {
        return $this->rama;
    }


    /**
     * Set gradoCursa
     *
     * @param string $gradoCursa
     *
     * @return InformacionExtraUsuario
     */
    public function setGradoCursa($gradoCursa) {
        $this->gradoCursa = $gradoCursa;

        return $this;
    }

    /**
     * Get gradoCursa
     *
     * @return string
     */
    public function getGradoCursa() {
        return $this->gradoCursa;
    }

    /**
     * Set tallaPantalon
     *
     * @param string $tallaPantalon
     *
     * @return InformacionExtraUsuario
     */
    public function setTallaPantalon($tallaPantalon) {
        $this->tallaPantalon = $tallaPantalon;

        return $this;
    }

    /**
     * Get tallaPantalon
     *
     * @return string
     */
    public function getTallaPantalon() {
        return $this->tallaPantalon;
    }

    /**
     * Set tallaCamisa
     *
     * @param string $tallaCamisa
     *
     * @return InformacionExtraUsuario
     */
    public function setTallaCamisa($tallaCamisa) {
        $this->tallaCamisa = $tallaCamisa;

        return $this;
    }

    /**
     * Get tallaCamisa
     *
     * @return string
     */
    public function getTallaCamisa() {
        return $this->tallaCamisa;
    }

    /**
     * Set tallaZapatos
     *
     * @param string $tallaZapatos
     *
     * @return InformacionExtraUsuario
     */
    public function setTallaZapatos($tallaZapatos) {
        $this->tallaZapatos = $tallaZapatos;

        return $this;
    }

    /**
     * Get tallaZapatos
     *
     * @return string
     */
    public function getTallaZapatos() {
        return $this->tallaZapatos;
    }

    /**
     * Set numeroMatricula
     *
     * @param string $numeroMatricula
     *
     * @return InformacionExtraUsuario
     */
    public function setNumeroMatricula($numeroMatricula) {
        $this->numeroMatricula = $numeroMatricula;

        return $this;
    }

    /**
     * Get numeroMatricula
     *
     * @return string
     */
    public function getNumeroMatricula() {
        return $this->numeroMatricula;
    }

    /**
     * Set tipoDiscapacidad
     *
     * @param string $tipoDiscapacidad
     *
     * @return InformacionExtraUsuario
     */
    public function setTipoDiscapacidad($tipoDiscapacidad) {
        $this->tipoDiscapacidad = $tipoDiscapacidad;

        return $this;
    }

    /**
     * Get tipoDiscapacidad
     *
     * @return string
     */
    public function getTipoDiscapacidad() {
        return $this->tipoDiscapacidad;
    }

    /**
     * Set adjuntarDocumentos
     *
     * @param string $adjuntarDocumentos
     *
     * @return InformacionExtraUsuario
     */
    public function setAdjuntarDocumentos($adjuntarDocumentos) {
        $this->adjuntarDocumentos = $adjuntarDocumentos;

        return $this;
    }

    /**
     * Get adjuntarDocumentos
     *
     * @return string
     */
    public function getAdjuntarDocumentos() {
        return $this->adjuntarDocumentos;
    }

    /**
     * Set licenciaCiclismo
     *
     * @param boolean $licenciaCiclismo
     *
     * @return InformacionExtraUsuario
     */
    public function setLicenciaCiclismo($licenciaCiclismo) {
        $this->licenciaCiclismo = $licenciaCiclismo;

        return $this;
    }

    /**
     * Get licenciaCiclismo
     *
     * @return boolean
     */
    public function getLicenciaCiclismo() {
        return $this->licenciaCiclismo;
    }

    /**
     * Set perteneceLGBTI
     *
     * @param boolean $perteneceLGBTI
     *
     * @return InformacionExtraUsuario
     */
    public function setPerteneceLGBTI($perteneceLGBTI) {
        $this->perteneceLGBTI = $perteneceLGBTI;

        return $this;
    }

    /**
     * Get perteneceLGBTI
     *
     * @return boolean
     */
    public function getPerteneceLGBTI() {
        return $this->perteneceLGBTI;
    }

    /**
     * Set rol
     *
     * @param string $rol
     *
     * @return InformacionExtraUsuario
     */
    public function setRol($rol) {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol() {
        return $this->rol;
    }

    /**
     * Set jefeCabezaHogar
     *
     * @param boolean $jefeCabezaHogar
     *
     * @return InformacionExtraUsuario
     */
    public function setJefeCabezaHogar($jefeCabezaHogar) {
        $this->jefeCabezaHogar = $jefeCabezaHogar;

        return $this;
    }

    /**
     * Get jefeCabezaHogar
     *
     * @return boolean
     */
    public function getJefeCabezaHogar() {
        return $this->jefeCabezaHogar;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return InformacionExtraUsuario
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
     * Set sexo
     *
     * @param string $sexo
     *
     * @return InformacionExtraUsuario
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * Set discapacitado
     *
     * @param boolean $discapacitado
     *
     * @return InformacionExtraUsuario
     */
    public function setDiscapacitado($discapacitado) {
        $this->discapacitado = $discapacitado;

        return $this;
    }

    /**
     * Get discapacitado
     *
     * @return boolean
     */
    public function getDiscapacitado() {
        return $this->discapacitado;
    }

    /**
     * Set subDiscapacidad
     *
     * @param string $subDiscapacidad
     *
     * @return InformacionExtraUsuario
     */
    public function setSubDiscapacidad($subDiscapacidad) {
        $this->subDiscapacidad = $subDiscapacidad;

        return $this;
    }

    /**
     * Get subDiscapacidad
     *
     * @return string
     */
    public function getSubDiscapacidad() {
        return $this->subDiscapacidad;
    }

    /**
     * Set correoElectronico
     *
     * @param string $correoElectronico
     *
     * @return InformacionExtraUsuario
     */
    public function setCorreoElectronico($correoElectronico) {
        $this->correoElectronico = $correoElectronico;

        return $this;
    }

    /**
     * Get correoElectronico
     *
     * @return string
     */
    public function getCorreoElectronico() {
        return $this->correoElectronico;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return InformacionExtraUsuario
     */
    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
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
     * Set medicamentos.
     *
     * @param string|null $medicamentos
     *
     * @return InformacionExtraUsuario
     */
    public function setMedicamentos($medicamentos = null) {
        $this->medicamentos = $medicamentos;

        return $this;
    }

    /**
     * Get medicamentos.
     *
     * @return string|null
     */
    public function getMedicamentos() {
        return $this->medicamentos;
    }

    /**
     * Set enfermedades.
     *
     * @param string|null $enfermedades
     *
     * @return InformacionExtraUsuario
     */
    public function setEnfermedades($enfermedades = null) {
        $this->enfermedades = $enfermedades;

        return $this;
    }

    /**
     * Get enfermedades.
     *
     * @return string|null
     */
    public function getEnfermedades() {
        return $this->enfermedades;
    }


    /**
     * Set tieneContacto.
     *
     * @param bool|null $tieneContacto
     *
     * @return InformacionExtraUsuario
     */
    public function setTieneContacto($tieneContacto = null)
    {
        $this->tieneContacto = $tieneContacto;

        return $this;
    }

    /**
     * Get tieneContacto.
     *
     * @return bool|null
     */
    public function getTieneContacto()
    {
        return $this->tieneContacto;
    }

    /**
     * Set nombreContacto.
     *
     * @param string|null $nombreContacto
     *
     * @return InformacionExtraUsuario
     */
    public function setNombreContacto($nombreContacto = null)
    {
        $this->nombreContacto = $nombreContacto;

        return $this;
    }

    /**
     * Get nombreContacto.
     *
     * @return string|null
     */
    public function getNombreContacto()
    {
        return $this->nombreContacto;
    }

    /**
     * Set telefonoContacto.
     *
     * @param string|null $telefonoContacto
     *
     * @return InformacionExtraUsuario
     */
    public function setTelefonoContacto($telefonoContacto = null)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto.
     *
     * @return string|null
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set categoria.
     *
     * @param \LogicBundle\Entity\CategoriaEvento|null $categoria
     *
     * @return InformacionExtraUsuario
     */
    public function setCategoria(\LogicBundle\Entity\CategoriaEvento $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria.
     *
     * @return \LogicBundle\Entity\CategoriaEvento|null
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set subCategoria.
     *
     * @param \LogicBundle\Entity\SubCategoriaEvento|null $subCategoria
     *
     * @return InformacionExtraUsuario
     */
    public function setSubCategoria(\LogicBundle\Entity\SubCategoriaEvento $subCategoria = null)
    {
        $this->subCategoria = $subCategoria;

        return $this;
    }

    /**
     * Get subCategoria.
     *
     * @return \LogicBundle\Entity\SubCategoriaEvento|null
     */
    public function getSubCategoria()
    {
        return $this->subCategoria;
    }
}
