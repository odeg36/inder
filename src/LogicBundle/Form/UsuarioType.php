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


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Application\Sonata\UserBundle\Entity\User;
use LogicBundle\Entity\TipoIdentificacion;
use LogicBundle\Entity\EventoRol;
use Doctrine\ORM\EntityRepository;


class UsuarioType extends AbstractType {

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
                'placeholder' => 'Seleccione una opción',
                //'mapped' => false,
                'attr' => array(
                    'class' => 'form-control',
                    //'onchange' => 'inder.reserva.asignarNoDocumentoReservaPaso1(this)',
                    'placeholder' => 'formulario_reserva.labels.paso_tres.tipo_documento',
                )
            ))
            ->add('numeroIdentificacion', TextType::class, array(                    
                //'mapped' => false,
                'attr' => array(
                    'class' => 'form-control',                        
                    'placeholder' => 'formulario_reserva.labels.paso_tres.no_documento',
                    //'onchange' => 'inder.reserva.changeNoDocumentoReservaPaso1(this)',
                    
                ),
                //'data' => $object 
            ))
            ->add('firstname', TextType::class,array(                
                'attr' => array('class' => 'nombreUsuario form-control'),
            ));
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuario_reserva_type';
    }

}
