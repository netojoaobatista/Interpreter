<?php
namespace Neto\Interpreter;

class Token
{
    //delimiters
    const DELIMITER = 1;
    const OPERATOR = 3;
    const BLOCK = 5;

    //identifiers
    const IDENTIFIER = 8;
    const KEYWORD = 24;

    //types
    const LITERAL = 40;
    const BOOLEAN = 104;
    const NUMBER = 168;
    const STRING = 296;

    private $type;
    private $value;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }
}