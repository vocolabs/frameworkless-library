<?php

namespace Vocolabs\Frameworkless\Database\Connection;

use PDO;
use PDOException;
use Vocolabs\Frameworkless\Database\Contracts\ConnectionException;

class DB
{
    protected static $instance = null;

    // Allow multiple connections, unique across DSN (Data Source Name)
    private static $connections = [];

    protected function __construct()
    {
        //
    }

    protected function __clone()
    {
        //
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = self::getPdo();
        }

        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::getInstance(), $method], $args);
    }

    public static function run($sql, $args = [])
    {
        if (! $args) {
            return self::getInstance()->query($sql);
        }

        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }

    // -------------------------------------------------------------------------
    // Private functions
    // -------------------------------------------------------------------------

    private static function getPdo(): PDO
    {
        $host = config('database.mysql.host');
        $port = config('database.mysql.port');
        $username = config('database.mysql.username');
        $password = config('database.mysql.password');
        $database = config('database.mysql.database');

        // The unique connection maker!
        $uniq_key = serialize([$host, $port, $username, $password, $database]);

        // If any existing connection found, then return immediately
        if (isset(self::$connections[$uniq_key])) {
            return self::$connections[$uniq_key];
        }

        // Prepare DSN for PDO
        $dsn = [
            'host' => $host,
            'port' => $port,
            'dbname' => $database,
            'charset' => config('database.mysql.charset'),
        ];

        $prop_bindings = array_map(
            fn (string $dsn_key, mixed $dsn_value): string => $dsn_key.'='.$dsn_value,
            array_keys($dsn),
            array_values($dsn),
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            // Make sure to cache the connection before returning
            return self::$connections[$uniq_key] = new PDO('mysql:'.implode(';', $prop_bindings), $username, $password, $options);
        } catch (PDOException) {
            throw new ConnectionException('Could not establish the database connection.');
        }
    }
}
