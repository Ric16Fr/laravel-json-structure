<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use JsonStructure\ValidationError;
use LaravelJsonStructure\LaravelJsonStructure\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Shared test helpers
|--------------------------------------------------------------------------
|
| The "Person" example schema comes from the JSON Structure Core specification
| and is used across the unit and feature suites. The inline array mirrors
| tests/Fixtures/person.struct.json exactly.
|
| @see https://json-structure.github.io/core/draft-vasters-json-structure-core.html
*/

/**
 * The valid Person schema as a PHP array (mirror of Fixtures/person.struct.json).
 *
 * @return array<string, mixed>
 */
function personSchema(): array
{
    return [
        '$id' => 'https://example.com/person.struct.json',
        '$schema' => 'https://json-structure.org/meta/core/v0/#',
        'name' => 'Person',
        'type' => 'object',
        'properties' => [
            'name' => ['type' => 'string'],
            'age' => ['type' => 'int32'],
            'email' => ['type' => 'string'],
        ],
        'required' => ['name'],
    ];
}

function personSchemaPath(): string
{
    return __DIR__.'/Fixtures/person.struct.json';
}

function invalidSchemaPath(): string
{
    return __DIR__.'/Fixtures/person.invalid.struct.json';
}

/**
 * Build a JSON request whose body is $payload, so that Request::array()
 * returns the decoded payload with its original types (e.g. age stays int).
 *
 * @param  array<string, mixed>  $payload
 */
function jsonRequest(array $payload): Request
{
    return Request::create(
        '/person',
        'POST',
        [],
        [],
        [],
        ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
        json_encode($payload, JSON_THROW_ON_ERROR),
    );
}

/**
 * Extract the error codes from a list of ValidationError objects.
 *
 * @param  array<int, ValidationError>  $errors
 * @return array<int, string>
 */
function errorCodes(array $errors): array
{
    return array_map(static fn (ValidationError $error): string => $error->code, $errors);
}
