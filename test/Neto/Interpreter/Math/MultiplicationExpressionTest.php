<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * MultiplicationExpression test case.
 */
class MultiplicationExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(20);

        $expression = new MultiplicationExpression($lvalue, $rvalue);

        $this->assertEquals(200, $expression->parse(new Context()));
    }
}