<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrganizacionDeportiva
 *
 * @ORM\Table(name="organizacion_deportiva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OrganizacionDeportivaRepository")
 */
class OrganizacionDeportiva {

    /////////////// ******** MODIFICADO ************////////////
    
    const APROBAR = 'Aprobar';
    const RECHAZAR = 'Rechazar';
    
    public function __toString() {
        return $this->nit ? $this->nit : '';
    }
    
    /**
     * Add disciplina
     *
     * @param \LogicBundle\Entity\DisciplinaOrganizacion $disciplina
     *
     * @return OrganizacionDeportiva
     */
    public function addDisciplinaOrganizacione(\LogicBundle\Entity\DisciplinaOrganizacion $disciplinaOrganizacion) {
        $disciplinaOrganizacion->setOrganizacion($this);
        $this->$disciplinaOrganizacion[] = $disciplinaOrganizacion;

        return $this;
    }

    /**
     * Add documento
     *
     * @param \LogicBundle\Entity\DocumentosOrganismo $documento
     *
     * @return OrganizacionDeportiva
     */
    public function addDocumento(\LogicBundle\Entity\DocumentosOrganismo $documento) {
        $documento->setOrganizacion($this);
        $this->documentos[] = $documento;

        return $this;
    }
    
    /**
     * Add organismosorganizacion
     *
     * @param \LogicBundle\Entity\Organo $organismosorganizacion
     *
     * @return OrganizacionDeportiva
     */
    public function addOrganismosorganizacion(\LogicBundle\Entity\Organo $organismosorganizacion)
    {
        $organismosorganizacion->setOrganizacionDeportiva($this);
        $this->organismosorganizacion[] = $organismosorganizacion;

        return $this;
    }
    
    private $disciplinas;
    
    function getDisciplinas() {
        return $this->disciplinas;
    }

    function setDisciplinas($disciplinas) {
        $this->disciplinas = $disciplinas;
    }
    
    //////////////********** FIN MODIFICADO *******///////////
    
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
     * @ORM\Column(name="nit", type="string", length=15)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_social", type="string", length=255)
     */
    private $razonSocial;

    /**
     * @var date
     *
     * @ORM\Column(name="periodoestatutario", type="date", nullable=true)
     */
    private $periodoestatutario;

    /**
     * @var boolean
     *
     * @ORM\Column(name="terminoregistro", type="boolean", nullable=true)
     */
    private $terminoregistro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aprobado", type="boolean", nullable=true)
     */
    private $aprobado;

    /**
     * @var date
     *
     * @ORM\Column(name="fecharegistro", type="date", nullable=true)
     */
    private $fecharegistro;

    /**
     * @var date
     *
     * @ORM\Column(name="vigencia", type="date", length=255, nullable=true)
     */
    private $vigencia;
    
