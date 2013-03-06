<?php
namespace Neto\Interpreter;

/**
 * TokenIterator test case.
 */
class TokenIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructWithAnElementInArrayThatIsNotATokenWillThrowAnException()
    {
        $this->setExpectedException('\UnexpectedValueException');

        $tokenIterator = new TokenIterator(array(2));
    }

    public function testCountWillReturnTheNumberOfElementsPassedToConstructor()
    {
        $tokenIterator = new TokenIterator(array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
         ));

        $this->assertCount(3, $tokenIterator);
    }

    public function testCurrentWillReturnNullWhenOffsetIsOutOfIteratorBounds()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);

        $this->assertEquals($tokens[0], $tokenIterator->current());
        $tokenIterator->next();

        $this->assertEquals($tokens[1], $tokenIterator->current());
        $tokenIterator->next();

        $this->assertEquals($tokens[2], $tokenIterator->current());
        $tokenIterator->next();

        $this->assertNull($tokenIterator->current());
    }

    public function testKeyWillReturnTokenOffsetInIterator()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);

        $this->assertEquals(0, $tokenIterator->key());
        $tokenIterator->next();

        $this->assertEquals(1, $tokenIterator->key());
        $tokenIterator->next();

        $this->assertEquals(2, $tokenIterator->key());
    }

    public function testNextWillAdvanceToNextToken()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);

        $this->assertEquals($tokens[0], $tokenIterator->current());
        $tokenIterator->next();

        $this->assertEquals($tokens[1], $tokenIterator->current());
        $tokenIterator->next();

        $this->assertEquals($tokens[2], $tokenIterator->current());
    }

    public function testPreviousWillRegressToPreviousToken()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);
        $tokenIterator->seek($tokenIterator->count() - 1);

        $this->assertEquals($tokens[2], $tokenIterator->current());
        $tokenIterator->previous();

        $this->assertEquals($tokens[1], $tokenIterator->current());
        $tokenIterator->previous();

        $this->assertEquals($tokens[0], $tokenIterator->current());
    }

    public function testRewindWillSetIteratorOffsetToFirstElement()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);
        $tokenIterator->next();
        $tokenIterator->next();

        $this->assertEquals($tokens[2], $tokenIterator->current());
        $tokenIterator->rewind();
        $this->assertEquals($tokens[0], $tokenIterator->current());
    }

    public function testSeekProvidesRandomAccessToTheElementsInIterator()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);

        $tokenIterator->seek(1);
        $this->assertEquals($tokens[1], $tokenIterator->current());

        $tokenIterator->seek(0);
        $this->assertEquals($tokens[0], $tokenIterator->current());

        $tokenIterator->seek(2);
        $this->assertEquals($tokens[2], $tokenIterator->current());
    }

    public function testValidWillReturnFalseIfIteratorOffsetIsOutOfBounds()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);

        $this->assertTrue($tokenIterator->valid());

        $tokenIterator->seek(-1);
        $this->assertFalse($tokenIterator->valid());

        $tokenIterator->seek(0);
        $this->assertTrue($tokenIterator->valid());

        $tokenIterator->seek(1);
        $this->assertTrue($tokenIterator->valid());

        $tokenIterator->seek(2);
        $this->assertTrue($tokenIterator->valid());

        $tokenIterator->seek(100);
        $this->assertFalse($tokenIterator->valid());
    }

    public function testIteratingOverIteratorWillGetAllElementsInIterator()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);
        $i = 0;

        foreach ($tokenIterator as $offset => $token) {
            $this->assertEquals($i++, $offset);
            $this->assertSame($tokens[$offset], $token);
        }
    }

    public function testIterationUsingPreviousWillWalkBackward()
    {
        $tokens = array(
            new Token(Token::BOOLEAN, true),
            new Token(Token::DELIMITER, ','),
            new Token(Token::IDENTIFIER, 'test')
        );

        $tokenIterator = new TokenIterator($tokens);
        $tokenIterator->seek($tokenIterator->count() - 1);

        $token = $tokenIterator->current();
        $this->assertEquals(Token::IDENTIFIER, $token->getType());
        $tokenIterator->previous();
        $this->assertTrue($tokenIterator->valid());

        $token = $tokenIterator->current();
        $this->assertEquals(Token::DELIMITER, $token->getType());
        $tokenIterator->previous();
        $this->assertTrue($tokenIterator->valid());

        $token = $tokenIterator->current();
        $this->assertEquals(Token::BOOLEAN, $token->getType());
        $tokenIterator->previous();
        $this->assertFalse($tokenIterator->valid());
    }
}