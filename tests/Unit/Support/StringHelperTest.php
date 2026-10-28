<?php

declare(strict_types=1);

use TLabsCo\Formatter\Support\StringHelper;

it('generates random string', function () {
    $result = StringHelper::random(10);
    expect($result)->toBeString()->toHaveLength(10);
});

it('generates random string with custom length', function () {
    $result = StringHelper::random(5);
    expect($result)->toBeString()->toHaveLength(5);
});

it('trims custom', function () {
    $result = StringHelper::trimCustom('  hello  ');
    expect($result)->toBe('hello');
});

it('converts to studly case', function () {
    $result = StringHelper::studly('hello-world_test');
    expect($result)->toBe('HelloWorldTest');
});

it('checks starts with', function () {
    expect(StringHelper::startsWith('hello world', 'hello'))->toBeTrue();
    expect(StringHelper::startsWith('hello world', 'world'))->toBeFalse();
});

it('limits string', function () {
    $result = StringHelper::limit('Hello World', 5);
    expect($result)->toBe('Hello...');
});

it('pads both sides', function () {
    $result = StringHelper::padBoth('hi', 6, '*');
    expect($result)->toBe('**hi**');
});

it('pads left', function () {
    $result = StringHelper::padLeft('hi', 5, '*');
    expect($result)->toBe('***hi');
});

it('pads right', function () {
    $result = StringHelper::padRight('hi', 5, '*');
    expect($result)->toBe('hi***');
});
