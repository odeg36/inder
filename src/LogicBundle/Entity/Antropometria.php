<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Antropometria
 *
 * @ORM\Table(name="antropometria")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AntropometriaRepository")
 */
class Antropometria
{
    const GRASA_MUY_BAJA = "Muy bajo";
    const GRASA_OPTIMO = "Optimo";
    const GRASA_MODERADAMENTE_ALTO = "Moderadamente alto";
    const GRASA_ALTO = "Alto";
    const GRASA_MUY_ALTO = "Muy alto";
    const GRASA_ADECUADO = "Adecuado";
    const GRASA_DELGADO = "Delgado";

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
     * @var float
     *
     * @ORM\Column(name="perimetro_cintura", type="float", nullable=true)
     */
    private $perimetroCintura;

    /**
     * @var float
     *
     * @ORM\Column(name="Perimetro_cadera", type="float", nullable=true)
     */
    private $perimetroCadera;

    /**
     * @var float
     *
     * @ORM\Column(name="perimetro_muneca", type="float", nullable=true)
     */
    private $perimetroMuneca;

    /**
     * @var float
     *
     * @ORM\Column(name="somatipo_x", type="float", nullable=true)
     */
    private $somatipoX;

    /**
     * @var float
     *
     * @ORM\Column(name="somatipo_y", type="float", nullable=true)
     */
    private $somatipoY;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_envergadura", type="float", nullable=true)
     */
    private $longitudEnvergadura;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_acromial_radial", type="float", nullable=true)
     */
    private $longitudAcromialRadial;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_radial_estilodeo", type="float", nullable=true)
     */
    private $longitudRadialEstilodeo;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_mediaestiloide", type="float", nullable=true)
     */
    private $longitudMediaestiloide;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_tibiamedia", type="float", nullable=true)
     */
    private $longitudTibiamedia;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_ilioespinal", type="float", nullable=true)
     */
    private $longitudIlioespinal;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud_trocantera", type="float", nullable=true)
     */
    private $longitudTrocantera;

    /**
     * @var float
     *
     * @ORM\Column(name="grasa_mgl", type="float", nullable=true)
     */
    private $grasaMLG;

    /**
     * @var float
     *
     * @ORM\Column(name="grasa", type="float", nullable=true)
     */
    private $grasa;

    /**
     * @var float
     *
     * @ORM\Column(name="grasa_aks", type="float", nullable=true)
     */
    private $grasaAKS;

    /**
     * @var float
     *
     * @ORM\Column(name="grasa_sematipo", type="float", nullable=true)
     */
    private $grasaSematipo;

