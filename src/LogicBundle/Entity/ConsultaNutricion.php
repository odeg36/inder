<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ConsultaNutricion
 *
 * @ORM\Table(name="consulta_nutricion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ConsultaNutricionRepository")
 */
class ConsultaNutricion
{
    
    public function __toString() {
        if($this->getFechaCreacion()){
            return $this->getFechaCreacion()->format("d/m/Y") . ' - ' . $this->getDeportista(); 
        }
        
        return (string)$this->getId();
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
     * @var bool
     *
     * @ORM\Column(name="finalizado", type="boolean", nullable=true)
     */
    protected $finalizado;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="nutriciones")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;
    
    /**
     * @ORM\OneToOne(targetEntity="Antecedente", mappedBy="nutricion")
     */
    private $antecedente;
    
    /**
     * @ORM\OneToOne(targetEntity="DatoFuncional", mappedBy="nutricion")
     */
    private $datoFuncional;
    
    /**
     * @ORM\OneToOne(targetEntity="ActividadFisica", mappedBy="nutricion")
     */
    private $actividadFisica;

    /**
     * @ORM\OneToOne(targetEntity="RespuestaFuncional", mappedBy="nutricion")
     */
    private $respuestaFuncional;
    
    /**
     * @ORM\OneToOne(targetEntity="ValoracionAlimentaria", mappedBy="nutricion")
     */
    private $valoracionAlimentaria;
    
    /**
     * @ORM\OneToMany(targetEntity="ConsumoSuplemento", mappedBy="nutricion")
     */
    private $consumoSuplementos;
    
    /**
     * @ORM\OneToOne(targetEntity="Recordatorio", mappedBy="nutricion")
     */
    private $recordatorio;
    
    /**
     * @ORM\OneToOne(targetEntity="Antropometria", mappedBy="nutricion")
     */
    private $antropometria;
    
    /**
     * @ORM\OneToMany(targetEntity="Laboratorio", mappedBy="nutricion")
     */
    private $laboratorios;
    
    /**
     * @ORM\OneToOne(targetEntity="TratamientoAlimentacion", mappedBy="nutricion")
     */
    private $tratamientoAlimentacion;
    
    /**
     * @ORM\OneToMany(targetEntity="Ergogenica", mappedBy="nutricion")
     */
    private $ergogenicas;
    
    /**
     * @ORM\OneToMany(targetEntity="FichaCampoNutricion", mappedBy="nutricion")
     */
    private $fichaCampoNutriciones;
    
    /**
     * @ORM\OneToMany(targetEntity="NotaNutricion", mappedBy="nutricion")
     */
    private $notaNutriciones;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->consumoSuplementos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laboratorios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ergogenicas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fichaCampoNutriciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notaNutriciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ConsultaNutricion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return ConsultaNutricion
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return ConsultaNutricion
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ConsultaNutricion
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista()
    {
        return $this->deportista;
    }

    /**
     * Set antecedente
     *
     * @param \LogicBundle\Entity\Antecedente $antecedente
     *
     * @return ConsultaNutricion
     */
    public function setAntecedente(\LogicBundle\Entity\Antecedente $antecedente = null)
    {
        $this->antecedente = $antecedente;

        return $this;
    }

    /**
     * Get antecedente
     *
     * @return \LogicBundle\Entity\Antecedente
     */
    public function getAntecedente()
    {
        return $this->antecedente;
    }

    /**
     * Set datoFuncional
     *
     * @param \LogicBundle\Entity\DatoFuncional $datoFuncional
     *
     * @return ConsultaNutricion
     */
    public function setDatoFuncional(\LogicBundle\Entity\DatoFuncional $datoFuncional = null)
    {
        $this->datoFuncional = $datoFuncional;

        return $this;
    }

    /**
     * Get datoFuncional
     *
     * @return \LogicBundle\Entity\DatoFuncional
     */
    public function getDatoFuncional()
    {
        return $this->datoFuncional;
    }

    /**
     * Set actividadFisica
     *
     * @param \LogicBundle\Entity\ActividadFisica $actividadFisica
     *
     * @return ConsultaNutricion
     */
    public function setActividadFisica(\LogicBundle\Entity\ActividadFisica $actividadFisica = null)
    {
        $this->actividadFisica = $actividadFisica;

        return $this;
    }

    /**
     * Get actividadFisica
     *
     * @return \LogicBundle\Entity\ActividadFisica
     */
    public function getActividadFisica()
    {
        return $this->actividadFisica;
    }

    /**
     * Set respuestaFuncional
     *
     * @param \LogicBundle\Entity\RespuestaFuncional $respuestaFuncional
     *
     * @return ConsultaNutricion
     */
    public function setRespuestaFuncional(\LogicBundle\Entity\RespuestaFuncional $respuestaFuncional = null)
    {
        $this->respuestaFuncional = $respuestaFuncional;

        return $this;
    }

    /**
     * Get respuestaFuncional
     *
     * @return \LogicBundle\Entity\RespuestaFuncional
     */
    public function getRespuestaFuncional()
    {
        return $this->respuestaFuncional;
    }

    /**
     * Set valoracionAlimentaria
     *
     * @param \LogicBundle\Entity\ValoracionAlimentaria $valoracionAlimentaria
     *
     * @return ConsultaNutricion
     */
    public function setValoracionAlimentaria(\LogicBundle\Entity\ValoracionAlimentaria $valoracionAlimentaria = null)
    {
        $this->valoracionAlimentaria = $valoracionAlimentaria;

        return $this;
    }

    /**
     * Get valoracionAlimentaria
     *
     * @return \LogicBundle\Entity\ValoracionAlimentaria
     */
    public function getValoracionAlimentaria()
    {
        return $this->valoracionAlimentaria;
    }

