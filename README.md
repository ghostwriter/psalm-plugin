# Psalm plugin

[![Automation](https://github.com/ghostwriter/psalm-plugin/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/psalm-plugin/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/psalm-plugin?color=8892bf)](https://www.php.net/supported-versions)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/psalm-plugin&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Code Coverage](https://codecov.io/gh/ghostwriter/psalm-plugin/graph/badge.svg)](https://codecov.io/gh/ghostwriter/psalm-plugin)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/psalm-plugin/coverage.svg)](https://shepherd.dev/github/ghostwriter/psalm-plugin)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/psalm-plugin)](https://packagist.org/packages/ghostwriter/psalm-plugin)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/psalm-plugin?color=blue)](https://packagist.org/packages/ghostwriter/psalm-plugin)

Provides an **`ALL-IN-ONE`** plugin for [`Psalm`](https://github.com/vimeo/psalm)

## Usage

``` bash
composer require ghostwriter/psalm-plugin --dev
vendor/bin/psalm-plugin enable ghostwriter/psalm-plugin
```

```php
vendor/bin/psalm
```

### Feature

- [ ] [`PHP Standards Recommendations`](https://www.php-fig.org/psr/)
  - [ ] `Cache`
  - [x] `Container`
    - [x] Resolve return type for the `get` method.
  - [ ] `Clock`
  - [ ] `Event Dispatcher`
  - [ ] `Link`
  - [ ] `Log`
  - [ ] `Http`
- [ ] `PHPUnit`
  - [x] Suppress `MissingThrowsDocblock` for classes that extending `TestCase`
  - [x] Suppress `UnusedClass` for classes that extending `TestCase`
  - [x] Suppress `PropertyNotSetInConstructor` for classes that extending `TestCase`
    - [ ] Using `assertPreConditions` method
    - [x] Using `setUp` method
    - [x] Using `setUpBeforeClass` method
    - [x] Using `@before` docblock
    - [x] Using `@beforeClass` docblock
    - [x] Using `#[Before]` attribute
    - [x] Using `#[BeforeClass]` attribute
    - [ ] Using `#[PreCondition]` attribute
- [ ] `Privatization`
  - [ ] `Finalize every class that has no children` (!isAbstract && !isAnonymous)
  - [ ] `Finalize every class method on an abstract class if possible` (!isAbstract && !isPrivate)
  - [ ] `Change protected class method to private if possible`
  - [ ] `Change protected class property to private if possible`
- [ ] `Strict Types`
- [ ] [**`[Request a Feature]`**](https://github.com/ghostwriter/psalm-plugin/issues/new)

### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### Security

If you discover any security-related issues, please use [`Security Advisories`](https://github.com/ghostwriter/psalm-plugin/security/advisories/new) instead of using the issue tracker.

### Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/psalm-plugin/contributors)

### License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
