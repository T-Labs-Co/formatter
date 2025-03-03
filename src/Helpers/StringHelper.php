<?php

namespace TLabsCo\Formatter\Helpers;

final class StringHelper
{
    public static function random(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function trimCustom(string $value): string
    {
        return trim($value);
    }

    public static function studly(string $value): string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));

        $studlyWords = array_map(function ($word) {
            return ucfirst($word);
        }, $words);

        return implode($studlyWords);
    }

    public static function startsWith(array|string $haystack, array|string $needles): bool
    {
        if (! is_iterable($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && str_starts_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    public static function limit(string $str, int $limit, string $end = '...'): string
    {
        if (mb_strwidth($str, 'UTF-8') <= $limit) {
            return $str;
        }

        return rtrim(mb_strimwidth($str, 0, $limit, '', 'UTF-8')).$end;
    }

    public static function padBoth(string $value, int $length, string $pad = ' '): string
    {
        $short = max(0, $length - mb_strlen($value));
        $shortLeft = (int) floor($short / 2);
        $shortRight = (int) ceil($short / 2);

        return mb_substr(str_repeat($pad, $shortLeft), 0, $shortLeft).
            $value.
            mb_substr(str_repeat($pad, $shortRight), 0, $shortRight);
    }

    public static function padLeft(string $value, int $length, string $pad = ' '): string
    {
        $short = max(0, $length - mb_strlen($value));

        return mb_substr(str_repeat($pad, $short), 0, $short).$value;
    }

    public static function padRight(string $value, int $length, string $pad = ' '): string
    {
        $short = max(0, $length - mb_strlen($value));

        return $value.mb_substr(str_repeat($pad, $short), 0, $short);
    }
}
