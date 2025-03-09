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

use TLabsCo\Formatter\Exceptions\FormatterException;
use TLabsCo\Formatter\Helpers\ArrayHelper;
use TLabsCo\Formatter\Helpers\StringHelper;

/**
 * Class Formatter
 *
 * Usage
 * $formatterRules = [
 * 'title' => 'trim|replace:Local Composer Dependencies,[Local Composer Dependencies]|replace:[Local Composer Dependencies],[Composer Dependencies]|limit:150',
 * 'publish_date' => 'date_format:Y-m-d',
 * ];
 *  $data = [
 *      'title' => '  How to resolve missing Local Composer Dependencies on CentOS 8?  ',
 *      'publish_date' => '2024/05/02 13:00'
 *  ]
 *  $formatted = Formatter::make($data, $formatterRules)->format()->formatted();
 *
 * Output:
 *   $formatted = [
 *      'title' => 'How to resolve missing [Composer Dependencies] on CentOS 8?',
 *      'publish_date' => '2024-05-02'
 *   ]
 */
class Formatter
{
    use FormatAttributes;

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules;

    /**
     * The data under formatter.
     *
     * @var array
     */
    protected $data = [];

    protected $formattedData = [];

    protected $dotPlaceholder;

    protected $currentRule;

    protected $excludeAttributes = [];

    protected $implicitAttributes = [];

    /**
     * @var array
     */
    protected $implicitRules = [
        'Boolean',
        'Integer',
        'Float',
        'Substring',
        'Trim',
        'TrimEnd',
        'TrimStart',
        'TrimSingleQuote',
        'TrimEccube',
        'Array',
        'Replace',
        'ReplaceFirst',
        'ReplaceLast',
        'Studly',
        'Title',
        'Upper',
        'Lower',
        'Limit',
        'Pad',
        'PadLeft',
        'PadRight',
        'Pricing',
    ];

    protected $excludeRules = [];

    protected $initialRules = [];

    public function __construct(array $data, array $rules)
    {
        $this->dotPlaceholder = StringHelper::random(3);

        $this->data = $this->parseData($data);
        // NOTE: set & explode rules
        $this->setRules($rules);
    }

    public static function make($data, $rules)
    {
        return new Formatter($data, $rules);
    }

    /**
     * @return array
     */
    public static function singleFormat(string $value, string $rule)
    {
        $field = '_'.StringHelper::random();
        $data = [
            $field => $value,
        ];

        $rules = [
            $field => $rule,
        ];

        $formatter = (new Formatter($data, $rules));

        return $formatter->format()->getFormattedData($field);
    }

    /**
     * Parse the data array, converting dots and asterisks.
     *
     * @return array
     */
    public function parseData(array $data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->parseData($value);
            }

            $key = str_replace(
                ['.', '*'],
                [$this->dotPlaceholder, '__asterisk__'],
                $key
            );

