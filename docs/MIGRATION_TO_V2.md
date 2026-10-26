# Upgrading from v1.x to v2.0.0

This guide covers all breaking changes and migration steps for upgrading `t-labs-co/formatter` from v1.x to v2.0.0.

## Requirements

| Component | v1.x | v2.0.0 |
|-----------|------|--------|
| PHP | ^8.2 | **^8.4** |
| Laravel | ^8|^9|^10|^11 | **^12\|^13** |

Make sure your project meets the new minimum requirements before upgrading.

## Migration Steps

### 1. Update composer.json

Run the following command to update the package:

```bash
composer require t-labs-co/formatter:^2.0
```

**What changed:**
- Spatie package tools dependency (`spatie/laravel-package-tools`) has been removed
- `illuminate/support` is now explicitly required
- Dev dependencies have been updated to their latest versions

### 2. Update Service Provider

The service provider has moved from the root namespace to `Providers/`.

**Before (v1.x):**
```php
use TLabsCo\Formatter\FormatterServiceProvider;
```

**After (v2.0):**
```php
use TLabsCo\Formatter\Providers\FormatterServiceProvider;
```

If you're using Laravel's package auto-discovery, no changes are needed — the `composer.json` autoload configuration handles this automatically.

If you manually registered the service provider in `config/app.php` or a custom service provider, update the import statement.

### 3. Update Helper Classes Namespace

The `Helpers/` directory has been renamed to `Support/`.

**Before (v1.x):**
```php
use TLabsCo\Formatter\Helpers\StringHelper;
use TLabsCo\Formatter\Helpers\ArrayHelper;
```

**After (v2.0):**
```php
use TLabsCo\Formatter\Support\StringHelper;
use TLabsCo\Formatter\Support\ArrayHelper;
```

### 4. Update Formatter Rule Contract Namespace

The `FormatterRuleContract` interface has moved from `Rules/` to `Contracts/`.

**Before (v1.x):**
```php
use TLabsCo\Formatter\Rules\FormatterRuleContract;
```

**After (v2.0):**
```php
use TLabsCo\Formatter\Contracts\FormatterRuleContract;
```

If you have custom format rules, update their `use` statements:

**Before (v1.x):**
```php
namespace App\Formatters;

use TLabsCo\Formatter\Rules\FormatterRuleContract;
use TLabsCo\Formatter\Formatter;

class MyCustomFormatter implements FormatterRuleContract
{
    protected $formatter;

    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format($attribute, $value)
    {
        // Your custom formatting logic
        return $value;
    }
}
```

**After (v2.0):**
```php
namespace App\Formatters;

use TLabsCo\Formatter\Contracts\FormatterRuleContract;
use TLabsCo\Formatter\Formatter;

class MyCustomFormatter implements FormatterRuleContract
{
    protected Formatter $formatter;

    public function setFormatter(Formatter $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function format(string $attribute, mixed $value): mixed
    {
        // Your custom formatting logic
        return $value;
    }
}
```

> **Note:** Method signatures now require return types and parameter types. Update your custom rules accordingly.

### 5. Update Custom Formatter Rules (if extending `FormatterRule`)

If you extend the abstract `FormatterRule` class, update method signatures to match the new typed contract:

**Before (v1.x):**
```php
abstract public function format($attribute, $value);
```

**After (v2.0):**
```php
abstract public function format(string $attribute, mixed $value): mixed;
```

### 6. Use the New Facade (Optional)

v2.0 introduces a Facade for convenient static-style access. You can now use the Formatter without injecting it:

```php
use TLabsCo\Formatter\Facades\Formatter;

// Format data using the Facade
$result = Formatter::make($data, $rules)->format()->formatted();

// Single value formatting
$formatted = Formatter::singleFormat('hello world', 'Upper');
```

### 7. Update Method Call Expectations

The `format()` method now returns `$this` (the Formatter instance) instead of `void`:

**Before (v1.x):**
```php
$formatter = new Formatter($data, $rules);
$formatter->format(); // returned void
$formatter->formatted(); // get results
```

**After (v2.0):**
```php
// Chaining is now possible
$formatter = Formatter::make($data, $rules)
    ->format()
    ->formatted();

// Or the old style still works
$formatter = new Formatter($data, $rules);
$formatter->format();
$results = $formatter->formatted();
```

### 8. Remove Formatter Command (if used)

The `FormatterCommand` artisan command has been removed in v2.0. If you were using it, you'll need to remove any references:

```bash
# This command no longer exists
php artisan formatter:format
```

### 9. Update Configuration Publishing

The config publishing tag remains the same, but the service provider path has changed. If you need to republish the config:

```bash
php artisan vendor:publish --tag=formatter-config
```

## Summary of Namespace Changes

| v1.x Namespace | v2.0 Namespace |
|---------------|----------------|
| `TLabsCo\Formatter\Helpers\StringHelper` | `TLabsCo\Formatter\Support\StringHelper` |
| `TLabsCo\Formatter\Helpers\ArrayHelper` | `TLabsCo\Formatter\Support\ArrayHelper` |
| `TLabsCo\Formatter\Rules\FormatterRuleContract` | `TLabsCo\Formatter\Contracts\FormatterRuleContract` |
| `TLabsCo\Formatter\FormatterServiceProvider` | `TLabsCo\Formatter\Providers\FormatterServiceProvider` |

## New in v2.0

- **Facade**: `TLabsCo\Formatter\Facades\Formatter` for static-style access
- **Strict typing**: All source files use `declare(strict_types=1)`
- **Full type hints**: All public and protected methods have parameter and return types
- **PHPStan Level 8**: Static analysis at Level 8 with no baseline
- **Comprehensive tests**: Full test coverage for all 24+ format rules
- **Laravel Pint**: Code style configured with Laravel preset

## Need Help?

If you encounter issues during migration, please [open an issue](https://github.com/T-Labs-Co/formatter/issues) on GitHub.
