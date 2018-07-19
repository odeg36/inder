<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadHorario
 *
 * @ORM\Table(name="actividad_horario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ActividadHorarioRepository")
 */
class ActividadHorario
{
    const AM = "AM";
    const PM = "PM";


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
     * @ORM\Column(name="hora_am", type="integer")
     */
    private $horaAM;
    
    /**
     * @var int
     *
     * @ORM\Column(name="hora_pm", type="integer")
     */
    private $horaPM;

    /**
     * @var string
     *
     * @ORM\Column(name="horario_am", type="string", length=2)
     */
    private $horarioAM;
    
    /**
     * @var string
     *
     * @ORM\Column(name="horario_pm", type="string", length=2)
     */
    private $horarioPM;

    /**
     * @ORM\ManyToOne(targetEntity="ActividadFisica", inversedBy="actividadHorarios")
     * @ORM\JoinColumn(name="actividad_fisica_id", referencedColumnName="id")
     */
    private $actividadFisica;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dia", inversedBy="actividadHorarios")
     * @ORM\JoinColumn(name="dia_id", referencedColumnName="id")
     */
    private $dia;
    

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
     * Set hora
     *
     * @param integer $hora
     *
     * @return ActividadHorario
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return integer
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set horario
     *
     * @param string $horario
     *
     * @return ActividadHorario
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set actividadFisica
     *
     * @param \LogicBundle\Entity\ActividadFisica $actividadFisica
     *
     * @return ActividadHorario
     */
    public function setActividadFisica(\LogicBundle\Entity\ActividadFisica $actividadFisica = null)
    {
        $this->actividadFisica = $actividadFisica;

        return $this;
    }

    /**
     * Get actividadFisica
     *
     * @return \LogicBundle\Entity\ActividadFisica
     */
    public function getActividadFisica()
    {
        return $this->actividadFisica;
    }

    /**
     * Set dia
     *
     * @param \LogicBundle\Entity\Dia $dia
     *
     * @return ActividadHorario
     */
    public function setDia(\LogicBundle\Entity\Dia $dia = null)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \LogicBundle\Entity\Dia
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set horaAM
     *
     * @param integer $horaAM
     *
     * @return ActividadHorario
     */
    public function setHoraAM($horaAM)
    {
        $this->horaAM = $horaAM;

        return $this;
    }

    /**
     * Get horaAM
     *
     * @return integer
     */
    public function getHoraAM()
    {
        return $this->horaAM;
    }

    /**
     * Set horaPM
     *
     * @param integer $horaPM
     *
     * @return ActividadHorario
     */
    public function setHoraPM($horaPM)
    {
        $this->horaPM = $horaPM;

        return $this;
    }

    /**
     * Get horaPM
     *
     * @return integer
     */
    public function getHoraPM()
    {
        return $this->horaPM;
    }

    /**
     * Set horarioAM
     *
     * @param string $horarioAM
     *
     * @return ActividadHorario
     */
    public function setHorarioAM($horarioAM)
    {
        $this->horarioAM = $horarioAM;

        return $this;
    }

    /**
     * Get horarioAM
     *
     * @return string
     */
    public function getHorarioAM()
    {
        return $this->horarioAM;
    }

    /**
     * Set horarioPM
     *
     * @param string $horarioPM
     *
     * @return ActividadHorario
     */
    public function setHorarioPM($horarioPM)
    {
        $this->horarioPM = $horarioPM;

        return $this;
    }

    /**
     * Get horarioPM
     *
     * @return string
     */
    public function getHorarioPM()
    {
        return $this->horarioPM;
    }
}
