<?php

namespace LogicBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ComunaType extends AbstractType {

    protected $container;
    protected $trans;

    public function __construct(ContainerInterface $container = null) {
        $this->container = $container;
        $this->trans = $this->container->get("translator");
    }

    public function getTipoVia() {
        return [
            $this->trans->trans('seleccionar.ninguna') => '',
            $this->trans->trans('formulario.direccion.tipo_via.kilometro') => $this->trans->trans('formulario.direccion.tipo_via.kilometro'),
            $this->trans->trans('formulario.direccion.tipo_via.corregimiento') => $this->trans->trans('formulario.direccion.tipo_via.corregimiento')
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $object = $options['object']['data'];
        $required = key_exists("required", $options) ? $options["required"] : false;

        $formPadre = $options['formularioPadre'];
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
            'choices' => $this->getTipoVia(),
            "required" => false,
            'attr' => array(
                'onchange' => 'inder.formulario.actualizarDireccionComuna("' . $formPadre . '");',
                'class' => 'form-control dato_comuna'
            ),
            'label' => false,
            "constraints" => $noBlank,
            'placeholder' => '',
        ));
        $builder->add('numero_via', TextType::class, array(
            "required" => false,
            'attr' => array(
                'oninput' => 'inder.formulario.actualizarDireccionComuna("' . $formPadre . '");',
                'class' => 'form-control dato_comuna',
                'placeholder' => '',
            ),
            'label' => false,
            "constraints" => $noBlank
        ));
        $builder->add('complemento', TextType::class, array(
            "required" => false,
            'attr' => array(
                'oninput' => 'inder.formulario.actualizarDireccionComuna("' . $formPadre . '");',
                'class' => 'form-control dato_comuna complemento',
                'placeholder' => '',
            ),
            'label' => false,
            "constraints" => $noBlank
        ));
    }

    public function getParent() {
        return FormType::class;
    }

    public function getName() {
        return 'comuna';
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
