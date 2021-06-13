<?php
return [
    '' => [
        'controller' => 'main', //контроллер для главной стр
        'action' => 'index' // это действие внутри донного контроллера (что показ на стр.)
    ],
    'catalogue' => [
        'controller' => 'catalogue',
        'action' => 'index'
    ],
    'catalogue/add_to_cart' => [
        'controller' => 'catalogue',
        'action' => 'add_to_cart'
    ],
    'catalogue/get_client_cart' => [
        'controller' => 'catalogue',
        'action' => 'get_client_cart'
    ], // в CatalogueController будет вызываться этот метод. описан он будет в model Catalogue

    'catalogue/delete_from_cart' => [
        'controller' => 'catalogue',
        'action' => 'delete_from_cart'
    ],

    'catalogue/checkout' => [
        'controller' => 'catalogue',
        'action' => 'checkout'
    ],

    'catalogue/clothes' => [
        'controller' => 'catalogue',
        'action' => 'clothes'
    ],
    'catalogue/computers' => [
        'controller' => 'catalogue',
        'action' => 'computers'
    ],
    'contacts' => [
        'controller' => 'contacts',
        'action' => 'index'
    ]

    //public/images/comps_cats.png
];
