<?php

namespace AdminBundle\Form;

use LogicBundle\Entity\InformacionExtraUsuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformacionExtraUsuarioType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $oferta = $options['oferta'];
        $usuario = $options['usuario'];
        $container = $options['container'];
        $estrategiaCampos = $oferta->getEstrategia()->getEstrategiaCampos();
        $tiposCampos = $container->getParameter('tipos_campos');

        foreach ($estrategiaCampos as $estrategiaCampo) {
            if (!$estrategiaCampo->getUsar()) {
                continue;
            }
            $nombreMapeado = $estrategiaCampo->getCampoUsuario()->getNombreMapeado();
            $label = $estrategiaCampo->getCampoUsuario()->getNombre();
            $opciones = [
                "label" => $label,
                "required" => false,
                "constraints" => [],
                'attr' => array('class' => 'form-control', 'autocomplete' => 'off'),
            ];

            if ($estrategiaCampo->getRequerido()) {
                $opciones["required"] = true;
                $opciones["constraints"] = $noVacio;
            }

            if ($estrategiaCampo->getCampoUsuario()->getTipo() == $tiposCampos['entidad']) {
                $opciones['placeholder'] = "";
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

            if ($nombreMapeado == "consumeMedicamentos") {
                $opciones['attr'] = [
                    'class' => 'checkbox-medicamentos'
                ];
            }

            if ($nombreMapeado == "medicamentos") {
                $opciones['attr'] = [];
                if (!$usuario || ($usuario && !$usuario->getInformacionExtraUsuario()) || ($usuario->getInformacionExtraUsuario() && !$usuario->getInformacionExtraUsuario()->getConsumeMedicamentos())) {
                    $opciones['attr']['disabled'] = 'disabled';
                }
                $opciones['attr']['class'] = 'form-control area-medicamentos';
            }

            if ($nombreMapeado == "padeceEnfermedadesCronicas") {
                $opciones['attr'] = [
                    'class' => 'checkbox-enfermedades'
                ];
            }
            if ($nombreMapeado == "enfermedades") {
                $opciones['attr'] = [];
                if (!$usuario || ($usuario && !$usuario->getInformacionExtraUsuario()) || ($usuario->getInformacionExtraUsuario() && !$usuario->getInformacionExtraUsuario()->getPadeceEnfermedadesCronicas())) {
                    $opciones['attr']['disabled'] = 'disabled';
                }
                $opciones['attr']['class'] = 'form-control area-enfermedades';
            }
            $builder
                    ->add($nombreMapeado, null, $opciones)
            ;
            $builder
                    ->add($nombreMapeado, null, $opciones)
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => InformacionExtraUsuario::class,
            'oferta' => null,
            'usuario' => null,
            'em' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'informacion_extra';
    }

}
