<div align="center">
    <h1>Laravel Json Structure</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/v/ricfr/json-structure.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/php-v/ricfr/json-structure.svg?style=flat-square" alt="PHP from Packagist"></a>
     <a href="https://github.com/Ric16Fr/laravel-json-structure/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/Ric16Fr/laravel-json-structure/tests.yml?branch=main&style=flat-square&label=All%20Tests%20and%20Linting"></a>
    <a href="https://packagist.org/packages/ricfr/json-structure"><img src="https://img.shields.io/packagist/dt/ricfr/json-structure.svg?style=flat-square" alt="Total Downloads"></a>
</p>

This Package validates Laravel Requests against the new JSON Structure Specification which provides easier verification, typing, 
modularity, and determinism. Please also 
see [json-structure.org](json-structure.org) for more information and also https://json-structure.org/codegen.html for how to turn 
classes into a structure and vies verta.

It aims to replace Laravels own `->verify()` method with this emerging standard for several progamming languages.

## Installation

You can install the package via Composer:

```bash
composer require ricfr/json-structure
```

## Usage
### Creating the structure document
To verify a JSON from a request against a structure, you'll first need to create one. Use
```php
\LaravelJsonStructure\LaravelJsonStructure\Logic\JSONStructure::createFromObject($struct) 
```
to create a structure document from an PHP array. Thats usually the case when you either have the structure document defined by yourself 
(see the second link above to automate this) or you are reccieving the structure from an endpoint as well-

Or you can use 
```php
\LaravelJsonStructure\LaravelJsonStructure\Logic\JSONStructure::createFromFile($pathToFile) 
```
when you got the structure in form of a JSON file somewhere. Which happens usually when a colleague (from backend or so) creates it and 
sends it over.

### Verifying a Request
You can then validate a Laravel Request object against a created JSONStructure Object by calling
```php
$request->validateWithJSONStructure($structure)
```
This will return an array with all validation errors, meaning that if its empty the validation was succesfull.

_Alternatively_ you can also _skip the first step_ and call
```php
$request->validateWithUncheckedJSONStructure($structureAsArray)
```
to directly insert the Structure definiton as an array and not create a structure object out of it. This ist **not reccomended** though, 
since it skips the validation of the structure itself. But it may be usefull for structure definitions not totally complying to the 
standard (yet).

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
