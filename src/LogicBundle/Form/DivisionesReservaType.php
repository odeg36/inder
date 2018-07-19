<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use LogicBundle\Entity\DivisionReserva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DivisionesReservaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $em = $options['em'];
        $usuarios = $em->getRepository('ApplicationSonataUserBundle:User')->createQueryBuilder('user')
                        ->where('user.organizacionDeportiva = :idOrganizacionDeportiva')
                        ->setParameter('idOrganizacionDeportiva', $options['organizacionDeporId'] ?: 0)
                        ->getQuery()->getResult();

        $usuariosOrganismo = array();
        foreach ($usuarios as $user) {
            $nombre = $user->nombreCompleto();
            $id = $user->getId();
            $usuariosOrganismo[] = array($nombre => $id);
        }
        $usuariosDeportivos = $options['organismoDeportistas'];
        $usuariosOrganismo = $usuariosOrganismo;
        $formOptions = array(
            'mapped' => false,
            'class' => 'ApplicationSonataUserBundle:User',
            'required' => false,
            'placeholder' => '',
            'expanded' => true,
            'multiple' => true,
            'attr' => [
                'class' => "coleccionDivisiones"
            ],
            'choice_label' => function($usuariosDeportivos, $key, $index) {
                return ucwords($usuariosDeportivos->nombreCompleto());
            },
            'query_builder' => function(EntityRepository $er) use ($usuariosDeportivos) {
                $usuarios = $er->createQueryBuilder('user')
                        ->join('user.deportistas', 'depor')
                        ->where('depor.usuarioDeportista IN (:organismoDeportistas)')
                        ->setParameters(array('organismoDeportistas' => $usuariosDeportivos));

                return $usuarios;
            }
        );
        $builder
                ->add('usuarios', EntityType::class, $formOptions)
                ->add('division');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => DivisionReserva::class,
            'em' => null,
            'organizacionDeporId' => null,
            'organismoDeportistas' => null,
            'reserva' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'usuarios_division_reserva_type';
    }

}
