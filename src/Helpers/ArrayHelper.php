<?php

namespace TLabsCo\Formatter\Helpers;

final class ArrayHelper
{
    public static function has($arr, $attr): bool
    {
        return isset($arr[$attr]);
    }

    public static function get($arr, $attr, $default = false): mixed
    {
        return $arr[$attr] ?? $default;
    }

    public static function set(&$arr, $attr, $value): array
    {
        $arr[$attr] = $value;

        return $arr;
    }

    public static function forget(&$arr, $attr): array
    {
        if (isset($arr[$attr])) {
            unset($arr[$attr]);
        }

        return $arr;
    }

    public static function trim($arr): array
    {
        return array_map('trim', $arr);
    }
}
