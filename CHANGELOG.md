# Changelog

All notable changes to `t-labs-co/formatter` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-10-26

### Breaking Changes
- **Dropped PHP 8.2 and 8.3 support** — requires PHP ^8.4
- **Dropped Laravel 11 support** — requires Laravel ^12|^13
- **Namespace change**: `TLabsCo\Formatter\Helpers\` → `TLabsCo\Formatter\Support\` (StringHelper, ArrayHelper)
- **Namespace change**: `TLabsCo\Formatter\Rules\FormatterRuleContract` → `TLabsCo\Formatter\Contracts\FormatterRuleContract`
- **Namespace change**: `TLabsCo\Formatter\FormatterServiceProvider` → `TLabsCo\Formatter\Providers\FormatterServiceProvider`
- **Removed** `TLabsCo\Formatter\Commands\FormatterCommand`
- **Removed** Spatie package tools dependency (`spatie/laravel-package-tools`)
- **Method signature changes**: `format()` now returns `self`, `make()` now typed with `array` parameters and `self` return

### Added
- **Facade**: `TLabsCo\Formatter\Facades\Formatter` for convenient static-style access
- **Native service provider**: `TLabsCo\Formatter\Providers\FormatterServiceProvider` replacing Spatie-based provider
- **`declare(strict_types=1)`** on all source files for type safety
- **Full parameter and return type hints** on all public and protected methods
- **Comprehensive test suite** covering all 24+ format rules, core API, parser, helpers, and custom rules
- **Unit/Feature test split** with dedicated PHPUnit suites
- **`pint.json`** configuration for Laravel code style preset
- **`AGENTS.md`** for AI-assisted development guidance
- **GitHub Actions workflows**: `tests.yml` (PHP 8.4/8.5, Laravel 12/13 matrix) and `static-analysis.yml` (PHPStan Level 8)

### Changed
- **Restructured `src/` to DDD layout**: `Helpers/` → `Support/`, `Contracts/`, `Providers/`, `Facades/`
- **Upgraded PHPStan** to Level 8 with single `phpstan.neon` config (dropped baseline)
- **Renamed** `phpunit.xml.dist` → `phpunit.xml`
- **Updated `README.md`** with format rules reference table, Facade usage, version support, and credits
- **Fixed `preg_replace()` null-safety** in `FormatAttributes.php` with explicit `(string)` casts
- **Updated `composer.json`** to require `illuminate/support` and cleaned autoload configuration

### Removed
- **Deleted obsolete directories**: `database/`, `resources/`, `build/`
- **Deleted `Commands/FormatterCommand.php`**
- **Deleted root `FormatterServiceProvider.php`**
- **Deleted `phpstan-baseline.neon`** and `phpstan.neon.dist`
- **Deleted `tests/ExampleTest.php`**

## [1.0.2] - 2025-03-22

### Changed
- Updated composer version

## [1.0.1] - 2025-03-22

### Changed
- Updated README and changelog

## [1.0.0] - 2025-03-21

### Added
- Initial release of the `formatter` package.
- Simple and intuitive syntax for defining formatting rules.
- Support for multiple built-in formatting rules (e.g., trim, replace, limit, date_format).
- Ability to create custom formatting rules.
- Integration with Laravel models and validation.
- Comprehensive unit tests for ensuring reliability.
