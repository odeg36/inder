<?php

namespace LogicBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsistenciaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $oferta = $options['oferta'];
        $fecha = $options['fecha'] != "" ? $options['fecha'] : null;
        $builder
            ->add('usuario', EntityType::class, [
                'class' => 'Application\Sonata\UserBundle\Entity\User',
                'label' => 'formulario.tipo.documento.acompanate',
                'query_builder' => function(EntityRepository $er) use ($oferta, $fecha) {
                        return $er->buscarAsistentesOfertaHorario($oferta, $fecha, true);
                    }
            ])
            ->add('asistio', CheckboxType::class, []); 
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LogicBundle\Entity\Asistencia',
            'oferta' => null,
            'fecha' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'asistencia_type';
    }

}
