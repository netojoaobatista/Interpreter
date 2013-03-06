<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * SubtractionExpression test case.
 */
class SubtractionExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(20);
        $rvalue = new NumberExpression(10);

        $expression = new SubtractionExpression($lvalue, $rvalue);

        $this->assertEquals(10, $expression->parse(new Context()));
    }
}