<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValoracionInicial
 *
 * @ORM\Table(name="valoracion_inicial")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ValoracionInicialRepository")
 */
class ValoracionInicial
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
     * @ORM\Column(name="modalidad", type="string", length=255)
     */
    private $modalidad;

    /**
     * @var int
     *
     * @ORM\Column(name="entrenamiento_dias", type="integer")
     */
    private $entrenamientoDias;

    /**
     * @var int
     *
     * @ORM\Column(name="entrenamiento_minutos", type="integer")
     */
    private $entrenamientoMinutos;

    /**
     * @var string
     *
     * @ORM\Column(name="preparacion_dias", type="integer")
     */
    private $preparacionDias;

    /**
     * @var int
     *
     * @ORM\Column(name="preparacion_minutos", type="integer")
     */
    private $preparacionMinutos;

    /**
     * @var bool
     *
     * @ORM\Column(name="realiza_actividad_fisica_extrema", type="boolean")
     */
    private $realizaActividadFisicaExtrema;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_actividad_fisica_extrema", type="string", length=255, nullable=true)
     */
    private $nombreActividadFisicaExtrema;

    /**
     * @var int
     *
     * @ORM\Column(name="dias_actividad_fisica_extrema", type="integer", nullable=true)
     */
    private $diasActividadFisicaExtrema;

    /**
     * @var int
     *
     * @ORM\Column(name="minutos_actividad_fisica_extrema", type="integer", nullable=true)
     */
    private $minutosActividadFisicaExtrema;

     /**
     * @var string
     *
     * @ORM\Column(name="motivo_consulta", type="text")
     */
    private $motivoConsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="enfermedad_actual", type="text")
     */
    private $enfermedadActual;

    /**
     * @var string
     *
     * @ORM\Column(name="revision_sistema", type="text")
     */
    private $revisionSistema;
    
    /**
     * @ORM\OneToOne(targetEntity="ConsultaMedico", inversedBy="valoracionInicial")
     */
    private $consultaMedico;
    
    /**
     * @ORM\ManyToOne(targetEntity="Nivel", inversedBy="valoracionesIniciales")
     * @ORM\JoinColumn(name="nivel_deportista_id", referencedColumnName="id")
     */
    private $nivel;
    
    /**
     * @ORM\ManyToOne(targetEntity="PrecedenciaDeportiva", inversedBy="valoracionesIniciales")
     * @ORM\JoinColumn(name="precedencia_deportiva_id", referencedColumnName="id")
     */
    private $precedenciaDeportiva;
    
    /**
     * @ORM\ManyToOne(targetEntity="CategoriaNivel", inversedBy="valoracionesIniciales")
     * @ORM\JoinColumn(name="categoria_nivel_id", referencedColumnName="id")
     */
    private $categoriaNivel;

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
     * Set modalidad
     *
     * @param string $modalidad
     *
     * @return ValoracionInicial
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return string
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * Set entrenamientoDias
     *
     * @param integer $entrenamientoDias
     *
     * @return ValoracionInicial
     */
    public function setEntrenamientoDias($entrenamientoDias)
    {
        $this->entrenamientoDias = $entrenamientoDias;

        return $this;
    }

    /**
     * Get entrenamientoDias
     *
     * @return integer
     */
    public function getEntrenamientoDias()
    {
        return $this->entrenamientoDias;
    }

    /**
     * Set entrenamientoMinutos
     *
     * @param integer $entrenamientoMinutos
     *
     * @return ValoracionInicial
     */
    public function setEntrenamientoMinutos($entrenamientoMinutos)
    {
        $this->entrenamientoMinutos = $entrenamientoMinutos;

        return $this;
    }

    /**
     * Get entrenamientoMinutos
     *
     * @return integer
     */
    public function getEntrenamientoMinutos()
    {
        return $this->entrenamientoMinutos;
    }

    /**
     * Set preparacionDias
     *
     * @param integer $preparacionDias
     *
     * @return ValoracionInicial
     */
    public function setPreparacionDias($preparacionDias)
    {
        $this->preparacionDias = $preparacionDias;

        return $this;
    }

    /**
     * Get preparacionDias
     *
     * @return integer
     */
    public function getPreparacionDias()
    {
        return $this->preparacionDias;
    }

    /**
     * Set preparacionMinutos
     *
     * @param integer $preparacionMinutos
     *
     * @return ValoracionInicial
     */
    public function setPreparacionMinutos($preparacionMinutos)
    {
        $this->preparacionMinutos = $preparacionMinutos;

        return $this;
    }

    /**
     * Get preparacionMinutos
     *
     * @return integer
     */
    public function getPreparacionMinutos()
    {
        return $this->preparacionMinutos;
    }

    /**
     * Set realizaActividadFisicaExtrema
     *
     * @param boolean $realizaActividadFisicaExtrema
     *
     * @return ValoracionInicial
     */
    public function setRealizaActividadFisicaExtrema($realizaActividadFisicaExtrema)
    {
        $this->realizaActividadFisicaExtrema = $realizaActividadFisicaExtrema;

        return $this;
    }

    /**
     * Get realizaActividadFisicaExtrema
     *
     * @return boolean
     */
    public function getRealizaActividadFisicaExtrema()
    {
        return $this->realizaActividadFisicaExtrema;
    }

    /**
     * Set nombreActividadFisicaExtrema
     *
     * @param string $nombreActividadFisicaExtrema
     *
     * @return ValoracionInicial
     */
    public function setNombreActividadFisicaExtrema($nombreActividadFisicaExtrema)
    {
        $this->nombreActividadFisicaExtrema = $nombreActividadFisicaExtrema;

        return $this;
    }

    /**
     * Get nombreActividadFisicaExtrema
     *
     * @return string
     */
    public function getNombreActividadFisicaExtrema()
    {
        return $this->nombreActividadFisicaExtrema;
    }

    /**
     * Set diasActividadFisicaExtrema
     *
     * @param integer $diasActividadFisicaExtrema
     *
     * @return ValoracionInicial
     */
    public function setDiasActividadFisicaExtrema($diasActividadFisicaExtrema)
    {
        $this->diasActividadFisicaExtrema = $diasActividadFisicaExtrema;

        return $this;
    }

    /**
     * Get diasActividadFisicaExtrema
     *
     * @return integer
     */
    public function getDiasActividadFisicaExtrema()
    {
        return $this->diasActividadFisicaExtrema;
    }

    /**
     * Set minutosActividadFisicaExtrema
     *
     * @param integer $minutosActividadFisicaExtrema
     *
     * @return ValoracionInicial
     */
    public function setMinutosActividadFisicaExtrema($minutosActividadFisicaExtrema)
    {
        $this->minutosActividadFisicaExtrema = $minutosActividadFisicaExtrema;

        return $this;
    }

    /**
     * Get minutosActividadFisicaExtrema
     *
     * @return integer
     */
    public function getMinutosActividadFisicaExtrema()
    {
        return $this->minutosActividadFisicaExtrema;
    }

    /**
     * Set motivoConsulta
     *
     * @param string $motivoConsulta
     *
     * @return ValoracionInicial
     */
    public function setMotivoConsulta($motivoConsulta)
    {
        $this->motivoConsulta = $motivoConsulta;

        return $this;
    }

    /**
     * Get motivoConsulta
     *
     * @return string
     */
    public function getMotivoConsulta()
    {
        return $this->motivoConsulta;
    }

    /**
     * Set enfermedadActual
     *
     * @param string $enfermedadActual
     *
     * @return ValoracionInicial
     */
    public function setEnfermedadActual($enfermedadActual)
    {
        $this->enfermedadActual = $enfermedadActual;

        return $this;
    }

    /**
     * Get enfermedadActual
     *
     * @return string
     */
    public function getEnfermedadActual()
    {
        return $this->enfermedadActual;
    }

    /**
     * Set revisionSistema
     *
     * @param string $revisionSistema
     *
     * @return ValoracionInicial
     */
    public function setRevisionSistema($revisionSistema)
    {
        $this->revisionSistema = $revisionSistema;

        return $this;
    }

    /**
     * Get revisionSistema
     *
     * @return string
     */
    public function getRevisionSistema()
    {
        return $this->revisionSistema;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return ValoracionInicial
     */
    public function setConsultaMedico(\LogicBundle\Entity\ConsultaMedico $consultaMedico = null)
    {
        $this->consultaMedico = $consultaMedico;

        return $this;
    }

    /**
     * Get consultaMedico
     *
     * @return \LogicBundle\Entity\ConsultaMedico
     */
    public function getConsultaMedico()
    {
        return $this->consultaMedico;
    }

    /**
     * Set nivel
     *
     * @param \LogicBundle\Entity\Nivel $nivel
     *
     * @return ValoracionInicial
     */
    public function setNivel(\LogicBundle\Entity\Nivel $nivel = null)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return \LogicBundle\Entity\Nivel
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set precedenciaDeportiva
     *
     * @param \LogicBundle\Entity\PrecedenciaDeportiva $precedenciaDeportiva
     *
     * @return ValoracionInicial
     */
    public function setPrecedenciaDeportiva(\LogicBundle\Entity\PrecedenciaDeportiva $precedenciaDeportiva = null)
    {
        $this->precedenciaDeportiva = $precedenciaDeportiva;

        return $this;
    }

    /**
     * Get precedenciaDeportiva
     *
     * @return \LogicBundle\Entity\PrecedenciaDeportiva
     */
    public function getPrecedenciaDeportiva()
    {
        return $this->precedenciaDeportiva;
    }

    /**
     * Set categoriaNivel
     *
     * @param \LogicBundle\Entity\CategoriaNivel $categoriaNivel
     *
     * @return ValoracionInicial
     */
    public function setCategoriaNivel(\LogicBundle\Entity\CategoriaNivel $categoriaNivel = null)
    {
        $this->categoriaNivel = $categoriaNivel;

        return $this;
    }

    /**
     * Get categoriaNivel
     *
     * @return \LogicBundle\Entity\CategoriaNivel
     */
    public function getCategoriaNivel()
    {
        return $this->categoriaNivel;
    }
}
