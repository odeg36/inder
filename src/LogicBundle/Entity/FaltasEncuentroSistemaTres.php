<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaltasEncuentroJugador
 *
 * @ORM\Table(name="faltas_encuentro_sistema_tres")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FaltasEncuentroSistemaTresRepository")
 */
class FaltasEncuentroSistemaTres
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoFalta", inversedBy="faltasEncuentroSistemaTres")
     * @ORM\JoinColumn(name="tipoFalta_id",referencedColumnName="id")
     */

    private $tipoFalta;



     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaTres", inversedBy="faltasEncuentroSistemaTres")
     * @ORM\JoinColumn(name="encuentroSistemaTres_id",referencedColumnName="id")
     */

    private $encuentroSistemaTres;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EquipoEvento", inversedBy="faltasEquipoEncuentroSistemaTres")
     * @ORM\JoinColumn(name="equipoEvento_id",referencedColumnName="id")
     */
    private $equipoEvento;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\SancionEvento")
     * @ORM\JoinColumn(name="sancionEvento_id",referencedColumnName="id")
     */
    
    private $sancionEvento;



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
     * Set encuentroSistemaTres
     *
     * @param \LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres
     *
     * @return FaltasEncuentroJugador
     */
    public function setEncuentroSistemaTres(\LogicBundle\Entity\EncuentroSistemaTres $encuentroSistemaTres = null) {
        $this->encuentroSistemaTres = $encuentroSistemaTres;

        return $this;
    }

    /**
     * Get encuentroSistemaTres
     *
     * @return \LogicBundle\Entity\EncuentroSistemaTres
     */
    public function getEncuentroSistemaTres() {
        return $this->encuentroSistemaTres;
    }
    /**
     * Set equipoEvento
     *
     * @param \LogicBundle\Entity\EquipoEvento $encuentroSistemaTres
     *
     * @return EquipoEvento
     */
    public function setEquipoEvento(\LogicBundle\Entity\EquipoEvento $equipoEvento = null)
    {
        $this->equipoEvento = $equipoEvento;

        return $this;
    }


    /**
     * Get equipoEvento
     *
     * @return \LogicBundle\Entity\EquipoEvento
     */
    public function getEquipoEvento()
    {
        return $this->equipoEvento;
    }

    /**
     * Set sancionEvento
     *
     * @param \LogicBundle\Entity\SancionEvento $sancionEvento
     *
     * @return FaltasEncuentroSistemaTres
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
    

}
