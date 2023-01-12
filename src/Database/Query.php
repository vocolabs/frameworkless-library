<?php

namespace Vocolabs\Frameworkless\Database;

use Vocolabs\Frameworkless\Database\QueryBuilder\BuilderBase;
use Vocolabs\Frameworkless\Database\QueryBuilder\RawExpression;

class Query extends BuilderBase
{
    public static function raw(string $expression)
    {
        return new RawExpression($expression);
    }
}
