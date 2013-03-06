<?php
namespace Neto\Interpreter;

/**
 * Token test case.
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWillSetTokenTypeAndValue()
    {
        $token = new Token(TOKEN::BOOLEAN, true);
        $this->assertEquals(Token::BOOLEAN, $token->getType());
        $this->assertTrue($token->getValue());
    }
}