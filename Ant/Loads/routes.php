<?php
global $_ANT;

$files = glob($_ANT['BASE'] . $_ANT['CONFIG']['paths']['routes'] . '/*.php');
$routes = [];

foreach ($files as $file) {
    $routes_list = require($file);

    if(is_array($routes_list)){
        $routes = array_merge($routes, $routes_list);
    }
}

return $routes;
