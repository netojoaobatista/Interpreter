<?php
namespace Neto\Interpreter;

interface Expression
{
    public function parse(Context $context);
}