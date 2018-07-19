<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarneType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $object = $builder->getdata();
        $builder
                ->add('mostrarNombre', null, ["label" => "label.mostrar.nombre"])
                ->add('mostrarEquipo', null, ["label" => "label.mostrar.equipo"])
                ->add('mostrarEvento', null, ["label" => "label.mostrar.evento"])
                ->add('mostrarColegio', null, ["label" => "label.mostrar.colegio"])
                ->add('mostrarComuna', null, ["label" => "label.mostrar.comuna"])
                ->add('mostrarFechaNacimiento', null, ["label" => "label.mostrar.fecha"])
                ->add('mostrarDeporte', null, ["label" => "label.mostrar.deporte"])
                ->add('mostrarRama', null, ["label" => "label.mostrar.rama"])
                ->add('mostrarRol', null, ["label" => "label.mostrar.rol"])
                ->add('file', FileType::class, [
                    'data_class' => null,
                    'label' => 'label.mostrar.cabezote',
                    'mapped' => true,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => array(
                                "image/png",
                                "image/jpeg",
                                "image/jpg",
                                "image/gif"
                            ),
                                ])
                    ],
                    'attr' => [
                        "class" => "file",
                        "data-show-upload" => "false",
                        "data-show-caption" => "true",
                        "data-msg-placeholder" => "Seleccione una imagen para el carné"
                    ],
                ])
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_escenario_deportivo.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\CarneEvento',
            'evento' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'carne_type';
    }

}