    /**
     * @ORM\ManyToOne(targetEntity="ClasificacionOrganizacion", inversedBy="organizaciones")
     * @ORM\JoinColumn(name="clasificacion_organizacion_id",referencedColumnName="id")
     */
    private $clasificacionOrganizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text",nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoEntidad")
     * @ORM\JoinColumn(name="tipoentidad", referencedColumnName="id", nullable=true)
     */
    private $tipoEntidad;

    /**
     * @ORM\OneToMany(targetEntity="Organo", mappedBy="organizacionDeportiva", cascade={"persist"})
     */
    protected $organos;
    
    /**
     * @ORM\OneToMany(targetEntity="DisciplinaOrganizacion", cascade={"persist"}, mappedBy="organizacion", orphanRemoval=true)
     */
    protected $disciplinaOrganizaciones;

    /**
     * @ORM\ManyToMany(targetEntity="Organo", cascade={"persist"})
     */
    protected $organismosorganizacion;

    /**
     * @ORM\OneToMany(targetEntity="DocumentosOrganismo", cascade={"persist"}, mappedBy="organizacion")
     */
    protected $documentos;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tendencia", inversedBy="organizacionDeportivas")
     * @ORM\JoinTable(name="organizacion_deportiva_tendencias",
     *      joinColumns={@ORM\JoinColumn(name="organizacion_deportica_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tendencia_id", referencedColumnName="id")}
     * )
     */
    private $tendencias;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="organizacionDeportiva", cascade={"persist", "remove"})
     */
    private $usuarios;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    private $usuario;

    /**
     * Remove usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     */
    public function removeUsuario(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $user->setOrganizaciondeportiva(null);
        $this->usuarios->removeElement($user);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinaOrganizaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organismosorganizacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendencias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nit
     *
     * @param string $nit
     *
     * @return OrganizacionDeportiva
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set razonSocial
     *
     * @param string $razonSocial
     *
     * @return OrganizacionDeportiva
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return string
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set periodoestatutario
     *
     * @param \DateTime $periodoestatutario
     *
     * @return OrganizacionDeportiva
     */
    public function setPeriodoestatutario($periodoestatutario)
    {
        $this->periodoestatutario = $periodoestatutario;

        return $this;
    }

    /**
     * Get periodoestatutario
     *
     * @return \DateTime
     */
    public function getPeriodoestatutario()
    {
        return $this->periodoestatutario;
    }

    /**
     * Set terminoregistro
     *
     * @param boolean $terminoregistro
     *
     * @return OrganizacionDeportiva
     */
    public function setTerminoregistro($terminoregistro)
    {
        $this->terminoregistro = $terminoregistro;

        return $this;
    }

    /**
     * Get terminoregistro
     *
     * @return boolean
     */
    public function getTerminoregistro()
    {
        return $this->terminoregistro;
    }

    /**
     * Set aprobado
     *
     * @param boolean $aprobado
     *
     * @return OrganizacionDeportiva
     */
    public function setAprobado($aprobado)
    {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado
     *
     * @return boolean
     */
    public function getAprobado()
    {
        return $this->aprobado;
    }

    /**
     * Set fecharegistro
     *
     * @param \DateTime $fecharegistro
     *
     * @return OrganizacionDeportiva
     */
    public function setFecharegistro($fecharegistro)
    {
        $this->fecharegistro = $fecharegistro;

        return $this;
    }

    /**
     * Get fecharegistro
     *
     * @return \DateTime
     */
    public function getFecharegistro()
    {
        return $this->fecharegistro;
    }

    /**
     * Set vigencia
     *
     * @param \DateTime $vigencia
     *
     * @return OrganizacionDeportiva
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    /**
     * Get vigencia
     *
     * @return \DateTime
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return OrganizacionDeportiva
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set tipoEntidad
     *
     * @param \LogicBundle\Entity\TipoEntidad $tipoEntidad
     *
     * @return OrganizacionDeportiva
     */
    public function setTipoEntidad(\LogicBundle\Entity\TipoEntidad $tipoEntidad = null)
    {
        $this->tipoEntidad = $tipoEntidad;

        return $this;
    }

    /**
     * Get tipoEntidad
     *
     * @return \LogicBundle\Entity\TipoEntidad
     */
    public function getTipoEntidad()
    {
        return $this->tipoEntidad;
    }

    /**
     * Add organo
     *
     * @param \LogicBundle\Entity\Organo $organo
     *
     * @return OrganizacionDeportiva
     */
    public function addOrgano(\LogicBundle\Entity\Organo $organo)
    {
        $this->organos[] = $organo;

        return $this;
    }

    /**
     * Remove organo
     *
     * @param \LogicBundle\Entity\Organo $organo
     */
    public function removeOrgano(\LogicBundle\Entity\Organo $organo)
    {
        $this->organos->removeElement($organo);
    }

    /**
     * Get organos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganos()
    {
        return $this->organos;
    }

    /**
     * Remove disciplinaOrganizacione
     *
     * @param \LogicBundle\Entity\DisciplinaOrganizacion $disciplinaOrganizacione
     */
    public function removeDisciplinaOrganizacione(\LogicBundle\Entity\DisciplinaOrganizacion $disciplinaOrganizacione)
    {
        $this->disciplinaOrganizaciones->removeElement($disciplinaOrganizacione);
    }

    /**
     * Get disciplinaOrganizaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinaOrganizaciones()
    {
        return $this->disciplinaOrganizaciones;
    }

    /**
     * Remove organismosorganizacion
     *
     * @param \LogicBundle\Entity\Organo $organismosorganizacion
     */
    public function removeOrganismosorganizacion(\LogicBundle\Entity\Organo $organismosorganizacion)
    {
        $this->organismosorganizacion->removeElement($organismosorganizacion);
    }

    /**
     * Get organismosorganizacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganismosorganizacion()
    {
        return $this->organismosorganizacion;
    }

    /**
     * Remove documento
     *
     * @param \LogicBundle\Entity\DocumentosOrganismo $documento
     */
    public function removeDocumento(\LogicBundle\Entity\DocumentosOrganismo $documento)
    {
        $this->documentos->removeElement($documento);
    }

    /**
     * Get documentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentos()
    {
        return $this->documentos;
    }

    /**
     * Add tendencia
     *
     * @param \LogicBundle\Entity\Tendencia $tendencia
     *
     * @return OrganizacionDeportiva
     */
    public function addTendencia(\LogicBundle\Entity\Tendencia $tendencia)
    {
        $this->tendencias[] = $tendencia;

        return $this;
    }

    /**
     * Remove tendencia
     *
     * @param \LogicBundle\Entity\Tendencia $tendencia
     */
    public function removeTendencia(\LogicBundle\Entity\Tendencia $tendencia)
    {
        $this->tendencias->removeElement($tendencia);
    }

    /**
     * Get tendencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTendencias()
    {
        return $this->tendencias;
    }

    /**
     * Add usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     *
     * @return OrganizacionDeportiva
     */
    public function addUsuario(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->usuarios[] = $user;

        return $this;
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return OrganizacionDeportiva
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set clasificacionOrganizacion
     *
     * @param \LogicBundle\Entity\ClasificacionOrganizacion $clasificacionOrganizacion
     *
     * @return OrganizacionDeportiva
     */
    public function setClasificacionOrganizacion(\LogicBundle\Entity\ClasificacionOrganizacion $clasificacionOrganizacion = null)
    {
        $this->clasificacionOrganizacion = $clasificacionOrganizacion;

        return $this;
    }

    /**
     * Get clasificacionOrganizacion
     *
     * @return \LogicBundle\Entity\ClasificacionOrganizacion
     */
    public function getClasificacionOrganizacion()
    {
        return $this->clasificacionOrganizacion;
    }
}
