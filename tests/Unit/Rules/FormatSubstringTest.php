<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('extracts substring by position and length', function () {
    $result = Formatter::singleFormat('Hello World', 'substring:0,5');
    expect($result)->toBe('Hello');
});

it('extracts substring by start and end strings', function () {
    $result = Formatter::singleFormat('<tag>content</tag>', 'substring:<tag>,</tag>');
    expect($result)->toBe('content');
});

it('returns false with missing parameters', function () {
    $result = Formatter::singleFormat('hello', 'substring:0');
    expect($result)->toBeFalse();
});
