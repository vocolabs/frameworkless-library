<?php

namespace Vocolabs\Frameworkless\Database\QueryBuilder;

use Vocolabs\Frameworkless\Database\QueryBuilder\Traits\InsertResolver;

abstract class BuilderBase
{
    use InsertResolver;

    private static $instance = null;

    protected string $table;

    protected string $alias;

    public static function table(string|RawExpression $table)
    {
        $instance = self::getInstance();

        $instance->resolveTableName($table);

        return $instance;
    }

    protected function getTable(): string
    {
        return config('database.mysql.prefix').$this->table;
    }

    protected function insert(array $data)
    {
        return $this->executeInsert($data);
    }

    // -------------------------------------------------------------------------
    // Private functions
    // -------------------------------------------------------------------------

    private static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function resolveTableName(string|RawExpression $name)
    {
        $table_props = parse_table_name(
            ($name instanceof RawExpression) ? $name->get() : $name
        );

        $this->table = $table_props->table;
        $this->alias = $table_props->alias;
    }
}
