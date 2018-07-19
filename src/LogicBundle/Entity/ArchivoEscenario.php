<?php

namespace LogicBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ArchivoEscenario
 *
 * @ORM\Table(name="archivo_escenario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ArchivoEscenarioRepository")
 */
class ArchivoEscenario
{
    public function __toString() {
        return $this->file ? $this->file : '';
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
     *  @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 2MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     *     
     * 
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;

    

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="archivoEscenarios")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set file
     *
     * @param string $file
     *
     * @return ArchivoEscenario
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ArchivoEscenario
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return ArchivoEscenario
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;
        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
        }

        $tamaño = filesize($file);
        
        if($tamaño == false){
            
           return "tamaño grande";
            
        }else{

        }

        $filename = $file->getClientOriginalName();

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = uniqid(date('YmdHis')) . '.' . $ext;
        $file->move(
                $path, $filename
        );

        return $filename;
    }


}
