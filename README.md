# Psalm Plugin

[![Automation](https://github.com/ghostwriter/psalm-plugin/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/psalm-plugin/actions/workflows/automation.yml)
[![PHP Version](https://badgen.net/packagist/php/ghostwriter/psalm-plugin?color=777BB4)](https://www.php.net/supported-versions)
[![Packagist Downloads](https://badgen.net/packagist/dt/ghostwriter/psalm-plugin?color=F28D1A)](https://packagist.org/packages/ghostwriter/psalm-plugin)
[![PayPal](https://img.shields.io/badge/paypal-@codepoet-0079C1?logo=data%3Aimage%2Fsvg%2Bxml%3Bbase64%2CPHN2ZyB2aWV3Qm94PSIwIDAgMjQgMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI%2BPHBhdGggZD0iTTE5LjcxNSA2LjEzM2MuMjQ5LTEuODY2IDAtMy4xMS0uOTk5LTQuMjY2QzE3LjYzNC42MjIgMTUuNzIxIDAgMTMuMzA3IDBINi4yMzVjLS40MTggMC0uOTE2LjQ0NC0xIC44ODlMMi4zMjMgMjAuNjIyYzAgLjM1Ni4yNS44LjY2NS44aDQuMzI4bC0uMjUgMS45NTZjLS4wODQuMzU1LjE2Ni42MjIuNDk4LjYyMmgzLjY2M2MuNDE3IDAgLjgzMi0uMjY3LjkxNS0uNzExdi0uMjY3bC43NDktNC42MjJ2LS4xNzhjLjA4My0uNDQ0LjUtLjguOTE1LS44aC41YzMuNTc4IDAgNi4zMjUtMS41MSA3LjE1Ni01Ljk1NS40MTgtMS44NjcuMjUyLTMuMzc4LS43NDctNC40NDUtLjI1LS4zNTUtLjY2Ni0uNjIyLTEtLjg4OSIgZmlsbD0iIzAwOWNkZSIvPjxwYXRoIGQ9Ik0xOS43MTUgNi4xMzNjLjI0OS0xLjg2NiAwLTMuMTEtLjk5OS00LjI2NkMxNy42MzQuNjIyIDE1LjcyMSAwIDEzLjMwNyAwSDYuMjM1Yy0uNDE4IDAtLjkxNi40NDQtMSAuODg5TDIuMzIzIDIwLjYyMmMwIC4zNTYuMjUuOC42NjUuOGg0LjMyOGwxLjE2NC03LjM3OC0uMDgzLjI2N2MuMDg0LS41MzMuNS0uODg5Ljk5OC0uODg5aDIuMDhjNC4wNzkgMCA3LjI0MS0xLjc3OCA4LjI0LTYuNzU1LS4wODMtLjI2NyAwLS4zNTYgMC0uNTM0IiBmaWxsPSIjMDEyMTY5Ii8%2BPHBhdGggZD0iTTkuNTYzIDYuMTMzYy4wODItLjI2Ni4yNS0uNTMzLjQ5OC0uNzEuMTY2IDAgLjI1LS4wOS40MTYtLjA5aDUuNDk0Yy42NjYgMCAxLjMzLjA5IDEuODMuMTc4LjE2NiAwIC4zMzMgMCAuNDk4LjA4OS4xNjguMDg5LjMzNC4wODkuNDE4LjE3OGguMjVjLjI0OC4wODkuNDk3LjI2Ni43NDguMzU1LjI0OC0xLjg2NiAwLTMuMTEtLjk5OS00LjM1NUMxNy43MTcuNTMzIDE1LjgwNCAwIDEzLjM5IDBINi4yMzVjLS40MTggMC0uOTE2LjM1Ni0xIC44ODlMMi4zMjMgMjAuNjIyYzAgLjM1Ni4yNS44LjY2NS44aDQuMzI4bDEuMTY0LTcuMzc4IDEuMDg0LTcuOTF6IiBmaWxsPSIjMDAzMDg3Ii8%2BPC9zdmc%2B)](https://paypal.me/codepoet)
[![Sponsors via GitHub](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/psalm-plugin&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)

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
