<?php

namespace LogicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LogicBundle\Form\DireccionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;

use LogicBundle\Form\CategoriaEventoType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class JugadorEventoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_escenario_deportivo.no_vacio',
            )),
        );
        $jugadorEventoObject = $builder->getData();
        $opcion = [
            'data_class' => null,
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        "image/jpeg", "image/jpg", "image/png"
                    ]])
            ],
            'attr' => [
                "class" => "file",
                "data-show-upload" => "false",
                "data-show-caption" => "true",
            ]
            ];

            if ($jugadorEventoObject->getId()) {
                $noVacioClave = [];
                $imagen = $jugadorEventoObject->getEpsImagen();
                $fileFieldOptions = array_merge($fileFieldOptions, [
                    'required' => true,
                ]);
                if ($imagen) {
                    // get the container so the full path to the image can be set
                    $container = $this->getConfigurationPool()->getContainer();
                    $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagen;
    
                    // add a 'help' option containing the preview's img tag
                    $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
                }
            }

            if ($jugadorEventoObject->getId()) {
                $noVacioClave = [];
                $imagen = $jugadorEventoObject->getDocumentoImagen();
                $fileFieldOptions = array_merge($fileFieldOptions, [
                    'required' => true,
                ]);
                if ($imagen) {
                    // get the container so the full path to the image can be set
                    $container = $this->getConfigurationPool()->getContainer();
                    $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/uploads/' . $imagen;
    
                    // add a 'help' option containing the preview's img tag
                    $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="fotoPerfil" />';
                }
            }

            $opcion = array_merge($opcion, [
                'required' => true,
                'label' => 'Selecciona una foto del carnet de EPS para subir',
            ]);

            $eventoObject = $builder->getData();

            $noVacio = array(
                new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                ))
            );

        $builder
                ->add('observacion',null,array(
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ))
                ->add('epsImagen', FileType::class, $opcion);
        
        $opcion = array_merge($opcion, [
            'required' => true,
            'label' => 'Selecciona una foto del documento de identificación para subir',
        ]);
        $builder
                ->add('documentoImagen', FileType::class, $opcion)          
               
                //->addEventSubscriber(new AddBarrioFieldSubscriber())
                ->add('tipoIdentificacion', EntityType::class, array(
                    'class' => 'LogicBundle:TipoIdentificacion',
                    "required" => false,
                    'mapped' => false,
                    "constraints" => $noVacio,
                    'empty_data' => null,
                    'multiple' => false,                
                    'attr' => array('class' => 'form-control tipoDocumento')                
                ))
                ->add('numeroIdentificacion',null,array(
                    'attr' => array('class' => 'form-control numeroDocumento','placeholder' => 'No. Documento'),
                    'mapped' => false,
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'formulario_encuesta.labels.guardarcontinuar',
                    'attr' => array('class' => 'btn btnVerde'))
                );
                
            
                
        
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\JugadorEvento',
            'idevento' => null
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'encuesta_jugador_evento_type';
    }
  

}
