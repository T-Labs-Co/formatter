<?php

declare(strict_types=1);

use TLabsCo\Formatter\FormatterRuleParser;

it('parses string rule without parameters', function () {
    [$rule, $parameters] = FormatterRuleParser::parse('trim');
    expect($rule)->toBe('Trim');
    expect($parameters)->toBe([]);
});

it('parses string rule with parameters', function () {
    [$rule, $parameters] = FormatterRuleParser::parse('replace:old,new');
    expect($rule)->toBe('Replace');
    expect($parameters)->toBe(['old', 'new']);
});

it('parses array rule', function () {
    [$rule, $parameters] = FormatterRuleParser::parse(['replace', 'old', 'new']);
    expect($rule)->toBe('Replace');
    expect($parameters)->toBe(['old', 'new']);
});

it('normalizes int to integer', function () {
    [$rule, $parameters] = FormatterRuleParser::parse('int');
    expect($rule)->toBe('Integer');
});

it('normalizes bool to boolean', function () {
    [$rule, $parameters] = FormatterRuleParser::parse('bool');
    expect($rule)->toBe('Boolean');
});

it('explodes rules array', function () {
    $parser = new FormatterRuleParser([]);
    $result = $parser->explode([
        'name' => 'trim|upper',
        'age' => 'integer',
    ]);

    expect($result->rules)->toBe([
        'name' => ['trim', 'upper'],
        'age' => ['integer'],
    ]);
});

it('converts snake_case to studly', function () {
    [$rule, $parameters] = FormatterRuleParser::parse('date_format:Y-m-d');
    expect($rule)->toBe('DateFormat');
});
