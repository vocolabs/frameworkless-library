<?php

namespace Vocolabs\Frameworkless\Database\QueryBuilder;

class RawExpression
{
    public function __construct(
        private string $expression,
    ) {
        //
    }

    public function get(): string
    {
        return $this->expression;
    }
}
