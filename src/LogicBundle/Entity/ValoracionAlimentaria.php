<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValoracionAlimentaria
 *
 * @ORM\Table(name="valoracion_alimentaria")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ValoracionAlimentariaRepository")
 */
class ValoracionAlimentaria
{
    const APETITO_BUENO = "Bueno";
    const APETITO_REGULAR = "Regular";
    const APETITO_MALO = "Malo";
    
    const MASTICA_RAPIDO = "Rapido";
    const MASTICA_LENTO = "Lento";
    const MASTICA_NORMAL = "Normal";
    
    const COME_SOLO = "Solo";
    const COME_ACOMPANADO = "Acompañado";
    
    const CONSUMO_ALIMNETO_COMEDOR = "Comedor";
    const CONSUMO_ALIMNETO_COCINA = "Cocina";
    const CONSUMO_ALIMNETO_HABITACION = "Habitación";
    const CONSUMO_ALIMNETO_SALONTV = "Salon Tv";
    const CONSUMO_ALIMNETO_OTRO = "Otro";

    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
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
     * @ORM\Column(name="alimento_preferido", type="text", nullable=true)
     */
    private $alimentoPreferido;

    /**
     * @var string
     *
     * @ORM\Column(name="alimento_echazado", type="text", nullable=true)
     */
    private $alimentoRechazado;

    /**
     * @var string
     *
     * @ORM\Column(name="alimento_no_tolerado_alergia", type="text", nullable=true)
     */
    private $alimentoNoToleradoAlergia;

    /**
     * @var string
     *
     * @ORM\Column(name="apetito", type="string", length=20)
     */
    private $apetito;

