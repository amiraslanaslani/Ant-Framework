<?php

namespace Ant\InputAllocator\Exceptions;

class MethodIsNotSetException extends \Exception{
    public function __construct(){
        parent::__construct('Method must be set before parameters getted.');
    }
}
?>
