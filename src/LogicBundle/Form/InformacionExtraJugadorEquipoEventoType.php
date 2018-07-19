<?php

namespace LogicBundle\Form;

use AdminBundle\Form\EventListener\AddSubCategoriaFieldSubscriber;
use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\InformacionExtraUsuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformacionExtraJugadorEquipoEventoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $evento = $options['evento'];
        $usuario = $options['usuario'];
        $em = $options['em'];
        $eventoCampos = $evento->getCampoFormularioEventos();
        $tiposCampos = $em->getParameter('tipos_campos');

        foreach ($eventoCampos as $eventoCampo) {
            $nombreMapeado = $eventoCampo->getCampoEvento()->getNombreMapeado();
            $label = $eventoCampo->getCampoEvento()->getNombre();
            $opciones = [
                "label" => $label,
                "required" => false,
                "constraints" => [],
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
            ];

            if ($eventoCampo->getCampoEvento()->getTipo() == $tiposCampos['entidad']) {
                $opciones['placeholder'] = "";
            }
            if ($eventoCampo->getCampoEvento()->getTipo() != $tiposCampos['boolean']) {
                $opciones['required'] = true;
                $opciones['constraints'] = $noVacio;
            }
            if ($nombreMapeado == "licenciaCiclismo") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control licenciaCiclismo';
            }
            if ($nombreMapeado == "perteneceLGBTI") {
                $opciones['attr'] = [
                    'class' => 'checkbox-perteneceLGBTI'
                ];
            }
            if ($nombreMapeado == "estatura") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control estatura';
                $opciones['attr']['oninput'] = 'inder.preinscripcion.calcularIMC();';
            }
            if ($nombreMapeado == "peso") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control peso';
                $opciones['attr']['oninput'] = 'inder.preinscripcion.calcularIMC();';
            }
            if ($nombreMapeado == "indiceMasaCorporal") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control imc';
            }
            if ($nombreMapeado == "consumeBebidasAlcoholicas") {
                $opciones['attr'] = [
                    'class' => 'checkbox-consumeBebidasAlcoholicas'
                ];
            }
            if ($nombreMapeado == "desplazado") {
                $opciones['attr'] = [
                    'class' => 'checkbox-desplazado'
                ];
            }
            if ($nombreMapeado == "tipoDesplazado") {
                $opciones['class'] = 'LogicBundle:TipoDesplazado';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "barrio") {
                $opciones['class'] = 'LogicBundle:Barrio';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "padeceEnfermedadesCronicas") {
                $opciones['attr'] = [
                    'class' => 'checkbox-enfermedades'
                ];
            }
            if ($nombreMapeado == "consumeMedicamentos") {
                $opciones['attr'] = [
                    'class' => 'checkbox-medicamentos'
                ];
            }
            if ($nombreMapeado == "tipoSangre") {
                $opciones['class'] = 'LogicBundle:TipoSangre';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "medioTransporte") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control medioTransporte';
            }
            if ($nombreMapeado == "puntoRecoleccion") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control puntoRecoleccion';
            }
            if ($nombreMapeado == "establecimientoEducativo") {
                $opciones['class'] = 'LogicBundle:EstablecimientoEducativo';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "nivelEscolaridad") {
                $opciones['class'] = 'LogicBundle:NivelEscolaridad';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "estrato") {
                $opciones['class'] = 'LogicBundle:Estrato';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "nombreClubEquipo") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control nombreClubEquipo';
            }
            if ($nombreMapeado == "nombreCarro") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control nombreCarro';
            }
            if ($nombreMapeado == "rama") {
                $opciones = [];
                $opciones['class'] = 'LogicBundle:Rama';
                $opciones['empty_data'] = null;
                $opciones['placeholder'] = 'Seleccione una opción';
                $opciones['query_builder'] = function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('c')
                            ->orderBy("c.nombre", 'DESC');
                    return $qb;
                };
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
                $opciones['attr']['class'] = 'form-control rama';
            }
            if ($nombreMapeado == "rol") {
                $opciones = [];
                $opciones['placeholder'] = "Seleccione una opción";
                $opciones['choices'] = [
                    'formulario_evento.labels.configuracion.roles.rol1' => 'formulario_evento.labels.configuracion.roles.rol1',
                    'formulario_evento.labels.configuracion.roles.rol6' => 'formulario_evento.labels.configuracion.roles.rol6',
                    'formulario_evento.labels.configuracion.roles.rol2' => 'formulario_evento.labels.configuracion.roles.rol2',
                    'formulario_evento.labels.configuracion.roles.rol5' => 'formulario_evento.labels.configuracion.roles.rol5',
                    'formulario_evento.labels.configuracion.roles.rol3' => 'formulario_evento.labels.configuracion.roles.rol3',
                    'formulario_evento.labels.configuracion.roles.rol4' => 'formulario_evento.labels.configuracion.roles.rol4',
                ];
                $opciones['choice_attr'] = function($val, $key, $index) {
                    return ['class' => 'eventoRol' . strtolower($key)];
                };
                $opciones['mapped'] = false;
                $opciones['choices_as_values'] = true;
                $opciones['attr']['class'] = 'form-control rol';
            }
            if ($nombreMapeado == "categoria") {
                $categoriasEvento = [];
                foreach ($evento->getCategoriaSubcategorias() as $categoria) {
                    $categoriasEvento[] = $categoria->getCategoria();
                }
                $opciones['class'] = 'LogicBundle:CategoriaEvento';
                $opciones['empty_data'] = null;
                $opciones['placeholder'] = 'Seleccione una opción';
                $opciones['query_builder'] = function(EntityRepository $er) use ($categoriasEvento) {
                    $qb = $er->createQueryBuilder('c')
                            ->orderBy("c.nombre", 'DESC')
                            ->where('c.id IN (:categorias)')
                            ->setParameter('categorias', $categoriasEvento);
                    return $qb;
                };
                $opciones['attr'] = [
                    'class' => 'form-control',
                    'onchange' => "inder.evento.subcategoriasIncripcion(this)"
                ];
            }
            if ($nombreMapeado == "subcategoria") {
                $opciones['class'] = 'LogicBundle:SubCategoriaEvento';
                $opciones['placeholder'] = 'Seleccione una opción';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control subcategoriasIncripcion'
                ];
            }
            if ($nombreMapeado == "ocupacion") {
                $opciones['class'] = 'LogicBundle:Ocupacion';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "jefeCabezaHogar") {
                $opciones['attr'] = [
                    'class' => 'checkbox-jefeCabezaHogar'
                ];
            }
            if ($nombreMapeado == "telefonoContacto") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control telefonoContacto';
            }
            if ($nombreMapeado == "gradoCursa") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control gradoCursa';
            }
            if ($nombreMapeado == "tallaPantalon") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control tallaPantalon';
            }
            if ($nombreMapeado == "tallaCamisa") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control tallaCamisa';
            }
            if ($nombreMapeado == "tallaZapatos") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control tallaZapatos';
            }
            if ($nombreMapeado == "numeroMatricula") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control numeroMatricula';
            }
            if ($nombreMapeado == "municipio") {
                $opciones['class'] = 'LogicBundle:Municipio';
                $opciones['empty_data'] = null;
                $opciones['attr'] = [
                    'class' => 'form-control'
                ];
            }
            if ($nombreMapeado == "direccion") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control direccion';
            }
            if ($nombreMapeado == "sexo") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control sexo';
            }
            if ($nombreMapeado == "discapacitado") {
                $opciones['attr'] = [
                    'class' => 'checkbox-discapacitado'
                ];
            }
            if ($nombreMapeado == "tipoDiscapacidad") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control tipoDiscapacidad';
            }
            if ($nombreMapeado == "subDiscapacidad") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control subDiscapacidad';
            }
            if ($nombreMapeado == "adjuntarDocumentos") {
                $opciones = [];
                $opciones['required'] = false;
                $opciones["data_class"] = null;
                $opciones['attr']['class'] = 'file adjuntarDocumentos';
            }
            if ($nombreMapeado == "correoElectronico") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control correoElectronico';
            }
            if ($nombreMapeado == "fechaNacimiento") {
                $opciones['attr'] = [];
                $opciones['attr']['class'] = 'form-control fechaNacimiento';
            }
            if ($nombreMapeado == "fuma") {
                $opciones['attr'] = [
                    'class' => 'checkbox-fuma'
                ];
            }
            if ($eventoCampo->getCampoEvento()->getTipo() == $tiposCampos['entidad'] || $nombreMapeado == "rama") {
                $builder->add($nombreMapeado, EntityType::class, $opciones);
                if ($nombreMapeado == "subcategoria") {
                    $builder->addEventSubscriber(new AddSubCategoriaFieldSubscriber(false, false));
                }
            } else if ($nombreMapeado == "rol") {
                $builder->add($nombreMapeado, ChoiceType::class, $opciones);
            } else if ($nombreMapeado == "adjuntarDocumentos") {
                $builder->add($nombreMapeado, FileType::class, $opciones);
            } else {
                $builder->add($nombreMapeado, null, $opciones);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => InformacionExtraUsuario::class,
            'evento' => null,
            'usuario' => null,
            'em' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'informacion_extra';
    }

}
