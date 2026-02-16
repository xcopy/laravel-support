# Laravel Support

[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/xcopy/laravel-support/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/xcopy/laravel-support/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/xcopy/laravel-support/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/xcopy/laravel-support/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/xcopy/laravel-support.svg?style=flat-square)](https://packagist.org/packages/xcopy/laravel-support)

Laravel support utilities.

## Installation

**Note:** This package is not yet available on Packagist. You must add it to your `composer.json` manually.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/xcopy/laravel-support"
        }
    ],
    "require": {
        "xcopy/laravel-support": "dev-main"
    }
}
```

## Features

### Enum Traits

- **HasLabel** - Generates human-readable labels from enum values
- **HasValues** - Returns array of all enum values
- **HasChoices** - Returns enum cases as value => label pairs for dropdowns
- **HasStaticCase** - Magic static method access using camelCase only

### Eloquent Casts

- **AsEmailString** - Trims and lowercases email addresses
- **AsModelClass** - Casts to model class name
- **AsTitledString** - Converts to a title case
- **AsTrimmedString** - Trims whitespace

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
