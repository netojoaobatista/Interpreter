<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * NegationExpression test case.
 */
class NegationExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $lvalue = new NumberExpression(10);

        $negation1 = new NegationExpression($lvalue);
        $negation2 = new NegationExpression($negation1);

        $this->assertEquals(-10, $negation1->parse(new Context()));
        $this->assertEquals(10, $negation2->parse(new Context()));
    }
}