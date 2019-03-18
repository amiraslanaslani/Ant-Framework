<?php

namespace Ant\InputAllocator;

class InputAllocator {

    protected $inputs = [];
    protected $reflection = null;

    public function addInput(Input $input){ // Instertion Sort
        for($i = 0;$i < count($this->inputs);$i ++){
            if($this->inputs[$i]->getDegree() > $input->getDegree()){
                array_splice($this->inputs, $i, 0, $input);
                return;
            }
        }
        $this->inputs[] = $input;
    }

    public function parseArray(array $arr){
        foreach($arr as $name => $value){
            $input = new Input($value);
            $input->addBound(Input::NAME, $name);
            $this->addInput($input);
        }
    }

    protected $method_inputs = [];

    public function setMethod(string $class, string $method){
        $this->method_inputs = [];
        $this->reflection = new \ReflectionMethod($class, $method);

        foreach($this->reflection->getParameters() as $param){
            $this->method_inputs[] = new MethodInput($param);
        }

        return $this->reflection;
    }

    public function getParameters() : array {
        $filled_params = [];
        for($i = 0;$i < count($this->method_inputs);$i ++)
            $filled_params[$i] = null;

        if($this->reflection == null)
            throw new Exceptions\MethodIsNotSetException();

        for($i = count($this->inputs) - 1;($i >= 0) && (! $this->isFilled($filled_params));$i --){
            $input = $this->inputs[$i];
            for($j = 0;$j < count($this->method_inputs);$j ++){
                $method_input = $this->method_inputs[$j];
                if($filled_params[ $method_input->getPosition() ] != null)
                    continue;
                if($input->isAccepts($method_input)){
                    $filled_params[ $method_input->getPosition() ] = $input->getInput();
                    break;
                }
            }
        }
        // \var_dump($this->injectDefaultValues($filled_params));
        return $this->injectDefaultValues($filled_params);
    }

    private function isFilled(array $arr) : bool {
        for($i = 0;$i < count($arr);$i ++)
            if($arr[$i] == null)
                return false;
        return true;
    }

    private function injectDefaultValues(array $params) : array {
        foreach($this->method_inputs as $method_input){
            if($params[ $method_input->getPosition() ] == null && $method_input->getDefaultValue() != null){
                $params[ $method_input->getPosition() ] = $method_input->getDefaultValue();
            }
        }
        return $params;
    }
}

?>
