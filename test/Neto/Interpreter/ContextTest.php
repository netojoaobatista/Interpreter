<?php
namespace Neto\Interpreter;

/**
 * Context test case.
 */
class ContextTest extends \PHPUnit_Framework_TestCase
{
    private function createExpressionMock($parseResult)
    {
        $expressionMock = $this->getMockBuilder('Neto\Interpreter\Expression')
                               ->setMethods('parse')
                               ->getMockForAbstractClass();

        $expressionMock->expects($this->any())
                       ->method('parse')
                       ->will($this->returnValue($parseResult));

        return $expressionMock;
    }

    public function testSettingAnExpressionWeCanGetItBack()
    {
        $expression1 = $this->createExpressionMock(10);
        $expression2 = $this->createExpressionMock(20);
        $expression3 = $this->createExpressionMock(30);

        $context = new Context();
        $context->set('e1', $expression1);
        $context->set('e2', $expression2);
        $context->set('e3', $expression3);

        $this->assertSame($expression1, $context->get('e1'));
        $this->assertEquals(10, $context->get('e1')->parse($context));

        $this->assertSame($expression2, $context->get('e2'));
        $this->assertEquals(20, $context->get('e2')->parse($context));

        $this->assertSame($expression3, $context->get('e3'));
        $this->assertEquals(30, $context->get('e3')->parse($context));
    }

    public function testGettingFromContextUsingANonexistentKeyWillReturnNull()
    {
        $context = new Context();
        $this->assertNull($context->get('Nonexistent Key'));
    }
}