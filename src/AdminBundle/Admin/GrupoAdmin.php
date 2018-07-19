<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints as Assert;

class GrupoAdmin extends AbstractAdmin {

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        
        $collection->remove("delete");
        $collection->remove("show");
    }
    
    /**
     * {@inheritdoc}
     */
    public function getNewInstance() {
        $class = $this->getClass();

        return new $class('', array());
    }

    public function getExportFormats(){
        return ['csv', 'xls'];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('name', null, [
                    'label' => 'formulario.labels.nombre'
                ])
                ->add('roles', null, [
                    'label' => 'formulario.labels.permisos',
                    'template' => 'AdminBundle:Grupo/List:roles.html.twig'
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'edit' => array(),
                        'show' => array(),
                        'delete' => array()
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('name', null, [
                    'label' => 'formulario.labels.permisos'
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        // NEXT_MAJOR: Keep FQCN when bumping Symfony requirement to 2.8+.
        $securityRolesType = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ? 'Sonata\UserBundle\Form\Type\SecurityRolesType' : 'sonata_security_roles';
        $formMapper
            ->add('name', null, [
                'disabled' => $object->getId() ? true : false,
                'label' => 'formulario.labels.nombre',
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('roles', $securityRolesType, array(
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'label' => 'formulario.labels.permisos',
                'constraints' => [
                    new Assert\Count(array(
                        'min' => 1
                    ))
                ]
            ))
        ;
    }

}
