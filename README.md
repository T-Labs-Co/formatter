# t-labs-co/formatter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/t-labs-co/formatter.svg?style=flat-square)](https://packagist.org/packages/t-labs-co/formatter)
[![Tests](https://img.shields.io/github/actions/workflow/status/t-labs-co/formatter/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/t-labs-co/formatter/actions?query=workflow%3Atests+branch%3Amain)
[![Static Analysis](https://img.shields.io/github/actions/workflow/status/t-labs-co/formatter/static-analysis.yml?branch=main&label=static%20analysis&style=flat-square)](https://github.com/t-labs-co/formatter/actions?query=workflow%3Astatic-analysis+branch%3Amain)
[![Code Style](https://img.shields.io/github/actions/workflow/status/t-labs-co/formatter/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/t-labs-co/formatter/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/t-labs-co/formatter.svg?style=flat-square)](https://packagist.org/packages/t-labs-co/formatter)

A powerful data formatting package for Laravel — apply chainable format rules to transform, clean, and normalize data values.

## Features

- Simple and intuitive syntax for defining formatting rules
- 24+ built-in formatting rules (trim, replace, limit, date_format, etc.)
- Chainable rules using pipe `|` separator
- Custom formatting rules via `FormatterRule` abstract class
- Facade support for convenient usage
- Full strict typing (PHPStan Level 8)
- Comprehensive test coverage

## PHP and Laravel Version Support

| Package Version | PHP      | Laravel     |
| --------------- | -------- | ----------- |
| ^2.0            | ^8.4     | ^12 \| ^13  |
| ^1.0            | ^8.2     | ^11 \| ^12  |

## Installation

```bash
composer require t-labs-co/formatter
```

> **Upgrading from v1.x?** Read the [Migration Guide](https://github.com/T-Labs-Co/formatter/blob/main/docs/MIGRATION_TO_V2.md) for a step-by-step upgrade path.


## Usage

### Basic Usage

```php
use TLabsCo\Formatter\Formatter;

$formatterRules = [
    'title' => 'trim|replace:Local Composer Dependencies,[Local Composer Dependencies]|replace:[Local Composer Dependencies],[Composer Dependencies]|limit:150',
    'publish_date' => 'date_format:Y-m-d',
];

$data = [
    'title' => '  How to resolve missing Local Composer Dependencies on CentOS 8?  ',
    'publish_date' => '2024/05/02 13:00',
];

$formatted = Formatter::make($data, $formatterRules)->format()->formatted();

/*
Output:
$formatted = [
    'title' => 'How to resolve missing [Composer Dependencies] on CentOS 8?',
    'publish_date' => '2024-05-02'
];
*/
```

### Using the Facade

```php
use TLabsCo\Formatter\Facades\Formatter;

$result = Formatter::make($data, $rules)->format()->formatted();
```

### Single Value Formatting

```php
use TLabsCo\Formatter\Formatter;

$result = Formatter::singleFormat('  hello world  ', 'trim|upper');
// 'HELLO WORLD'
```

### Custom Format Rules

```php
use TLabsCo\Formatter\Rules\FormatterRule;

class Max100FormatRule extends FormatterRule
{
    public function format(string $attribute, mixed $value): mixed
    {
        if (strlen((string) $value) > 100) {
            return substr((string) $value, 0, 100);
        }

        return $value;
    }
}

// Using with custom rule
$formatterRules = [
    'title' => 'trim|limit:150',
    'short_description' => ['trim', new Max100FormatRule()],
];
```

## Format Rules Reference

| Rule             | Description                          | Example                              |
| ---------------- | ------------------------------------ | ------------------------------------ |
| `boolean`        | Convert to boolean (true/false)      | `boolean` or `boolean:0` for 1/0     |
| `integer`        | Convert to integer                   | `integer`                            |
| `float`          | Convert to float                     | `float`                              |
| `pricing`        | Format as pricing number             | `pricing:2`                          |
| `array`          | Convert to array                     | `array`                              |
| `substring`      | Extract substring                    | `substring:0,5` or `substring:from,to` |
| `trim`           | Trim whitespace                      | `trim` or `trim:.,!`                |
| `trimstart`      | Trim leading whitespace              | `trimstart`                          |
| `trimend`        | Trim trailing whitespace             | `trimend`                            |
| `trimcustom`     | Custom trim                          | `trimcustom`                         |
| `replace`        | Case-insensitive string replace      | `replace:old,new`                    |
| `replacefirst`   | Replace first occurrence             | `replacefirst:old,new`               |
| `replacelast`    | Replace last occurrence              | `replacelast:old,new`                |
| `studly`         | Convert to StudlyCase                | `studly`                             |
| `title`          | Convert to Title Case                | `title`                              |
| `upper`          | Convert to UPPER CASE                | `upper`                              |
| `lower`          | Convert to lower case                | `lower`                              |
| `md5`            | Generate MD5 hash                    | `md5`                                |
| `limit`          | Limit string length                  | `limit:50` or `limit:50,--`         |
| `pad`            | Pad both sides                       | `pad:10,*`                           |
| `padleft`        | Pad left side                        | `padleft:10,*`                       |
| `padright`       | Pad right side                       | `padright:10,*`                      |
| `date`           | Validate and format date             | `date`                               |
| `date_format`    | Format date to specific format       | `date_format:Y-m-d`                  |
| `timestamp`      | Convert to Unix timestamp            | `timestamp`                          |
| `url_param`      | Extract URL query parameter          | `url_param:page`                     |

## Quality & Development Practices

- **PHPStan Level 8** — Static analysis with maximum strictness
- **Laravel Pint** — Code style following Laravel conventions
- **Pest PHP** — Modern, elegant testing framework
- **Strict Types** — All source files use `declare(strict_types=1)`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrade Guide

Please see [MIGRATION](MIGRATION_TO_V2.md) for instructions on upgrading from v1.x to v2.0.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ty Huynh](https://github.com/ty-huynh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
