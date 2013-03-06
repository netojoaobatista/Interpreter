<?php
namespace Neto\Interpreter;

class TokenIterator implements \Countable, \SeekableIterator
{
    private $length = 0;
    private $offset = 0;
    private $tokens = array();

    public function __construct(array $tokens)
    {
        foreach ($tokens as $token) {
            if (!($token instanceof Token)) {
                throw new \UnexpectedValueException('Invalid token');
            }

            $this->length++;
            $this->tokens[] = $token;
        }
    }

    public function count()
    {
        return $this->length;
    }

    public function current()
    {
        if (isset($this->tokens[$this->offset])) {
            return $this->tokens[$this->offset];
        }
    }

    public function key()
    {
        return $this->offset;
    }

    public function next()
    {
        ++$this->offset;
    }

    public function previous()
    {
        --$this->offset;
    }

    public function rewind()
    {
        $this->offset = 0;
    }

    public function seek($position)
    {
        $this->offset = $position;
    }

    public function valid()
    {
        return $this->offset >= 0 && $this->offset < $this->length;
    }
}