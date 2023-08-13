# Psalm plugin

[![Compliance](https://github.com/ghostwriter/psalm-plugin/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/psalm-plugin/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/psalm-plugin?color=8892bf)](https://www.php.net/supported-versions)
[![Mutation Coverage](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fghostwriter%2Fwip%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/ghostwriter/psalm-plugin/main)
[![Code Coverage](https://codecov.io/gh/ghostwriter/psalm-plugin/branch/0.1.x/graph/badge.svg?token=UPDATE_TOKEN)](https://codecov.io/gh/ghostwriter/psalm-plugin)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/psalm-plugin/coverage.svg)](https://shepherd.dev/github/ghostwriter/psalm-plugin)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/psalm-plugin)](https://packagist.org/packages/ghostwriter/psalm-plugin)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/psalm-plugin?color=blue)](https://packagist.org/packages/ghostwriter/psalm-plugin)

Provides an `ALL-IN-ONE` plugin for [`Psalm`](https://github.com/vimeo/psalm)

## Feature
- `PHPUnit` (wip)
- `PSR/*` (tbd)
- `Strict Types/Finalization/Privatization` (tbd)
- [`[Request a Feature]`](https://github.com/ghostwriter/psalm-plugin/issues/new) (tbd)

## Usage

### Step 1: Install

``` bash
composer require ghostwriter/psalm-plugin --dev
```

### Step 2: Enable

``` bash
vendor/bin/psalm-plugin enable ghostwriter/psalm-plugin
```

### Step 3: ???! PROFIT

```php
vendor/bin/psalm
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security-related issues, please use [`Security Advisories`](https://github.com/ghostwriter/psalm-plugin/security/advisories/new) instead of using the issue tracker.

## Support

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [vimeo/psalm](https://github.com/vimeo/psalm)
- [All Contributors](https://github.com/ghostwriter/psalm-plugin/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
