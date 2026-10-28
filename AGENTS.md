# AGENTS.md

## Project Overview

`t-labs-co/formatter` is a powerful data formatting package for Laravel that applies chainable format rules to transform, clean, and normalize data values. It provides 24+ built-in formatting rules with a simple, intuitive syntax similar to Laravel validation rules.

## Directory Structure

```
formatter/
├── src/
│   ├── Contracts/              # Interfaces
│   │   └── FormatterRuleContract.php
│   ├── Exceptions/
│   │   └── FormatterException.php
│   ├── Facades/
│   │   └── Formatter.php
│   ├── Providers/
│   │   └── FormatterServiceProvider.php
│   ├── Rules/
│   │   └── FormatterRule.php   # Abstract base for custom rules
│   ├── Support/
│   │   ├── ArrayHelper.php
│   │   └── StringHelper.php
│   ├── FormatAttributes.php    # Trait with all format rule implementations
│   ├── Formatter.php           # Main formatter class
│   └── FormatterRuleParser.php # Rule string parser
├── config/
│   └── formatter.php
├── tests/
│   ├── Unit/
│   │   ├── Rules/              # Format rule tests
│   │   ├── Support/            # Helper tests
│   │   ├── FormatterTest.php
│   │   └── FormatterRuleParserTest.php
│   ├── Feature/
│   ├── ArchTest.php
│   ├── Pest.php
│   └── TestCase.php
├── .github/workflows/
│   ├── tests.yml
│   ├── static-analysis.yml
│   ├── fix-php-code-style-issues.yml
│   ├── dependabot-auto-merge.yml
│   └── update-changelog.yml
├── composer.json
├── phpstan.neon
├── phpunit.xml
├── pint.json
└── README.md
```

## Architecture

### Core Components

- **Formatter** — Main class that orchestrates data formatting. Uses the `FormatAttributes` trait for rule implementations.
- **FormatAttributes** — Trait containing all 24+ format rule methods (formatTrim, formatUpper, etc.).
- **FormatterRuleParser** — Parses rule strings like `trim|replace:old,new` into structured rule arrays.
- **FormatterRuleContract** — Interface for custom format rules.
- **FormatterRule** — Abstract base class implementing `FormatterRuleContract` for easy custom rule creation.

### Support Classes

- **StringHelper** — String utility functions (random, studly, limit, pad, etc.).
- **ArrayHelper** — Array utility functions (get, set, forget, has, trim).

### Service Provider

- **FormatterServiceProvider** — Native Laravel service provider (no Spatie dependency). Publishes config file.

### Facade

- **Formatter** — Laravel Facade for convenient static-style access to the Formatter class.

## Coding Standards

- **PHP 8.4+** — All code uses modern PHP features.
- **Strict Types** — Every source file starts with `declare(strict_types=1)`.
- **Full Type Hints** — All parameters and return types are explicitly declared.
- **PHPStan Level 8** — Maximum static analysis strictness.
- **Laravel Pint** — Code style follows Laravel conventions with `concat_space: one`.
- **DDD Structure** — `Support/` for helpers, `Contracts/` for interfaces, `Providers/` for service providers.
- **No Spatie Dependencies** — Native Laravel service provider pattern.

## Development Workflow

### Commands

```bash
composer test        # Run all tests with Pest
composer analyse     # Run PHPStan static analysis
composer format      # Run Laravel Pint code formatter
```

### Testing

- Tests use **Pest PHP** with **Orchestra Testbench**.
- Test suites are split into `Unit` and `Feature`.
- All test files use `declare(strict_types=1)`.
- Arch tests enforce strict types and no debugging functions.

### Adding a New Format Rule

1. Add the format method to `src/FormatAttributes.php` trait.
2. Add the rule name to the `$implicitRules` array in `Formatter.php` if it should be implicit.
3. Add tests in `tests/Unit/Rules/`.
4. Update the format rules reference table in `README.md`.

## Dependencies

- **PHP**: ^8.4
- **Laravel**: ^12 | ^13
- **illuminate/contracts**: ^12 | ^13
- **illuminate/support**: ^12 | ^13
