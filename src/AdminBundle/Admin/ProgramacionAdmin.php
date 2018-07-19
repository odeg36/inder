<?php

namespace AdminBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProgramacionAdmin extends AbstractAdmin {

    public $cuenta = 0;

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('horaInicial')
                ->add('horaFinal')
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
                ->add('id')
                ->add('horaInicial')
                ->add('horaFinal')
                ->add('_action', null, array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    ),
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $object = $this->getSubject();
        if ($object->getHoraInicial() instanceof \DateTime) {
            $inicio = $object->getHoraInicial()->format("H:i");
            $fin = $object->getHoraFinal()->format("H:i");
            $object->setHoraInicial($inicio);
            $object->setHoraFinal($fin);
        }
        $formMapper
                ->add('dia', null, array(
                    'label' => 'formulario.dia',
                    'attr' => array('disabled' => 'true', 'class' => 'dia_programacion hora_programacion')
                ))
                ->add('horaInicial', TextType::class, array(
                    'label' => 'formulario.hora_inicial',
                    "required" => true,
                    'attr' => array(
                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time'
                    )
                ))
                ->add('horaFinal', TextType::class, array(
                    'label' => 'formulario.hora_final',
                    "required" => true,
                    'attr' => array(
                        'data-mask' => 'hh:mm',
                        'class' => 'form-control time'
                    )
        ));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('horaInicial')
                ->add('horaFinal')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'AdminBundle:Oferta:base_edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

}
