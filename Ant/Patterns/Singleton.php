<?php
namespace Ant\Patterns;

abstract class Singleton{
    public static $instance = null;

    final public static function getInstance(){
        if(! isset(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }
}
?>
