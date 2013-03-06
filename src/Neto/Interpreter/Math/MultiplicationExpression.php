<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Expression;
use Neto\Interpreter\Context;

class MultiplicationExpression implements Expression
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
        return $this->lvalue->parse($context) * $this->rvalue->parse($context);
    }
}