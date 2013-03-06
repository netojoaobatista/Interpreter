<?php
namespace Neto\Interpreter\Math;

use Neto\Interpreter\Tokenizer;
use Neto\Interpreter\Context;
use Neto\Interpreter\Token;

class MathInterpreter
{
    const GLOBAL_KEY = '_GLOBAL_';
    private $tokenizer;
    private $tokenIterator;
    private $globalContext;

    private function checkIdentifier(Context $context)
    {
        $token = $this->tokenIterator->current();

        if ($token->getType() == Token::IDENTIFIER) {
            $this->tokenIterator->next();
            $next = $this->tokenIterator->current();

            if ($next->getValue() == '=') {
                $this->tokenIterator->next();
                $this->checkIdentifier($context);

                $context->set($token->getValue(),
                    $context->get(self::GLOBAL_KEY));

                return;
            } else {
                $this->tokenIterator->previous();
            }
        }

        $this->checkLowPriorityBinaryOperators($context);
    }

    private function checkLowPriorityBinaryOperators(Context $context)
    {
        $this->checkHighPriorityBinaryOperators($context);

        $localContext = new Context();

        while ( $this->tokenIterator->valid() ) {
            $token = $this->tokenIterator->current();

            if ($token->getValue() == '+' || $token->getValue() == '-') {
                $this->tokenIterator->next();
                $this->checkHighPriorityBinaryOperators($localContext);

                $lvalue = $context->get(self::GLOBAL_KEY);
                $rvalue = $localContext->get(self::GLOBAL_KEY);
                $expression = null;

                switch ($token->getValue()) {
                    case '+' :
                        $expression = new AdditionExpression($lvalue, $rvalue);
                        break;
                    case '-' :
                        $expression = new SubtractionExpression($lvalue, $rvalue);
                        break;
                }

                if ($expression !== null) {
                    $context->set(self::GLOBAL_KEY, $expression);
                }
            } else {
                break;
            }
        }
    }

    private function checkHighPriorityBinaryOperators(Context $context)
    {
        $this->checkUnaryOperators($context);

        $localContext = new Context();

        while ( $this->tokenIterator->valid() ) {
            $token = $this->tokenIterator->current();
            $tokenValue = $token->getValue();

            if ($tokenValue == '*' || $tokenValue == '/' || $tokenValue == '%') {
                $this->tokenIterator->next();
                $this->checkUnaryOperators($localContext);

                $lvalue = $context->get(self::GLOBAL_KEY);
                $rvalue = $localContext->get(self::GLOBAL_KEY);

                $expression = null;

                switch ($tokenValue) {
                    case '*' :
                        $expression = new MultiplicationExpression($lvalue, $rvalue);
                        break;
                    case '/' :
                        $expression = new DivisionExpression($lvalue, $rvalue);
                        break;
                    case '%' :
                        $expression = new ModulusExpression($lvalue, $rvalue);
                        break;
                }

                if ($expression !== null) {
                    $context->set(self::GLOBAL_KEY, $expression);
                }
            } else {
                break;
            }
        }
    }

    private function checkUnaryOperators(Context $context)
    {
        $token = $this->tokenIterator->current();

        if ($token->getValue() == '+' || $token->getValue() == '-') {
            $this->tokenIterator->next();
        }

        $this->checkParentheses($context);

        if ($token->getValue() == '-') {
            $expression = $context->get(self::GLOBAL_KEY);

            $context->set(self::GLOBAL_KEY, new NegationExpression($expression));
        }
    }

    private function checkParentheses(Context $context)
    {
        $token = $this->tokenIterator->current();

        if ($token->getValue() == '(') {
            $this->tokenIterator->next();

            $this->checkIdentifier($context);

            if ($this->tokenIterator->current()->getValue() !== ')') {
                throw new \RuntimeException('Invalid expression');
            }

            $this->tokenIterator->next();
        } else {
            $this->getAtom($context);
        }
    }

    private function getAtom(Context $context)
    {
        $token = $this->tokenIterator->current();

        switch ($token->getType()) {
            case Token::IDENTIFIER :
                $expression = $context->get($token->getValue());

                if ($expression === null) {
                    $expression = $this->globalContext->get($token->getValue());
                }

                $context->set(self::GLOBAL_KEY, $expression);

                $this->tokenIterator->next();
                break;
            case Token::NUMBER :
                $expression = new NumberExpression($token->getValue());

                $context->set(self::GLOBAL_KEY, $expression);
                $this->tokenIterator->next();
                break;
        }
    }

    public function getGlobalContext()
    {
        if ($this->globalContext == null) {
            $this->globalContext = new Context();
        }

        return $this->globalContext;
    }

    public function parse($expression)
    {
        $this->tokenizer = new Tokenizer($expression);
        $this->tokenIterator = $this->tokenizer->getIterator();
        $this->globalContext = $this->getGlobalContext();

        $zero = new NumberExpression(0);

        while ( $this->tokenIterator->valid() ) {
            $this->globalContext->set(self::GLOBAL_KEY, $zero);
            $this->startAnalysis($this->globalContext);
            $currentToken = $this->tokenIterator->current();

            if ($currentToken == null || $currentToken->getValue() !== ';') {
                throw new \RuntimeException('Invalid expression');
            }

            $this->tokenIterator->next();
        }

        $globalExpression = $this->globalContext->get(self::GLOBAL_KEY);
        $parseResult = $globalExpression->parse($this->globalContext);

        return $parseResult;
    }

    private function startAnalysis(Context $context)
    {
        $this->checkIdentifier($context);
    }
}