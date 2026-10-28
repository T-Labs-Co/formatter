<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats integer', function () {
    $result = Formatter::singleFormat('42', 'integer');
    expect($result)->toBe(42);
});

it('formats integer from float string', function () {
    $result = Formatter::singleFormat('3.14', 'integer');
    expect($result)->toBe(3);
});

it('formats integer zero', function () {
    $result = Formatter::singleFormat('0', 'integer');
    expect($result)->toBe(0);
});

it('formats integer negative', function () {
    $result = Formatter::singleFormat('-5', 'integer');
    expect($result)->toBe(-5);
});
