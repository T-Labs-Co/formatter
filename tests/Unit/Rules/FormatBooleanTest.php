<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats boolean strict true', function () {
    $result = Formatter::singleFormat('yes', 'boolean');
    expect($result)->toBeTrue();
});

it('formats boolean strict false', function () {
    $result = Formatter::singleFormat('no', 'boolean');
    expect($result)->toBeFalse();
});

it('formats boolean non-strict returns int', function () {
    $result = Formatter::singleFormat('yes', 'boolean:0');
    expect($result)->toBe(1);
});

it('formats boolean with numeric value', function () {
    $result = Formatter::singleFormat('1', 'boolean');
    expect($result)->toBeTrue();
});
