<?php

declare(strict_types=1);

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('it will use strict types')
    ->expect('TLabsCo\Formatter')
    ->toUseStrictTypes();

arch('it will not have unused dependencies')
    ->expect('TLabsCo\Formatter')
    ->not->toUse('Spatie');
