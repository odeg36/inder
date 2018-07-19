<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Familiar
 *
 * @ORM\Table(name="familiar")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FamiliarRepository")
 */
class Familiar
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Parentezco", inversedBy="familiares")
     * @ORM\JoinColumn(name="parentezco_id", referencedColumnName="id")
     */
    private $parentezco;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true, nullable=true)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="familiares")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Familiar
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Familiar
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
     * Set parentezco
     *
     * @param \LogicBundle\Entity\Parentezco $parentezco
     *
     * @return Familiar
     */
    public function setParentezco(\LogicBundle\Entity\Parentezco $parentezco = null)
    {
        $this->parentezco = $parentezco;

        return $this;
    }

    /**
     * Get parentezco
     *
     * @return \LogicBundle\Entity\Parentezco
     */
    public function getParentezco()
    {
        return $this->parentezco;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Familiar
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
