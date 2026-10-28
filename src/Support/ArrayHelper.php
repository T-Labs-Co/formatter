<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Support;

final class ArrayHelper
{
    public static function has(array $arr, string $attr): bool
    {
        return isset($arr[$attr]);
    }

    public static function get(array $arr, string $attr, mixed $default = null): mixed
    {
        return $arr[$attr] ?? $default;
    }

    public static function set(array &$arr, string $attr, mixed $value): array
    {
        $arr[$attr] = $value;

        return $arr;
    }

    public static function forget(array &$arr, string $attr): array
    {
        if (isset($arr[$attr])) {
            unset($arr[$attr]);
        }

        return $arr;
    }

    /**
     * @param  array<string, string>  $arr
     * @return array<string, string>
     */
    public static function trim(array $arr): array
    {
        return array_map('trim', $arr);
    }
}
