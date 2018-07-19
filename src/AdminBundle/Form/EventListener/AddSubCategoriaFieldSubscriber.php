<?php

namespace AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddSubCategoriaFieldSubscriber implements EventSubscriberInterface {

    private $es_requerido;
    private $mapeado;

    public function __construct($es_requerido = false, $mapeado = false) {
        $this->es_requerido = $es_requerido;
        $this->mapeado = $mapeado;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addSubCategoriaForm($form, $categoria = 0, $subcategoria = 0) {
        $noVacio = array();
        if ($this->es_requerido) {
            $noVacio = array(
                new NotBlank(array(
                    'message' => 'formulario_registro.no_vacio',
                        ))
            );
        }
        $formOptions = array(
            'required' => true,
            'class' => 'LogicBundle:SubCategoriaEvento',
            'mapped' => $this->mapeado,
            'placeholder' => '',
            'empty_data' => '',
            'constraints' => $noVacio,
            'query_builder' => function(EntityRepository $er) use ($categoria) {
                $query = $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                if ($categoria != "" && $categoria != null) {
                    $query->where('c.categoria = :categoria ')
                            ->setParameter('categoria', $categoria);
                }
                return $query;
            },
            'attr' => array(
                'class' => 'form-control subcategoriasIncripcion'
            ),
        );
        if ($subcategoria) {
            $formOptions['data'] = $subcategoria;
        }
        $form->add('subcategoria', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $categoria = null;
        $subcategoria = null;
        $accessor = PropertyAccess::createPropertyAccessor();
        $objetivo = $accessor->getValue($data, "subcategoria");
        if ($objetivo) {
            $subcategoria = $objetivo->getSubCategoria();
        }

        $this->addSubCategoriaForm($form, $categoria, $subcategoria);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $categoria = array_key_exists('categoria', $data) ? $data['categoria'] : null;
        $subcategoria = array_key_exists('subcategoria', $data) ? $data['subcategoria'] : null;
        $this->addSubCategoriaForm($form, $categoria, $subcategoria);
    }

}
