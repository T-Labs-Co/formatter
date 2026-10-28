<?php

declare(strict_types=1);

namespace TLabsCo\Formatter;

use DateTimeInterface;
use TLabsCo\Formatter\Exceptions\FormatterException;
use TLabsCo\Formatter\Support\StringHelper;

trait FormatAttributes
{
    /**
     * If strict is true then return true/false, otherwise return 1/0.
     */
    protected function formatBoolean(string $attribute, mixed $value, array $parameters = []): bool|int
    {
        $strict = true;

        if (! empty($parameters)) {
            $trueValues = [true, 'yes', 'ok', 1, '1', 'on', 'enabled'];
            $strict = in_array($parameters[0], $trueValues, true);
        }

        $falseValues = [false, 'no', '0', 'off', 'disabled'];
        $trueValues = [true, 'yes', 'ok', 1, '1', 'on', 'enabled'];

        $isTruthy = in_array($value, $trueValues, true);
        $isFalsy = in_array($value, $falseValues, true);

        if ($strict) {
            return $isTruthy || (! $isFalsy && (bool) $value);
        }

        return (int) ($isTruthy || (! $isFalsy && (bool) $value));
    }

    protected function formatInteger(string $attribute, mixed $value): int
    {
        return (int) $value;
    }

    protected function formatFloat(string $attribute, mixed $value): float
    {
        return (float) $value;
    }

    protected function formatPricing(string $attribute, mixed $value, array $parameters): string|false
    {
        $decimals = $parameters[0] ?? 2;

        if (is_float($value) || is_int($value)) {
            $value = (string) number_format($value, (int) $decimals);
        }

        return str_replace(',', '', (string) $value);
    }

    /**
     * @return array<mixed>
     */
    protected function formatArray(string $attribute, mixed $value): array
    {
        return is_array($value) ? $value : [$value];
    }

    protected function formatSubstring(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(2, $parameters, 'substring')) {
            return false;
        }

        $stringValue = (string) $value;

        if (is_numeric($parameters[0])) {
            $from = (int) $parameters[0];
            $length = (int) $parameters[1];

            return mb_substr($stringValue, $from, $length);
        }

        $from_s = (string) $parameters[0];
        $to_s = (string) $parameters[1];
        $start = 0;
        $end = strlen($stringValue);
        if ($from_s !== '') {
            $pos = strpos($stringValue, $from_s);
            if ($pos !== false) {
                $start = $pos;
            }
        }
        if ($to_s !== '') {
            $pos = strpos($stringValue, $to_s);
            if ($pos !== false) {
                $end = $pos;
            }
        }

