<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;


use AdminBundle\Form\EventListener\Reserva\AddEscenarioFieldSubscriber;
use AdminBundle\Form\EventListener\Reserva\AddDisciplinaEscenarioFieldSubscriber;
use AdminBundle\Form\EventListener\Reserva\AddBarrioFieldSubscriber;
use AdminBundle\Form\EventListener\Reserva\AddMunicipioFieldSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use LogicBundle\Form\UsuarioType;


class OfertaType extends AbstractType {

    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            ))
        );
        $object = $builder->getData();
         
        $em =  $options['em'];

        $builder
            ->add('tipoReporte', ChoiceType::class, array(
                'mapped' => false,
                'choices'  => array(
                    'PDF' => 'PDF',
                    'EXCEL' => 'EXCEL',
                ),
            ))
            ->add('fechaInicial', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'mapped' => false,
                'attr' => array('class' => 'form-control'),
            ))    
            ->add('fechaFinal', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'mapped' => false,
                'attr' => array('class' => 'form-control'),
            ));
        

        $builder
            ->add('save', SubmitType::class, array(
                'label' => 'formulario_registro.descargar_informe_oferta',
                'attr' => array('class' => 'btn btnVerde')));
        ;

        $formModifier = function ($form, $data) {

        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            if (null != $event->getData()) {
                $reserva = $event->getData();

                $formModifier($event->getForm(), $reserva);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'em' => null,            
        ));
    }

}
