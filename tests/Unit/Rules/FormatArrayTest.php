<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats array from string', function () {
    $result = Formatter::singleFormat('hello', 'array');
    expect($result)->toBe(['hello']);
});

it('keeps array as array', function () {
    $data = ['items' => ['a', 'b']];
    $rules = ['items' => 'array'];
    $result = Formatter::make($data, $rules)->format()->formatted();
    expect($result['items'])->toBe(['a', 'b']);
});
