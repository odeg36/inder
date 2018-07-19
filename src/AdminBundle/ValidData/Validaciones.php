<?php

namespace AdminBundle\ValidData;

class Validaciones {

    public function getValidarAsistentes() {
        return [
            'tipo_documento' => [
                'validaciones' => [
                    [
                        'tipo' => 'no-null',
                        'mensaje_error' => 'error.import.null.data'
                    ],
                    [
                        'tipo' => 'texto',
                        'mensaje_error' => 'error.import.text.data'
                    ],
                    [
                        'tipo' => 'entidad',
                        'clase' => 'TipoIdentificacion',
                        'campo' => 'abreviatura',
                        'mensaje_error' => 'error.import.entity.data'
                    ]
                ]
            ],
            'numero_documento' => [
                'validaciones' => [
                    [
                        'tipo' => 'no-null',
                        'mensaje_error' => 'error.import.null.data'
                    ],
                    [
                        'tipo' => 'busquedaCombinada',
                        'clase' => 'ApplicationSonataUserBundle:User',
                        'campo' => 'username',
                        'metodo' => 'buscarUsername',
                        'campoCombinar' => ['tipo_documento', 'numero_documento'],
                        'mensaje_error' => 'error.import.entity.data'
                    ]
                ]
            ]
        ];
    }

}
