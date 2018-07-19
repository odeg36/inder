<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DireccionType extends AbstractType {

    private $object;

    public function __construct($object = null) {
        $this->object = $object;
    }

    protected static $choices_tipo_via = [
        'seleccionar.ninguna' => '',
        'Autopista' => 'Autopista',
        'Avenida' => 'Avenida',
        'Calle' => 'Calle',
        'Carrera' => 'Carrera',
        'Diagonal' => 'Diagonal',
        'Transversal' => 'Transversal'
    ];
    protected static $choices_cuadrante = [
        'seleccionar.ninguna' => '',
        'Norte' => 'Norte',
        'Oeste' => 'Oeste',
        'Oriente' => 'Oriente',
        'Sur' => 'Sur'
    ];
    protected static $choices_prefijo = [
        'seleccionar.ninguna' => '',
        'BIS' => 'BIS'
    ];
    protected static $choices_sufijo = [
        'seleccionar.ninguna' => '',
        'BIS' => 'BIS'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $object = $options['object']['data'];
        $required = key_exists("required", $options) ? $options["required"] : false;

        $formPadre = $options['formularioPadre'];
        $letras = [
            'seleccionar.ninguna' => '',
        ];
        for ($i = "A"; $i != "AA"; $i++) {
            $letras[$i] = $i;
        }

        $noBlank = array();

        if ($required) {
            $noBlank = array(
                new NotBlank(['message' => '<span><i class="fa fa-question-circle-o" data-toggle="tooltip" title="Campo requerido"></i></span>']),
            );
        }

        if ($object && $object->getId()) {
            $noBlank = array();
        }

        $builder->add('tipo_via', ChoiceType::class, array(
            'choices' => self::$choices_tipo_via,
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion',
            ),
            'label' => false,
            "constraints" => $noBlank,
            'placeholder' => '',
        ));
        $builder->add('numero_via', TextType::class, array(
            "required" => false,
            'attr' => array(
                'oninput' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion'
            ),
            'label' => false,
            "constraints" => $noBlank
        ));
        $builder->add('letra_prefijo', ChoiceType::class, array(
            'choices' => $letras,
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion'
            ),
            'label' => false,
            'placeholder' => '',
        ));
        $builder->add('prefijo', ChoiceType::class, array(
            'choices' => self::$choices_prefijo,
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion'
            ),
            'label' => false,
            'placeholder' => '',
        ));
        $builder->add('cuadrante', ChoiceType::class, array(
            'choices' => self::$choices_cuadrante,
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion'
            ),
            'label' => false,
            'placeholder' => '',
        ));
        $builder->add('numero_generador', TextType::class, array(
            "required" => false,
            'attr' => array(
                'style' => 'width:100%;float:right;',
                'oninput' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion numero_generador'
            ),
            'label' => false,
            "constraints" => $noBlank
        ));
        $builder->add('letra_sufijo', ChoiceType::class, array(
            'choices' => $letras,
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion',
                'placeholder' => '',
            ),
            'label' => false,
            'placeholder' => '',
        ));
        $builder->add('sufijo', ChoiceType::class, array(
            "required" => false,
            'choices' => self::$choices_sufijo,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion'
            ),
            'label' => false,
            'placeholder' => '',
        ));
        $builder->add('numero_placa', TextType::class, array(
            "required" => false,
            'attr' => array(
                'oninput' => 'inder.formulario.actualizarDireccion("' . $formPadre . '");',
                'class' => 'form-control dato_direccion numero_placa'
            ),
            'label' => false,
            "constraints" => $noBlank
        ));
    }

    public function getParent() {
        return FormType::class;
    }

    public function getName() {
        return 'direccion';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'object' => null,
            'formularioPadre' => null,
            'required' => null
        ));
    }

}
