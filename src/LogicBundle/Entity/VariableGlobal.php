<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariableGlobal
 *
 * @ORM\Table(name="variable_global")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\VariableGlobalRepository")
 */
class VariableGlobal
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dato1", type="datetime")     
     */
    private $dato1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dato2", type="datetime")
     */
    private $dato2;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return VariableGlobal
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
     * Set dato1
     *
    * @param \DateTime $dato1
     *
     * @return VariableGlobal
     */
    public function setDato1($dato1)
    {
        $this->dato1 = $dato1;

        return $this;
    }

    /**
     * Get dato1
     *
     * @return \DateTime
     */
    public function getDato1()
    {
        return $this->dato1;
    }

    /**
     * Set dato2
     *
     * @param \DateTime $dato2
     *
     * @return VariableGlobal
     */
    public function setDato2($dato2)
    {
        $this->dato2 = $dato2;

        return $this;
    }

    /**
     * Get dato2
     *
     * @return \DateTime
     */
    public function getDato2()
    {
        return $this->dato2;
    }
}
