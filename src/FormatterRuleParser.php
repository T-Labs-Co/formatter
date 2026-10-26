<?php

declare(strict_types=1);

namespace TLabsCo\Formatter;

use TLabsCo\Formatter\Support\StringHelper;

/**
 * Class FormatterRuleParser
 */
class FormatterRuleParser
{
    /** @var array<string, mixed> */
    public array $data;

    /** @var string[] */
    public array $implicitAttributes;

    /**
     * Create a new formatter rule parser.
     *
     * @param  array<string, mixed>  $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Parse the human-friendly rules into a full rules array for the validator.
     *
     * @return object{rules: array<string, array<int, string>>, implicitAttributes: array<int, string>}
     */
    public function explode(array $rules): object
    {
        $this->implicitAttributes = [];

        $rules = $this->explodeRules($rules);

        return (object) [
            'rules' => $rules,
            'implicitAttributes' => $this->implicitAttributes,
        ];
    }

    /**
     * Explode the rules into an array of explicit rules.
     *
     * @param  array<string, mixed>  $rules
     * @return array<string, array<int, string>>
     */
    protected function explodeRules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            // TODO: support wild card rules string
            $rules[$key] = $this->explodeExplicitRule($rule);
        }

        return $rules;
    }

    /**
     * Explode the explicit rule into an array if necessary.
     *
     * @return array<int, string>
     */
    protected function explodeExplicitRule(mixed $rule): array
    {
        if (is_string($rule)) {
            return explode('|', $rule);
        }

        if (is_array($rule)) {
            return $rule;
        }

        return [];
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @param  array<int, mixed>|string  $rules
     * @return array{0: string, 1: array<int, string>}
     */
    public static function parse(array|string $rules): array
    {
        if (is_array($rules)) {
            $rules = static::parseArrayRule($rules);
        } else {
            $rules = static::parseStringRule($rules);
        }

        $rules[0] = static::normalizeRule($rules[0]);

        return $rules;
    }

    /**
     * Parse an array based rule.
     *
     * @param  array<int, mixed>  $rules
     * @return array{0: string, 1: array<int, mixed>}
     */
    protected static function parseArrayRule(array $rules): array
    {
        return [StringHelper::studly(trim((string) ($rules[0] ?? ''))), array_slice($rules, 1)];
    }

    /**
     * Parse a string based rule.
     *
     * @return array{0: string, 1: array<int, string>}
     */
    protected static function parseStringRule(string $rules): array
    {
        $parameters = [];

        // The format for specifying validation rules and parameters follows an
        // easy {rule}:{parameters} formatting convention. For instance the
        // rule "Max:3" states that the value may only be three letters.
        if (strpos($rules, ':') !== false) {
            [$rules, $parameter] = explode(':', $rules, 2);

            $parameters = static::parseParameters($rules, $parameter);
        }

        // NOTE: studly convert snake_case to SnakeCase with caps
        return [StringHelper::studly(trim($rules)), $parameters];
    }

    /**
     * Parse a parameter list.
     *
     * @return array<int, string>
     */
    protected static function parseParameters(string $rule, string $parameter): array
    {
        $params = str_getcsv($parameter);

        /** @var array<int, string> $params */
        return $params;
    }

    /**
     * Normalizes a rule so that we can accept short types.
     */
    protected static function normalizeRule(string $rule): string
    {
        switch ($rule) {
            case 'Int':
                return 'Integer';
            case 'Bool':
                return 'Boolean';
            default:
                return $rule;
        }
    }
}
