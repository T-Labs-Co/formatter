<?php

declare(strict_types=1);

namespace TLabsCo\Formatter;

use TLabsCo\Formatter\Exceptions\FormatterException;
use TLabsCo\Formatter\Support\ArrayHelper;
use TLabsCo\Formatter\Support\StringHelper;

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
     * @var array<string, array<int, string>>
     */
    protected array $rules = [];

    /**
     * The data under formatter.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /** @var array<string, mixed> */
    protected array $formattedData = [];

    protected string $dotPlaceholder;

    protected ?string $currentRule = null;

    /** @var string[] */
    protected array $excludeAttributes = [];

    /** @var string[] */
    protected array $implicitAttributes = [];

    /**
     * @var string[]
     */
    protected array $implicitRules = [
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

    /** @var string[] */
    protected array $excludeRules = [];

    /** @var array<string, mixed> */
    protected array $initialRules = [];

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->dotPlaceholder = StringHelper::random(3);

        $this->data = $this->parseData($data);
        // NOTE: set & explode rules
        $this->setRules($rules);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $rules
     */
    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    /**
     * Format a single value with a single rule.
     */
    public static function singleFormat(mixed $value, string $rule): mixed
    {
        $field = '_' . StringHelper::random();
        $data = [
            $field => $value,
        ];

        $rules = [
            $field => $rule,
        ];

        $formatter = new self($data, $rules);

        return $formatter->format()->getFormattedData($field);
    }

    /**
     * Parse the data array, converting dots and asterisks.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function parseData(array $data): array
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->parseData($value);
            }

            $key = str_replace(
                ['.', '*'],
                [$this->dotPlaceholder, '__asterisk__'],
                (string) $key
            );

            $newData[$key] = $value;
        }

        return $newData;
    }

    /**
     * Process the formatter rules.
     */
    public function format(): self
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

    protected function formatAttribute(string $attribute, string $rule): void
    {
        $this->currentRule = $rule;

        [$rule, $parameters] = FormatterRuleParser::parse($rule);

        if ($rule === '') {
            return;
        }

        // If we have made it this far we will make sure the attribute is formattable and if it is
        // we will call the formatter method with the attribute. If a method returns false the
        // attribute is invalid
        $formattable = $this->isFormattable($rule, $attribute);
        $value = $this->getValue($attribute);

        $method = 'format' . $rule;

        if ($formattable && method_exists($this, $method)) {
            $value = $this->$method($attribute, $value, $parameters, $this);
        }

        $this->saveValue($attribute, $value);
    }

    /**
     * Determine if the attribute is formattable.
     */
    protected function isFormattable(string $rule, string $attribute): bool
    {
        if (in_array($rule, $this->excludeRules, true)) {
            return true;
        }

        return $this->hasRule($attribute, $rule);
    }

    /**
     * Determine if a given rule implies the attribute is required.
     */
    protected function isImplicit(string $rule): bool
    {
        return in_array($rule, $this->implicitRules, true);
    }

    /**
     * Determine if the given attribute has a rule in the given set.
     */
    public function hasRule(string $attribute, string|array $rules): bool
    {
        return ! is_null($this->getRule($attribute, $rules));
    }

    /**
     * Get a rule and its parameters for a given attribute.
     */
    protected function getRule(string $attribute, string|array $rules): ?array
    {
        if (! array_key_exists($attribute, $this->rules)) {
            return null;
        }

        $rules = (array) $rules;

        foreach ($this->rules[$attribute] as $rule) {
            [$rule, $parameters] = FormatterRuleParser::parse($rule);

            if (in_array($rule, $rules, true)) {
                return [$rule, $parameters];
            }
        }

        return null;
    }

    /**
     * Get the formatter rules.
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Set the formatter rules.
     */
    public function setRules(array $rules): self
    {
        $this->initialRules = $rules;

        $this->rules = [];

        $this->addRules($rules);

        return $this;
    }

    /**
     * Parse the given rules and merge them into current rules.
     */
    public function addRules(array $rules): void
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
     * @return array<string, mixed>
     *
     * @throws FormatterException
     */
    public function formatted(): array
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
     */
    protected function shouldBeExcluded(string $attribute): bool
    {
        if (! empty($this->excludeAttributes)) {
            foreach ($this->excludeAttributes as $excludeAttribute) {
                if ($attribute === $excludeAttribute ||
                    StringHelper::startsWith($attribute, $excludeAttribute . '.')) {

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Remove the given attribute.
     */
    protected function removeAttribute(string $attribute): void
    {
        ArrayHelper::forget($this->data, $attribute);
        ArrayHelper::forget($this->formattedData, $attribute);
        ArrayHelper::forget($this->rules, $attribute);
    }

    /**
     * Get the data under formatter.
     */
    public function attributes(): array
    {
        return $this->getData();
    }

    /**
     * Get the data under formatter.
     */
    public function getData(?string $key = null): mixed
    {
        return $key ? ArrayHelper::get($this->data, $key) : $this->data;
    }

    /**
     * Get the data under formatted.
     */
    public function getFormattedData(?string $key = null): mixed
    {
        return $key ? ArrayHelper::get($this->formattedData, $key) : $this->formattedData;
    }

    /**
     * Set the data under formatter.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): self
    {
        $this->data = $this->parseData($data);

        $this->setRules($this->initialRules);

        return $this;
    }

    /**
     * Replace the placeholders used in data keys.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function replacePlaceholders(array $data): array
    {
        $originalData = [];

        foreach ($data as $key => $value) {
            $originalData[$this->replacePlaceholderInString((string) $key)] = is_array($value)
                ? $this->replacePlaceholders($value)
                : $value;
        }

        return $originalData;
    }

    /**
     * Replace the placeholders in the given string.
     */
    protected function replacePlaceholderInString(string $value): string
    {
        return str_replace(
            [$this->dotPlaceholder, '__asterisk__'],
            ['.', '*'],
            $value
        );
    }

    protected function shouldStopFormatting(string $attribute): bool
    {
        return false;
    }

    /**
     * Get the value of a given attribute.
     */
    protected function getValue(string $attribute): mixed
    {
        if (ArrayHelper::has($this->formattedData, $attribute)) {
            return ArrayHelper::get($this->formattedData, $attribute);
        }

        return ArrayHelper::get($this->data, $attribute);
    }

    protected function saveValue(string $attribute, mixed $value): void
    {
        ArrayHelper::set($this->formattedData, $attribute, $value);
    }
}
