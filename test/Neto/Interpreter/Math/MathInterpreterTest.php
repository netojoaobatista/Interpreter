<?php
namespace Neto\Interpreter\Math;

/**
 * MathInterpreter test case.
 */
class MathInterpreterTest extends \PHPUnit_Framework_TestCase
{
    public function testParseExpressionWithoutSemicolonAtEndOfLineWillThrowAnException()
    {
        $this->setExpectedException('\RuntimeException');

        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('10'));
    }

    public function testParseLiteralExpression()
    {
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('10;'));
    }

    public function testParseUnaryOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(-1, $math->parse('-1;'));
    }

    public function testParseModulusOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(1, $math->parse('10 % 3;'));
    }

    public function testParseMultiplicationOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('2 * 5;'));
    }

    public function testParseDivisionOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('20 / 2;'));
    }

    public function testParseAdditionOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(20, $math->parse('10 + 10;'));
    }

    public function testParseSubtractionOperation()
    {
        $math = new MathInterpreter();

        $this->assertEquals(0, $math->parse('10 - 10;'));
    }

    public function testParsePriorityOperations()
    {
        $math = new MathInterpreter();

        $this->assertEquals(0, $math->parse('-2 * 3 + 6;'));
    }

    public function testParseExpressionWithUnclosedParenthesesWillThrowAnException()
    {
        $this->setExpectedException('\RuntimeException');
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('(2 + 3 * 2;'));
    }

    public function testParsePriorityWithParentheses()
    {
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('(2 + 3) * 2;'));
    }

    public function testParsePriorityWithChainedParentheses()
    {
        $math = new MathInterpreter();

        $this->assertEquals(16, $math->parse('(2 + (3 - 1)) * (1 + (5 - 2));'));
    }

    public function testParseAttributionOperations()
    {
        $math = new MathInterpreter();

        $this->assertEquals(10, $math->parse('a = 10;'));
        $this->assertEquals(20, $math->parse('10 + a;'));
    }

    public function testParseMultipleAttributions()
    {
        $math = new MathInterpreter();
        $this->assertEquals(10, $math->parse('a = 10;'));
        $this->assertEquals(20, $math->parse('b = 20;'));
        $this->assertEquals(30, $math->parse('c = a + b;'));
    }

    public function testParseMultipleOperations()
    {
        $math = new MathInterpreter();
        $math->parse('
            a = 10;
            b = 20;
            c = a * 2;
            d = c / a * 3;
        ');

        $context = $math->getGlobalContext();

        $this->assertEquals(10, $context->get('a')->parse($context));
        $this->assertEquals(20, $context->get('b')->parse($context));
        $this->assertEquals(20, $context->get('c')->parse($context));
        $this->assertEquals( 6, $context->get('d')->parse($context));

    }
}