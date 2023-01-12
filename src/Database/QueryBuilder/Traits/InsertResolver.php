<?php

namespace Vocolabs\Frameworkless\Database\QueryBuilder\Traits;

use Vocolabs\Frameworkless\Database\Connection\DB;
use Vocolabs\Frameworkless\Database\Contracts\QueryBuilderException;

trait InsertResolver
{
    private function executeInsert(array $data)
    {
        return is_assoc($data) ?
            $this->executeInsertSingle($data) :
            $this->executeInsertMultiple($data);
    }

    private function executeInsertSingle(array $data)
    {
        if (! $data) {
            throw new QueryBuilderException('Empty data provided for insert query.');
        }

        $safe_columns = array_map(fn ($item) => "`{$item}`", array_keys($data));
        $value_bindings = implode(',', array_fill(0, count($safe_columns), '?'));

        $sql = "INSERT INTO `{$this->getTable()}` (".implode(',', $safe_columns).') VALUES ('.$value_bindings.')';

        DB::run($sql, array_values($data));

        return DB::lastInsertId();
    }

    private function executeInsertMultiple(array $data_set)
    {
        if (! $data_set) {
            throw new QueryBuilderException('Empty data provided for insert query.');
        }

        $columns = null;
        foreach ($data_set as $data) {
            if ($columns === null) {
                $columns = array_keys($data);
            } elseif ($columns !== array_keys($data)) {
                throw new QueryBuilderException('Column mismatch while inserting multiple records.');
            }
        }

        $safe_columns = array_map(fn ($item) => "`{$item}`", $columns);

        $row_bindings = '('.implode(',', array_fill(0, count($safe_columns), '?')).')';
        $all_bindings = implode(',', array_fill(0, count($data_set), $row_bindings));

        $sql = "INSERT INTO `{$this->getTable()}` (".implode(',', $safe_columns).') VALUES '.$all_bindings;

        DB::run($sql, array_values($data));

        return DB::lastInsertId();
    }
}
