<?php

namespace Ant\InputAllocator;

class Input {
    const NAME = 10;
    const TYPE = 11;
    const POSITON = 12;

    protected $bounds = [];
    protected $input = null;
    protected $hard_code_degree = 0;

    public function __construct($value = null){
        $this->setInputValue($value);
    }

    public function getDegree() : int {
        return $this->hard_code_degree > 0 ? $this->hard_code_degree : count($this->bounds);
    }

    public function setHardCodeDegree(int $degree) {
        $this->hard_code_degree = $degree;
    }

    public function addBound(int $boundType, $value){
        if($value == null || $boundType == null)
            return;

        $this->bounds[] = [
            'type' => $boundType,
            'value' => $value
        ];
    }

    public function setInputValue($value){
        $this->input = $value;
    }

    public function getInput(){
        return $this->input;
    }

    public function isAccepts(MethodInput $mi) : bool {
        foreach($this->bounds as $bound){
            switch ($bound['type']) {
                case self::NAME:
                    if($mi->getName() != $bound['value'])
                        return false;
                    break;

                case self::TYPE:
                    if($mi->getType() != $bound['value'])
                        return false;
                    break;

                case self::POSITON:
                    if($mi->getPosition() != $bound['value'])
                        return false;
                    break;
            }
        }
        return true;
    }

}

?>
