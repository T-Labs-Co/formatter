<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats float', function () {
    $result = Formatter::singleFormat('3.14', 'float');
    expect($result)->toBe(3.14);
});

it('formats float from integer string', function () {
    $result = Formatter::singleFormat('42', 'float');
    expect($result)->toBe(42.0);
});

it('formats float zero', function () {
    $result = Formatter::singleFormat('0.0', 'float');
    expect($result)->toBe(0.0);
});
