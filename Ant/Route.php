<?php
namespace Ant;

class Route{
    public $name = false;
    public $method = 'GET|POST|PATCH|PUT|DELETE';
    public $route = false;
    public $target = false;

    // Checkers
    public function isOK(){
        return  $this->route !== false &&
                $this->target !== false;
    }

    // Setters
    public function name($name){
        $this->name = $name;
        return $this;
    }

    public function route($route){
        $this->route = $route;
        return $this;
    }

    public function target($target){
        $this->target = $target;
        return $this;
    }

    public function method($method){
        $this->method = $method;
        return $this;
    }

    // Statics
    public static function create($route_uri, $target, $method = 'GET|POST|PATCH|PUT|DELETE', $name = false){
        $route = new self();

        $route->method($method)
              ->route($route_uri)
              ->target($target)
              ->name($name);

        return $route;
    }
}

?>
