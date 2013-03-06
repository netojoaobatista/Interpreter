<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * DivisionExpression test case.
 */
class DivisionExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(20);

        $expression = new DivisionExpression($lvalue, $rvalue);

        $this->assertEquals(0.5, $expression->parse(new Context()));
    }

    public function testParseDivisionByZeroShouldThrowAnException()
    {
        $this->setExpectedException('\UnexpectedValueException');

        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(0);

        $expression = new DivisionExpression($lvalue, $rvalue);

        $expression->parse(new Context());
    }
}