        return mb_substr($stringValue, $start + strlen($from_s), $end - $start - strlen($from_s));
    }

    protected function formatTrimCustom(string $attribute, mixed $value, array $parameters): string
    {
        return StringHelper::trimCustom((string) $value);
    }

    protected function formatTrim(string $attribute, mixed $value, array $parameters = []): string|false
    {
        $charlist = " \t\n\r\0\x0B";

        if (! empty($parameters)) {
            $charlist = implode('', $parameters);
        }

        if ($value === null || $value === '') {
            return (string) $value;
        }

        $stringValue = (string) $value;

        // avoid 2 bytes space
        return (string) trim((string) preg_replace('/(^\s+)|(\s+$)/u', '', $stringValue), $charlist);
    }

    protected function formatTrimStart(string $attribute, mixed $value, array $parameters): string|false
    {
        $charlist = " \t\n\r\0\x0B";

        if (! empty($parameters)) {
            $charlist = implode('', $parameters);
        }

        if ($value === null || $value === '') {
            return (string) $value;
        }

        $stringValue = (string) $value;

        return (string) ltrim((string) preg_replace('/(^\s+)/u', '', $stringValue), $charlist);
    }

    protected function formatTrimEnd(string $attribute, mixed $value, array $parameters = []): string|false
    {
        $charlist = " \t\n\r\0\x0B";

        if (! empty($parameters)) {
            $charlist = implode('', $parameters);
        }

        if ($value === null || $value === '') {
            return (string) $value;
        }

        $stringValue = (string) $value;

        return (string) rtrim((string) preg_replace('/(\s+$)/u', '', $stringValue), $charlist);
    }

    protected function formatTrimSingleQuote(string $attribute, mixed $value, array $parameters = []): string|false
    {
        $charlist = ", \t\n\r\0\x0B";

        if (! empty($parameters)) {
            $charlist = implode('', $parameters);
        }

        if ($value === null || $value === '') {
            return (string) $value;
        }

        $stringValue = (string) $value;
        $trimmed = (string) preg_replace('/(^\s+)|(\s+$)/u', '', $stringValue);

        return trim($trimmed, $charlist);
    }

    protected function formatReplace(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(2, $parameters, 'replace')) {
            return false;
        }

        $search = (string) $parameters[0];
        $replace = (string) $parameters[1];

        return str_ireplace($search, $replace, (string) $value);
    }

    protected function formatReplaceFirst(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(2, $parameters, 'replace_first')) {
            return false;
        }

        $search = (string) $parameters[0];
        $replace = (string) $parameters[1];
        $subject = (string) $value;

        $position = strpos($subject, $search);
        if ($position !== false && $position > 0) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    protected function formatReplaceLast(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(2, $parameters, 'replace_last')) {
            return false;
        }

        $search = (string) $parameters[0];
        $replace = (string) $parameters[1];
        $subject = (string) $value;

        $position = strrpos($subject, $search);
        if ($position !== false && $position > 0) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    protected function formatStudly(string $attribute, mixed $value): string
    {
        return StringHelper::studly((string) $value);
    }

    protected function formatTitle(string $attribute, mixed $value): string
    {
        return mb_convert_case((string) $value, MB_CASE_TITLE, 'UTF-8');
    }

    protected function formatUpper(string $attribute, mixed $value): string
    {
        return mb_strtoupper((string) $value, 'UTF-8');
    }

    protected function formatLower(string $attribute, mixed $value): string
    {
        return mb_strtolower((string) $value, 'UTF-8');
    }

    protected function formatMd5(string $attribute, mixed $value): string
    {
        return md5((string) $value);
    }

    protected function formatUrlParam(string $attribute, mixed $value, array $parameters): string|null|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'url_param')) {
            return false;
        }

        $name = (string) $parameters[0];
        $stringValue = (string) $value;

        $urlComponents = parse_url($stringValue);
        if (! is_array($urlComponents) || ! isset($urlComponents['query'])) {
            return null;
        }

        $params = [];
        parse_str($urlComponents['query'], $params);

        return is_string($params[$name] ?? null) ? $params[$name] : null;
    }

    protected function formatLimit(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'limit')) {
            return false;
        }

        $number = (int) $parameters[0];
        $end = isset($parameters[1]) && $parameters[1] ? (string) $parameters[1] : '...';

        return StringHelper::limit((string) $value, $number, $end);
    }

    protected function formatPad(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'pad')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? (string) $parameters[1] : ' ';

        return StringHelper::padBoth((string) $value, $length, $pad);
    }

    protected function formatPadLeft(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'pad_left')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? (string) $parameters[1] : ' ';

        return StringHelper::padLeft((string) $value, $length, $pad);
    }

    protected function formatPadRight(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'pad_right')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? (string) $parameters[1] : ' ';

        return StringHelper::padRight((string) $value, $length, $pad);
    }

    /**
     * Format that an attribute to a valid date.
     */
    protected function formatDate(string $attribute, mixed $value): string|bool
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if ((! is_string($value) && ! is_numeric($value)) || strtotime((string) $value) === false) {
            return false;
        }

        $stringValue = (string) $value;
        $date = date_parse($stringValue);

        if (! checkdate((int) ($date['month'] ?? 0), (int) ($date['day'] ?? 0), (int) ($date['year'] ?? 0))) {
            return false;
        }

        return ($date['year'] ?? '') . '-' . ($date['month'] ?? '') . '-' . ($date['day'] ?? '');
    }

    /**
     * Format that an to a timestamp.
     */
    protected function formatTimestamp(string $attribute, mixed $value): int|false
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return strtotime((string) $value);
    }

    /**
     * Format that an attribute to a date format.
     */
    protected function formatDateFormat(string $attribute, mixed $value, array $parameters): string|false
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'date_format')) {
            return false;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $format = (string) $parameters[0];

        $timestamp = strtotime((string) $value);
        if ($timestamp === false) {
            return false;
        }

        return date($format, $timestamp);
    }

    /**
     * Require a certain number of parameters to be present.
     *
     * @throws FormatterException
     */
    protected function requireParameterCount(int $count, array $parameters, string $rule): void
    {
        if (count($parameters) < $count) {
            throw new FormatterException("Formatter rule {$rule} requires at least {$count} parameters.");
        }
    }

    /**
     * Check a certain number of parameters to be present.
     */
    protected function checkValidParameterCount(int $count, array $parameters, string $rule): bool
    {
        return count($parameters) >= $count;
    }
}
