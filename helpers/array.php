<?php

/**
 * Get array item with dotted notation
 */
function array_get(array $array, string|int|null $key, mixed $default = null): mixed
{
    if (is_null($key)) {
        return $array;
    }

    if (array_key_exists($key, $array)) {
        return $array[$key];
    }

    if (! str_contains($key, '.')) {
        return $array[$key] ?? $default;
    }

    foreach (explode('.', $key) as $segment) {
        if (array_key_exists($segment, $array)) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }

    return $array;
}

/**
 * Determines if an array is associative.
 */
function is_assoc(array $array): bool
{
    $keys = array_keys($array);

    return array_keys($keys) !== $keys;
}
