<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Diagnosotico
 *
 * @ORM\Table(name="diagnostico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiagnosticoRepository")
 */
class Diagnostico {

    const TIPO_ESTUDIO = "Estudio";
    const TIPO_CONFIRMADO = "Confirmado";
    const TIPO_RESUELTO = "Resuelto";

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
     * @ORM\Column(name="tipo", type="string", length=30)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="conducta", type="text")
     */
    private $conducta;

    /**
     * @ORM\ManyToOne(targetEntity="Antropometria", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="antropometria_id", referencedColumnName="id")
     */
    private $antropometria;

    /**
     * @ORM\ManyToOne(targetEntity="ExamenFisicoMedico", inversedBy="diagnostico")
     * @ORM\JoinColumn(name="diagnostico_id", referencedColumnName="id")
     */
    private $ExamenesFisicosMedicos;

    /**
     * @ORM\ManyToOne(targetEntity="FichaCampoNutricion", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="ficha_campo_nutricion_id", referencedColumnName="id")
     */
    private $fichaCampoNutricion;

    /**
     * @ORM\ManyToOne(targetEntity="DescripcionHojaEvolucion", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="descripcion_hoja_evolucion_id", referencedColumnName="id")
     */
    private $descripcionHojaEvolucion;
    

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Diagnostico
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Diagnostico
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set conducta.
     *
     * @param string $conducta
     *
     * @return Diagnostico
     */
    public function setConducta($conducta)
    {
        $this->conducta = $conducta;

        return $this;
    }

    /**
     * Get conducta.
     *
     * @return string
     */
    public function getConducta()
    {
        return $this->conducta;
    }

    /**
     * Set antropometria.
     *
     * @param \LogicBundle\Entity\Antropometria|null $antropometria
     *
     * @return Diagnostico
     */
    public function setAntropometria(\LogicBundle\Entity\Antropometria $antropometria = null)
    {
        $this->antropometria = $antropometria;

        return $this;
    }

    /**
     * Get antropometria.
     *
     * @return \LogicBundle\Entity\Antropometria|null
     */
    public function getAntropometria()
    {
        return $this->antropometria;
    }

    /**
     * Set examenesFisicosMedicos.
     *
     * @param \LogicBundle\Entity\ExamenFisicoMedico|null $examenesFisicosMedicos
     *
     * @return Diagnostico
     */
    public function setExamenesFisicosMedicos(\LogicBundle\Entity\ExamenFisicoMedico $examenesFisicosMedicos = null)
    {
        $this->ExamenesFisicosMedicos = $examenesFisicosMedicos;

        return $this;
    }

    /**
     * Get examenesFisicosMedicos.
     *
     * @return \LogicBundle\Entity\ExamenFisicoMedico|null
     */
    public function getExamenesFisicosMedicos()
    {
        return $this->ExamenesFisicosMedicos;
    }

    /**
     * Set fichaCampoNutricion.
     *
     * @param \LogicBundle\Entity\FichaCampoNutricion|null $fichaCampoNutricion
     *
     * @return Diagnostico
     */
    public function setFichaCampoNutricion(\LogicBundle\Entity\FichaCampoNutricion $fichaCampoNutricion = null)
    {
        $this->fichaCampoNutricion = $fichaCampoNutricion;

        return $this;
    }

    /**
     * Get fichaCampoNutricion.
     *
     * @return \LogicBundle\Entity\FichaCampoNutricion|null
     */
    public function getFichaCampoNutricion()
    {
        return $this->fichaCampoNutricion;
    }

    /**
     * Set descripcionHojaEvolucion.
     *
     * @param \LogicBundle\Entity\DescripcionHojaEvolucion|null $descripcionHojaEvolucion
     *
     * @return Diagnostico
     */
    public function setDescripcionHojaEvolucion(\LogicBundle\Entity\DescripcionHojaEvolucion $descripcionHojaEvolucion = null)
    {
        $this->descripcionHojaEvolucion = $descripcionHojaEvolucion;

        return $this;
    }

    /**
     * Get descripcionHojaEvolucion.
     *
     * @return \LogicBundle\Entity\DescripcionHojaEvolucion|null
     */
    public function getDescripcionHojaEvolucion()
    {
        return $this->descripcionHojaEvolucion;
    }
}
