<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultadoEncuentroSistemaLiga
 *
 * @ORM\Table(name="resultado_encuentro_sistema_liga")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResultadoEncuentroSistemaLigaRepository")
 */
class ResultadoEncuentroSistemaLiga
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
     * @var int
     *
     * @ORM\Column(name="set_uno", type="integer")
     */
    private $setUno;

    /**
     * @var int
     *
     * @ORM\Column(name="set_dos", type="integer")
     */
    private $setDos;

    /**
     * @var int
     *
     * @ORM\Column(name="set_tres", type="integer")
     */
    private $setTres;

    /**
     * @var int
     *
     * @ORM\Column(name="set_cuatro", type="integer")
     */
    private $setCuatro;

    /**
     * @var int
     *
     * @ORM\Column(name="set_scoe", type="integer")
     */
    private $setScoe;

    /**
     * @var int
     *
     * @ORM\Column(name="tantos_contra", type="integer")
     */
    private $tantosContra;

    /**
     * @var int
     *
     * @ORM\Column(name="tcoe", type="integer")
     */
    private $tcoe;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuentroSistemaLiga", inversedBy="resultadoEncuentroSistemaLigas")
     * @ORM\JoinColumn(name="encuentro_sistema_liga_id",referencedColumnName="id")
     */
    private $encuentroSistemaLiga;


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
     * Set setUno
     *
     * @param integer $setUno
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setSetUno($setUno)
    {
        $this->setUno = $setUno;

        return $this;
    }

    /**
     * Get setUno
     *
     * @return int
     */
    public function getSetUno()
    {
        return $this->setUno;
    }

    /**
     * Set setDos
     *
     * @param integer $setDos
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setSetDos($setDos)
    {
        $this->setDos = $setDos;

        return $this;
    }

    /**
     * Get setDos
     *
     * @return int
     */
    public function getSetDos()
    {
        return $this->setDos;
    }

    /**
     * Set setTres
     *
     * @param integer $setTres
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setSetTres($setTres)
    {
        $this->setTres = $setTres;

        return $this;
    }

    /**
     * Get setTres
     *
     * @return int
     */
    public function getSetTres()
    {
        return $this->setTres;
    }

    /**
     * Set setCuatro
     *
     * @param integer $setCuatro
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setSetCuatro($setCuatro)
    {
        $this->setCuatro = $setCuatro;

        return $this;
    }

    /**
     * Get setCuatro
     *
     * @return int
     */
    public function getSetCuatro()
    {
        return $this->setCuatro;
    }

    /**
     * Set setScoe
     *
     * @param integer $setScoe
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setSetScoe($setScoe)
    {
        $this->setScoe = $setScoe;

        return $this;
    }

    /**
     * Get setScoe
     *
     * @return int
     */
    public function getSetScoe()
    {
        return $this->setScoe;
    }

    /**
     * Set tantosContra
     *
     * @param integer $tantosContra
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setTantosContra($tantosContra)
    {
        $this->tantosContra = $tantosContra;

        return $this;
    }

    /**
     * Get tantosContra
     *
     * @return int
     */
    public function getTantosContra()
    {
        return $this->tantosContra;
    }

    /**
     * Set tcoe
     *
     * @param integer $tcoe
     *
     * @return ResultadoEncuentroSistemaDos
     */
    public function setTcoe($tcoe)
    {
        $this->tcoe = $tcoe;

        return $this;
    }

    /**
     * Get tcoe
     *
     * @return int
     */
    public function getTcoe()
    {
        return $this->tcoe;
    }

     /**
     * Set encuentroSistemaLiga
     *
     * @param \LogicBundle\Entity\EncuentroSistemaLiga $sistemaJencuentroSistemaLigauegoLiga
     *
     * @return ResultadoEncuentroSistemaLiga
     */
    public function setEncuentroSistemaLiga(\LogicBundle\Entity\EncuentroSistemaLiga $encuentroSistemaLiga = null) {
        $this->encuentroSistemaLiga = $encuentroSistemaLiga;

        return $this;
    }

    /**
     * Get encuentroSistemaLiga
     *
     * @return \LogicBundle\Entity\EncuentroSistemaLiga
     */
    public function getEncuentroSistemaLiga() {
        return $this->encuentroSistemaLiga;
    }
}
