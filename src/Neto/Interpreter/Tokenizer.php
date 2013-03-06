<?php
namespace Neto\Interpreter;

class Tokenizer implements \IteratorAggregate
{
    private $length = 0;
    private $offset = 0;
    private $statement;
    private $tokenIterator;

    public function __construct($statement)
    {
        if (!is_string($statement) || empty($statement)) {
            throw new \InvalidArgumentException('Invalid Statement');
        }

        $this->statement = $statement;
        $this->length = strlen($this->statement);
    }

    public function getStatement()
    {
        return $this->statement;
    }

    private function ignoreWhite($char)
    {
        //ignoring white spaces, line break and carriage return
        while ($this->offset < $this->length &&
              ($char == ' ' || $char == "\n" || $char == "\r")) {

            $char = substr($this->statement, ++$this->offset, 1);
        }
    }

    public function getToken()
    {
        while ($this->offset < $this->length) {
            $char = substr($this->statement, $this->offset, 1);

            $this->ignoreWhite($char);

            if (strstr(';,()+-*/%!<>=', $char) !== false) {
                $next = substr($this->statement, ++$this->offset, 1);
                $value = $char;
                $type = Token::OPERATOR;

                switch ($char) {
                    case ';':
                    case ',':
                    case '(':
                    case ')':
                        $type = Token::DELIMITER;
                        break;
                    case '!':
                        if ($next == '=') {
                            ++$this->offset;
                            $value .= $next;
                        }

                        break;
                    case '<':
                        switch ($next) {
                            case '=':
                            case '>':
                                ++$this->offset;
                                $value .= $next;
                        }

                        break;

                    case '>':
                    case '=':
                        if ($next == '=') {
                            ++$this->offset;
                            $value .= $next;
                        }

                        break;
                }

                return new Token($type, $value);
            }

            if ($char == '"' || $char == "'") {
                $endstr = false;
                $start = $this->offset;
                $value = $char;

                while ($this->offset < $this->length) {
                    $current = substr($this->statement, ++$this->offset, 1);
                    $value .= $current;

                    if ($current == $char) {
                        ++$this->offset;
                        $endstr = true;
                        break;
                    }
                }

                if (!$endstr) {
                    throw new \UnexpectedValueException('Invalid statement');
                }

                return new Token(Token::STRING, $value);
            }

            if (is_numeric($char)) {
                $value = $char;

                while ($this->offset < $this->length) {
                    $current = substr($this->statement, ++$this->offset, 1);

                    if (is_numeric($value . $current)) {
                        $value .= $current;
                    } else {
                        break;
                    }
                }

                return new Token(Token::NUMBER, $value * 1);
            }

            if (preg_match('/^\w$/', $char) > 0) {
                $value = $char;

                while ($this->offset < $this->length) {
                    $current = substr($this->statement, ++$this->offset, 1);

                    if(preg_match('/^\w+$/', $value . $current) > 0) {
                        $value .= $current;
                    } else {
                        break;
                    }
                }

                return new Token(Token::IDENTIFIER, $value);
            }
        }
    }

    public function getIterator()
    {
        if ($this->tokenIterator !== null) {
            return $this->tokenIterator;
        }

        $tokens = array();

        while (($token = $this->getToken()) !== null) {
            $tokens[] = $token;
        }

        return $this->tokenIterator = new TokenIterator($tokens);
    }
}