<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SancionEvento
 *
 * @ORM\Table(name="sancion_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SancionEventoRepository")
 */
class SancionEvento
{
    public function __toString() {
        return $this->getSancion() ? $this->getSancion()->getNombre() : '';
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
     * @var int
     *
     * @ORM\Column(name="puntaje_juego_limpio", type="integer")
     */
    private $puntajeJuegoLimpio;



    //relacion muchos a muchos con evento
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="sancionEvento")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;


    //relacion muchos a muchos con sancion
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Sancion", inversedBy="sancionEvento")
     * @ORM\JoinColumn(name="sancion_id",referencedColumnName="id")
     */
    private $sancion;


    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaDos", mappedBy="sancionEvento")
     */
    private $faltasEncuentroSistemaDos;
    
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\FaltasEncuentroSistemaCuatro", mappedBy="sancionEvento")
     */
    private $faltasEncuentroSistemaCuatro;


    /**
     * Constructor
     */
    public function __construct() {

        $this->faltasEncuentroSistemaDos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faltasEncuentroSistemaCuatro = new \Doctrine\Common\Collections\ArrayCollection();

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
     * Set puntajeJuegoLimpio
     *
     * @param integer $puntajeJuegoLimpio
     *
     * @return SancionEvento
     */
    public function setPuntajeJuegoLimpio($puntajeJuegoLimpio)
    {
        $this->puntajeJuegoLimpio = $puntajeJuegoLimpio;

        return $this;
    }

    /**
     * Get puntajeJuegoLimpio
     *
     * @return int
     */
    public function getPuntajeJuegoLimpio()
    {
        return $this->puntajeJuegoLimpio;
    }


    //paramaetros para traer de evento
    /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return SancionEvento
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null) {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \LogicBundle\Entity\Evento
     */
    public function getEvento() {
        return $this->evento;
    }


    ////parametros para traer con sanciones
    /**
     * Set sancion
     *
     * @param \LogicBundle\Entity\Sancion $sancion
     *
     * @return SancionEvento
     */
    public function setSancion(\LogicBundle\Entity\Sancion $sancion = null) {
        $this->sancion = $sancion;

        return $this;
    }

    /**
     * Get sancion
     *
     * @return \LogicBundle\Entity\Sancion
     */
    public function getSancion() {
        return $this->sancion;
    }

    /**
     * Add faltasEncuentroSistemaDos
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos
     *
     * @return SancionEvento
     */
    public function addFaltasEncuentroSistemaDos(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos) {
        $this->faltasEncuentroSistemaDos[] = $faltasEncuentroSistemaDos;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaDos
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos
     */
    public function removeFaltasEncuentroSistemaDos(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDos) {
        $this->faltasEncuentroSistemaDos->removeElement($faltasEncuentroSistemaDos);
    }

    /**
     * Get faltasEncuentroSistemaDos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaDos() {
        return $this->faltasEncuentroSistemaDos;
    }
    
    
    /**
     * Add faltasEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemaCuatro
     *
     * @return SancionEvento
     */
    public function addFaltasEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemaCuatro) {
        $this->faltasEncuentroSistemaCuatro[] = $faltasEncuentroSistemaCuatro;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaCuatro
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemaCuatro
     */
    public function removeFaltasEncuentroSistemaCuatro(\LogicBundle\Entity\FaltasEncuentroSistemaCuatro $faltasEncuentroSistemaCuatro) {
        $this->faltasEncuentroSistemaCuatro->removeElement($faltasEncuentroSistemaCuatro);
    }

    /**
     * Get faltasEncuentroSistemaCuatro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaltasEncuentroSistemaCuatro() {
        return $this->faltasEncuentroSistemaCuatro;
    }

    /**
     * Add faltasEncuentroSistemaDo
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo
     *
     * @return SancionEvento
     */
    public function addFaltasEncuentroSistemaDo(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo)
    {
        $this->faltasEncuentroSistemaDos[] = $faltasEncuentroSistemaDo;

        return $this;
    }

    /**
     * Remove faltasEncuentroSistemaDo
     *
     * @param \LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo
     */
    public function removeFaltasEncuentroSistemaDo(\LogicBundle\Entity\FaltasEncuentroSistemaDos $faltasEncuentroSistemaDo)
    {
        $this->faltasEncuentroSistemaDos->removeElement($faltasEncuentroSistemaDo);
    }
}
