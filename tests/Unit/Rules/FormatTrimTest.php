<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('trims whitespace', function () {
    $result = Formatter::singleFormat('  hello  ', 'trim');
    expect($result)->toBe('hello');
});

it('trims with custom characters', function () {
    $result = Formatter::singleFormat('...hello...', 'trim:.');
    expect($result)->toBe('hello');
});

it('trims start', function () {
    $result = Formatter::singleFormat('  hello  ', 'trimstart');
    expect($result)->toBe('hello  ');
});

it('trims end', function () {
    $result = Formatter::singleFormat('  hello  ', 'trimend');
    expect($result)->toBe('  hello');
});

it('handles empty string trim', function () {
    $result = Formatter::singleFormat('', 'trim');
    expect($result)->toBe('');
});
