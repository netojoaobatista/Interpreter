<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * ModulusExpression test case.
 */
class ModulusExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(20);

        $expression = new ModulusExpression($lvalue, $rvalue);

        $this->assertEquals(10, $expression->parse(new Context()));
    }

    public function testParseDivisionByZeroShouldThrowAnException()
    {
        $this->setExpectedException('\UnexpectedValueException');

        $lvalue = new NumberExpression(10);
        $rvalue = new NumberExpression(0);

        $expression = new ModulusExpression($lvalue, $rvalue);

        $expression->parse(new Context());
    }
}