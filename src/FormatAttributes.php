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

namespace TLabsCo\Formatter;

use DateTimeInterface;
use TLabsCo\Formatter\Exceptions\FormatterException;
use TLabsCo\Formatter\Helpers\StringHelper;

trait FormatAttributes
{
    /**
     * if strict is true then return true/false other else return 1/0
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return bool|int
     */
    protected function formatBoolean($attribute, $value, $parameters = [])
    {
        $strict = true;

        if ($parameters && is_array($parameters)) {
            $trueValues = [true, 'yes', 'ok', 1, '1', 'on', 'enabled'];
            $strict = in_array($parameters[0], $trueValues, true);
        }

        return $strict ? (bool) $value : intval((bool) $value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return bool|int
     */
    protected function formatInteger($attribute, $value)
    {
        return intval($value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return float
     */
    protected function formatFloat($attribute, $value)
    {
        return floatval($value);
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array  $parameters
     * @return float|string
     */
    protected function formatPricing($attribute, $value, $parameters)
    {
        //        if (!$this->checkValidParameterCount(1, $parameters, 'pricing')) {
        //            return false;
        //        }

        if (is_float($value) || is_int($value)) {
            $value = number_format($value, $parameters[0] ?? 2);
        }

        return str_replace(',', '', $value);
    }

    /**
     * @param  string  $attribute
     * @param  string|array  $value
     * @return array
     */
    protected function formatArray($attribute, $value)
    {
        return is_array($value) ? $value : [$value];
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatSubstring($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(2, $parameters, 'substring');
        if (! $this->checkValidParameterCount(2, $parameters, 'substring')) {
            return false;
        }

        if (is_int($parameters[0])) {
            $from = (int) $parameters[0];
            $length = (int) $parameters[1];

            return mb_substr($value, $from, $length);
        } else {
            $from_s = $parameters[0];
            $to_s = $parameters[1];
            $start = 0;
            $end = strlen($value);
            if (! empty($from_s)) {
                $start = strpos($value, $from_s);
            }
            if (! $start) {
                $start = 0;
            }
            if (! empty($from_s)) {
                $end = strpos($value, $to_s);
            }
            if (! $end) {
                $end = strlen($value);
            }

            return mb_substr($value, $start + strlen($from_s), $end - $start);
        }
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string
     */
    protected function formatTrimCustom($attribute, $value, $parameters)
    {
        return StringHelper::trimCustom($value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string
     */
    protected function formatTrim($attribute, $value, $parameters = [])
    {
        $charlist = " \t\n\r\0\x0B";

        if ($parameters && is_array($parameters)) {
            $charlist = implode('', $parameters);
        }

        if (! $value) {
            return $value;
        }

        // avoid 2 bytes space
        return trim(preg_replace('/(^\s+)|(\s+$)/u', '', $value), $charlist);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string
     */
    protected function formatTrimStart($attribute, $value, $parameters)
    {
        $charlist = " \t\n\r\0\x0B";

        if ($parameters && is_array($parameters)) {
            $charlist = implode('', $parameters);
        }

        if (! $value) {
            return $value;
        }

        return ltrim(preg_replace('/(^\s+)/u', '', $value), $charlist);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string
     */
    protected function formatTrimEnd($attribute, $value, $parameters = [])
    {
        $charlist = " \t\n\r\0\x0B";

        if ($parameters && is_array($parameters)) {
            $charlist = implode('', $parameters);
        }

        if (! $value) {
            return $value;
        }

        return rtrim(preg_replace('/(\s+$)/u', '', $value), $charlist);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string
     */
    protected function formatTrimSingleQuote($attribute, $value, $parameters = [])
    {
        $charlist = ", \t\n\r\0\x0B";

        if ($parameters && is_array($parameters)) {
            $charlist = implode('', $parameters);
        }

        if (! $value) {
            return $value;
        }

        $value = preg_replace('/(^\s+)|(\s+$)/u', '', $value);

        return trim($value, $charlist);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatReplace($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(2, $parameters, 'replace');
        if (! $this->checkValidParameterCount(2, $parameters, 'replace')) {
            return false;
        }

        $search = $parameters[0];
        $replace = $parameters[1];

        return str_ireplace($search, $replace, $value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatReplaceFirst($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(2, $parameters, 'replace_first');
        if (! $this->checkValidParameterCount(2, $parameters, 'replace_first')) {
            return false;
        }

        $search = $parameters[0];
        $replace = $parameters[1];
        $subject = $value;

        if ($position = strpos($subject, $search)) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatReplaceLast($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(2, $parameters, 'replace_last');
        if (! $this->checkValidParameterCount(2, $parameters, 'replace_last')) {
            return false;
        }

        $search = $parameters[0];
        $replace = $parameters[1];
        $subject = $value;

        if ($position = strpos($subject, $search)) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return string
     */
    protected function formatStudly($attribute, $value)
    {
        return StringHelper::studly($value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return string
     */
    protected function formatTitle($attribute, $value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return string
     */
    protected function formatUpper($attribute, $value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return string
     */
    protected function formatLower($attribute, $value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @return string
     */
    protected function formatMd5($attribute, $value)
    {
        return md5($value);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return bool|null|string
     */
    protected function formatUrlParam($attribute, $value, $parameters)
    {
        if (! $this->checkValidParameterCount(1, $parameters, 'url_param')) {
            return false;
        }

        // param name
        $name = $parameters[0];

        // Use parse_url() function to parse the URL
        // and return an associative array which
        // contains its various components
        $url_components = parse_url($value);

        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components['query'], $params);

        // Display result
        return $params[$name] ?? null;
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatLimit($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(1, $parameters, 'limit');
        if (! $this->checkValidParameterCount(1, $parameters, 'limit')) {
            return false;
        }

        $number = (int) $parameters[0];
        $end = isset($parameters[1]) && $parameters[1] ? $parameters[1] : '...';

        return StringHelper::limit($value, $number, $end);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatPad($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(1, $parameters, 'pad');
        if (! $this->checkValidParameterCount(1, $parameters, 'pad')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? $parameters[1] : ' ';

        return StringHelper::padBoth($value, $length, $pad);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatPadLeft($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(1, $parameters, 'pad_left');
        if (! $this->checkValidParameterCount(1, $parameters, 'pad_left')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? $parameters[1] : ' ';

        return StringHelper::padLeft($value, $length, $pad);
    }

    /**
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatPadRight($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(1, $parameters, 'pad_right');
        if (! $this->checkValidParameterCount(1, $parameters, 'pad_right')) {
            return false;
        }

        $length = (int) $parameters[0];
        $pad = isset($parameters[1]) && $parameters[1] ? $parameters[1] : ' ';

        return StringHelper::padRight($value, $length, $pad);
    }

    /**
     * Format that an attribute to a valid date.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return string|bool
     */
    protected function formatDate($attribute, $value)
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if ((! is_string($value) && ! is_numeric($value)) || strtotime($value) === false) {
            return false;
        }

        $date = date_parse($value);

        if (! checkdate($date['month'], $date['day'], $date['year'])) {
            return false;
        }

        return $date['year'].'-'.$date['month'].'-'.$date['day'];
    }

    /**
     * Format that an to a timestamp.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return string|bool|int
     */
    protected function formatTimestamp($attribute, $value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return strtotime($value);
    }

    /**
     * Format that an attribute to a date format.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array  $parameters
     * @return string|bool
     */
    protected function formatDateFormat($attribute, $value, $parameters)
    {
        // $this->requireParameterCount(1, $parameters, 'date_format');
        if (! $this->checkValidParameterCount(1, $parameters, 'date_format')) {
            return false;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $format = $parameters[0];

        return date($format, strtotime($value));
    }

    /**
     * Require a certain number of parameters to be present.
     *
     * @param  int  $count
     * @param  array  $parameters
     * @param  string  $rule
     * @return void
     *
     * @throws FormatterException
     */
    protected function requireParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count) {
            throw new FormatterException("Formatter rule $rule requires at least $count parameters.");
        }
    }

    /**
     * Check a certain number of parameters to be present.
     *
     * @param  int  $count
     * @param  array  $parameters
     * @param  string  $rule
     * @return bool
     */
    protected function checkValidParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count) {
            return false;
        }

        return true;
    }
}
