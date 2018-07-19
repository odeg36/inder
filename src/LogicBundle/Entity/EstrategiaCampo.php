<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstrategiaCampo
 *
 * @ORM\Table(name="estrategia_campo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EstrategiaCampoRepository")
 */
class EstrategiaCampo
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
     * @var bool
     *
     * @ORM\Column(name="requerido", type="boolean")
     */
    private $requerido;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="usar", type="boolean")
     */
    private $usar;

    /**
     * @ORM\ManyToOne(targetEntity="CampoUsuario", inversedBy="campoUsuarios")
     * @ORM\JoinColumn(name="campo_usuario_id",referencedColumnName="id")
     */
    private $campoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estrategia", inversedBy="estrategiaCampos")
     * @ORM\JoinColumn(name="estrategia_id",referencedColumnName="id")
     */
    private $estrategia;

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
     * Set requerido
     *
     * @param boolean $requerido
     *
     * @return EstrategiaCampo
     */
    public function setRequerido($requerido)
    {
        $this->requerido = $requerido;

        return $this;
    }

    /**
     * Get requerido
     *
     * @return boolean
     */
    public function getRequerido()
    {
        return $this->requerido;
    }

    /**
     * Set campoUsuario
     *
     * @param \LogicBundle\Entity\CampoUsuario $campoUsuario
     *
     * @return EstrategiaCampo
     */
    public function setCampoUsuario(\LogicBundle\Entity\CampoUsuario $campoUsuario = null)
    {
        $this->campoUsuario = $campoUsuario;

        return $this;
    }

    /**
     * Get campoUsuario
     *
     * @return \LogicBundle\Entity\CampoUsuario
     */
    public function getCampoUsuario()
    {
        return $this->campoUsuario;
    }

    /**
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return EstrategiaCampo
     */
    public function setEstrategia(\LogicBundle\Entity\Estrategia $estrategia = null)
    {
        $this->estrategia = $estrategia;

        return $this;
    }

    /**
     * Get estrategia
     *
     * @return \LogicBundle\Entity\Estrategia
     */
    public function getEstrategia()
    {
        return $this->estrategia;
    }

    /**
     * Set usar
     *
     * @param boolean $usar
     *
     * @return EstrategiaCampo
     */
    public function setUsar($usar)
    {
        $this->usar = $usar;

        return $this;
    }

    /**
     * Get usar
     *
     * @return boolean
     */
    public function getUsar()
    {
        return $this->usar;
    }
}
