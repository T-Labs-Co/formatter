<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('formats upper case', function () {
    $result = Formatter::singleFormat('hello world', 'upper');
    expect($result)->toBe('HELLO WORLD');
});

it('formats lower case', function () {
    $result = Formatter::singleFormat('HELLO WORLD', 'lower');
    expect($result)->toBe('hello world');
});

it('formats title case', function () {
    $result = Formatter::singleFormat('hello world', 'title');
    expect($result)->toBe('Hello World');
});

it('formats studly case', function () {
    $result = Formatter::singleFormat('hello-world', 'studly');
    expect($result)->toBe('HelloWorld');
});

it('formats md5 hash', function () {
    $result = Formatter::singleFormat('hello', 'md5');
    expect($result)->toBe(md5('hello'));
});

it('limits string length', function () {
    $result = Formatter::singleFormat('Hello World', 'limit:5');
    expect($result)->toBe('Hello...');
});

it('limits string with custom end', function () {
    $result = Formatter::singleFormat('Hello World', 'limit:5,--');
    expect($result)->toBe('Hello--');
});

it('pads string both sides', function () {
    $result = Formatter::singleFormat('hi', 'pad:6,*');
    expect($result)->toBe('**hi**');
});

it('pads string left', function () {
    $result = Formatter::singleFormat('hi', 'padleft:5,*');
    expect($result)->toBe('***hi');
});

it('pads string right', function () {
    $result = Formatter::singleFormat('hi', 'padright:5,*');
    expect($result)->toBe('hi***');
});
