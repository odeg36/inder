<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CarneEvento
 *
 * @ORM\Table(name="carne_evento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CarneEventoRepository")
 */
class CarneEvento {

    public function __toString() {
        return $this->getEvento()->__toString();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarNombre", type="boolean")
     */
    private $mostrarNombre;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarEquipo", type="boolean")
     */
    private $mostrarEquipo;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarEvento", type="boolean")
     */
    private $mostrarEvento;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarColegio", type="boolean")
     */
    private $mostrarColegio;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarComuna", type="boolean")
     */
    private $mostrarComuna;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarFechaNacimiento", type="boolean")
     */
    private $mostrarFechaNacimiento;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarDeporte", type="boolean")
     */
    private $mostrarDeporte;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarRama", type="boolean")
     */
    private $mostrarRama;

    /**
     * @var bool
     *
     * @ORM\Column(name="mostrarRol", type="boolean")
     */
    private $mostrarRol;

    /**
     * @ORM\OneToOne(targetEntity="Evento", inversedBy="carne")
     * @ORM\JoinColumn(name="evento_id",referencedColumnName="id")
     */
    private $evento;

    /**
     *  @Assert\Image(
     *     maxSize = "2M",
     *     minWidth = 349,
     *     maxWidth = 349,
     *     minHeight = 100,
     *     maxHeight = 100,
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "El tamaño máximo permitido apra el archivo es 2MB.",
     *     mimeTypesMessage = "Solo se permite la carga de imagenes.",
     *     minWidthMessage = "La imágen debe ser de exactamente 349 x 100 px.",
     *     maxWidthMessage = "La imágen debe ser de exactamente 349 x 100 px.",
     *     maxHeightMessage = "La imágen debe ser de exactamente 349 x 100 px.",
     *     minHeightMessage = "La imágen debe ser de exactamente 349 x 100 px."
     * )
     *     
     * 
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set mostrarNombre.
     *
     * @param bool $mostrarNombre
     *
     * @return CarneEvento
     */
    public function setMostrarNombre($mostrarNombre) {
        $this->mostrarNombre = $mostrarNombre;

        return $this;
    }

    /**
     * Get mostrarNombre.
     *
     * @return bool
     */
    public function getMostrarNombre() {
        return $this->mostrarNombre;
    }

    /**
     * Set mostrarEquipo.
     *
     * @param bool $mostrarEquipo
     *
     * @return CarneEvento
     */
    public function setMostrarEquipo($mostrarEquipo) {
        $this->mostrarEquipo = $mostrarEquipo;

        return $this;
    }

    /**
     * Get mostrarEquipo.
     *
     * @return bool
     */
    public function getMostrarEquipo() {
        return $this->mostrarEquipo;
    }

    /**
     * Set mostrarEvento.
     *
     * @param bool $mostrarEvento
     *
     * @return CarneEvento
     */
    public function setMostrarEvento($mostrarEvento) {
        $this->mostrarEvento = $mostrarEvento;

        return $this;
    }

    /**
     * Get mostrarEvento.
     *
     * @return bool
     */
    public function getMostrarEvento() {
        return $this->mostrarEvento;
    }

    /**
     * Set mostrarColegio.
     *
     * @param bool $mostrarColegio
     *
     * @return CarneEvento
     */
    public function setMostrarColegio($mostrarColegio) {
        $this->mostrarColegio = $mostrarColegio;

        return $this;
    }

    /**
     * Get mostrarColegio.
     *
     * @return bool
     */
    public function getMostrarColegio() {
        return $this->mostrarColegio;
    }

    /**
     * Set mostrarComuna.
     *
     * @param bool $mostrarComuna
     *
     * @return CarneEvento
     */
    public function setMostrarComuna($mostrarComuna) {
        $this->mostrarComuna = $mostrarComuna;

        return $this;
    }

    /**
     * Get mostrarComuna.
     *
     * @return bool
     */
    public function getMostrarComuna() {
        return $this->mostrarComuna;
    }

    /**
     * Set mostrarFechaNacimiento.
     *
     * @param bool $mostrarFechaNacimiento
     *
     * @return CarneEvento
     */
    public function setMostrarFechaNacimiento($mostrarFechaNacimiento) {
        $this->mostrarFechaNacimiento = $mostrarFechaNacimiento;

        return $this;
    }

    /**
     * Get mostrarFechaNacimiento.
     *
     * @return bool
     */
    public function getMostrarFechaNacimiento() {
        return $this->mostrarFechaNacimiento;
    }

    /**
     * Set mostrarDeporte.
     *
     * @param bool $mostrarDeporte
     *
     * @return CarneEvento
     */
    public function setMostrarDeporte($mostrarDeporte) {
        $this->mostrarDeporte = $mostrarDeporte;

        return $this;
    }

    /**
     * Get mostrarDeporte.
     *
     * @return bool
     */
    public function getMostrarDeporte() {
        return $this->mostrarDeporte;
    }

    /**
     * Set mostrarRama.
     *
     * @param bool $mostrarRama
     *
     * @return CarneEvento
     */
    public function setMostrarRama($mostrarRama) {
        $this->mostrarRama = $mostrarRama;

        return $this;
    }

    /**
     * Get mostrarRama.
     *
     * @return bool
     */
    public function getMostrarRama() {
        return $this->mostrarRama;
    }

    /**
     * Set mostrarRol.
     *
     * @param bool $mostrarRol
     *
     * @return CarneEvento
     */
    public function setMostrarRol($mostrarRol) {
        $this->mostrarRol = $mostrarRol;

        return $this;
    }

    /**
     * Get mostrarRol.
     *
     * @return bool
     */
    public function getMostrarRol() {
        return $this->mostrarRol;
    }

    /**
     * Set evento.
     *
     * @param \LogicBundle\Entity\Evento|null $evento
     *
     * @return CarneEvento
     */
    public function setEvento(\LogicBundle\Entity\Evento $evento = null) {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento.
     *
     * @return \LogicBundle\Entity\Evento|null
     */
    public function getEvento() {
        return $this->evento;
    }

    /**
     * Set file.
     *
     * @param string $file
     *
     * @return CarneEvento
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
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
