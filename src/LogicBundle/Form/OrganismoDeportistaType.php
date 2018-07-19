<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\TipoIdentificacion;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganismoDeportistaType extends AbstractType {

    protected $deportista;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $organizacionDeportiva = $options['organizacionDeportiva'];

        $builder
                ->add('tipoidentificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    'placeholder' => '',
                    'empty_data' => false,
                    'mapped' => false,
                    'query_builder' => function(EntityRepository $repository) {
                        return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                    },
                    "constraints" => $noVacio,
                    'label' => 'formulario_registro.pnatural_informacion.tipo_documento',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'form-control', 'autocomplete' => 'off'
                    ]
                ))
                ->add('usuariodeportista', AutocompleteType::class, array(
                    'class' => 'Application\Sonata\UserBundle\Entity\User',
                    "label" => "formulario_registro.pnatural_informacion.numero_identificacion",
                    "required" => true,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control', 'autocomplete' => 'off',
                        'placeholder' => '',
                    )
                ))
                ->add('organizacionDeportiva', HiddenType::class, array(
                    'data' => $organizacionDeportiva,
                    'mapped' => false
        ));

        $formModifier = function ($form, TipoIdentificacion $tipoIdentificacion) {
            $form->add('tipoidentificacion', EntityType::class, array(
                'class' => 'LogicBundle:TipoIdentificacion',
                'data' => $tipoIdentificacion ?: null,
                'placeholder' => '',
                'empty_data' => false,
                'mapped' => false,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('t')->orderBy('t.nombre', 'ASC');
                },
                'label' => 'formulario_registro.pnatural_informacion.tipo_documento',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control', 'autocomplete' => 'off'
                ]
            ));
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $this->deportista = $event->getData();
                $tipoIdentificacion = $this->deportista->getUsuarioDeportista()->getTipoIdentificacion();

                $formModifier($event->getForm(), $tipoIdentificacion);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\OrganismoDeportista',
            'organizacionDeportiva' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'deportistaType';
    }

}
