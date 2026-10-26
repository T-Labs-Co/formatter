<?php

declare(strict_types=1);

use TLabsCo\Formatter\Contracts\FormatterRuleContract;
use TLabsCo\Formatter\Formatter;
use TLabsCo\Formatter\Rules\FormatterRule;

it('can create custom formatter rule', function () {
    $rule = new class extends FormatterRule
    {
        public function format(string $attribute, mixed $value): mixed
        {
            return strtoupper((string) $value);
        }
    };

    expect($rule)->toBeInstanceOf(FormatterRuleContract::class);
});

it('custom rule can set formatter', function () {
    $rule = new class extends FormatterRule
    {
        public function format(string $attribute, mixed $value): mixed
        {
            return $value;
        }
    };

    $formatter = Formatter::make(['a' => '1'], []);
    $rule->setFormatter($formatter);

    expect($rule)->toBeInstanceOf(FormatterRuleContract::class);
});
