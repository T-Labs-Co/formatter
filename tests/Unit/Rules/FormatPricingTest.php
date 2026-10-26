<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats pricing with default decimals', function () {
    $result = Formatter::singleFormat(1234.5, 'pricing:2');
    expect($result)->toBe('1234.50');
});

it('formats pricing with custom decimals', function () {
    $result = Formatter::singleFormat(1234.567, 'pricing:1');
    expect($result)->toBe('1234.6');
});

it('formats pricing string', function () {
    $result = Formatter::singleFormat('1234.5', 'pricing:2');
    expect($result)->toBe('1234.5');
});
