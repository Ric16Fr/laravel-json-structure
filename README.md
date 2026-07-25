<div align="center">
    <h1>Laravel Json Structure</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/v/ricfr/json-structure.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/php-v/ricfr/json-structure.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://badge.laravel.cloud/badge/ricfr/json-structure?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/ricfr/json-structure/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/ricfr/json-structure/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/dt/ricfr/json-structure.svg?style=flat-square" alt="Total Downloads"></a>
</p>

This Package validates Laravel Requests against the new JSON Structure Specification which provides easier verification, typing, 
modularity, and determinism. Please also 
see [json-structure.org](json-structure.org) for more information and also https://json-structure.org/codegen.html for how to turn 
classes into a structure and vies verta.

## Installation

You can install the package via Composer:

```bash
composer require ricfr/json-structure
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="json-structure"
```

## Usage
### Creating the structure document
To verify a JSON from a request against a structure, you'll first need to create one. Use
```php
\LaravelJsonStructure\LaravelJsonStructure\logic\JSONStructure::createFromObject($struct) 
```
to create a structure document from an PHP array. Thats usually the case when you either have the structure document defined by yourself 
(see the second link above to automate this) or you are reccieving the structure from an endpoint as well-

Or you can use 
```php
\LaravelJsonStructure\LaravelJsonStructure\logic\JSONStructure::createFromFile($pathToFile) 
```
when you got the structure in form of a JSON file somewhere. Which happens usually when a colleague (from backend or so) creates it and 
sends it over.

### Verifying a Request



## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Laravel Json Structure! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Ric16Fr](https://github.com/ricfr)
- [All Contributors](../../contributors)

## License

Laravel Json Structure is open-sourced software licensed under the [GPL 3.0 license](LICENSE).
