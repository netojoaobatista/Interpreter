<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Expression;
use Neto\Interpreter\Context;

class NumberExpression implements Expression
{
    private $value = 0;

    public function __construct($number)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException('Invalid number');
        }

        $this->value = $number * 1;
    }

    public function parse(Context $context)
    {
        return $this->value;
    }
}