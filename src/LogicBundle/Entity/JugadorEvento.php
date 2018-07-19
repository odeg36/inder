<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JugadorEvento
 *
 * @ORM\Table(name="jugador_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\JugadorEventoRepository")
 */
class JugadorEvento
{
    public function __toString() {
        return $this->getUsuarioJugadorEvento() ? $this->getUsuarioJugadorEvento()->getFirstname() . ' ' . $this->getUsuarioJugadorEvento()->getLastname() : '';
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
     * @ORM\Column(name="documento_imagen", type="string", length=255, nullable=true)
     */
    private $documentoImagen;

    /**
     * @var string
     *
     * @ORM\Column(name="eps_imagen", type="string", length=255, nullable=true)
     */
    private $epsImagen;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="jugadorEventos")
     * @ORM\JoinColumn(name="equipo_evento_id",referencedColumnName="id")
     */
    private $equipoEvento;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="jugadorEventos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuarioJugadorEvento;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="jugadorEventos")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    private $noDocumento;

    private $nombreUsuario;

    private $nombreEquipo;

     /**
     * Constructor
     */
    public function __construct() {
        
    }
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set documentoImagen
     *
     * @param string $documentoImagen
     *
     * @return JugadorEvento
     */
    public function setDocumentoImagen($documentoImagen)
    {
        $this->documentoImagen = $documentoImagen;

        return $this;
    }

    /**
     * Get documentoImagen
     *
     * @return string
     */
    public function getDocumentoImagen()
    {
        return $this->documentoImagen;
    }
    
    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return JugadorEvento
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return JugadorEvento
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set epsImagen
     *
     * @param string $epsImagen
     *
     * @return JugadorEvento
     */
    public function setEpsImagen($epsImagen)
    {
        $this->epsImagen = $epsImagen;

        return $this;
    }

    /**
     * Get epsImagen
     *
     * @return string
     */
    public function getEpsImagen()
    {
        return $this->epsImagen;
    }

     /**
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $equipoEvento
     *
     * @return JugadorEvento
     */
    public function setEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento = null) {
        $this->equipoEvento = $equipoEvento;

        return $this;
    }

    /**
     * Get equipoEvento
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getEquipoEvento() {
        return $this->equipoEvento;
    }

    /**
     * Set usuarioJugadorEvento
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuarioJugadorEvento
     *
     * @return JugadorEvento
     */
    public function setUsuarioJugadorEvento(\Application\Sonata\UserBundle\Entity\User $usuarioJugadorEvento = null) {
        $this->usuarioJugadorEvento = $usuarioJugadorEvento;

        return $this;
    }

    /**
     * Get usuarioJugadorEvento
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuarioJugadorEvento() {
        return $this->usuarioJugadorEvento;
    }

    /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return JugadorEvento
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento()
    {
        return $this->evento;
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

    public function getNoDocumento()
    {   
    
        if ($this->getId() != null) {
            
            
            if($this->getUsuarioJugadorEvento() != null)
            {
                if($this->getUsuarioJugadorEvento()->getNumeroIdentificacion() != null)
                {
                    return $this->getUsuarioJugadorEvento()->getNumeroIdentificacion();
                }else{

                    return $this->noDocumento;
                }
                
            }else{

                return $this->noDocumento;
            }
            
        }else{
            
            return $this->noDocumento;
        }
    }

    public function getNombreUsuario()
    {
        if ($this->getId() != null) {

            if($this->getUsuarioJugadorEvento() != null)
            {
                if($this->getUsuarioJugadorEvento()->nombreCompleto() != null)
                {
                    return $this->getUsuarioJugadorEvento()->nombreCompleto();

                }else
                {
                    return $this->nombreUsuario;        
                }
                
            }else{
            
                return $this->nombreUsuario;
            }
            
        }else{
            return $this->nombreUsuario;
        }
    }

    public function getNombreEquipo()
    {    
        if ($this->getId() != null) {
            if ($this->getEquipoEvento()  != null) {             
                return $this->getEquipoEvento()->getNombre();
            }else{
                return $this->nombreEquipo;
            }
        }else{
            return $this->nombreEquipo;
        }
    }
}
