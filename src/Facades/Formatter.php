<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static static make(array<string, mixed> $data, array<string, mixed> $rules)
 * @method static static singleFormat(mixed $value, string $rule)
 * @method static array<string, mixed> formatted()
 * @method static array<string, array<int, string>> getRules()
 * @method static static setRules(array<string, mixed> $rules)
 * @method static static setData(array<string, mixed> $data)
 * @method static array<string, mixed> attributes()
 * @method static mixed getData(string|null $key)
 * @method static mixed getFormattedData(string|null $key)
 *
 * @see \TLabsCo\Formatter\Formatter
 */
class Formatter extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \TLabsCo\Formatter\Formatter::class;
    }
}
