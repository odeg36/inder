<?php

namespace LogicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class CargarMultiplesUsuariosType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('archivo_usuarios', FileType::class, [
                    'data_class' => null,
                    'label' => 'formulario_registro.carga_archivo.excel_usuarios',
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => array(
                                'application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel',
                                'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel',
                                'application/xls', 'application/x-xls', 'text/csv', 
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ),
                            'mimeTypesMessage' => 'formulario_registro.carga_archivo.archivo_valido',
                                ])
                    ],
                    'attr' => [
                        "class" => "file",
                        "data-show-upload" => "false",
                        "data-show-caption" => "true",
                        "data-msg-placeholder" => "Seleccione un archivo excel para subir"
                    ],
                ])
        ;
    }

}
