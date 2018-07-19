<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use LogicBundle\Form\InformacionExtraJugadorEquipoEventoType;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class JugadorEquipoEventoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_plan_anual_metodologico.no_vacio',
                    ))
        );
        $builder
                ->add('tipoIdentificacion', EntityType::class, array(
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
                ->add('usuarioJugadorEvento', AutocompleteType::class, array(
                    'label'=> 'Documento del jugador',
                    'class' => 'Application\Sonata\UserBundle\Entity\User',
                    "required" => true,
//                    'mapped' => false,
                    "constraints" => $noVacio,
                    'attr' => array(
                        'class' => 'form-control', 'autocomplete' => 'off',
                        'placeholder' => '',
                    )
                ))
                ->add('observacion', null, array(
                    "required" => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ))
                ->add('informacionExtraUsuario', InformacionExtraJugadorEquipoEventoType::class, [
                    'usuario' => $options['usuario'],
                    'evento' => $options['evento'],
                    'em' => $options['em'],
                    'mapped' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\JugadorEvento',
            'em' => null,
            'usuario' => null,
            'evento' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'jugador_equipo_evento_type';
    }

}
