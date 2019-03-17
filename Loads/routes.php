<?php
$files = glob(__DIR__ . '/../SRC/Routes/*.php');
$routes = [];

foreach ($files as $file) {
    $routes_list = require($file);

    if(is_array($routes_list)){
        $routes = array_merge($routes, $routes_list);
    }
}
// var_dump($routes);
return $routes;
