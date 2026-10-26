<?php

declare(strict_types=1);

use TLabsCo\Formatter\Formatter;

it('replaces case insensitive', function () {
    $result = Formatter::singleFormat('Hello World', 'replace:World,Universe');
    expect($result)->toBe('Hello Universe');
});

it('replaces multiple occurrences', function () {
    $result = Formatter::singleFormat('foo bar foo', 'replace:foo,baz');
    expect($result)->toBe('baz bar baz');
});

it('returns false with missing parameters', function () {
    $result = Formatter::singleFormat('hello', 'replace:hello');
    expect($result)->toBeFalse();
});
