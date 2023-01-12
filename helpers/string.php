<?php

use stdClass;
use Vocolabs\Frameworkless\Database\Contracts\QueryBuilderException;

function parse_table_name($table): stdClass
{
    $parts = preg_split('/\s+as\s+/i', $table);

    return match (count($parts)) {
        1 => (object) ['table' => $parts[0], 'alias' => null],
        2 => (object) ['table' => $parts[0], 'alias' => $parts[1]],
        default => throw new QueryBuilderException('Could not resolve table name.'),
    };
}
