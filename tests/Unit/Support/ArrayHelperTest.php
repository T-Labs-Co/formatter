<?php

declare(strict_types=1);

use TLabsCo\Formatter\Support\ArrayHelper;

it('checks if array has key', function () {
    $arr = ['name' => 'John'];
    expect(ArrayHelper::has($arr, 'name'))->toBeTrue();
    expect(ArrayHelper::has($arr, 'age'))->toBeFalse();
});

it('gets value from array', function () {
    $arr = ['name' => 'John', 'age' => 30];
    expect(ArrayHelper::get($arr, 'name'))->toBe('John');
    expect(ArrayHelper::get($arr, 'missing', 'default'))->toBe('default');
});

it('sets value in array', function () {
    $arr = ['name' => 'John'];
    $result = ArrayHelper::set($arr, 'age', 30);
    expect($arr['age'])->toBe(30);
    expect($result)->toBe($arr);
});

it('forgets value from array', function () {
    $arr = ['name' => 'John', 'age' => 30];
    ArrayHelper::forget($arr, 'age');
    expect(isset($arr['age']))->toBeFalse();
});

it('trims array values', function () {
    $arr = ['  hello  ', '  world  '];
    $result = ArrayHelper::trim($arr);
    expect($result)->toBe(['hello', 'world']);
});
