<?php

namespace Vocolabs\Frameworkless\Database\QueryBuilder;

class Insert extends BuilderBase
{
    public function into(string $table)
    {
        return $this->table($table);
    }

    public function execute(array $values)
    {
    }
}