    /**
     * @var float
     *
     * @ORM\Column(name="grasa_final", type="float", nullable=true)
     */
    private $grasaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=30, nullable=true)
     */
    private $clasificacion;

    /**
     * @var float
     *
     * @ORM\Column(name="porcentaje_grasa", type="float", nullable=true)
     */
    private $porcentajeGrasa;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_triceps", type="float", nullable=true)
     */
    private $pliegueTriceps;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_subescapular", type="float", nullable=true)
     */
    private $pliegueSubescapular;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_bicepts", type="float", nullable=true)
     */
    private $pliegueBicepts;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_pectorales", type="float", nullable=true)
     */
    private $plieguePectorales;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_axilar", type="float", nullable=true)
     */
    private $pliegueAxilar;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_suprailico", type="float", nullable=true)
     */
    private $pliegueSuprailico;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_supraespinal", type="float", nullable=true)
     */
    private $pliegueSupraespinal;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_adominal", type="float", nullable=true)
     */
    private $pliegueAdominal;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_muslo", type="float", nullable=true)
     */
    private $pliegueMuslo;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_pierna", type="float", nullable=true)
     */
    private $plieguePierna;

    /**
     * @var float
     *
     * @ORM\Column(name="pliegue_sumatoria", type="float", nullable=true)
     */
    private $pliegueSumatoria;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_biacrominal", type="float", nullable=true)
     */
    private $diametroBiacrominal;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_bicrestal", type="float", nullable=true)
     */
    private $diametroBicrestal;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_bitrocante", type="float", nullable=true)
     */
    private $diametroBitrocante;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_torax", type="float", nullable=true)
     */
    private $diametroTorax;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_ancho_mano", type="float", nullable=true)
     */
    private $diametroAnchoMano;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_biepicondilar_humero", type="float", nullable=true)
     */
    private $diametroBiepicondilarHumero;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_biepicondilar_femur", type="float", nullable=true)
     */
    private $diametroBiepicondilarFemur;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_ancho_pie", type="float", nullable=true)
     */
    private $diametroAnchoPie;

    /**
     * @var float
     *
     * @ORM\Column(name="diametro_largo_pie", type="float", nullable=true)
     */
    private $diametroLargoPie;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="antropometria")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\OneToMany(targetEntity="Diagnostico", mappedBy="antropometria", cascade={"persist"})
     */
    private $diagnosticos;
    
    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create" )
     * @ORM\Column(name="fecha_creacion", type="date" )
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->diagnosticos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set perimetroCintura
     *
     * @param float $perimetroCintura
     *
     * @return Antropometria
     */
    public function setPerimetroCintura($perimetroCintura)
    {
        $this->perimetroCintura = $perimetroCintura;

        return $this;
    }

    /**
     * Get perimetroCintura
     *
     * @return float
     */
    public function getPerimetroCintura()
    {
        return $this->perimetroCintura;
    }

    /**
     * Set perimetroCadera
     *
     * @param float $perimetroCadera
     *
     * @return Antropometria
     */
    public function setPerimetroCadera($perimetroCadera)
    {
        $this->perimetroCadera = $perimetroCadera;

        return $this;
    }

    /**
     * Get perimetroCadera
     *
     * @return float
     */
    public function getPerimetroCadera()
    {
        return $this->perimetroCadera;
    }

    /**
     * Set perimetroMuneca
     *
     * @param float $perimetroMuneca
     *
     * @return Antropometria
     */
    public function setPerimetroMuneca($perimetroMuneca)
    {
        $this->perimetroMuneca = $perimetroMuneca;

        return $this;
    }

    /**
     * Get perimetroMuneca
     *
     * @return float
     */
    public function getPerimetroMuneca()
    {
        return $this->perimetroMuneca;
    }

    /**
     * Set somatipoX
     *
     * @param float $somatipoX
     *
     * @return Antropometria
     */
    public function setSomatipoX($somatipoX)
    {
        $this->somatipoX = $somatipoX;

        return $this;
    }

    /**
     * Get somatipoX
     *
     * @return float
     */
    public function getSomatipoX()
    {
        return $this->somatipoX;
    }

    /**
     * Set somatipoY
     *
     * @param float $somatipoY
     *
     * @return Antropometria
     */
    public function setSomatipoY($somatipoY)
    {
        $this->somatipoY = $somatipoY;

        return $this;
    }

    /**
     * Get somatipoY
     *
     * @return float
     */
    public function getSomatipoY()
    {
        return $this->somatipoY;
    }

    /**
     * Set longitudEnvergadura
     *
     * @param float $longitudEnvergadura
     *
     * @return Antropometria
     */
    public function setLongitudEnvergadura($longitudEnvergadura)
    {
        $this->longitudEnvergadura = $longitudEnvergadura;

        return $this;
    }

    /**
     * Get longitudEnvergadura
     *
     * @return float
     */
    public function getLongitudEnvergadura()
    {
        return $this->longitudEnvergadura;
    }

    /**
     * Set longitudAcromialRadial
     *
     * @param float $longitudAcromialRadial
     *
     * @return Antropometria
     */
    public function setLongitudAcromialRadial($longitudAcromialRadial)
    {
        $this->longitudAcromialRadial = $longitudAcromialRadial;

        return $this;
    }

    /**
     * Get longitudAcromialRadial
     *
     * @return float
     */
    public function getLongitudAcromialRadial()
    {
        return $this->longitudAcromialRadial;
    }

    /**
     * Set longitudRadialEstilodeo
     *
     * @param float $longitudRadialEstilodeo
     *
     * @return Antropometria
     */
    public function setLongitudRadialEstilodeo($longitudRadialEstilodeo)
    {
        $this->longitudRadialEstilodeo = $longitudRadialEstilodeo;

        return $this;
    }

    /**
     * Get longitudRadialEstilodeo
     *
     * @return float
     */
    public function getLongitudRadialEstilodeo()
    {
        return $this->longitudRadialEstilodeo;
    }

    /**
     * Set longitudMediaestiloide
     *
     * @param float $longitudMediaestiloide
     *
     * @return Antropometria
     */
    public function setLongitudMediaestiloide($longitudMediaestiloide)
    {
        $this->longitudMediaestiloide = $longitudMediaestiloide;

        return $this;
    }

    /**
     * Get longitudMediaestiloide
     *
     * @return float
     */
    public function getLongitudMediaestiloide()
    {
        return $this->longitudMediaestiloide;
    }

    /**
     * Set longitudTibiamedia
     *
     * @param float $longitudTibiamedia
     *
     * @return Antropometria
     */
    public function setLongitudTibiamedia($longitudTibiamedia)
    {
        $this->longitudTibiamedia = $longitudTibiamedia;

        return $this;
    }

    /**
     * Get longitudTibiamedia
     *
     * @return float
     */
    public function getLongitudTibiamedia()
    {
        return $this->longitudTibiamedia;
    }

    /**
     * Set longitudIlioespinal
     *
     * @param float $longitudIlioespinal
     *
     * @return Antropometria
     */
    public function setLongitudIlioespinal($longitudIlioespinal)
    {
        $this->longitudIlioespinal = $longitudIlioespinal;

        return $this;
    }

    /**
     * Get longitudIlioespinal
     *
     * @return float
     */
    public function getLongitudIlioespinal()
    {
        return $this->longitudIlioespinal;
    }

    /**
     * Set longitudTrocantera
     *
     * @param float $longitudTrocantera
     *
     * @return Antropometria
     */
    public function setLongitudTrocantera($longitudTrocantera)
    {
        $this->longitudTrocantera = $longitudTrocantera;

        return $this;
    }

    /**
     * Get longitudTrocantera
     *
     * @return float
     */
    public function getLongitudTrocantera()
    {
        return $this->longitudTrocantera;
    }

    /**
     * Set grasaMLG
     *
     * @param float $grasaMLG
     *
     * @return Antropometria
     */
    public function setGrasaMLG($grasaMLG)
    {
        $this->grasaMLG = $grasaMLG;

        return $this;
    }

    /**
     * Get grasaMLG
     *
     * @return float
     */
    public function getGrasaMLG()
    {
        return $this->grasaMLG;
    }

    /**
     * Set grasa
     *
     * @param float $grasa
     *
     * @return Antropometria
     */
    public function setGrasa($grasa)
    {
        $this->grasa = $grasa;

        return $this;
    }

    /**
     * Get grasa
     *
     * @return float
     */
    public function getGrasa()
    {
        return $this->grasa;
    }

    /**
     * Set grasaAKS
     *
     * @param float $grasaAKS
     *
     * @return Antropometria
     */
    public function setGrasaAKS($grasaAKS)
    {
        $this->grasaAKS = $grasaAKS;

        return $this;
    }

    /**
     * Get grasaAKS
     *
     * @return float
     */
    public function getGrasaAKS()
    {
        return $this->grasaAKS;
    }

    /**
     * Set grasaSematipo
     *
     * @param float $grasaSematipo
     *
     * @return Antropometria
     */
    public function setGrasaSematipo($grasaSematipo)
    {
        $this->grasaSematipo = $grasaSematipo;

        return $this;
    }

    /**
     * Get grasaSematipo
     *
     * @return float
     */
    public function getGrasaSematipo()
    {
        return $this->grasaSematipo;
    }

    /**
     * Set grasaFinal
     *
     * @param float $grasaFinal
     *
     * @return Antropometria
     */
    public function setGrasaFinal($grasaFinal)
    {
        $this->grasaFinal = $grasaFinal;

        return $this;
    }

    /**
     * Get grasaFinal
     *
     * @return float
     */
    public function getGrasaFinal()
    {
        return $this->grasaFinal;
    }

    /**
     * Set clasificacion
     *
     * @param string $clasificacion
     *
     * @return Antropometria
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set porcentajeGrasa
     *
     * @param float $porcentajeGrasa
     *
     * @return Antropometria
     */
    public function setPorcentajeGrasa($porcentajeGrasa)
    {
        $this->porcentajeGrasa = $porcentajeGrasa;

        return $this;
    }

    /**
     * Get porcentajeGrasa
     *
     * @return float
     */
    public function getPorcentajeGrasa()
    {
        return $this->porcentajeGrasa;
    }

    /**
     * Set pliegueTriceps
     *
     * @param float $pliegueTriceps
     *
     * @return Antropometria
     */
    public function setPliegueTriceps($pliegueTriceps)
    {
        $this->pliegueTriceps = $pliegueTriceps;

        return $this;
    }

    /**
     * Get pliegueTriceps
     *
     * @return float
     */
    public function getPliegueTriceps()
    {
        return $this->pliegueTriceps;
    }

    /**
     * Set pliegueSubescapular
     *
     * @param float $pliegueSubescapular
     *
     * @return Antropometria
     */
    public function setPliegueSubescapular($pliegueSubescapular)
    {
        $this->pliegueSubescapular = $pliegueSubescapular;

        return $this;
    }

    /**
     * Get pliegueSubescapular
     *
     * @return float
     */
    public function getPliegueSubescapular()
    {
        return $this->pliegueSubescapular;
    }

    /**
     * Set pliegueBicepts
     *
     * @param float $pliegueBicepts
     *
     * @return Antropometria
     */
    public function setPliegueBicepts($pliegueBicepts)
    {
        $this->pliegueBicepts = $pliegueBicepts;

        return $this;
    }

    /**
     * Get pliegueBicepts
     *
     * @return float
     */
    public function getPliegueBicepts()
    {
        return $this->pliegueBicepts;
    }

    /**
     * Set plieguePectorales
     *
     * @param float $plieguePectorales
     *
     * @return Antropometria
     */
    public function setPlieguePectorales($plieguePectorales)
    {
        $this->plieguePectorales = $plieguePectorales;

        return $this;
    }

    /**
     * Get plieguePectorales
     *
     * @return float
     */
    public function getPlieguePectorales()
    {
        return $this->plieguePectorales;
    }

    /**
     * Set pliegueAxilar
     *
     * @param float $pliegueAxilar
     *
     * @return Antropometria
     */
    public function setPliegueAxilar($pliegueAxilar)
    {
        $this->pliegueAxilar = $pliegueAxilar;

        return $this;
    }

    /**
     * Get pliegueAxilar
     *
     * @return float
     */
    public function getPliegueAxilar()
    {
        return $this->pliegueAxilar;
    }

    /**
     * Set pliegueSuprailico
     *
     * @param float $pliegueSuprailico
     *
     * @return Antropometria
     */
    public function setPliegueSuprailico($pliegueSuprailico)
    {
        $this->pliegueSuprailico = $pliegueSuprailico;

        return $this;
    }

    /**
     * Get pliegueSuprailico
     *
     * @return float
     */
    public function getPliegueSuprailico()
    {
        return $this->pliegueSuprailico;
    }

    /**
     * Set pliegueSupraespinal
     *
     * @param float $pliegueSupraespinal
     *
     * @return Antropometria
     */
    public function setPliegueSupraespinal($pliegueSupraespinal)
    {
        $this->pliegueSupraespinal = $pliegueSupraespinal;

        return $this;
    }

    /**
     * Get pliegueSupraespinal
     *
     * @return float
     */
    public function getPliegueSupraespinal()
    {
        return $this->pliegueSupraespinal;
    }

    /**
     * Set pliegueAdominal
     *
     * @param float $pliegueAdominal
     *
     * @return Antropometria
     */
    public function setPliegueAdominal($pliegueAdominal)
    {
        $this->pliegueAdominal = $pliegueAdominal;

        return $this;
    }

    /**
     * Get pliegueAdominal
     *
     * @return float
     */
    public function getPliegueAdominal()
    {
        return $this->pliegueAdominal;
    }

    /**
     * Set pliegueMuslo
     *
     * @param float $pliegueMuslo
     *
     * @return Antropometria
     */
    public function setPliegueMuslo($pliegueMuslo)
    {
        $this->pliegueMuslo = $pliegueMuslo;

        return $this;
    }

    /**
     * Get pliegueMuslo
     *
     * @return float
     */
    public function getPliegueMuslo()
    {
        return $this->pliegueMuslo;
    }

    /**
     * Set plieguePierna
     *
     * @param float $plieguePierna
     *
     * @return Antropometria
     */
    public function setPlieguePierna($plieguePierna)
    {
        $this->plieguePierna = $plieguePierna;

        return $this;
    }

    /**
     * Get plieguePierna
     *
     * @return float
     */
    public function getPlieguePierna()
    {
        return $this->plieguePierna;
    }

    /**
     * Set pliegueSumatoria
     *
     * @param float $pliegueSumatoria
     *
     * @return Antropometria
     */
    public function setPliegueSumatoria($pliegueSumatoria)
    {
        $this->pliegueSumatoria = $pliegueSumatoria;

        return $this;
    }

    /**
     * Get pliegueSumatoria
     *
     * @return float
     */
    public function getPliegueSumatoria()
    {
        return $this->pliegueSumatoria;
    }

    /**
     * Set diametroBiacrominal
     *
     * @param float $diametroBiacrominal
     *
     * @return Antropometria
     */
    public function setDiametroBiacrominal($diametroBiacrominal)
    {
        $this->diametroBiacrominal = $diametroBiacrominal;

        return $this;
    }

    /**
     * Get diametroBiacrominal
     *
     * @return float
     */
    public function getDiametroBiacrominal()
    {
        return $this->diametroBiacrominal;
    }

    /**
     * Set diametroBicrestal
     *
     * @param float $diametroBicrestal
     *
     * @return Antropometria
     */
    public function setDiametroBicrestal($diametroBicrestal)
    {
        $this->diametroBicrestal = $diametroBicrestal;

        return $this;
    }

    /**
     * Get diametroBicrestal
     *
     * @return float
     */
    public function getDiametroBicrestal()
    {
        return $this->diametroBicrestal;
    }

    /**
     * Set diametroBitrocante
     *
     * @param float $diametroBitrocante
     *
     * @return Antropometria
     */
    public function setDiametroBitrocante($diametroBitrocante)
    {
        $this->diametroBitrocante = $diametroBitrocante;

        return $this;
    }

    /**
     * Get diametroBitrocante
     *
     * @return float
     */
    public function getDiametroBitrocante()
    {
        return $this->diametroBitrocante;
    }

    /**
     * Set diametroTorax
     *
     * @param float $diametroTorax
     *
     * @return Antropometria
     */
    public function setDiametroTorax($diametroTorax)
    {
        $this->diametroTorax = $diametroTorax;

        return $this;
    }

    /**
     * Get diametroTorax
     *
     * @return float
     */
    public function getDiametroTorax()
    {
        return $this->diametroTorax;
    }

    /**
     * Set diametroAnchoMano
     *
     * @param float $diametroAnchoMano
     *
     * @return Antropometria
     */
    public function setDiametroAnchoMano($diametroAnchoMano)
    {
        $this->diametroAnchoMano = $diametroAnchoMano;

        return $this;
    }

    /**
     * Get diametroAnchoMano
     *
     * @return float
     */
    public function getDiametroAnchoMano()
    {
        return $this->diametroAnchoMano;
    }

    /**
     * Set diametroBiepicondilarHumero
     *
     * @param float $diametroBiepicondilarHumero
     *
     * @return Antropometria
     */
    public function setDiametroBiepicondilarHumero($diametroBiepicondilarHumero)
    {
        $this->diametroBiepicondilarHumero = $diametroBiepicondilarHumero;

        return $this;
    }

    /**
     * Get diametroBiepicondilarHumero
     *
     * @return float
     */
    public function getDiametroBiepicondilarHumero()
    {
        return $this->diametroBiepicondilarHumero;
    }

    /**
     * Set diametroBiepicondilarFemur
     *
     * @param float $diametroBiepicondilarFemur
     *
     * @return Antropometria
     */
    public function setDiametroBiepicondilarFemur($diametroBiepicondilarFemur)
    {
        $this->diametroBiepicondilarFemur = $diametroBiepicondilarFemur;

        return $this;
    }

    /**
     * Get diametroBiepicondilarFemur
     *
     * @return float
     */
    public function getDiametroBiepicondilarFemur()
    {
        return $this->diametroBiepicondilarFemur;
    }

    /**
     * Set diametroAnchoPie
     *
     * @param float $diametroAnchoPie
     *
     * @return Antropometria
     */
    public function setDiametroAnchoPie($diametroAnchoPie)
    {
        $this->diametroAnchoPie = $diametroAnchoPie;

        return $this;
    }

    /**
     * Get diametroAnchoPie
     *
     * @return float
     */
    public function getDiametroAnchoPie()
    {
        return $this->diametroAnchoPie;
    }

    /**
     * Set diametroLargoPie
     *
     * @param float $diametroLargoPie
     *
     * @return Antropometria
     */
    public function setDiametroLargoPie($diametroLargoPie)
    {
        $this->diametroLargoPie = $diametroLargoPie;

        return $this;
    }

    /**
     * Get diametroLargoPie
     *
     * @return float
     */
    public function getDiametroLargoPie()
    {
        return $this->diametroLargoPie;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Antropometria
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Antropometria
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return Antropometria
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
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     *
     * @return Antropometria
     */
    public function addDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
    {
        $this->diagnosticos[] = $diagnostico;

        return $this;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico)
    {
        $this->diagnosticos->removeElement($diagnostico);
    }

    /**
     * Get diagnosticos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnosticos()
    {
        return $this->diagnosticos;
    }
}
