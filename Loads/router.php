<?php

$router = new \AltoRouter();
$routes = require('Loads/routes.php');

// Add Routes to Router
foreach($routes as $route){
    if($route->isOK()){
        $router->map(
            $route->method,
            $route->route,
            $route->target,
            $route->name === false ? null : $route->name
        );
    }
}

return $router;
?>
