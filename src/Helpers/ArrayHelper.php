<?php

/*
 * This file is a part of package t-co-labs/formatter
 *
 * (c) T.Labs & Co.
 * Contact for Work: T. <hongty.huynh@gmail.com>
 *
 * We're PHP and Laravel whizzes, and we'd love to work with you! We can:
 *  - Design the perfect fit solution for your app.
 *  - Make your code cleaner and faster.
 *  - Refactoring and Optimize performance.
 *  - Ensure Laravel best practices are followed.
 *  - Provide expert Laravel support.
 *  - Review code and Quality Assurance.
 *  - Offer team and project leadership.
 *  - Delivery Manager
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
