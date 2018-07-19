<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use LogicBundle\Form\InformacionExtraJugadorEquipoEventoType;

class ParticipanteEventoType extends AbstractType {

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
                ])
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_plan_anual_metodologico.guardarContinuar',
                    'attr' => array(
                        'class' => 'btn btnVerde')
        ));
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
