<?php
return [
    'server' => [
        'listen' => [
            "0.0.0.0:1337", // IPv4
            "[::]:1337" // IPv6
        ]
    ],
    'paths' => [
        'public' => 'App/Public/',
        'routes' => 'App/Routes/',
        'controllers' => 'App/Controllers/',
        'loads' => 'Ant/Loads/',
    ]
];
?>
