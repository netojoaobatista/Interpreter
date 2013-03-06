<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Context;

/**
 * NumberExpression test case.
 */
class NumberExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructNumberExpressionWithNonNumericValueWillThrowAnException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $expression = new NumberExpression('teste');
    }

    public function testParse()
    {
        $expression = new NumberExpression(10);

        $this->assertEquals(10, $expression->parse(new Context()));
    }
}