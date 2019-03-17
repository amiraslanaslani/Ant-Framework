<?php
return [
    'debug' => true,

    'server' => [
        'listen' => [
            "0.0.0.0:1337", // IPv4
            "[::]:1337" // IPv6
        ],
    ],

    'paths' => [
        'public' => 'App/Public/',
        'routes' => 'App/Routes/',
        'views' => 'App/View', // Passed To Twig
        'controllers' => 'App/Controllers/',

        'loads' => 'Ant/Loads/',
        'functions' => 'Ant/Functions/',
        'server_view' => 'Ant/Server/View', // Passed To Twig

        'caches' => [
            'templates' => 'Cache/Twig/' // Passed To Twig
        ],

    ]
];
?>
