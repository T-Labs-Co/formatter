<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('can format data with rules', function () {
    $data = [
        'name' => '  John Doe  ',
        'age' => '30',
    ];
    $rules = [
        'name' => 'trim|upper',
        'age' => 'integer',
    ];

    $result = Formatter::make($data, $rules)->format()->formatted();

    expect($result)->toBe([
        'name' => 'JOHN DOE',
        'age' => 30,
    ]);
});

it('can format single value', function () {
    $result = Formatter::singleFormat('  hello world  ', 'trim|upper');

    expect($result)->toBe('HELLO WORLD');
});

it('returns formatted data as array', function () {
    $data = ['title' => 'test'];
    $rules = ['title' => 'upper'];

    $result = Formatter::make($data, $rules)->format()->formatted();

    expect($result)->toBeArray();
});

it('supports chained formatting', function () {
    $data = ['value' => '  hello-world  '];
    $rules = ['value' => 'trim|studly'];

    $result = Formatter::make($data, $rules)->format()->formatted();

    expect($result['value'])->toBe('HelloWorld');
});

it('can set and get rules', function () {
    $formatter = Formatter::make(['a' => '1'], ['a' => 'integer']);

    expect($formatter->getRules())->toBe(['a' => ['integer']]);
});

it('can set and get data', function () {
    $data = ['x' => 'test'];
    $rules = ['x' => 'upper'];

    $formatter = Formatter::make($data, $rules);

    expect($formatter->getData())->toBe(['x' => 'test']);
    expect($formatter->getData('x'))->toBe('test');
});

it('can update data', function () {
    $formatter = Formatter::make(['a' => '1'], ['a' => 'upper']);
    $formatter->setData(['a' => 'hello']);

    $result = $formatter->format()->formatted();

    expect($result['a'])->toBe('HELLO');
});

it('handles empty data', function () {
    $result = Formatter::make([], [])->format()->formatted();

    expect($result)->toBe([]);
});
