<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Doctrine\Common\Collections\ArrayCollection;

use LogicBundle\Entity\EventoRol;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Entity\TipoIdentificacion;

use Doctrine\ORM\EntityRepository;


class UsuarioEventoType extends AbstractType {

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
        
        $builder            
            ->add('tipoIdentificacion', EntityType::class, array(
                'class' => 'LogicBundle:TipoIdentificacion',
                "required" => false,
                "constraints" => $noVacio,
                'empty_data' => null,
                'multiple' => false,                
                'attr' => array(
                    'class' => 'form-control tipoDocumento',
                    'placeholder' => 'formulario_reserva.labels.paso_tres.tipo_documento',
                )                
            ))
            //quitar Rol para usuario Evento
            ->add('eventoRoles', ChoiceType::class, array(
                'choices' => array(
                    'formulario_evento.labels.configuracion.roles.rol1' => 'formulario_evento.labels.configuracion.roles.rol1',
                    'formulario_evento.labels.configuracion.roles.rol2' => 'formulario_evento.labels.configuracion.roles.rol2',
                    'formulario_evento.labels.configuracion.roles.rol3' => 'formulario_evento.labels.configuracion.roles.rol3',
                    'formulario_evento.labels.configuracion.roles.rol4' => 'formulario_evento.labels.configuracion.roles.rol4',
                    'formulario_evento.labels.configuracion.roles.rol5' => 'formulario_evento.labels.configuracion.roles.rol5',
                    'formulario_evento.labels.configuracion.roles.rol6' => 'formulario_evento.labels.configuracion.roles.rol6',                       
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'eventoRol'.strtolower($key)];
                },
                'mapped' => false,
                'choices_as_values' => true,
                'attr' => array('class' => 'form-control eventoRol')
            )) 
            ->add('numeroIdentificacion', TextType::class, array(                    
                'attr' => array(
                    'class' => 'form-control',                        
                    'placeholder' => 'formulario_reserva.labels.paso_tres.no_documento',
                    
                ),
            ))
            ->add('firstname', TextType::class,array(    
                'disabled' => true,            
                'attr' => array('class' => 'nombreUsuario form-control'),
            ));
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
            'eventoroles' => null
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuario_type';
    }

}
