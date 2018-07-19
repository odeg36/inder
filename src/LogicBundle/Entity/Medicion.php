<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Medicion
 *
 * @ORM\Table(name="medicion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MedicionRepository")
 */
class Medicion {

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Prueba", inversedBy="mediciones")
     * @ORM\JoinColumn(name="prueba_id",referencedColumnName="id")
     */
    private $prueba;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="mediciones")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $deportista;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\DisciplinaPruebaRama", inversedBy="mediciones")
     * @ORM\JoinColumn(name="disciplina_prueba_rama_id", referencedColumnName="id")
     */
    protected $disciplinaPruebaRama;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="resultado", type="text", nullable=true)
     */
    private $resultado;

    /**
     * @var string
     *
     * @ORM\Column(name="entrada", type="text", nullable=true)
     */
    private $entrada;

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

    public function __toString() {
        return $this->resultado ? $this->resultado : '';
    }

    ////************ FIN MODIFICACIONES ********////////////

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set resultado
     *
     * @param string $resultado
     *
     * @return Medicion
     */
    public function setResultado($resultado) {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado() {
        return $this->resultado;
    }

    /**
     * Set entrada
     *
     * @param string $entrada
     *
     * @return Medicion
     */
    public function setEntrada($entrada) {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return string
     */
    public function getEntrada() {
        return $this->entrada;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Medicion
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
     * @return Medicion
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
     * Set prueba
     *
     * @param \LogicBundle\Entity\Prueba $prueba
     *
     * @return Medicion
     */
    public function setPrueba(\LogicBundle\Entity\Prueba $prueba = null) {
        $this->prueba = $prueba;

        return $this;
    }

    /**
     * Get prueba
     *
     * @return \LogicBundle\Entity\Prueba
     */
    public function getPrueba() {
        return $this->prueba;
    }

    /**
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return Medicion
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null) {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista() {
        return $this->deportista;
    }

    /**
     * Set disciplinaPruebaRama
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinaPruebaRama
     *
     * @return Medicion
     */
    public function setDisciplinaPruebaRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinaPruebaRama = null) {
        $this->disciplinaPruebaRama = $disciplinaPruebaRama;

        return $this;
    }

    /**
     * Get disciplinaPruebaRama
     *
     * @return \LogicBundle\Entity\DisciplinaPruebaRama
     */
    public function getDisciplinaPruebaRama() {
        return $this->disciplinaPruebaRama;
    }
}
