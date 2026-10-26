<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats date', function () {
    $result = Formatter::singleFormat('2024/05/02 13:00', 'date');
    expect($result)->toBe('2024-5-2');
});

it('formats date format', function () {
    $result = Formatter::singleFormat('2024/05/02 13:00', 'date_format:Y-m-d');
    expect($result)->toBe('2024-05-02');
});

it('formats date format with different format', function () {
    $result = Formatter::singleFormat('2024-01-15', 'date_format:d/m/Y');
    expect($result)->toBe('15/01/2024');
});

it('returns false for invalid date', function () {
    $result = Formatter::singleFormat('not-a-date', 'date');
    expect($result)->toBeFalse();
});

it('formats timestamp', function () {
    $result = Formatter::singleFormat('2024-01-01', 'timestamp');
    expect($result)->toBe(1704067200);
});