            $newData[$key] = $value;
        }

        return $newData;
    }

    /**
     * process the formatter rules.
     */
    public function format()
    {
        // We'll spin through each rule, formatting the attributes attached to that
        // rule. Any error messages will be added to the containers with each of
        // the other error messages, returning true if we don't have messages.
        foreach ($this->rules as $attribute => $rules) {
            if ($this->shouldBeExcluded($attribute)) {
                $this->removeAttribute($attribute);

                continue;
            }

            foreach ($rules as $rule) {
                $this->formatAttribute($attribute, $rule);

                if ($this->shouldBeExcluded($attribute)) {
                    $this->removeAttribute($attribute);

                    break;
                }

                if ($this->shouldStopFormatting($attribute)) {
                    break;
                }
            }
        }

        return $this;
    }

    /**
     * @param  string  $attribute
     * @param  string  $rule
     */
    protected function formatAttribute($attribute, $rule)
    {
        $this->currentRule = $rule;

        [$rule, $parameters] = FormatterRuleParser::parse($rule);

        if ($rule == '') {
            return;
        }

        // If we have made it this far we will make sure the attribute is formattable and if it is
        // we will call the formatter method with the attribute. If a method returns false the
        // attribute is invalid
        $formattable = $this->isFormattable($rule, $attribute);
        $value = $this->getValue($attribute);

        $method = "format{$rule}";

        if ($formattable && method_exists($this, $method)) {
            $value = $this->$method($attribute, $value, $parameters, $this);
        }

        $this->saveValue($attribute, $value);
    }

    /**
     * Determine if the attribute is formattable
     *
     * @param  object|string  $rule
     * @param  string  $attribute
     * @return bool
     */
    protected function isFormattable($rule, $attribute)
    {
        if (in_array($rule, $this->excludeRules)) {
            return true;
        }

        return $this->hasRule($attribute, $rule);
    }

    /**
     * Determine if a given rule implies the attribute is required.
     *
     * @param  object|string  $rule
     * @return bool
     */
    protected function isImplicit($rule)
    {
        return in_array($rule, $this->implicitRules);
    }

    /**
     * Determine if the given attribute has a rule in the given set.
     *
     * @param  string  $attribute
     * @param  string|array  $rules
     * @return bool
     */
    public function hasRule($attribute, $rules)
    {
        return ! is_null($this->getRule($attribute, $rules));
    }

    /**
     * Get a rule and its parameters for a given attribute.
     *
     * @param  string  $attribute
     * @param  string|array  $rules
     */
    protected function getRule($attribute, $rules): ?array
    {
        if (! array_key_exists($attribute, $this->rules)) {
            return null;
        }

        $rules = (array) $rules;

        foreach ($this->rules[$attribute] as $rule) {

            [$rule, $parameters] = FormatterRuleParser::parse($rule);

            if (in_array($rule, $rules)) {
                return [$rule, $parameters];
            }
        }

        return null;
    }

    /**
     * Get the formatter rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set the formatter rules.
     *
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->initialRules = $rules;

        $this->rules = [];

        $this->addRules($rules);

        return $this;
    }

    /**
     * Parse the given rules and merge them into current rules.
     *
     * @param  array  $rules
     * @return void
     */
    public function addRules($rules)
    {
        // The primary purpose of this parser is to expand any "*" rules to the all
        // of the explicit rules needed for the given data. For example the rule
        // names.* would get expanded to names.0, names.1, etc. for this data.
        $response = (new FormatterRuleParser($this->data))
            ->explode($rules);

        $this->rules = array_merge_recursive(
            $this->rules, $response->rules
        );

        $this->implicitAttributes = array_merge(
            $this->implicitAttributes, $response->implicitAttributes
        );
    }

    /**
     * Get the attributes and values that were formatted.
     *
     * @return array
     *
     * @throws FormatterException
     */
    public function formatted()
    {
        $results = [];

        $missingValue = StringHelper::random();

        foreach (array_keys($this->getRules()) as $key) {
            $value = ArrayHelper::get($this->getFormattedData(), $key, $missingValue);

            if ($value !== $missingValue) {
                $results[$key] = $value;
            }
        }

        return $this->replacePlaceholders($results);
    }

    /**
     * Determine if the attribute should be excluded.
     *
     * @param  string  $attribute
     * @return bool
     */
    protected function shouldBeExcluded($attribute)
    {
        if (! empty($this->excludeAttributes)) {
            foreach ($this->excludeAttributes as $excludeAttribute) {
                if ($attribute === $excludeAttribute ||
                    StringHelper::startsWith($attribute, $excludeAttribute.'.')) {

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Remove the given attribute.
     *
     * @param  string  $attribute
     * @return void
     */
    protected function removeAttribute($attribute)
    {
        ArrayHelper::forget($this->data, $attribute);
        ArrayHelper::forget($this->formattedData, $attribute);
        ArrayHelper::forget($this->rules, $attribute);
    }

    /**
     * Get the data under formatter.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->getData();
    }

    /**
     * Get the data under formatter.
     *
     * @return array
     */
    public function getData($key = null)
    {
        return $key ? ArrayHelper::get($this->data, $key) : $this->data;
    }

    /**
     * Get the data under formatted.
     *
     * @return array
     */
    public function getFormattedData($key = null)
    {
        return $key ? ArrayHelper::get($this->formattedData, $key) : $this->formattedData;
    }

    /**
     * Set the data under formatter.
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $this->parseData($data);

        $this->setRules($this->initialRules);

        return $this;
    }

    /**
     * Replace the placeholders used in data keys.
     *
     * @param  array  $data
     * @return array
     */
    protected function replacePlaceholders($data)
    {
        $originalData = [];

        foreach ($data as $key => $value) {
            $originalData[$this->replacePlaceholderInString($key)] = is_array($value)
                ? $this->replacePlaceholders($value)
                : $value;
        }

        return $originalData;
    }

    /**
     * Replace the placeholders in the given string.
     *
     * @return string
     */
    protected function replacePlaceholderInString(string $value)
    {
        return str_replace(
            [$this->dotPlaceholder, '__asterisk__'],
            ['.', '*'],
            $value
        );
    }

    protected function shouldStopFormatting($attribute)
    {
        return false;
    }

    /**
     * Get the value of a given attribute.
     *
     * @param  string  $attribute
     * @return mixed
     */
    protected function getValue($attribute)
    {
        if (ArrayHelper::has($this->formattedData, $attribute)) {
            return ArrayHelper::get($this->formattedData, $attribute);
        }

        return ArrayHelper::get($this->data, $attribute);
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     */
    protected function saveValue($attribute, $value)
    {
        ArrayHelper::set($this->formattedData, $attribute, $value);
    }
}