    /**
     * @var string
     *
     * @ORM\Column(name="apetito_observacion", type="text", nullable=true)
     */
    private $apetitoObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="masticacion", type="string", length=20)
     */
    private $masticacion;

    /**
     * @var string
     *
     * @ORM\Column(name="masticacion_observacion", type="text", nullable=true)
     */
    private $masticacionObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar_consumo_alimento", type="string", length=30, nullable=true)
     */
    private $lugarConsumoAlimento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lugar_consumo_alimento_observacion", type="text", nullable=true)
     */
    private $lugarConsumoAlimentoObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="persona_con_quien_come", type="string", length=30)
     */
    private $personaConQuienCome;
    
    /**
     * @var string
     *
     * @ORM\Column(name="persona_con_quien_come_observacion", type="text", nullable=true)
     */
    private $personaConQuienComeObservacion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="valoracionAlimentaria")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\OneToMany(targetEntity="ValoracionAlimentacionFactorExterno", mappedBy="valoracionAlimentaria", cascade={"persist"})
     */
    private $valoracionAlimentariaFactorExternos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valoracionAlimentariaFactorExternos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set alimentoPreferido
     *
     * @param string $alimentoPreferido
     *
     * @return ValoracionAlimentaria
     */
    public function setAlimentoPreferido($alimentoPreferido)
    {
        $this->alimentoPreferido = $alimentoPreferido;

        return $this;
    }

    /**
     * Get alimentoPreferido
     *
     * @return string
     */
    public function getAlimentoPreferido()
    {
        return $this->alimentoPreferido;
    }

    /**
     * Set alimentoRechazado
     *
     * @param string $alimentoRechazado
     *
     * @return ValoracionAlimentaria
     */
    public function setAlimentoRechazado($alimentoRechazado)
    {
        $this->alimentoRechazado = $alimentoRechazado;

        return $this;
    }

    /**
     * Get alimentoRechazado
     *
     * @return string
     */
    public function getAlimentoRechazado()
    {
        return $this->alimentoRechazado;
    }

    /**
     * Set alimentoNoToleradoAlergia
     *
     * @param string $alimentoNoToleradoAlergia
     *
     * @return ValoracionAlimentaria
     */
    public function setAlimentoNoToleradoAlergia($alimentoNoToleradoAlergia)
    {
        $this->alimentoNoToleradoAlergia = $alimentoNoToleradoAlergia;

        return $this;
    }

    /**
     * Get alimentoNoToleradoAlergia
     *
     * @return string
     */
    public function getAlimentoNoToleradoAlergia()
    {
        return $this->alimentoNoToleradoAlergia;
    }

    /**
     * Set apetito
     *
     * @param string $apetito
     *
     * @return ValoracionAlimentaria
     */
    public function setApetito($apetito)
    {
        $this->apetito = $apetito;

        return $this;
    }

    /**
     * Get apetito
     *
     * @return string
     */
    public function getApetito()
    {
        return $this->apetito;
    }

    /**
     * Set apetitoObservacion
     *
     * @param string $apetitoObservacion
     *
     * @return ValoracionAlimentaria
     */
    public function setApetitoObservacion($apetitoObservacion)
    {
        $this->apetitoObservacion = $apetitoObservacion;

        return $this;
    }

    /**
     * Get apetitoObservacion
     *
     * @return string
     */
    public function getApetitoObservacion()
    {
        return $this->apetitoObservacion;
    }

    /**
     * Set masticacion
     *
     * @param string $masticacion
     *
     * @return ValoracionAlimentaria
     */
    public function setMasticacion($masticacion)
    {
        $this->masticacion = $masticacion;

        return $this;
    }

    /**
     * Get masticacion
     *
     * @return string
     */
    public function getMasticacion()
    {
        return $this->masticacion;
    }

    /**
     * Set masticacionObservacion
     *
     * @param string $masticacionObservacion
     *
     * @return ValoracionAlimentaria
     */
    public function setMasticacionObservacion($masticacionObservacion)
    {
        $this->masticacionObservacion = $masticacionObservacion;

        return $this;
    }

    /**
     * Get masticacionObservacion
     *
     * @return string
     */
    public function getMasticacionObservacion()
    {
        return $this->masticacionObservacion;
    }

    /**
     * Set lugarConsumoAlimento
     *
     * @param string $lugarConsumoAlimento
     *
     * @return ValoracionAlimentaria
     */
    public function setLugarConsumoAlimento($lugarConsumoAlimento)
    {
        $this->lugarConsumoAlimento = $lugarConsumoAlimento;

        return $this;
    }

    /**
     * Get lugarConsumoAlimento
     *
     * @return string
     */
    public function getLugarConsumoAlimento()
    {
        return $this->lugarConsumoAlimento;
    }

    /**
     * Set lugarConsumoAlimentoObservacion
     *
     * @param string $lugarConsumoAlimentoObservacion
     *
     * @return ValoracionAlimentaria
     */
    public function setLugarConsumoAlimentoObservacion($lugarConsumoAlimentoObservacion)
    {
        $this->lugarConsumoAlimentoObservacion = $lugarConsumoAlimentoObservacion;

        return $this;
    }

    /**
     * Get lugarConsumoAlimentoObservacion
     *
     * @return string
     */
    public function getLugarConsumoAlimentoObservacion()
    {
        return $this->lugarConsumoAlimentoObservacion;
    }

    /**
     * Set personaConQuienCome
     *
     * @param string $personaConQuienCome
     *
     * @return ValoracionAlimentaria
     */
    public function setPersonaConQuienCome($personaConQuienCome)
    {
        $this->personaConQuienCome = $personaConQuienCome;

        return $this;
    }

    /**
     * Get personaConQuienCome
     *
     * @return string
     */
    public function getPersonaConQuienCome()
    {
        return $this->personaConQuienCome;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return ValoracionAlimentaria
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }

    /**
     * Add valoracionAlimentariaFactorExterno
     *
     * @param \LogicBundle\Entity\ValoracionAlimentacionFactorExterno $valoracionAlimentariaFactorExterno
     *
     * @return ValoracionAlimentaria
     */
    public function addValoracionAlimentariaFactorExterno(\LogicBundle\Entity\ValoracionAlimentacionFactorExterno $valoracionAlimentariaFactorExterno)
    {
        $this->valoracionAlimentariaFactorExternos[] = $valoracionAlimentariaFactorExterno;

        return $this;
    }

    /**
     * Remove valoracionAlimentariaFactorExterno
     *
     * @param \LogicBundle\Entity\ValoracionAlimentacionFactorExterno $valoracionAlimentariaFactorExterno
     */
    public function removeValoracionAlimentariaFactorExterno(\LogicBundle\Entity\ValoracionAlimentacionFactorExterno $valoracionAlimentariaFactorExterno)
    {
        $this->valoracionAlimentariaFactorExternos->removeElement($valoracionAlimentariaFactorExterno);
    }

    /**
     * Get valoracionAlimentariaFactorExternos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValoracionAlimentariaFactorExternos()
    {
        return $this->valoracionAlimentariaFactorExternos;
    }

    /**
     * Set personaConQuienComeObservacion
     *
     * @param string $personaConQuienComeObservacion
     *
     * @return ValoracionAlimentaria
     */
    public function setPersonaConQuienComeObservacion($personaConQuienComeObservacion)
    {
        $this->personaConQuienComeObservacion = $personaConQuienComeObservacion;

        return $this;
    }

    /**
     * Get personaConQuienComeObservacion
     *
     * @return string
     */
    public function getPersonaConQuienComeObservacion()
    {
        return $this->personaConQuienComeObservacion;
    }
}
