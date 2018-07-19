<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Disciplina
 *
 * @ORM\Table(name="disciplina")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DisciplinaRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Disciplina {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            if ($this->getImagenDeporte()) {
                return $this->getImagenDeporte();
            }
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
     * Get imagenPerfil
     *
     * @return string
     */
    public function getIconoDeporte() {
        if (!$this->icono_deporte) {
            $this->icono_deporte = "copa.svg";
        }
        return $this->icono_deporte;
    }

    /**
     * Get imagenDeporte.
     *
     * @return string|null
     */
    public function getImagenDeporte() {
        if (!$this->imagen_deporte) {
            $this->imagen_deporte = "deporte.jpg";
        }
        return $this->imagen_deporte;
    }
    

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Serializer\Expose
     */
    private $nombre;

    /**
     * @var text
     *
     * @ORM\Column(name="descripcion", type="text", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen_deporte", type="string", length=255, nullable=true)
     */
    private $imagen_deporte;

    /**
     * @var string
     *
     * @ORM\Column(name="icono_deporte", type="string", length=255, nullable=true)
     */
    private $icono_deporte;

    /**
     * @var string
     *
     * @ORM\Column(name="reglamento", type="string", length=255, nullable=true)
     */
    private $reglamento;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinaEstrategia", mappedBy="disciplina",cascade={"persist"})
     */
    private $estrategias;

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

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinaOrganizacion", mappedBy="disciplina")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $organizacionesdeportivas;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinasEscenarioDeportivo", mappedBy="disciplina")
     */
    private $disciplinas;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", mappedBy="disciplina")
     */
    private $planesAnualesMetodologicos;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaDisciplina", inversedBy="disciplinas")
     * @ORM\JoinColumn(name="categoria_disciplina_id",referencedColumnName="id")
     */
    private $categoriaDisciplina;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="disciplina")
     */
    private $eventos;

    /**
     * @ORM\ManyToMany(targetEntity="ClasificacionDeporte", mappedBy="disciplinas", cascade={"persist"})
     */
    private $clasificaciones;

    /**
     * Many Users have Many Reserva.
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="disciplinas", cascade={"all"})
     * @ORM\JoinTable(name="disciplina_usuario",
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")}
     *      )
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="PlanMetodologico", mappedBy="disciplina")
     */
    private $planMetodologicos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinaPruebaRama", mappedBy="disciplina")
     */
    private $disciplinasPruebasRamas;

    /**
     * Add clasificacione
     *
     * @param \LogicBundle\Entity\ClasificacionDeporte $clasificacione
     *
     * @return Disciplina
     */
    public function addClasificacione(\LogicBundle\Entity\ClasificacionDeporte $clasificacione) {
        $clasificacione->addDisciplina($this);
        $this->clasificaciones[] = $clasificacione;

        return $this;
    }

    /**
     * Remove clasificacione
     *
     * @param \LogicBundle\Entity\ClasificacionDeporte $clasificacione
     */
    public function removeClasificacione(\LogicBundle\Entity\ClasificacionDeporte $clasificacione) {
        $clasificacione->removeDisciplina($this);
        $this->clasificaciones->removeElement($clasificacione);
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->estrategias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizacionesdeportivas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planesAnualesMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clasificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinasPruebasRamas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Disciplina
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Disciplina
     */
    public function setDescripcion($descripcion = null) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set imagenDeporte.
     *
     * @param string|null $imagenDeporte
     *
     * @return Disciplina
     */
    public function setImagenDeporte($imagenDeporte = null) {
        $this->imagen_deporte = $imagenDeporte;

        return $this;
    }

    /**
     * Set iconoDeporte.
     *
     * @param string|null $iconoDeporte
     *
     * @return Disciplina
     */
    public function setIconoDeporte($iconoDeporte = null) {
        $this->icono_deporte = $iconoDeporte;

        return $this;
    }

    /**
     * Set reglamento.
     *
     * @param string|null $reglamento
     *
     * @return Disciplina
     */
    public function setReglamento($reglamento = null) {
        $this->reglamento = $reglamento;

        return $this;
    }

    /**
     * Get reglamento.
     *
     * @return string|null
     */
    public function getReglamento() {
        return $this->reglamento;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Disciplina
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Disciplina
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Add estrategia.
     *
     * @param \LogicBundle\Entity\DisciplinaEstrategia $estrategia
     *
     * @return Disciplina
     */
    public function addEstrategia(\LogicBundle\Entity\DisciplinaEstrategia $estrategia) {
        $this->estrategias[] = $estrategia;

        return $this;
    }

    /**
     * Remove estrategia.
     *
     * @param \LogicBundle\Entity\DisciplinaEstrategia $estrategia
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEstrategia(\LogicBundle\Entity\DisciplinaEstrategia $estrategia) {
        return $this->estrategias->removeElement($estrategia);
    }

    /**
     * Get estrategias.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstrategias() {
        return $this->estrategias;
    }

    /**
     * Add organizacionesdeportiva.
     *
     * @param \LogicBundle\Entity\DisciplinaOrganizacion $organizacionesdeportiva
     *
     * @return Disciplina
     */
    public function addOrganizacionesdeportiva(\LogicBundle\Entity\DisciplinaOrganizacion $organizacionesdeportiva) {
        $this->organizacionesdeportivas[] = $organizacionesdeportiva;

        return $this;
    }

    /**
     * Remove organizacionesdeportiva.
     *
     * @param \LogicBundle\Entity\DisciplinaOrganizacion $organizacionesdeportiva
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOrganizacionesdeportiva(\LogicBundle\Entity\DisciplinaOrganizacion $organizacionesdeportiva) {
        return $this->organizacionesdeportivas->removeElement($organizacionesdeportiva);
    }

    /**
     * Get organizacionesdeportivas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizacionesdeportivas() {
        return $this->organizacionesdeportivas;
    }

    /**
     * Add disciplina.
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplina
     *
     * @return Disciplina
     */
    public function addDisciplina(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplina) {
        $this->disciplinas[] = $disciplina;

        return $this;
    }

    /**
     * Remove disciplina.
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplina
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDisciplina(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplina) {
        return $this->disciplinas->removeElement($disciplina);
    }

    /**
     * Get disciplinas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinas() {
        return $this->disciplinas;
    }

    /**
     * Add planesAnualesMetodologico.
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     *
     * @return Disciplina
     */
    public function addPlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico) {
        $this->planesAnualesMetodologicos[] = $planesAnualesMetodologico;

        return $this;
    }

    /**
     * Remove planesAnualesMetodologico.
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanesAnualesMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planesAnualesMetodologico) {
        return $this->planesAnualesMetodologicos->removeElement($planesAnualesMetodologico);
    }

    /**
     * Get planesAnualesMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanesAnualesMetodologicos() {
        return $this->planesAnualesMetodologicos;
    }

    /**
     * Set categoriaDisciplina.
     *
     * @param \LogicBundle\Entity\CategoriaDisciplina|null $categoriaDisciplina
     *
     * @return Disciplina
     */
    public function setCategoriaDisciplina(\LogicBundle\Entity\CategoriaDisciplina $categoriaDisciplina = null) {
        $this->categoriaDisciplina = $categoriaDisciplina;

        return $this;
    }

    /**
     * Get categoriaDisciplina.
     *
     * @return \LogicBundle\Entity\CategoriaDisciplina|null
     */
    public function getCategoriaDisciplina() {
        return $this->categoriaDisciplina;
    }

    /**
     * Add evento.
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Disciplina
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento.
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        return $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Get clasificaciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasificaciones() {
        return $this->clasificaciones;
    }

    /**
     * Add usuario.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Disciplina
     */
    public function addUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        return $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios() {
        return $this->usuarios;
    }

    /**
     * Add planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return Disciplina
     */
    public function addPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico) {
        $this->planMetodologicos[] = $planMetodologico;

        return $this;
    }

    /**
     * Remove planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico) {
        return $this->planMetodologicos->removeElement($planMetodologico);
    }

    /**
     * Get planMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanMetodologicos() {
        return $this->planMetodologicos;
    }

    /**
     * Add disciplinasPruebasRama.
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     *
     * @return Disciplina
     */
    public function addDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama) {
        $this->disciplinasPruebasRamas[] = $disciplinasPruebasRama;

        return $this;
    }

    /**
     * Remove disciplinasPruebasRama.
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama) {
        return $this->disciplinasPruebasRamas->removeElement($disciplinasPruebasRama);
    }

    /**
     * Get disciplinasPruebasRamas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinasPruebasRamas() {
        return $this->disciplinasPruebasRamas;
    }

}
