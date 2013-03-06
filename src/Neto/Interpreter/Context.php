<?php
namespace Neto\Interpreter;

class Context
{
    private $storage = array();

    public function get($key)
    {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }
    }

    public function set($key, Expression $expression)
    {
        $this->storage[$key] = $expression;
    }
}