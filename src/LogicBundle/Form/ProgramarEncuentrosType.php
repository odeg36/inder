<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use LogicBundle\Entity\PuntoAtencion;
use LogicBundle\Entity\Division;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use IT\InputMaskBundle\Form\Type\DateMaskType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProgramarEncuentrosType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
                    )),
        );


        $encuentroConsultado = $options['encuentro'];

        if (count($encuentroConsultado->getEscenarioDeportivo()) > 0) {
            $estatocheckEscenarioPuntoAtencion = "true";
        } else if (count($encuentroConsultado->getPuntoAtencion()) > 0) {
            $estatocheckEscenarioPuntoAtencion = "false";
        } else {
            $estatocheckEscenarioPuntoAtencion = null;
        }


        $builder
                ->add('sle', ChoiceType::class, [
                    'data' => $estatocheckEscenarioPuntoAtencion,
                    'choices' => array(
                        'formulario.oferta.escenario_seleccion.seleccion' => 'true',
                        'formulario.oferta.otro.seleccion' => 'false',
                    ),
                    'choice_attr' => function($val, $key, $index) {
                        return ['class' => 'seleccion-lugar-oferta'];
                    },
                    'label' => 'Seleccione',
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false,
                    "constraints" => $noVacio
                ])
                ->add('escenarioDeportivo', EntityType::class, array(
                    'class' => 'LogicBundle:EscenarioDeportivo',
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'col-md-12 seleccion-escenario ocultar',
                        'onchange' => 'inder.evento.divisiones(this)',
                    ],
                    'required' => false,
                ))
                ->add('division', EntityType::class, array(
                    "class" => "LogicBundle:Division",
                    'placeholder' => '',
                    'attr' => array(
                        'class' => 'col-md-12 seleccion-escenario ocultar',
                    ),
                    'required' => false,
                ))
                ->add('puntoAtencion', EntityType::class, array(
                    'class' => 'LogicBundle:PuntoAtencion',
                    'label_attr' => array('class' => 'label_puntoAtencion'),
                    'placeholder' => '',
                    'attr' => array(
                        'class' => 'mostrarDirCreada seleccion-puntoAtencion',
                    ),
                    'required' => false,
                ))
                //-------fin punto o escenario ------ //
                ->add('fecha', DateMaskType::class, array(
                    "label" => "formulario_escalera.fecha",
                    "required" => true,
                    'mask-alias' => 'dd/mm/yyyy',
                    'placeholder' => 'dd/mm/yyyy',
                    'attr' => array('class' => 'form-control col-lg-6 col-md-6 '),
                    "constraints" => $noVacio
                ))
                ->add('hora', TextType::class, array(
                    'label' => 'formulario_escalera.hora',
                    "required" => true,
                    'attr' => array('class' => 'hora_programacion form-control'),
                    "constraints" => $noVacio,
                    'data' => $encuentroConsultado->getHora(),
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_campo.labels.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde'),
                    'disabled' => $options['validarEdicion'],
        ));

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\EncuentroSistemaCuatro',
            'disciplina' => null,
            'tipoDeSistemaDeJuego' => null,
            'validarEdicion' => null,
            'eventoId' => null,
            'encuentro' => null,
        ));
    }

}
