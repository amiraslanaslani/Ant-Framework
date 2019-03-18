<?php

namespace Ant\InputAllocator;

class MethodInput {
    protected $name;
    protected $type;
    protected $position;
    protected $default;

    public function __construct(\ReflectionParameter $param){
        $this->name = $param->getName();
        $this->type = $param->hasType() ? $param->getType()->getName() : null;
        $this->position = $param->getPosition();
        $this->default = $param->isOptional() ? $param->getDefaultValue() : null;
    }

    public function getName(){
        return $this->name;
    }

    public function getType(){
        return $this->type;
    }

    public function getPosition(){
        return $this->position;
    }

    public function getDefaultValue(){
        return $this->default;
    }
}

?>
