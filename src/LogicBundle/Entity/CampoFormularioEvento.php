<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampoFormularioEvento
 *
 * @ORM\Table(name="campo_formulario_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoFormularioEventoRepository")
 */
class CampoFormularioEvento
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
     * @ORM\Column(name="pertenece", type="string", length=255)
     */
    private $pertenece;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Evento", inversedBy="campoFormulariosEventos")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

     /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CampoEvento", inversedBy="campoFormulariosEventos")
     * @ORM\JoinColumn(name="campo_evento_id",referencedColumnName="id")
     */
    private $campoEvento;
    
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
     * Set pertenece
     *
     * @param string $pertenece
     *
     * @return CampoFormularioEvento
     */
    public function setPertenece($pertenece)
    {
        $this->pertenece = $pertenece;

        return $this;
    }

    /**
     * Get pertenece
     *
     * @return string
     */
    public function getPertenece()
    {
        return $this->pertenece;
    }
     
    /**
     * Set evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return CampoFormulorioEvento
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

    /**
     * Set campoEvento
     *
     * @param \LogicBundle\Entity\CampoEvento $campoEvento
     *
     * @return CampoFormulorioEvento
     */
    public function setCampoEvento(\LogicBundle\Entity\CampoEvento $campoEvento = null) {
        $this->campoEvento = $campoEvento;

        return $this;
    }

    /**
     * Get campoEvento
     *
     * @return \LogicBundle\Entity\CampoEvento
     */
    public function getCampoEvento() {
        return $this->campoEvento;
    }
}
