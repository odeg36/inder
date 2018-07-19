<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * HojaEvolucion
 *
 * @ORM\Table(name="hoja_evolucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\HojaEvolucionRepository")
 */
class HojaEvolucion {

    public function __toString() {
        if($this->getFechaCreacion()){
            return $this->getFechaCreacion()->format("d/m/Y") . ' - ' . $this->getConsultaMedico()->getDeportista(); 
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
     * @ORM\OneToOne(targetEntity="DescripcionHojaEvolucion", mappedBy="hojaEvolucion")
     */
    private $descripcionHojaEvolucion;

    /**
     * @ORM\OneToMany(targetEntity="TratamientoHojaEvolucion", mappedBy="hojaEvolucion")
     */
    private $tratamientosHojasEvoluciones;

    /**
     * @ORM\OneToMany(targetEntity="LaboratorioHojaEvolucion", mappedBy="hojaEvolucion")
     */
    private $laboratoriosHojasEvoluciones;

    /**
     * @ORM\OneToOne(targetEntity="RemisionHojaEvolucion", mappedBy="hojaEvolucion")
     */
    private $remisionHojaEvolucion;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaMedico", inversedBy="hojasEvoluciones")
     * @ORM\JoinColumn(name="consulta_medico_id", referencedColumnName="id")
     */
    private $consultaMedico;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="finalizado", type="boolean", nullable=true)
     */
    protected $finalizado;
    
    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date", nullable=true)
     */
    protected $fechaActualizacion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tratamientosHojasEvoluciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laboratoriosHojasEvoluciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return HojaEvolucion
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return HojaEvolucion
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
     * @return HojaEvolucion
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
     * Set descripcionHojaEvolucion
     *
     * @param \LogicBundle\Entity\DescripcionHojaEvolucion $descripcionHojaEvolucion
     *
     * @return HojaEvolucion
     */
    public function setDescripcionHojaEvolucion(\LogicBundle\Entity\DescripcionHojaEvolucion $descripcionHojaEvolucion = null)
    {
        $this->descripcionHojaEvolucion = $descripcionHojaEvolucion;

        return $this;
    }

    /**
     * Get descripcionHojaEvolucion
     *
     * @return \LogicBundle\Entity\DescripcionHojaEvolucion
     */
    public function getDescripcionHojaEvolucion()
    {
        return $this->descripcionHojaEvolucion;
    }

    /**
     * Add tratamientosHojasEvolucione
     *
     * @param \LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione
     *
     * @return HojaEvolucion
     */
    public function addTratamientosHojasEvolucione(\LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione)
    {
        $this->tratamientosHojasEvoluciones[] = $tratamientosHojasEvolucione;

        return $this;
    }

    /**
     * Remove tratamientosHojasEvolucione
     *
     * @param \LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione
     */
    public function removeTratamientosHojasEvolucione(\LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione)
    {
        $this->tratamientosHojasEvoluciones->removeElement($tratamientosHojasEvolucione);
    }

    /**
     * Get tratamientosHojasEvoluciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTratamientosHojasEvoluciones()
    {
        return $this->tratamientosHojasEvoluciones;
    }

    /**
     * Add laboratoriosHojasEvolucione
     *
     * @param \LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione
     *
     * @return HojaEvolucion
     */
    public function addLaboratoriosHojasEvolucione(\LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione)
    {
        $this->laboratoriosHojasEvoluciones[] = $laboratoriosHojasEvolucione;

        return $this;
    }

    /**
     * Remove laboratoriosHojasEvolucione
     *
     * @param \LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione
     */
    public function removeLaboratoriosHojasEvolucione(\LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione)
    {
        $this->laboratoriosHojasEvoluciones->removeElement($laboratoriosHojasEvolucione);
    }

    /**
     * Get laboratoriosHojasEvoluciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratoriosHojasEvoluciones()
    {
        return $this->laboratoriosHojasEvoluciones;
    }

    /**
     * Set remisionHojaEvolucion
     *
     * @param \LogicBundle\Entity\RemisionHojaEvolucion $remisionHojaEvolucion
     *
     * @return HojaEvolucion
     */
    public function setRemisionHojaEvolucion(\LogicBundle\Entity\RemisionHojaEvolucion $remisionHojaEvolucion = null)
    {
        $this->remisionHojaEvolucion = $remisionHojaEvolucion;

        return $this;
    }

    /**
     * Get remisionHojaEvolucion
     *
     * @return \LogicBundle\Entity\RemisionHojaEvolucion
     */
    public function getRemisionHojaEvolucion()
    {
        return $this->remisionHojaEvolucion;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return HojaEvolucion
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
}
