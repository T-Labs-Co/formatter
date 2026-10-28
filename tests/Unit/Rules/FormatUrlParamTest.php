<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('extracts url parameter', function () {
    $result = Formatter::singleFormat('https://example.com?page=1&sort=asc', 'url_param:page');
    expect($result)->toBe('1');
});

it('extracts second url parameter', function () {
    $result = Formatter::singleFormat('https://example.com?page=1&sort=asc', 'url_param:sort');
    expect($result)->toBe('asc');
});

it('returns null for missing parameter', function () {
    $result = Formatter::singleFormat('https://example.com?page=1', 'url_param:missing');
    expect($result)->toBeNull();
});

it('returns false with missing parameters', function () {
    $result = Formatter::singleFormat('https://example.com', 'url_param');
    expect($result)->toBeFalse();
});
