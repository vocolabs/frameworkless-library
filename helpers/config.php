<?php

function config(string $key = null, mixed $default = null)
{
    // @TODO - consider other config files
    $config = [
        'database' => require __DIR__.'/../config/database.php',
    ];

    return array_get($config, $key, $default);
}
