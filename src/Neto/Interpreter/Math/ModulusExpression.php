<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Expression;
use Neto\Interpreter\Context;

class ModulusExpression implements Expression
{
    private $lvalue;
    private $rvalue;

    public function __construct(Expression $lvalue, Expression $rvalue)
    {
        $this->lvalue = $lvalue;
        $this->rvalue = $rvalue;
    }

    public function parse(Context $context)
    {
        $lvalue = $this->lvalue->parse($context);
        $rvalue = $this->rvalue->parse($context);

        if ($rvalue == 0) {
            throw new \UnexpectedValueException('Division by zero');
        }

        return $lvalue % $rvalue;
    }
}