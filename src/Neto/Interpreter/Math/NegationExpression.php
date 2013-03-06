<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Expression;
use Neto\Interpreter\Context;

class NegationExpression implements Expression
{
    private $value;

    public function __construct(Expression $value)
    {
        $this->value = $value;
    }

    public function parse(Context $context)
    {
        return -$this->value->parse($context);
    }
}