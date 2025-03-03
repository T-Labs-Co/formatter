# The PHP Formatter package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ty-huynh/formatter.svg?style=flat-square)](https://packagist.org/packages/ty-huynh/formatter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ty-huynh/formatter/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ty-huynh/formatter/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ty-huynh/formatter/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ty-huynh/formatter/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ty-huynh/formatter.svg?style=flat-square)](https://packagist.org/packages/ty-huynh/formatter)

This package helps you format your PHP strings. You can use it for one string or many, and it's as simple as using Laravel validation.

## Work with us

We're PHP and Laravel whizzes, and we'd love to work with you! We can:

- Design the perfect fit solution for your app.
- Make your code cleaner and faster.
- Refactoring and Optimize performance.
- Ensure Laravel best practices are followed.
- Provide expert Laravel support.
- Review code and Quality Assurance.
- Offer team and project leadership.
- Delivery Manager

## PHP and Laravel Version Support

This package supports the following versions of PHP:

- PHP: `^8.1`

## Installation

You can install the package via composer:

```bash
composer require t-labs-co/formatter
```

## Usage

```php
use TLabsCo\Formatter\Formatter;

$formatterRules = [
    'title' => 'trim|replace:Local Composer Dependencies,[Local Composer Dependencies]|replace:[Local Composer Dependencies],[Composer Dependencies]|limit:150',
    'publish_date' => 'date_format:Y-m-d',
];

$data = [
    'title' => '  How to resolve missing Local Composer Dependencies on CentOS 8?  ',
    'publish_date' => '2024/05/02 13:00'
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

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [T.](https://github.com/ty-huynh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
