<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    /**
     *  Размеры изображений
     */
    'sizes' => [
        'product_list' => [
            'description' => 'Размер изображения товара в категории',
            'width' => 300,
            'height' => 300
        ],
        'product' => [
            'description' => 'Размер главного изображения в карточке товара',
            'width' => 470,
            'height' => 470
        ]
    ],

    /**
     *  Типы изображений
     */
    'types' => [
        'default' => [
            'description' => 'Тип по умолчанию',
        ],
        'product' => [
            'description' => 'Изображение для продукта',
            'sizes' => [
                'product',
                'product_list'
            ]
        ]
    ]

);
