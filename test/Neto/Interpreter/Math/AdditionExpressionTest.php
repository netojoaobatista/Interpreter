<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * AdditionExpression test case.
 */
class AdditionExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(20);

        $expression = new AdditionExpression($lvalue, $rvalue);

        $this->assertEquals(30, $expression->parse(new Context()));
    }
}