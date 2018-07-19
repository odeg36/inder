<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaltasEncuentroJugador
 *
 * @ORM\Table(name="faltas_encuentro_jugador")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaltasEncuentroJugadorRepository")
 */
class FaltasEncuentroJugador
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoFalta", inversedBy="faltasEncuentroJugador")
     * @ORM\JoinColumn(name="tipoFalta_id",referencedColumnName="id")
     */

    private $tipoFalta;

     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SancionEvento")
     * @ORM\JoinColumn(name="sancionEvento_id",referencedColumnName="id")
     */
    
    private $sancionEvento;


     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaUno", inversedBy="faltasEncuentroJugador")
     * @ORM\JoinColumn(name="encuentroSistemaUno_id",referencedColumnName="id")
     */

    private $encuentroSistemaUno;

    /**
     * @var int
     *
     * @ORM\Column(name="jugadorEvento_id", type="integer" , nullable=true)
    */
    private $jugadorEvento;



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
     * Set tipoFalta
     *
     * @param \LogicBundle\Entity\TipoFalta $tipoFalta
     *
     * @return FaltasEncuentroJugador
     */
    public function setTipoFalta(\LogicBundle\Entity\TipoFalta $tipoFalta = null) {
        $this->tipoFalta = $tipoFalta;

        return $this;
    }

    /**
     * Get tipoFalta
     *
     * @return \LogicBundle\Entity\TipoFalta
     */
    public function getTipoFalta() {
        return $this->tipoFalta;
    }


    /**
     * Set sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return FaltasEncuentroSistemaDos
     */
    public function setSancionEvento(\LogicBundle\Entity\SancionEvento $sancionEvento = null) {
        $this->sancionEvento = $sancionEvento;

        return $this;
    }

    /**
     * Get sancionEvento
     *
     * @return \LogicBundle\Entity\SancionEvento
     */
    public function getSancionEvento() {
        return $this->sancionEvento;
    }


    /**
     * Set encuentroSistemaUno
     *
     * @param \LogicBundle\Entity\TipoFalta $encuentroSistemaUno
     *
     * @return FaltasEncuentroJugador
     */
    public function setEncuentroSistemaUno(\LogicBundle\Entity\EncuentroSistemaUno $encuentroSistemaUno = null) {
        $this->encuentroSistemaUno = $encuentroSistemaUno;

        return $this;
    }

    /**
     * Get encuentroSistemaUno
     *
     * @return \LogicBundle\Entity\EncuentroSistemaUno
     */
    public function getEncuentroSistemaUno() {
        return $this->encuentroSistemaUno;
    }
    /**
     * Set jugadorEvento
     *
     * @param integer $jugadorEvento
     *
     * @return FaltasEncuentroJugador
     */
    public function setJugadorEvento($jugadorEvento)
    {
        $this->jugadorEvento = $jugadorEvento;

        return $this;
    }

    /**
     * Get jugadorEvento
     *
     * @return int
     */
    public function getJugadorEvento()
    {
        return $this->jugadorEvento;
    }
    

}