    /**
     * Add consumoSuplemento
     *
     * @param \LogicBundle\Entity\ConsumoSuplemento $consumoSuplemento
     *
     * @return ConsultaNutricion
     */
    public function addConsumoSuplemento(\LogicBundle\Entity\ConsumoSuplemento $consumoSuplemento)
    {
        $this->consumoSuplementos[] = $consumoSuplemento;

        return $this;
    }

    /**
     * Remove consumoSuplemento
     *
     * @param \LogicBundle\Entity\ConsumoSuplemento $consumoSuplemento
     */
    public function removeConsumoSuplemento(\LogicBundle\Entity\ConsumoSuplemento $consumoSuplemento)
    {
        $this->consumoSuplementos->removeElement($consumoSuplemento);
    }

    /**
     * Get consumoSuplementos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsumoSuplementos()
    {
        return $this->consumoSuplementos;
    }

    /**
     * Set recordatorio
     *
     * @param \LogicBundle\Entity\Recordatorio $recordatorio
     *
     * @return ConsultaNutricion
     */
    public function setRecordatorio(\LogicBundle\Entity\Recordatorio $recordatorio = null)
    {
        $this->recordatorio = $recordatorio;

        return $this;
    }

    /**
     * Get recordatorio
     *
     * @return \LogicBundle\Entity\Recordatorio
     */
    public function getRecordatorio()
    {
        return $this->recordatorio;
    }

    /**
     * Set antropometria
     *
     * @param \LogicBundle\Entity\Antropometria $antropometria
     *
     * @return ConsultaNutricion
     */
    public function setAntropometria(\LogicBundle\Entity\Antropometria $antropometria = null)
    {
        $this->antropometria = $antropometria;

        return $this;
    }

    /**
     * Get antropometria
     *
     * @return \LogicBundle\Entity\Antropometria
     */
    public function getAntropometria()
    {
        return $this->antropometria;
    }

    /**
     * Add laboratorio
     *
     * @param \LogicBundle\Entity\Laboratorio $laboratorio
     *
     * @return ConsultaNutricion
     */
    public function addLaboratorio(\LogicBundle\Entity\Laboratorio $laboratorio)
    {
        $this->laboratorios[] = $laboratorio;

        return $this;
    }

    /**
     * Remove laboratorio
     *
     * @param \LogicBundle\Entity\Laboratorio $laboratorio
     */
    public function removeLaboratorio(\LogicBundle\Entity\Laboratorio $laboratorio)
    {
        $this->laboratorios->removeElement($laboratorio);
    }

    /**
     * Get laboratorios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratorios()
    {
        return $this->laboratorios;
    }

    /**
     * Set tratamientoAlimentacion
     *
     * @param \LogicBundle\Entity\TratamientoAlimentacion $tratamientoAlimentacion
     *
     * @return ConsultaNutricion
     */
    public function setTratamientoAlimentacion(\LogicBundle\Entity\TratamientoAlimentacion $tratamientoAlimentacion = null)
    {
        $this->tratamientoAlimentacion = $tratamientoAlimentacion;

        return $this;
    }

    /**
     * Get tratamientoAlimentacion
     *
     * @return \LogicBundle\Entity\TratamientoAlimentacion
     */
    public function getTratamientoAlimentacion()
    {
        return $this->tratamientoAlimentacion;
    }

    /**
     * Add ergogenica
     *
     * @param \LogicBundle\Entity\Ergogenica $ergogenica
     *
     * @return ConsultaNutricion
     */
    public function addErgogenica(\LogicBundle\Entity\Ergogenica $ergogenica)
    {
        $this->ergogenicas[] = $ergogenica;

        return $this;
    }

    /**
     * Remove ergogenica
     *
     * @param \LogicBundle\Entity\Ergogenica $ergogenica
     */
    public function removeErgogenica(\LogicBundle\Entity\Ergogenica $ergogenica)
    {
        $this->ergogenicas->removeElement($ergogenica);
    }

    /**
     * Get ergogenicas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getErgogenicas()
    {
        return $this->ergogenicas;
    }

    /**
     * Add fichaCampoNutricione
     *
     * @param \LogicBundle\Entity\FichaCampoNutricion $fichaCampoNutricione
     *
     * @return ConsultaNutricion
     */
    public function addFichaCampoNutricione(\LogicBundle\Entity\FichaCampoNutricion $fichaCampoNutricione)
    {
        $this->fichaCampoNutriciones[] = $fichaCampoNutricione;

        return $this;
    }

    /**
     * Remove fichaCampoNutricione
     *
     * @param \LogicBundle\Entity\FichaCampoNutricion $fichaCampoNutricione
     */
    public function removeFichaCampoNutricione(\LogicBundle\Entity\FichaCampoNutricion $fichaCampoNutricione)
    {
        $this->fichaCampoNutriciones->removeElement($fichaCampoNutricione);
    }

    /**
     * Get fichaCampoNutriciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichaCampoNutriciones()
    {
        return $this->fichaCampoNutriciones;
    }

    /**
     * Add notaNutricione
     *
     * @param \LogicBundle\Entity\NotaNutricion $notaNutricione
     *
     * @return ConsultaNutricion
     */
    public function addNotaNutricione(\LogicBundle\Entity\NotaNutricion $notaNutricione)
    {
        $this->notaNutriciones[] = $notaNutricione;

        return $this;
    }

    /**
     * Remove notaNutricione
     *
     * @param \LogicBundle\Entity\NotaNutricion $notaNutricione
     */
    public function removeNotaNutricione(\LogicBundle\Entity\NotaNutricion $notaNutricione)
    {
        $this->notaNutriciones->removeElement($notaNutricione);
    }

    /**
     * Get notaNutriciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotaNutriciones()
    {
        return $this->notaNutriciones;
    }
}
