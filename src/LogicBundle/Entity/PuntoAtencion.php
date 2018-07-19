<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

/**
 * PuntoAtencion
 *
 * @ORM\Table(name="punto_atencion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PuntoAtencionRepository")
 */
class PuntoAtencion {

    public function __toString() {
        return $this->direccion ? $this->direccion : '';
    }
    
    /**
     * Set localizacion
     *
     * @param string $localizacion
     *
     * @return PuntoAtencion
     */
    public function setLocalizacion($localizacion)
    {
        $this->setLongitud($localizacion['longitud']);
        $this->setLatitud($localizacion['latitud']);
        return $this;
    }
    
    /**
     * @Assert\NotBlank()
     */
    public function getLocalizacion()
    {
        return array('latitud'=>$this->getLatitud(),'longitud'=>$this->getLongitud());
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
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion_comuna", type="string", length=255, nullable=true)
     */
    private $direccionComuna;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Barrio", inversedBy="puntoAtencion");
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id")
     */
    private $barrio;

    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=255)
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=255)
     */
    private $longitud;
    
    /**
     * @var string
     *
     * @ORM\Column(name="localizacion", type="string", length=255, nullable=true)
     */
    private $localizacion;

    /**
     * @ORM\OneToMany(targetEntity="Oferta", mappedBy="puntoAtencion");
     */
    private $oferta;

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="puntoAtencion");
     */
    private $eventos;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaUno", mappedBy="puntoAtencion");
     */ 
    private $encuentroSistemaUno;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaDos", mappedBy="puntoAtencion");
     */ 
    private $encuentroSistemaDos;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaCuatro", mappedBy="puntoAtencion");
     */ 
    private $encuentroSistemaCuatro;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", mappedBy="puntoAtencion");
     */ 
    private $encuentroSistemaTres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->oferta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaUno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return PuntoAtencion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set latitud
     *
     * @param string $latitud
     *
     * @return PuntoAtencion
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return string
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     *
     * @return PuntoAtencion
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return string
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return PuntoAtencion
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
     * @return PuntoAtencion
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
     * Set barrio
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return PuntoAtencion
     */
    public function setBarrio(\LogicBundle\Entity\Barrio $barrio = null)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return \LogicBundle\Entity\Barrio
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Add ofertum
     *
     * @param \LogicBundle\Entity\Oferta $ofertum
     *
     * @return PuntoAtencion
     */
    public function addOfertum(\LogicBundle\Entity\Oferta $ofertum)
    {
        $this->oferta[] = $ofertum;

        return $this;
    }

    /**
     * Remove ofertum
     *
     * @param \LogicBundle\Entity\Oferta $ofertum
     */
    public function removeOfertum(\LogicBundle\Entity\Oferta $ofertum)
    {
        $this->oferta->removeElement($ofertum);
    }

    /**
     * Get oferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return PuntoAtencion
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



    ///////////

    /**
     * Add encuentroSistemaUno
     *
     * @param \LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno
     *
     * @return PuntoAtencion
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
     * Add encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos
     *
     * @return PuntoAtencion
     */
    public function addEncuentroSistemaDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos) {
        $this->encuentroSistemaDos[] = $encuentroSistemaDos;

        return $this;
    }

    /**
     * Remove encuentroSistemaDos
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos
     */
    public function removeEncuentroSistemaDos(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDos) {
        $this->encuentroSistemaDos->removeElement($encuentroSistemaDos);
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
     * @return PuntoAtencion
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
     * Add encuentroSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo
     *
     * @return PuntoAtencion
     */
    public function addEncuentroSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo)
    {
        $this->encuentroSistemaDos[] = $encuentroSistemaDo;

        return $this;
    }

    /**
     * Remove encuentroSistemaDo
     *
     * @param \LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo
     */
    public function removeEncuentroSistemaDo(\LogicBundle\Entity\EncuentroSistemaDos $encuentroSistemaDo)
    {
        $this->encuentroSistemaDos->removeElement($encuentroSistemaDo);
    }

    /**
     * Add encuentroSistemaTre
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTre
     *
     * @return PuntoAtencion
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
     * Get encuentroSistemaTres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuentroSistemaTres()
    {
        return $this->encuentroSistemaTres;
    }

    /**
     * Set direccionComuna.
     *
     * @param string|null $direccionComuna
     *
     * @return PuntoAtencion
     */
    public function setDireccionComuna($direccionComuna = null)
    {
        $this->direccionComuna = $direccionComuna;

        return $this;
    }

    /**
     * Get direccionComuna.
     *
     * @return string|null
     */
    public function getDireccionComuna()
    {
        return $this->direccionComuna;
    }
}
