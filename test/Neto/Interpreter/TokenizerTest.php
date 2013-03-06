<?php
namespace Neto\Interpreter;

/**
 * Tokenizer test case.
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithAnArgumentThatIsNotStringWillThrowAnException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $statement = new Tokenizer(123);
    }

    public function testConstructWithAnEmptyStringWillThrowAnException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $statement = new Tokenizer('');
    }

    public function testGetStatementWillReturnTheSameStatementPassedToConstructor()
    {
        $expected = 'SELECT expression FROM somewhere';

        $statement = new Tokenizer($expected);

        $this->assertEquals($expected, $statement->getStatement());
    }

    public function testGetTokenWillIdentifyAllDelimiters()
    {
        $statement = new Tokenizer('SELECT valuea, valueb, if(true, valuec, valued) FROM somewhere;');

        $token = $statement->getToken();
        $this->assertEquals('SELECT', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals('valuea', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals(',', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('valueb', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals(',', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('if', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals('(', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('true', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals(',', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('valuec', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals(',', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('valued', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals(')', $token->getValue());
        $this->assertTrue(($token->getType() & Token::DELIMITER) == Token::DELIMITER);

        $token = $statement->getToken();
        $this->assertEquals('FROM', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);

        $token = $statement->getToken();
        $this->assertEquals('somewhere', $token->getValue());
        $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);
    }

    public function testGetTokenWillIdentifyTheExclamationMarkOperatorVariations()
    {
        $statement = new Tokenizer('5! != 123');

        $token = $statement->getToken();
        $this->assertEquals('5', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('!', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('!=', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('123', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);
    }

    public function testGetTokenWillIdentifyTheLessThanOperatorVariations()
    {
        $statement = new Tokenizer('5 < 6 <= 10 <> 20');

        $token = $statement->getToken();
        $this->assertEquals('5', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('<', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('6', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('<=', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('10', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('<>', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);
    }

    public function testGetTokenWillIdentifyTheGreaterThanAndEqualOperatorsVariations()
    {
        $statement = new Tokenizer('6 > 5 >= 3 == 3 = true');

        $token = $statement->getToken();
        $this->assertEquals('6', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('>', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('5', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('>=', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('3', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('==', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('3', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('=', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);
    }

    public function testGetTokenWillIdentifyArithmeticOperators()
    {
        $statement = new Tokenizer('5 + 5 -3 * 2 / 1 % 4');

        $token = $statement->getToken();
        $this->assertEquals('5', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('+', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('5', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('-', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('3', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('*', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('2', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('/', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('1', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);

        $token = $statement->getToken();
        $this->assertEquals('%', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('4', $token->getValue());
        $this->assertTrue(($token->getType() & Token::NUMBER) == Token::NUMBER);
    }

    public function testGetTokenWillReturnStringVariations()
    {
        $statement = new Tokenizer('"some string" + "some other string"');

        $token = $statement->getToken();
        $this->assertEquals('"some string"', $token->getValue());
        $this->assertTrue(($token->getType() & Token::LITERAL) == Token::LITERAL);

        $token = $statement->getToken();
        $this->assertEquals('+', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('"some other string"', $token->getValue());
        $this->assertTrue(($token->getType() & Token::LITERAL) == Token::LITERAL);

        $statement = new Tokenizer('\'some string\' + \'some other string\'');

        $token = $statement->getToken();
        $this->assertEquals('\'some string\'', $token->getValue());
        $this->assertTrue(($token->getType() & Token::LITERAL) == Token::LITERAL);

        $token = $statement->getToken();
        $this->assertEquals('+', $token->getValue());
        $this->assertTrue(($token->getType() & Token::OPERATOR) == Token::OPERATOR);

        $token = $statement->getToken();
        $this->assertEquals('\'some other string\'', $token->getValue());
        $this->assertTrue(($token->getType() & Token::LITERAL) == Token::LITERAL);
    }

    public function testGetTokenWhenUnfinishedStringWasFound()
    {
        $this->setExpectedException('\UnexpectedValueException');

        $statement = new Tokenizer('"Some unfinished string');
        $statement->getToken();
    }

    public function testGetTokenWillReturnAllIdentifiersInStatement()
    {
        $identifiers = ['SELECT', 'expression', 'FROM', 'somewhere'];
        $statement = new Tokenizer(implode(' ', $identifiers));

        for ($i = 0, $t = count($identifiers); $i < $t; ++$i) {
            $token = $statement->getToken();
            $this->assertEquals($identifiers[$i], $token->getValue());
            $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);
        }
    }

    public function testGetTokenIteratorWillReturnAnIteratorWithAllTokensInStatement()
    {
        $identifiers = ['SELECT', 'expression', 'FROM', 'somewhere'];
        $statement = new Tokenizer(implode(' ', $identifiers));
        $iterator = $statement->getIterator();

        $this->assertInstanceOf('\Neto\Interpreter\TokenIterator', $iterator);

        for ($i = 0, $t = count($identifiers); $i < $t; ++$i, $iterator->next()) {
            $token = $iterator->current();
            $this->assertEquals($identifiers[$i], $token->getValue());
            $this->assertTrue(($token->getType() & Token::IDENTIFIER) == Token::IDENTIFIER);
        }
    }

    public function testGetTokenIteratorCalledTwiceWillReturnACachedIterator()
    {
        $statement = new Tokenizer('SELECT expression FROM somewhere');
        $iterator = $statement->getIterator();

        $this->assertSame($iterator, $statement->getIterator());
    }
}