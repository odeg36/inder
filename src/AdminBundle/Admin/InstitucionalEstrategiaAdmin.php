<?php

namespace AdminBundle\Admin;

use LogicBundle\Form\BorrarCampoType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstitucionalEstrategiaAdmin extends AbstractAdmin {

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
        ;
    }

    public function getExportFormats() {
        return ['csv', 'xls'];
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );
        $formMapper
                ->add('borrar', BorrarCampoType::class, array(
                    'mapped' => false,
                ))
                ->add('categoriaInstitucional', null, [
                    'constraints' => $noVacio,
                    'attr' => [
                        'onchange' => 'inder.oferta.validarCategoriasRepetidas(this)',
                    ]
                ])
                ->add('coberturaMinima', IntegerType::class, array(
                    'constraints' => array(
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                )),
                        new Assert\Range(array(
                            'min' => 0,
                            'max' => 2147483647,
                            'maxMessage' => 'error.estrategia.valor_max',
                                ))
                    )
                ))
                ->add('coberturaMaxima', IntegerType::class, array(
                    'constraints' => array(
                        new NotBlank(array(
                            'message' => 'formulario_registro.no_vacio',
                                )),
                        new Assert\Range(array(
                            'min' => 0,
                            'max' => 2147483647,
                            'maxMessage' => 'error.estrategia.valor_max',
                                ))
                    )
                ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
        ;
    }

}
