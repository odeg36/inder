<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventListener\Estrategia\AddAreaEstrategiaFieldSubscriber;
use AdminBundle\Form\EventListener\Estrategia\AddProyectoEstrategiaFieldSubscriber;
use LogicBundle\Entity\EstrategiaCampo;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EstrategiaAdmin extends AbstractAdmin {

    protected $em;

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    public function setConfigurationPool(Pool $configurationPool) {
        parent::setConfigurationPool($configurationPool);

        $this->em = $configurationPool->getContainer()->get("doctrine")->getManager();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('nombre')
                ->add('coberturaGeneralMin')
                ->add('coberturaGeneralMax')
                ->add('activo', null, array('label' => 'area.activo'))
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
                ->add('nombre')
                ->add('coberturaGeneralMin')
                ->add('coberturaGeneralMax')
                ->add('activo')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                        'creatOferta' => array(
                            "template" => "AdminBundle:Estrategia/btn:crear.oferta.html.twig"
                        ),
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $request = $this->getRequest();
        $proyecto = $request->get("proyecto", null);
        $object = $this->getSubject();
        if ($proyecto) {
            $proyectoEntity = $this->em->getRepository("LogicBundle:Proyecto")->findOneById($proyecto);

            if ($proyectoEntity) {
                $object->setProyecto($proyectoEntity);
            }
        }

        $campos = $this->em->getRepository('LogicBundle:CampoUsuario')->findAll();
        foreach ($campos as $campo) {
            $estrategiaCampo = $this->em->getRepository('LogicBundle:EstrategiaCampo')->busacrCampo($object, $campo);
            if (!$estrategiaCampo) {
                $estrategiaCampo = new EstrategiaCampo();
            }

            $estrategiaCampo->setEstrategia($object);
            $estrategiaCampo->setCampoUsuario($campo);
            $object->addEstrategiaCampo($estrategiaCampo);
        }

        $formMapper
                ->add('nombre', null, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/(?!^\d+$)^.+$/'
                                ])
                    ]
                ])
                ->add('area', EntityType::class, array(
                    'class' => 'LogicBundle:Area',
                        )
                )
                ->add('proyecto')
                ->add('disciplinas', CollectionType::class, array(
                    'required' => false,
                    'type_options' => array('delete' => false),
                    'by_reference' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
                ->add('tendencias', CollectionType::class, array(
                    'required' => false,
                    'type_options' => array('delete' => false),
                    'by_reference' => false
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
                ->add('institucionalEstrategias', CollectionType::class, array(
                    'required' => false,
                    'label' => 'formulario.estrategia.institucional',
                    'type_options' => array('delete' => false),
                    'by_reference' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
                ->add('coberturaGeneralMin', IntegerType::class, array(
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
                ->add('coberturaGeneralMax', IntegerType::class, array(
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
                ->add('segmentacion', null, [
                    'label' => 'formulario.estrategia.segmentacion',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'placeholder' => '',
                ])
                ->add('descripcion', null, ['required' => false])
                ->add('corteAsistencia', ChoiceType::class, array(
                    'placeholder' => '',
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'choices' => array(
                        'Mensual' => 'Mensual',
                        'Semanal' => 'Semanal',
                    ),
                ))
                ->add('plazoAdicional', null, [
                    'label' => 'formulario.estrategia.plazo_adicional',
                    'constraints' => array(
                        new Assert\Range(array(
                            'min' => 0
                                ))
                    )
                ])
                ->add('estrategiaCampos', CollectionType::class, array(
                    'type_options' => array('delete' => false),
                    'btn_add' => false,
                    'by_reference' => false,
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
                ->add('acompanantes', null, [
                    'label' => 'formulario.oferta.acompanantes'
                ])
                ->add('diagnostico', null, [
                    'label' => 'formulario.oferta.diagnostico'
                ])
                ->add('activo', null, array('label' => 'area.activo'))
                ->getFormBuilder()
                ->addEventSubscriber(new AddAreaEstrategiaFieldSubscriber())
                ->addEventSubscriber(new AddProyectoEstrategiaFieldSubscriber())
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('nombre')
                ->add('coberturaGeneralMin')
                ->add('coberturaGeneralMax')
                ->add('descripcion')
                ->add('activo', null, array('label' => 'area.activo'))
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:Estrategia:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function validate(ErrorElement $errorElement, $object) {
        $form = $this->getForm();
        foreach ($form->get('disciplinas') as $disciplina) {
            if ($disciplina->get('borrar')->getData() && $disciplina->getData()->getOfertas()->count() > 0) {
                $mensaje = $this->trans(
                        'error.oferta.disciplina_relacion', array('%disciplina%' => $disciplina->getData()->getDisciplina())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }
        }
        foreach ($form->get('tendencias') as $tendencia) {
            if ($tendencia->get('borrar')->getData() && $tendencia->getData()->getOfertas()->count() > 0) {
                $mensaje = $this->trans(
                        'error.oferta.tendencia_relacion', array('%tendencia%' => $tendencia->getData()->getTendencias())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }
        }
//        if (count($object->getDisciplinas()) <= 0 && count($object->getTendencias()) <= 0 && count($object->getInstitucionalEstrategias()) <= 0) {
//            $errorElement
//                    ->addViolation($this->trans('error.estrategia.disciplinas_tendencias_menos_uno'))
//                    ->end();
//        }

        if ($object->getCoberturaGeneralMin() > $object->getCoberturaGeneralMax()) {
            $mensaje = $this->trans('error.estrategia.cobertura_min');
            $errorElement
                    ->with('coberturaGeneralMin')
                    ->addViolation($mensaje)
                    ->end();
        }

        if ($object->getDisciplinas()) {
            foreach ($object->getDisciplinas() as $disciplina) {
                if ($disciplina->getCoberturaMinima() > $disciplina->getCoberturaMaxima()) {
                    $mensaje = $this->trans(
                            'error.estrategia_disciplina.cobertura_min', array('%disciplina%' => $disciplina->getDisciplina())
                    );
                    $errorElement
                            ->addViolation($mensaje)
                            ->end();
                }
            }
        }

        foreach ($object->getTendencias() as $tendencia) {
            if ($tendencia->getCoberturaMinima() > $tendencia->getCoberturaMaxima()) {
                $mensaje = $this->trans(
                        'error.estrategia_tendencia.cobertura_min', array('%tendencia%' => $tendencia->getTendencia())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }

            if (count($tendencia->getTendencia()) <= 0) {
                $mensaje = $this->trans(
                        'error.estrategia_tendencia.vacio.tendencia', array('%tendencia%' => $tendencia->getTendencia())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }
            if (count($tendencia->getCoberturaMaxima()) <= 0) {
                $mensaje = $this->trans(
                        'error.estrategia_tendencia.vacio.cobertura_max', array('%tendencia%' => $tendencia->getTendencia())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }
            if (count($tendencia->getCoberturaMinima()) <= 0) {
                $mensaje = $this->trans(
                        'error.estrategia_tendencia.vacio.cobertura_min', array('%tendencia%' => $tendencia->getTendencia())
                );
                $errorElement
                        ->addViolation($mensaje)
                        ->end();
            }
        }
    }

    public function getFormTheme() {
        return array_merge(
                parent::getFormTheme(), array('AdminBundle:Estrategia:form_admin_fields.html.twig')
        );
    }

}
